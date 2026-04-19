<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Staff;
use App\Models\UsageLog;
use Illuminate\Http\Request;

class UsageLogController extends Controller
{
    public function create(Request $request)
    {
        $items = Item::orderBy('name')->get();
        $staffList = Staff::orderBy('name')->get();

        return view('usage.create', compact('items', 'staffList'));
    }

    public function store(Request $request)
    {
        // Detect device checkbox mode (selected_entries[] submitted)
        $hasSelectedEntries = $request->has('selected_entries')
            && is_array($request->selected_entries)
            && count($request->selected_entries) > 0;

        // Build validation rules — quantity_used is auto-computed for device checkbox mode
        $rules = [
            'item_id'    => 'required|exists:items,id',
            'stock_type' => 'required|in:new,used',
            'used_by'    => 'nullable|string|max:255',
            'used_at'    => 'required|date',
            'notes'      => 'nullable|string',
            'lot_number' => 'nullable|string',
        ];

        if (!$hasSelectedEntries) {
            $rules['quantity_used'] = 'required|integer|min:1';
        }

        $validated = $request->validate($rules);

        $item = Item::findOrFail($validated['item_id']);

        // ── DEVICE CHECKBOX MODE ──────────────────────────────────────────
        if ($hasSelectedEntries) {
            $entryIds     = array_map('intval', $request->selected_entries);
            $quantityUsed = count($entryIds);

            if ($quantityUsed > $item->total_stock) {
                return back()->withErrors([
                    'selected_entries' => "Not enough New stock. You selected {$quantityUsed} but only {$item->total_stock} available.",
                ])->withInput();
            }

            \Illuminate\Support\Facades\DB::transaction(function () use ($entryIds, $item, $validated, $quantityUsed) {
                foreach ($entryIds as $entryId) {
                    UsageLog::create(array_merge($validated, [
                        'quantity_used'  => 1,
                        'stock_entry_id' => $entryId,
                        'stock_type'     => 'new',
                    ]));
                }

                // Reusable devices move to used stock
                if ($item->item_type !== 'consumable') {
                    $item->increment('stock_used', $quantityUsed);
                }
            });

            return redirect()->route('items.show', $validated['item_id'])
                ->with('success', "{$quantityUsed} device(s) logged as used successfully.");
        }

        // ── STANDARD MODE (consumables / used-stock) ──────────────────────
        if ($validated['stock_type'] === 'new') {
            if ($validated['quantity_used'] > $item->total_stock) {
                return back()->withErrors(['quantity_used' => 'Not enough New stock. Available: '.$item->total_stock])
                    ->withInput();
            }
        } else {
            if ($validated['quantity_used'] > $item->effective_stock_used) {
                return back()->withErrors(['quantity_used' => 'Not enough Used stock. Available: '.$item->effective_stock_used])
                    ->withInput();
            }
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($validated, $item) {
            if ($validated['stock_type'] === 'used') {
                UsageLog::create($validated);
                $item->decrement('stock_used', $validated['quantity_used']);
            } else {
                // For expirable items with a specific lot_number selected, sort batches to put that lot first
                $batches = $item->batches_breakdown;
                if ($item->is_expirable && !empty($validated['lot_number'])) {
                    $selectedLot = $validated['lot_number'];
                    usort($batches, function ($a, $b) use ($selectedLot) {
                        if (($a['lot_number'] ?? '') === $selectedLot && ($b['lot_number'] ?? '') !== $selectedLot) return -1;
                        if (($b['lot_number'] ?? '') === $selectedLot && ($a['lot_number'] ?? '') !== $selectedLot) return 1;
                        return ($a['expiry_date'] ?? '9999-12-31') <=> ($b['expiry_date'] ?? '9999-12-31');
                    });
                }

                $remainingToLog = $validated['quantity_used'];
                foreach ($batches as $batchData) {
                    if ($remainingToLog <= 0) break;
                    $take = min($remainingToLog, $batchData['remaining']);
                    UsageLog::create(array_merge($validated, [
                        'quantity_used'  => $take,
                        'stock_entry_id' => $batchData['id'],
                    ]));
                    $remainingToLog -= $take;
                }
                // Fallback
                if ($remainingToLog > 0) {
                    UsageLog::create(array_merge($validated, ['quantity_used' => $remainingToLog]));
                }

                if ($item->item_type !== 'consumable') {
                    $item->increment('stock_used', $validated['quantity_used']);
                }
            }
        });

        return redirect()->route('items.show', $validated['item_id'])
            ->with('success', 'Usage logged successfully.');
    }
}
