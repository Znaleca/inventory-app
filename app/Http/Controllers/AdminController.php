<?php

namespace App\Http\Controllers;

use App\Models\Borrow;
use App\Models\Disposal;
use App\Models\Item;
use App\Models\StockEntry;
use App\Models\Transfer;
use App\Models\UsageLog;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Show the admin record management dashboard with tabs.
     */
    public function index(Request $request)
    {
        $tab = $request->input('tab', 'stock-entries');

        $stockEntries = StockEntry::with('item')->latest()->get();
        $usageLogs = UsageLog::with('item')->latest()->get();
        $borrows = Borrow::with(['item', 'staff'])->latest()->get();
        $returns = Borrow::with(['item', 'staff'])->whereNotNull('returned_at')->latest('returned_at')->get();
        $transfers = Transfer::with('item')->latest()->get();
        $disposals = Disposal::with('item')->latest()->get();

        $items = Item::with('category')->latest()->get();

        // Prepare 7-day trend data for the chart
        $sevenDayTrend = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = \Carbon\Carbon::today()->subDays($i);
            $dateStr = $date->toDateString();
            $sevenDayTrend[$dateStr] = [
                'date' => $date->format('M d'),
                'in' => $stockEntries->filter(fn($s) => ($s->received_date instanceof \Carbon\Carbon ? $s->received_date : \Carbon\Carbon::parse($s->received_date))->toDateString() === $dateStr)->sum('quantity') +
                        $returns->filter(fn($r) => ($r->returned_at ? ($r->returned_at instanceof \Carbon\Carbon ? $r->returned_at : \Carbon\Carbon::parse($r->returned_at)) : $r->updated_at)->toDateString() === $dateStr)->sum(fn($r) => $r->quantity_returned + ($r->item->item_type === 'consumable' ? 0 : $r->quantity_used)),
                'out' => $usageLogs->filter(fn($u) => ($u->used_at instanceof \Carbon\Carbon ? $u->used_at : \Carbon\Carbon::parse($u->used_at))->toDateString() === $dateStr)->sum('quantity_used') +
                         $transfers->filter(fn($t) => ($t->transferred_at instanceof \Carbon\Carbon ? $t->transferred_at : \Carbon\Carbon::parse($t->transferred_at))->toDateString() === $dateStr)->sum('quantity') +
                         $disposals->filter(fn($d) => ($d->disposed_at instanceof \Carbon\Carbon ? $d->disposed_at : \Carbon\Carbon::parse($d->disposed_at))->toDateString() === $dateStr)->sum('quantity') +
                         $borrows->filter(fn($b) => ($b->borrowed_at instanceof \Carbon\Carbon ? $b->borrowed_at : \Carbon\Carbon::parse($b->borrowed_at))->toDateString() === $dateStr)->sum('quantity_borrowed'),
            ];
        }

        // Calculate totals for each category
        $totalStock = $stockEntries->sum('quantity');
        $totalUsage = $usageLogs->sum('quantity_used');
        $totalBorrow = $borrows->sum('quantity_borrowed');
        $totalReturn = $returns->sum(fn($r) => $r->quantity_returned + ($r->item->item_type === 'consumable' ? 0 : $r->quantity_used));
        $totalTransfer = $transfers->sum('quantity');
        $totalDisposal = $disposals->sum('quantity');
        $totalItems = $items->count();

        return view('admin.index', compact(
            'tab', 'stockEntries', 'usageLogs', 'borrows', 'returns', 'transfers', 'disposals', 'items', 'sevenDayTrend',
            'totalStock', 'totalUsage', 'totalBorrow', 'totalReturn', 'totalTransfer', 'totalDisposal', 'totalItems'
        ));
    }

    // ── Stock Entries ──

    public function editStockEntry(StockEntry $stockEntry)
    {
        $stockEntry->load('item');
        return view('admin.edit-stock-entry', compact('stockEntry'));
    }

    public function updateStockEntry(Request $request, StockEntry $stockEntry)
    {
        $rules = [
            'quantity'      => 'required|integer|min:0',
            'received_date' => 'nullable|date',
            'notes'         => 'nullable|string',
        ];

        if ($stockEntry->item->item_type === 'device') {
            $rules['serial_number'] = 'nullable|string|max:255';
        } else {
            $rules['lot_number']  = 'nullable|string|max:255';
            $rules['expiry_date'] = 'nullable|date';
        }

        $validated = $request->validate($rules);

        if ($stockEntry->item->item_type === 'device') {
            $validated['serial_number'] = empty(trim((string)$request->input('serial_number'))) ? 'N/A' : trim((string)$request->input('serial_number'));
        }

        $stockEntry->update($validated);

        return redirect()->route('admin.records.index', ['tab' => 'stock-entries'])
            ->with('success', 'Stock entry updated successfully.');
    }

    public function destroyStockEntry(StockEntry $stockEntry)
    {
        $stockEntry->delete();

        return redirect()->route('admin.records.index', ['tab' => 'stock-entries'])
            ->with('success', 'Stock entry deleted.');
    }

    // ── Usage Logs ──

    public function editUsageLog(UsageLog $usageLog)
    {
        $usageLog->load('item');
        $stockEntries = [];
        if ($usageLog->item && $usageLog->item->item_type === 'device') {
            $stockEntries = \App\Models\StockEntry::where('item_id', $usageLog->item_id)
                ->whereNotNull('serial_number')
                ->where('serial_number', '!=', 'N/A')
                ->get();
        }
        return view('admin.edit-usage-log', compact('usageLog', 'stockEntries'));
    }

    public function updateUsageLog(Request $request, UsageLog $usageLog)
    {
        $validated = $request->validate([
            'quantity_used'  => 'required|integer|min:0',
            'used_at'        => 'nullable|date',
            'notes'          => 'nullable|string',
            'used_by'        => 'nullable|string|max:255',
            'stock_entry_id' => 'nullable|exists:stock_entries,id'
        ]);

        $usageLog->update($validated);

        return redirect()->route('admin.records.index', ['tab' => 'usage-logs'])
            ->with('success', 'Usage log updated successfully.');
    }

    public function destroyUsageLog(UsageLog $usageLog)
    {
        $usageLog->delete();

        return redirect()->route('admin.records.index', ['tab' => 'usage-logs'])
            ->with('success', 'Usage log deleted.');
    }

    // ── Borrows ──

    public function editBorrow(Borrow $borrow)
    {
        $borrow->load(['item', 'staff']);
        
        $deviceSerials = [];
        if ($borrow->item && $borrow->item->item_type === 'device') {
            $stockEntries = \App\Models\StockEntry::where('item_id', $borrow->item_id)->get();
            foreach ($stockEntries as $entry) {
                if ($entry->serial_number && $entry->serial_number !== 'N/A') {
                    $serials = array_map('trim', explode(',', $entry->serial_number));
                    foreach ($serials as $s) {
                        if (!in_array($s, $deviceSerials) && $s !== '') {
                            $deviceSerials[] = $s;
                        }
                    }
                }
            }
            sort($deviceSerials);
        }

        return view('admin.edit-borrow', compact('borrow', 'deviceSerials'));
    }

    public function updateBorrow(Request $request, Borrow $borrow)
    {
        $rules = [
            'quantity_borrowed' => 'required|integer|min:0',
            'quantity_returned' => 'required|integer|min:0',
            'quantity_used'     => 'required|integer|min:0',
            'status'            => 'required|in:active,partial,returned',
            'department'        => 'nullable|string|max:255',
            'bio_id'            => 'nullable|string|max:255',
            'borrowed_at'       => 'required|date',
            'returned_at'       => 'nullable|date',
            'notes'             => 'nullable|string',
        ];

        if ($borrow->item && $borrow->item->item_type === 'device') {
            if ($borrow->type === 'out') {
                $rules['serial_number'] = 'nullable|array';
            } else {
                $rules['serial_number'] = 'nullable|string|max:255';
            }
        }

        $validated = $request->validate($rules);

        if ($borrow->item && $borrow->item->item_type === 'device') {
            if ($borrow->type === 'out' && is_array($request->input('serial_number'))) {
                $validated['serial_number'] = empty($request->input('serial_number')) ? 'N/A' : implode(', ', $request->input('serial_number'));
            } else {
                $validated['serial_number'] = empty(trim((string)$request->input('serial_number'))) ? 'N/A' : trim((string)$request->input('serial_number'));
            }
        }

        $borrow->update($validated);

        return redirect()->route('admin.records.index', ['tab' => 'borrows'])
            ->with('success', 'Borrow record updated successfully.');
    }

    public function destroyBorrow(Borrow $borrow)
    {
        $borrow->delete();

        return redirect()->route('admin.records.index', ['tab' => 'borrows'])
            ->with('success', 'Borrow record deleted.');
    }

    // ── Transfers ──

    public function editTransfer(Transfer $transfer)
    {
        $transfer->load('item');
        
        $deviceSerials = [];
        if ($transfer->item && $transfer->item->item_type === 'device') {
            $stockEntries = \App\Models\StockEntry::where('item_id', $transfer->item_id)->get();
            foreach ($stockEntries as $entry) {
                if ($entry->serial_number && $entry->serial_number !== 'N/A') {
                    $serials = array_map('trim', explode(',', $entry->serial_number));
                    foreach ($serials as $s) {
                        if (!in_array($s, $deviceSerials) && $s !== '') {
                            $deviceSerials[] = $s;
                        }
                    }
                }
            }
            sort($deviceSerials);
        }

        return view('admin.edit-transfer', compact('transfer', 'deviceSerials'));
    }

    public function updateTransfer(Request $request, Transfer $transfer)
    {
        $rules = [
            'quantity'        => 'required|integer|min:0',
            'destination'     => 'required|string|max:255',
            'transferred_by'  => 'nullable|string|max:255',
            'bio_id'          => 'nullable|string|max:255',
            'transferred_at'  => 'required|date',
            'notes'           => 'nullable|string',
        ];

        if ($transfer->item && $transfer->item->item_type === 'device') {
            if ($transfer->type === 'out') {
                $rules['serial_number'] = 'nullable|array';
            } else {
                $rules['serial_number'] = 'nullable|string|max:255';
            }
        }

        $validated = $request->validate($rules);

        if ($transfer->item && $transfer->item->item_type === 'device') {
            if ($transfer->type === 'out' && is_array($request->input('serial_number'))) {
                $validated['serial_number'] = empty($request->input('serial_number')) ? 'N/A' : implode(', ', $request->input('serial_number'));
            } else {
                $validated['serial_number'] = empty(trim((string)$request->input('serial_number'))) ? 'N/A' : trim((string)$request->input('serial_number'));
            }
        }

        $transfer->update($validated);

        return redirect()->route('admin.records.index', ['tab' => 'transfers'])
            ->with('success', 'Transfer record updated successfully.');
    }

    public function destroyTransfer(Transfer $transfer)
    {
        $transfer->delete();

        return redirect()->route('admin.records.index', ['tab' => 'transfers'])
            ->with('success', 'Transfer record deleted.');
    }

    // ── Disposals ──

    public function editDisposal(Disposal $disposal)
    {
        $disposal->load('item');
        $stockEntries = [];
        if ($disposal->item && $disposal->item->item_type === 'device') {
            $stockEntries = \App\Models\StockEntry::where('item_id', $disposal->item_id)
                ->whereNotNull('serial_number')
                ->where('serial_number', '!=', 'N/A')
                ->get();
        }
        return view('admin.edit-disposal', compact('disposal', 'stockEntries'));
    }

    public function updateDisposal(Request $request, Disposal $disposal)
    {
        $validated = $request->validate([
            'quantity'       => 'required|integer|min:0',
            'reason'         => 'required|string',
            'disposed_by'    => 'nullable|string|max:255',
            'disposed_at'    => 'required|date',
            'stock_entry_id' => 'nullable|exists:stock_entries,id'
        ]);

        $disposal->update($validated);

        return redirect()->route('admin.records.index', ['tab' => 'disposals'])
            ->with('success', 'Disposal record updated successfully.');
    }

    public function destroyDisposal(Disposal $disposal)
    {
        $disposal->delete();

        return redirect()->route('admin.records.index', ['tab' => 'disposals'])
            ->with('success', 'Disposal record deleted.');
    }
}
