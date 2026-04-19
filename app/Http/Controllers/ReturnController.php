<?php

namespace App\Http\Controllers;

use App\Models\Borrow;
use App\Models\BorrowEntry;
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
        if ($borrow->status === 'returned') {
            return redirect()->route('in-out.index', ['tab' => 'return'])->with('error', 'This borrow has already been fully processed.');
        }

        // Load device-level entries if they exist
        $borrowEntries = $borrow->borrowEntries()
            ->with('stockEntry')
            ->whereNull('disposition')
            ->get();

        return view('returns.edit', compact('borrow', 'borrowEntries'));
    }

    public function update(Request $request, Borrow $borrow)
    {
        if ($borrow->status === 'returned') {
            return redirect()->route('in-out.index', ['tab' => 'return'])->with('error', 'This borrow has already been fully processed.');
        }

        $item = $borrow->item;
        $isDeviceWithEntries = $item->item_type === 'device' && $borrow->borrowEntries()->exists();

        if ($isDeviceWithEntries) {
            // Per-device disposition mode
            $request->validate([
                'dispositions'   => 'required|array',
                'dispositions.*' => 'required|in:returned_new,returned_used,consumed',
                'returned_at'    => 'required|date',
                'notes'          => 'nullable|string',
            ]);

            $pendingEntries = $borrow->borrowEntries()->with('stockEntry')->whereNull('disposition')->get();
            $submittedIds   = array_keys($request->input('dispositions', []));

            // Only process entries that were submitted
            $toProcess = $pendingEntries->whereIn('id', $submittedIds);

            DB::transaction(function () use ($toProcess, $request, $borrow, $item) {
                $returningNew  = 0;
                $returningUsed = 0;
                $consumed      = 0;

                foreach ($toProcess as $entry) {
                    $disposition = $request->input('dispositions.' . $entry->id);
                    $entry->disposition = $disposition;
                    $entry->save();

                    match ($disposition) {
                        'returned_new'  => $returningNew++,
                        'returned_used' => $returningUsed++,
                        'consumed'      => $consumed++,
                        default         => null,
                    };
                }

                // returned_new → back to new stock (no stock_used change needed; borrow impact removed)
                // returned_used → add to used stock pool
                // consumed → permanently deducted (treat like quantity_used)
                $borrow->quantity_returned += $returningNew + $returningUsed;
                $borrow->quantity_used     += $consumed;

                if ($returningUsed > 0) {
                    $item->increment('stock_used', $returningUsed);
                }

                // Update status
                $totalProcessed = $borrow->quantity_returned + $borrow->quantity_used;
                if ($totalProcessed >= $borrow->quantity_borrowed) {
                    $borrow->status      = 'returned';
                    $borrow->returned_at = $request->input('returned_at');
                } elseif ($totalProcessed > 0) {
                    $borrow->status = 'partial';
                }

                if ($request->input('notes')) {
                    $borrow->notes = trim($borrow->notes . "\nReturn Notes: " . $request->input('notes'));
                }

                $borrow->save();
            });

            return redirect()->route('in-out.index', ['tab' => 'return'])->with('success', 'Return processed successfully.');
        }

        // ── Fallback: non-device or legacy borrow (quantity-based) ──
        $maxReturnable = $borrow->quantity_borrowed - $borrow->quantity_returned - $borrow->quantity_used;

        $validated = $request->validate([
            'quantity_returning' => 'required|integer|min:0|max:' . $maxReturnable,
            'quantity_using'     => 'required|integer|min:0|max:' . $maxReturnable,
            'returned_at'        => 'required|date',
            'notes'              => 'nullable|string',
        ]);

        if ($maxReturnable < $validated['quantity_returning'] + $validated['quantity_using']) {
            return back()->withErrors(['quantity_returning' => 'Total of returning and using cannot exceed ' . $maxReturnable])->withInput();
        }

        DB::transaction(function () use ($validated, $borrow, $item) {
            $borrow->quantity_returned += $validated['quantity_returning'];
            $borrow->quantity_used     += $validated['quantity_using'];

            if ($item->item_type !== 'consumable') {
                $quantityToMove = $validated['quantity_returning'] + $validated['quantity_using'];
                if ($quantityToMove > 0) {
                    $item->increment('stock_used', $quantityToMove);
                }
            }

            $totalProcessed = $borrow->quantity_returned + $borrow->quantity_used;
            if ($totalProcessed >= $borrow->quantity_borrowed) {
                $borrow->status      = 'returned';
                $borrow->returned_at = $validated['returned_at'];
            } elseif ($totalProcessed > 0) {
                $borrow->status = 'partial';
            }

            if ($validated['notes']) {
                $borrow->notes = trim($borrow->notes . "\nReturn Notes: " . $validated['notes']);
            }

            $borrow->save();
        });

        return redirect()->route('in-out.index', ['tab' => 'return'])->with('success', 'Return processed successfully.');
    }
}
