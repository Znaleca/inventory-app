<?php

namespace App\Http\Controllers;

use App\Models\Disposal;
use App\Models\Item;
use App\Models\Staff;
use App\Models\StockEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DisposalController extends Controller
{
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
                        return array_merge($entry->toArray(), ['remaining' => 1]);
                    })
                    ->values();
            } else {
                $batches = StockEntry::where('item_id', $item->id)
                    ->whereHas('usageLogs', fn($q) => $q->where('quantity_used', '>', 0))
                    ->orderBy('received_date')
                    ->get()
                    ->map(function ($entry) {
                        $usedQty = $entry->usageLogs()->sum('quantity_used');
                        return array_merge($entry->toArray(), ['remaining' => $usedQty]);
                    })
                    ->filter(fn($e) => $e['remaining'] > 0)
                    ->values();
            }

            if ($batches->isEmpty() && $item->stock_used <= 0) {
                return redirect()->route('items.show', $item)->with('error', 'No used stock available to dispose.');
            }

            $maxQty = $item->stock_used;
        }

        $staffList = Staff::orderBy('name')->get();

        return view('disposals.create', compact('item', 'staffList', 'disposalType', 'maxQty', 'batches'));
    }

    public function store(Request $request)
    {
        $item = Item::findOrFail($request->item_id);
        $disposalType = $request->input('disposal_type', 'used');

        $maxQty = $disposalType === 'new'
            ? StockEntry::where('item_id', $item->id)
                ->whereNotNull('expiry_date')
                ->where('expiry_date', '<', now()->startOfDay())
                ->sum('quantity')
            : $item->stock_used;

        $validated = $request->validate([
            'stock_entry_id' => 'nullable|exists:stock_entries,id',
            'quantity'       => 'required|integer|min:1|max:' . max(1, $maxQty),
            'reason'         => 'required|string|max:500',
            'disposed_by'    => 'required|string|max:255',
            'disposed_at'    => 'required|date',
        ]);

        DB::transaction(function () use ($item, $validated, $disposalType) {
            // Only decrement stock_used for used disposals
            if ($disposalType === 'used') {
                $item->decrement('stock_used', $validated['quantity']);
            }

            Disposal::create([
                'item_id'        => $item->id,
                'stock_entry_id' => $validated['stock_entry_id'] ?? null,
                'type'           => $disposalType === 'new' ? 'new' : 'used',
                'quantity'       => $validated['quantity'],
                'disposed_by'    => $validated['disposed_by'],
                'disposed_at'    => $validated['disposed_at'],
                'reason'         => $validated['reason'],
            ]);
        });

        $msg = $disposalType === 'new'
            ? 'Expired items disposed successfully.'
            : 'Used items disposed successfully.';

        return redirect()->route('items.show', $item)->with('success', $msg);
    }
}
