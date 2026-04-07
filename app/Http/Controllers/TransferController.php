<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Transfer;
use Illuminate\Http\Request;

class TransferController extends Controller
{
    public function index()
    {
        $transfers = Transfer::with('item')->orderByDesc('transferred_at')->get();

        return view('transfers.index', compact('transfers'));
    }

    public function create()
    {
        $items = Item::orderBy('name')->get();

        return view('transfers.create', compact('items'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_id'        => 'required|exists:items,id',
            'type'           => 'required|in:in,out',
            'new_quantity'   => 'nullable|integer|min:0',
            'used_quantity'  => 'nullable|integer|min:0',
            'department'     => 'required|string|max:255',
            'transferred_to' => 'required|string|max:255',
            'bio_id'         => 'required|string|max:255',
            'transferred_at' => 'required|date',
            'notes'          => 'nullable|string',
        ]);

        $newQty  = (int) ($validated['new_quantity']  ?? 0);
        $usedQty = (int) ($validated['used_quantity'] ?? 0);
        $total   = $newQty + $usedQty;

        if ($total < 1) {
            return back()->withErrors(['new_quantity' => 'Enter at least 1 for New or Used quantity.'])->withInput();
        }

        $item = Item::findOrFail($validated['item_id']);

        // Stock checks for Transfer Out
        if ($validated['type'] === 'out') {
            if ($newQty > $item->total_stock) {
                return back()->withErrors(['new_quantity' => 'Not enough new stock. Available: '.$item->total_stock])->withInput();
            }
            if ($usedQty > $item->effective_stock_used) {
                return back()->withErrors(['used_quantity' => 'Not enough used stock. Available: '.$item->effective_stock_used])->withInput();
            }
        }

        Transfer::create([
            'item_id'        => $item->id,
            'type'           => $validated['type'],
            'quantity'       => $total,
            'new_quantity'   => $newQty,
            'used_quantity'  => $usedQty,
            'destination'    => $validated['department'],
            'department'     => $validated['department'],
            'transferred_to' => $validated['transferred_to'],
            'bio_id'         => $validated['bio_id'],
            'transferred_at' => $validated['transferred_at'],
            'notes'          => $validated['notes'] ?? null,
            'approved_by'    => auth()->user()->name,
        ]);

        return redirect()->route('in-out.index', ['tab' => 'transfer'])->with('success', 'Transfer recorded successfully.');
    }
}
