<?php

namespace App\Http\Controllers;

use App\Models\Borrow;
use App\Models\Item;
use App\Models\Staff;
use Illuminate\Http\Request;

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
        $staffMembers = Staff::orderBy('name')->get();

        return view('borrows.create', compact('items', 'staffMembers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'type' => 'required|in:in,out',
            'source_department' => 'nullable|string|max:255',
            'borrower_name' => 'required|string|max:255',
            'bio_id' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'quantity_borrowed' => 'required|integer|min:1',
            'borrowed_at' => 'required|date',
            'return_date' => 'nullable|date|after_or_equal:borrowed_at',
            'notes' => 'nullable|string',
        ]);

        $item = Item::findOrFail($validated['item_id']);

        if ($validated['type'] === 'out' && $validated['quantity_borrowed'] > $item->total_stock) {
            return back()->withErrors(['quantity_borrowed' => 'Not enough stock. Available: '.$item->total_stock])->withInput();
        }

        Borrow::create(array_merge($validated, [
            'status' => 'active',
            'quantity_returned' => 0,
            'quantity_used' => 0,
            'approved_by' => auth()->user()->name,
        ]));

        return redirect()->route('in-out.index', ['tab' => 'borrow'])->with('success', 'Item borrowed successfully.');
    }
}
