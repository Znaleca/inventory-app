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

        return view('admin.index', compact(
            'tab', 'stockEntries', 'usageLogs', 'borrows', 'returns', 'transfers', 'disposals', 'items'
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
        $validated = $request->validate([
            'quantity'      => 'required|integer|min:0',
            'lot_number'    => 'nullable|string|max:255',
            'expiry_date'   => 'nullable|date',
            'received_date' => 'nullable|date',
            'notes'         => 'nullable|string',
        ]);

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
        return view('admin.edit-usage-log', compact('usageLog'));
    }

    public function updateUsageLog(Request $request, UsageLog $usageLog)
    {
        $validated = $request->validate([
            'quantity_used'  => 'required|integer|min:0',
            'patient_id'     => 'nullable|string|max:255',
            'procedure_type' => 'nullable|string|max:255',
            'used_by'        => 'nullable|string|max:255',
            'used_at'        => 'nullable|date',
            'notes'          => 'nullable|string',
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
        return view('admin.edit-borrow', compact('borrow'));
    }

    public function updateBorrow(Request $request, Borrow $borrow)
    {
        $validated = $request->validate([
            'quantity_borrowed' => 'required|integer|min:0',
            'quantity_returned' => 'required|integer|min:0',
            'quantity_used'     => 'required|integer|min:0',
            'status'            => 'required|in:active,partial,returned',
            'department'        => 'nullable|string|max:255',
            'bio_id'            => 'nullable|string|max:255',
            'borrowed_at'       => 'required|date',
            'returned_at'       => 'nullable|date',
            'notes'             => 'nullable|string',
        ]);

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
        return view('admin.edit-transfer', compact('transfer'));
    }

    public function updateTransfer(Request $request, Transfer $transfer)
    {
        $validated = $request->validate([
            'quantity'        => 'required|integer|min:0',
            'destination'     => 'required|string|max:255',
            'transferred_by'  => 'nullable|string|max:255',
            'bio_id'          => 'nullable|string|max:255',
            'transferred_at'  => 'required|date',
            'notes'           => 'nullable|string',
        ]);

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
        return view('admin.edit-disposal', compact('disposal'));
    }

    public function updateDisposal(Request $request, Disposal $disposal)
    {
        $validated = $request->validate([
            'quantity'    => 'required|integer|min:0',
            'reason'      => 'required|string',
            'disposed_by' => 'nullable|string|max:255',
            'disposed_at' => 'required|date',
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
