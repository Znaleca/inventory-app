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
        if ($item->item_type === 'consumable' && $request->input('condition') === 'used') {
            return redirect()->back()->with('error', 'Used stock cannot be added to a consumable item.');
        }
        $rules = [
            'condition'     => 'required|in:new,used',
            'quantity'      => 'required|integer|min:1',
            'received_date' => 'required|date',
            'notes'         => 'nullable|string',
        ];

        if ($item->item_type === 'device') {
            $rules['serial_numbers']   = 'required|array|size:' . max(1, (int)$request->input('quantity', 1));
            $rules['serial_numbers.*'] = 'required|string|max:255';
        } else {
            $rules['lot_number'] = $item->is_expirable ? 'required|string|max:255' : 'nullable|string|max:255';
        }

        // Only validate and allow expiry_date if the item is set as expirable
        if ($item->is_expirable) {
            $rules['expiry_date'] = 'required|date|after:today';
        }

        $validated = $request->validate($rules);

        if ($item->item_type === 'device') {
            $validated['expiry_date'] = null;
        }

        if ($validated['condition'] === 'used') {
            if ($item->item_type === 'device') {
                $serialNumbers = $request->input('serial_numbers', []);
                $baseData = collect($validated)->except(['serial_numbers', 'quantity', 'lot_number', 'condition'])->toArray();
                $baseData['item_id'] = $item->id;
                
                foreach ($serialNumbers as $sn) {
                    $entry = StockEntry::create(array_merge($baseData, [
                        'quantity'      => 1,
                        'serial_number' => $sn
                    ]));
                    
                    // Instantly log it as used to migrate it to the Used Pool
                    \App\Models\UsageLog::create([
                        'stock_entry_id' => $entry->id,
                        'item_id'       => $item->id,
                        'quantity_used' => 1,
                        'stock_type'    => 'new',
                        'used_at'       => now(),
                        'used_by'       => 'System (Pre-used Intake)',
                        'notes'         => 'Item received as already used.'
                    ]);
                }
            }
            $item->increment('stock_used', $validated['quantity']);
        } else {
            $validated['item_id'] = $item->id;
            
            if ($item->item_type === 'device') {
                $serialNumbers = $request->input('serial_numbers', []);
                $baseData = collect($validated)->except(['serial_numbers', 'quantity', 'lot_number'])->toArray();
                
                foreach ($serialNumbers as $sn) {
                    StockEntry::create(array_merge($baseData, [
                        'quantity'      => 1,
                        'serial_number' => $sn
                    ]));
                }
            } else {
                $entryData = collect($validated)->except(['serial_numbers'])->toArray();

                // If no expiry date is set, check if the batch already has one
                if (!empty($validated['lot_number']) && empty($validated['expiry_date'])) {
                    $existingBatch = StockEntry::where('item_id', $item->id)
                        ->where('lot_number', $validated['lot_number'])
                        ->whereNotNull('expiry_date')
                        ->first();
                    
                    if ($existingBatch) {
                        $entryData['expiry_date'] = $existingBatch->expiry_date;
                    }
                }

                StockEntry::create($entryData);

                // User requested: If adding stock to an existing lot/batch, sync the expiry date across all parts of that batch
                if (!empty($validated['lot_number']) && !empty($validated['expiry_date'])) {
                    StockEntry::where('item_id', $item->id)
                        ->where('lot_number', $validated['lot_number'])
                        ->update(['expiry_date' => $validated['expiry_date']]);
                }
            }
        }

        return redirect()->route('items.show', $item)
            ->with('success', 'Stock received successfully.');
    }
}
