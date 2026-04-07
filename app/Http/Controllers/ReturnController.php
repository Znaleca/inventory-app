<?php

namespace App\Http\Controllers;

use App\Models\Borrow;
use App\Models\UsageLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReturnController extends Controller
{
    public function index()
    {
        // Active borrows needing return
        $activeBorrows = Borrow::with(['item', 'staff'])
            ->whereIn('status', ['active', 'partial'])
            ->orderBy('borrowed_at')
            ->get();

        // Completed returns (History)
        $historyBorrows = Borrow::with(['item', 'staff'])
            ->where('status', 'returned')
            ->orderByDesc('returned_at')
            ->get();

        return view('returns.index', compact('activeBorrows', 'historyBorrows'));
    }

    public function edit(Borrow $borrow)
    {
        // A "return" is just updating a borrow record
        if ($borrow->status === 'returned') {
            return redirect()->route('in-out.index', ['tab' => 'return'])->with('error', 'This borrow has already been fully processed.');
        }

        return view('returns.edit', compact('borrow'));
    }

    public function update(Request $request, Borrow $borrow)
    {
        if ($borrow->status === 'returned') {
            return redirect()->route('in-out.index', ['tab' => 'return'])->with('error', 'This borrow has already been fully processed.');
        }

        $maxReturnable = $borrow->quantity_borrowed - $borrow->quantity_returned - $borrow->quantity_used;

        $validated = $request->validate([
            'quantity_returning' => 'required|integer|min:0|max:'.$maxReturnable,
            'quantity_using' => 'required|integer|min:0|max:'.$maxReturnable,
            'returned_at' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        if ($maxReturnable < $validated['quantity_returning'] + $validated['quantity_using']) {
            return back()->withErrors(['quantity_returning' => 'Total of returning and using cannot exceed '.$maxReturnable])->withInput();
        }

        DB::transaction(function () use ($validated, $borrow) {
            $borrow->quantity_returned += $validated['quantity_returning'];
            $borrow->quantity_used += $validated['quantity_using'];

            $item = $borrow->item;

            if ($item->is_one_time_use) {
                // Disposable: nothing moves to used stock. 
                // quantity_using is permanently consumed (not returned to pool).
                // quantity_returning will naturally be back in 'new' pool due to model formula updates.
            } else {
                // Reusable: BOTH returned and used quantities move to the 'Used' stock pool.
                $quantityToMove = $validated['quantity_returning'] + $validated['quantity_using'];
                if ($quantityToMove > 0) {
                    $item->increment('stock_used', $quantityToMove);
                }
            }

            // Update borrow status
            $totalProcessed = $borrow->quantity_returned + $borrow->quantity_used;
            if ($totalProcessed >= $borrow->quantity_borrowed) {
                $borrow->status = 'returned';
                $borrow->returned_at = $validated['returned_at'];
            } elseif ($totalProcessed > 0) {
                $borrow->status = 'partial';
            }

            if ($validated['notes']) {
                $borrow->notes = trim($borrow->notes."\nReturn Notes: ".$validated['notes']);
            }

            $borrow->save();
        });

        return redirect()->route('in-out.index', ['tab' => 'return'])->with('success', 'Return processed successfully.');
    }
}
