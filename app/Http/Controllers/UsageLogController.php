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
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity_used' => 'required|integer|min:1',
            'stock_type' => 'required|in:new,used',
            'patient_id' => 'nullable|string|max:255',
            'procedure_type' => 'nullable|string|max:255',
            'used_by' => 'nullable|string|max:255',
            'used_at' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $item = Item::findOrFail($validated['item_id']);

        if ($validated['stock_type'] === 'new') {
            if ($validated['quantity_used'] > $item->total_stock) {
                return back()->withErrors(['quantity_used' => 'Not enough New stock. Available: '.$item->total_stock])
                    ->withInput();
            }
        } else {
            if ($validated['quantity_used'] > $item->stock_used) {
                return back()->withErrors(['quantity_used' => 'Not enough Used stock. Available: '.$item->stock_used])
                    ->withInput();
            }
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($validated, $item) {
            if ($validated['stock_type'] === 'used') {
                UsageLog::create($validated);
                $item->decrement('stock_used', $validated['quantity_used']);
            } else {
                // FIFO Batch Selection for 'new' stock
                $remainingToLog = $validated['quantity_used'];
                $batches = $item->batches_breakdown;

                foreach ($batches as $batchData) {
                    if ($remainingToLog <= 0) break;

                    $take = min($remainingToLog, $batchData['remaining']);
                    
                    $logData = $validated;
                    $logData['quantity_used'] = $take;
                    $logData['stock_entry_id'] = $batchData['id'];
                    
                    UsageLog::create($logData);
                    
                    $remainingToLog -= $take;
                }

                // If item is REUSABLE (not one-time use), automatically move it to "Used" stock
                if (!$item->is_one_time_use) {
                    $item->increment('stock_used', $validated['quantity_used']);
                }

                // Fallback: If for some reason we still have remaining (e.g. unlinked items or rounding)
                if ($remainingToLog > 0) {
                    $logData = $validated;
                    $logData['quantity_used'] = $remainingToLog;
                    UsageLog::create($logData);
                }
            }
        });

        return redirect()->route('items.show', $validated['item_id'])
            ->with('success', 'Usage logged successfully.');
    }
}
