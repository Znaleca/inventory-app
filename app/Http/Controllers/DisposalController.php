<?php

namespace App\Http\Controllers;

use App\Models\Disposal;
use App\Models\Item;
use App\Models\Staff;
use App\Models\StockEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DisposalController extends Controller
{
    private function disposedUsedQtyForEntry(int $entryId): int
    {
        if (!Schema::hasColumn('disposals', 'stock_entry_id')) {
            return 0;
        }

        $query = Disposal::where('stock_entry_id', $entryId);

        if (Schema::hasColumn('disposals', 'type')) {
            $query->where('type', 'used');
        }

        return (int) $query->sum('quantity');
    }

    public function create(Request $request)
    {
        $item = Item::findOrFail($request->item_id);

        // disposal_type = 'new' (expired stock) OR 'used' (default)
        $disposalType = $request->get('disposal_type', 'used');

        if ($disposalType === 'new') {
            // Load batches that are expired (past expiry date) with remaining quantity
            $batches = StockEntry::where('item_id', $item->id)
                ->whereNotNull('expiry_date')
                ->where('expiry_date', '<', now()->startOfDay())
                ->orderBy('expiry_date')
                ->get()
                ->map(function ($entry) {
                    $used = $entry->usageLogs()->sum('quantity_used');
                    $remaining = max(0, $entry->quantity - $used);
                    return array_merge($entry->toArray(), ['remaining' => $remaining]);
                })
                ->filter(fn($e) => $e['remaining'] > 0)
                ->values();

            if ($batches->isEmpty()) {
                return redirect()->route('items.show', $item)->with('error', 'No expired batches to dispose.');
            }

            $maxQty = $batches->sum('remaining');
        } else {
            // Used stock: load stock entries that have been marked as used (via usage logs)
            // For devices: each StockEntry with usage logs = 1 used device
            // For consumables: stock_used column
            if ($item->item_type === 'device') {
                $batches = StockEntry::where('item_id', $item->id)
                    ->whereHas('usageLogs', fn($q) => $q->where('quantity_used', '>', 0))
                    ->orderBy('received_date')
                    ->get()
                    ->map(function ($entry) {
                        $disposedQty = $this->disposedUsedQtyForEntry($entry->id);
                        $remaining = max(0, 1 - $disposedQty);

                        return array_merge($entry->toArray(), ['remaining' => $remaining]);
                    })
                    ->filter(fn($e) => $e['remaining'] > 0)
                    ->values();
            } else {
                $batches = StockEntry::where('item_id', $item->id)
                    ->whereHas('usageLogs', fn($q) => $q->where('quantity_used', '>', 0))
                    ->orderBy('received_date')
                    ->get()
                    ->map(function ($entry) {
                        $usedQty = $entry->usageLogs()->sum('quantity_used');
                        $disposedQty = $this->disposedUsedQtyForEntry($entry->id);
                        $remaining = max(0, $usedQty - $disposedQty);

                        return array_merge($entry->toArray(), ['remaining' => $remaining]);
                    })
                    ->filter(fn($e) => $e['remaining'] > 0)
                    ->values();
            }

            $maxQty = (int) $batches->sum('remaining');

            if ($batches->isEmpty() && $maxQty <= 0) {
                return redirect()->route('items.show', $item)->with('error', 'No used stock available to dispose.');
            }
        }

        $staffList = Staff::orderBy('name')->get();

        return view('disposals.create', compact('item', 'staffList', 'disposalType', 'maxQty', 'batches'));
    }

    public function store(Request $request)
    {
        $item = Item::findOrFail($request->item_id);
        $disposalType = $request->input('disposal_type', 'used');
        $hasStockEntryColumn = Schema::hasColumn('disposals', 'stock_entry_id');
        $hasTypeColumn = Schema::hasColumn('disposals', 'type');

        if ($disposalType === 'new') {
            $maxQty = StockEntry::where('item_id', $item->id)
                ->whereNotNull('expiry_date')
                ->where('expiry_date', '<', now()->startOfDay())
                ->sum('quantity');
        } else {
            if ($item->item_type === 'device') {
                $usedBatches = StockEntry::where('item_id', $item->id)
                    ->whereHas('usageLogs', fn($q) => $q->where('quantity_used', '>', 0))
                    ->get()
                    ->map(function ($entry) {
                        $alreadyDisposed = $this->disposedUsedQtyForEntry($entry->id);
                        return max(0, 1 - $alreadyDisposed);
                    });
            } else {
                $usedBatches = StockEntry::where('item_id', $item->id)
                    ->whereHas('usageLogs', fn($q) => $q->where('quantity_used', '>', 0))
                    ->get()
                    ->map(function ($entry) {
                        $usedQty = $entry->usageLogs()->sum('quantity_used');
                        $alreadyDisposed = $this->disposedUsedQtyForEntry($entry->id);
                        return max(0, $usedQty - $alreadyDisposed);
                    });
            }

            $maxQty = (int) $usedBatches->sum();
        }

        $validated = $request->validate([
            'stock_entry_id'   => 'required|array|min:1',
            'stock_entry_id.*' => 'exists:stock_entries,id',
            'reason'           => 'required|string|max:500',
            'disposed_by'      => 'required|string|max:255',
            'disposed_at'      => 'required|date',
        ]);

        $stockEntryIds = array_filter($validated['stock_entry_id']);

        DB::transaction(function () use ($item, $validated, $disposalType, $stockEntryIds, $hasStockEntryColumn, $hasTypeColumn) {
            $disposedTotal = 0;

            // Multi-batch / checklist disposal
            foreach ($stockEntryIds as $entryId) {
                $entry = StockEntry::find($entryId);
                if (!$entry) continue;

                // Remaining logic specific to each entry for quantity calculation
                if ($disposalType === 'new') {
                    $used = $entry->usageLogs()->sum('quantity_used');
                    $qty = max(0, $entry->quantity - $used);
                } else {
                    if ($item->item_type === 'device') {
                        $usedQty = $entry->usageLogs()->where('quantity_used', '>', 0)->exists() ? 1 : 0;
                    } else {
                        $usedQty = $entry->usageLogs()->where('quantity_used', '>', 0)->sum('quantity_used');
                    }

                    $alreadyDisposed = $this->disposedUsedQtyForEntry($entry->id);
                    $qty = max(0, $usedQty - $alreadyDisposed);
                }

                if ($qty <= 0) continue;

                $payload = [
                    'item_id'        => $item->id,
                    'quantity'       => $qty,
                    'disposed_by'    => $validated['disposed_by'],
                    'disposed_at'    => $validated['disposed_at'],
                    'reason'         => $validated['reason'],
                ];

                if ($hasStockEntryColumn) {
                    $payload['stock_entry_id'] = $entryId;
                }

                if ($hasTypeColumn) {
                    $payload['type'] = $disposalType === 'new' ? 'new' : 'used';
                }

                Disposal::create($payload);

                $disposedTotal += $qty;
            }

            // Only decrement stock_used for used disposals and actual disposed qty
            if ($disposalType === 'used' && $disposedTotal > 0) {
                $item->decrement('stock_used', $disposedTotal);
            }
        });

        $msg = $disposalType === 'new'
            ? 'Expired items disposed successfully.'
            : 'Used items disposed successfully.';

        return redirect()->route('items.show', $item)->with('success', $msg);
    }
}
