<?php

namespace App\Http\Controllers;

use App\Models\Borrow;
use App\Models\BorrowEntry;
use App\Models\Item;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BorrowController extends Controller
{
    public function index()
    {
        $activeBorrows = Borrow::with(['item', 'staff'])
            ->whereIn('status', ['active', 'partial'])
            ->orderByDesc('borrowed_at')
            ->get();

        $historyBorrows = Borrow::with(['item', 'staff'])
            ->where('status', 'returned')
            ->orderByDesc('borrowed_at')
            ->get();

        return view('borrows.index', compact('activeBorrows', 'historyBorrows'));
    }

    public function create()
    {
        $items = Item::orderBy('name')->get();
        $categories = \App\Models\Category::orderBy('name')->get();
        $units = \App\Models\Unit::orderBy('name')->get();
        $staffMembers = Staff::orderBy('name')->get();

        return view('borrows.create', compact('items', 'categories', 'units', 'staffMembers'));
    }

    public function store(Request $request)
    {
        $hasSelectedEntries = $request->has('selected_entries') && is_array($request->selected_entries) && count($request->selected_entries) > 0;
        $hasSelectedEntries = $request->has('selected_entries') && is_array($request->selected_entries) && count($request->selected_entries) > 0;

        $validated = $request->validate([
            'item_id'           => 'required|exists:items,id',
            'borrower_name'     => 'required|string|max:255',
            'bio_id'            => 'required|string|max:255',
            'department'        => 'required|string|max:255',
            'new_quantity'      => $hasSelectedEntries ? 'nullable' : 'nullable|integer|min:0',
            'used_quantity'     => $hasSelectedEntries ? 'nullable' : 'nullable|integer|min:0',
            'borrowed_at'       => 'required|date',
            'serial_number'     => 'nullable|string|max:255',
            'notes'             => 'nullable|string',
        ]);

        $validated['type'] = 'out';

        if ($hasSelectedEntries) {
            $stockEntries = \App\Models\StockEntry::whereIn('id', $request->selected_entries)->get();
            $newQty  = 0;
            $usedQty = 0;
            foreach ($stockEntries as $entry) {
                $isUsed = (bool) preg_match('/\[USED\]/i', $entry->serial_number ?? '') ||
                          $entry->borrowEntries()->where('disposition', 'returned_used')->exists();
                if ($isUsed) {
                    $usedQty++;
                } else {
                    $newQty++;
                }
            }
            $validated['quantity_borrowed'] = $newQty + $usedQty;
            $validated['serial_number'] = $stockEntries->pluck('serial_number')->filter()->implode(', ');
        } else {
            $newQty  = (int) ($validated['new_quantity'] ?? 0);
            $usedQty = (int) ($validated['used_quantity'] ?? 0);
            $validated['quantity_borrowed'] = $newQty + $usedQty;
            
            if ($validated['quantity_borrowed'] < 1) {
                return back()->withErrors(['new_quantity' => 'Total borrow quantity must be at least 1.'])->withInput();
            }
        }

        $item = Item::findOrFail($validated['item_id']);

        if ($validated['type'] === 'out') {
            if ($newQty > $item->total_stock) {
                return back()->withErrors(['quantity_borrowed' => 'Not enough new stock. Available: '.$item->total_stock])->withInput();
            }
            if ($usedQty > $item->effective_stock_used) {
                return back()->withErrors(['quantity_borrowed' => 'Not enough used stock. Available: '.$item->effective_stock_used])->withInput();
            }
        }

        $borrow = Borrow::create(array_merge($validated, [
            'status'            => 'active',
            'new_quantity'      => $newQty,
            'used_quantity'     => $usedQty,
            'quantity_returned' => 0,
            'quantity_used'     => 0,
            'approved_by'       => auth()->user()->name,
        ]));

        // Create per-device borrow entries for devices so we can track return disposition
        if ($hasSelectedEntries && $item->item_type === 'device' && $validated['type'] === 'out') {
            foreach ($stockEntries as $entry) {
                $isUsed = (bool) preg_match('/\[USED\]/i', $entry->serial_number ?? '') ||
                          $entry->borrowEntries()->where('disposition', 'returned_used')->exists();
                $condition = $isUsed ? 'used' : 'new';
                BorrowEntry::create([
                    'borrow_id'          => $borrow->id,
                    'stock_entry_id'     => $entry->id,
                    'original_condition' => $condition,
                    'disposition'        => null,
                ]);
            }
        }

        return redirect()->route('in-out.index', ['tab' => 'borrow'])->with('success', 'Item borrowed successfully.');
    }
}
