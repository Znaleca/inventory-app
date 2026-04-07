<?php

namespace App\Http\Controllers;

use App\Models\Disposal;
use App\Models\Item;
use App\Models\Staff;
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
            $expiredCount = $item->stockEntries()
                ->whereNotNull('expiry_date')
                ->where('expiry_date', '<', now()->startOfDay())
                ->sum('quantity');

            if ($expiredCount <= 0) {
                return redirect()->route('items.show', $item)->with('error', 'No expired batches to dispose.');
            }

            $maxQty = $expiredCount;
        } else {
            if ($item->stock_used <= 0) {
                return redirect()->route('items.show', $item)->with('error', 'No used stock available to dispose.');
            }
            $maxQty = $item->stock_used;
        }

        $staffList = Staff::orderBy('name')->get();

        return view('disposals.create', compact('item', 'staffList', 'disposalType', 'maxQty'));
    }

    public function store(Request $request)
    {
        $item = Item::findOrFail($request->item_id);
        $disposalType = $request->input('disposal_type', 'used');

        $maxQty = $disposalType === 'new'
            ? $item->stockEntries()->whereNotNull('expiry_date')->where('expiry_date', '<', now()->startOfDay())->sum('quantity')
            : $item->stock_used;

        $validated = $request->validate([
            'quantity'    => 'required|integer|min:1|max:'.$maxQty,
            'reason'      => 'required|string|max:500',
            'disposed_by' => 'required|string|max:255',
            'disposed_at' => 'required|date',
        ]);

        DB::transaction(function () use ($item, $validated, $disposalType) {
            // Only decrement stock_used for used disposals
            if ($disposalType === 'used') {
                $item->decrement('stock_used', $validated['quantity']);
            }

            Disposal::create([
                'item_id'     => $item->id,
                'type'        => $disposalType === 'new' ? 'new' : 'used',
                'quantity'    => $validated['quantity'],
                'disposed_by' => $validated['disposed_by'],
                'disposed_at' => $validated['disposed_at'],
                'reason'      => $validated['reason'],
            ]);
        });

        $msg = $disposalType === 'new'
            ? 'Expired items disposed successfully.'
            : 'Used items disposed successfully.';

        return redirect()->route('items.show', $item)->with('success', $msg);
    }
}
