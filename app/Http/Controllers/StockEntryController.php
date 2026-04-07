<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\StockEntry;
use Illuminate\Http\Request;

class StockEntryController extends Controller
{
    public function create(Item $item)
    {
        return view('stock.create', compact('item'));
    }

    public function store(Request $request, Item $item)
    {
        $validated = $request->validate([
            'condition' => 'required|in:new,used',
            'quantity' => 'required|integer|min:1',
            'lot_number' => 'nullable|string|max:255',
            'expiry_date' => 'nullable|date|after:today',
            'received_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        if ($validated['condition'] === 'used') {
            $item->increment('stock_used', $validated['quantity']);
        } else {
            $validated['item_id'] = $item->id;
            StockEntry::create($validated);
        }

        return redirect()->route('items.show', $item)
            ->with('success', 'Stock received successfully.');
    }
}
