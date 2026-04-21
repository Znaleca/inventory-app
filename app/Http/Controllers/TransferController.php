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
        $categories = \App\Models\Category::orderBy('name')->get();
        $units = \App\Models\Unit::orderBy('name')->get();

        return view('transfers.create', compact('items', 'categories', 'units'));
    }

    public function store(Request $request)
    {
        $hasSelectedEntries = $request->has('selected_entries') && is_array($request->selected_entries) && count($request->selected_entries) > 0;
        $isNewItem = $request->input('is_new_item') === '1' && $request->input('type') === 'in';

        if ($isNewItem) {
            $itemData = $request->validate([
                'new_item_type'        => 'required|in:device,consumable',
                'new_item_category_id' => 'required|exists:categories,id',
                'new_item_name'        => 'required_if:new_item_type,consumable|nullable|string|max:255',
                'new_item_brand'       => 'required_if:new_item_type,device|nullable|string|max:255',
                'new_item_model'       => 'required_if:new_item_type,device|nullable|string|max:255',
                'new_item_unit_id'     => 'required|exists:units,id',
            ]);

            $name = $itemData['new_item_type'] === 'device' 
                ? trim($itemData['new_item_brand'] . ' ' . $itemData['new_item_model']) 
                : $itemData['new_item_name'];

            $item = \App\Models\Item::create([
                'name'              => $name,
                'category_id'       => $itemData['new_item_category_id'],
                'brand'             => $itemData['new_item_brand'] ?? null,
                'model'             => $itemData['new_item_model'] ?? null,
                'unit_id'           => $itemData['new_item_unit_id'],
                'item_type'         => $itemData['new_item_type'],
                'total_stock'       => 0,
                'stock_used'        => 0,
                'unit'              => \App\Models\Unit::find($itemData['new_item_unit_id'])->name,
            ]);

            $request->merge(['item_id' => $item->id]);
        }

        $validated = $request->validate([
            'item_id'        => 'required|exists:items,id',
            'type'           => 'required|in:in,out',
            'new_quantity'   => $hasSelectedEntries ? 'nullable' : 'nullable|integer|min:0',
            'used_quantity'  => $hasSelectedEntries ? 'nullable' : 'nullable|integer|min:0',
            'department'     => 'required|string|max:255',
            'transferred_to' => 'required|string|max:255',
            'bio_id'         => 'required|string|max:255',
            'transferred_at' => 'required|date',
            'serial_number'  => 'nullable|string|max:255',
            'notes'          => 'nullable|string',
        ]);

        $newQty  = (int) ($validated['new_quantity']  ?? 0);
        $usedQty = (int) ($validated['used_quantity'] ?? 0);
        $serialParts = [];

        if ($hasSelectedEntries) {
            $stockEntries = \App\Models\StockEntry::with('usageLogs')->whereIn('id', $request->selected_entries)->get();
            foreach ($stockEntries as $entry) {
                // Device is counted as used if it has usage logs, returned logged, or imported tags
                $isUsed = (bool) preg_match('/\[USED\]/i', $entry->serial_number ?? '') || ($entry->usageLogs->sum('quantity_used') > 0);
                if ($isUsed) {
                    $usedQty++;
                } else {
                    $newQty++;
                }
            }
            $serialStr = $stockEntries->pluck('serial_number')->filter()->implode(', ');
            if ($serialStr) $serialParts[] = $serialStr;
        }

        if ($validated['type'] === 'in' || $validated['type'] === 'out') {
            $newSerial = trim($request->input('new_serial_number', ''));
            $usedSerial = trim($request->input('used_serial_number', ''));
            
            if ($newSerial) $serialParts[] = "[NEW] " . implode(", ", array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', preg_replace('/^\d+\.\s*/m', '', $newSerial)))));
            if ($usedSerial) $serialParts[] = "[USED] " . implode(", ", array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', preg_replace('/^\d+\.\s*/m', '', $usedSerial)))));
        }
        
        if (count($serialParts) > 0) {
            $validated['serial_number'] = implode(" | ", $serialParts);
        }
        
        $total = $newQty + $usedQty;

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

        // Constraints for Transfer In
        if ($validated['type'] === 'in') {
            if ($item->item_type === 'device') {
                if (empty($validated['serial_number']) && empty($request->input('new_serial_number')) && empty($request->input('used_serial_number'))) {
                    return back()->withErrors(['new_serial_number' => 'Serial numbers are required when transferring in a device.'])->withInput();
                }
            }
            if ($usedQty > 0 && $item->item_type !== 'device') {
                return back()->withErrors(['used_quantity' => 'Used stock can only be tracked and transferred for reusable devices.'])->withInput();
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
            'serial_number'  => $validated['serial_number'] ?? null,
            'notes'          => $validated['notes'] ?? null,
            'approved_by'    => auth()->user()->name,
        ]);

        return redirect()->route('in-out.index', ['tab' => 'transfer'])->with('success', 'Transfer recorded successfully.');
    }
}
