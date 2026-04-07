<?php

namespace App\Http\Controllers;

use App\Models\Borrow;
use App\Models\Transfer;

class InOutController extends Controller
{
    /**
     * Show the combined In and Out page with tabs for Transfer, Borrow, and Return.
     */
    public function index()
    {
        // Transfers
        $transfers = Transfer::with('item')->orderByDesc('transferred_at')->get();

        // Borrows — active/partial
        $activeBorrows = Borrow::with(['item', 'staff'])
            ->whereIn('status', ['active', 'partial'])
            ->orderByDesc('borrowed_at')
            ->get();

        // Borrows — history
        $historyBorrows = Borrow::with(['item', 'staff'])
            ->where('status', 'returned')
            ->orderByDesc('borrowed_at')
            ->get();

        // Returns — pending (same active borrows, different context)
        $pendingReturns = Borrow::with(['item', 'staff'])
            ->whereIn('status', ['active', 'partial'])
            ->orderBy('borrowed_at')
            ->get();

        // Returns — history
        $returnHistory = Borrow::with(['item', 'staff'])
            ->where('status', 'returned')
            ->orderByDesc('returned_at')
            ->get();

        return view('in-out.index', compact(
            'transfers',
            'activeBorrows', 'historyBorrows',
            'pendingReturns', 'returnHistory'
        ));
    }
}
