<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\Location;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::with(['category', 'stockEntries', 'usageLogs']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('type') && in_array($request->type, ['device', 'consumable'])) {
            $query->where('item_type', $request->type);
        }

        $items = $query->orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('items.index', compact('items', 'categories'));
    }

    public function export(Request $request)
    {
        $query = Item::with(['category', 'stockEntries', 'usageLogs']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('type') && in_array($request->type, ['device', 'consumable'])) {
            $query->where('item_type', $request->type);
        }

        if ($request->filled('preset')) {
            $preset = $request->preset;
            if ($preset === 'consumables') {
                $query->where('item_type', 'consumable');
            } elseif ($preset === 'devices') {
                $query->where('item_type', 'device');
            }
        }

        $items = $query->orderBy('name')->get();

        if ($request->filled('preset')) {
            $preset = $request->preset;
            if ($preset === 'low_stock') {
                $items = $items->filter(function($item) {
                    return $item->total_stock <= 0 || ($item->is_low_stock && $item->total_stock <= config('inventory.reorder_level', 10));
                });
            } elseif ($preset === 'expired') {
                $items = $items->filter(function($item) {
                    $hasExpiredOrSoon = false;
                    foreach ($item->stockEntries as $entry) {
                        if ($entry->expiry_date) {
                            $days = now()->diffInDays(\Carbon\Carbon::parse($entry->expiry_date), false);
                            if ($days <= 30) {
                                $hasExpiredOrSoon = true;
                                break;
                            }
                        }
                    }
                    return $hasExpiredOrSoon;
                });
            }
        }

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=inventory_items_export_".date('Y-m-d_H-i').".csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($items) {
            $file = fopen('php://output', 'w');
            // Adding BOM for excel UTF-8 proper reading
            fputs($file, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));
            
            fputcsv($file, ['ID', 'Name', 'Category', 'Type', 'Status', 'New Stock', 'Used Stock', 'Lent Out', 'Storage Location', 'Storage Section']);

            foreach ($items as $item) {
                $status = 'In Stock';
                if ($item->total_stock <= 0) $status = 'Out of Stock';
                elseif ($item->is_low_stock && $item->total_stock <= config('inventory.reorder_level', 10)) $status = 'Low Stock';

                fputcsv($file, [
                    $item->id,
                    $item->name,
                    $item->category->name ?? 'Uncategorized',
                    ucfirst($item->item_type),
                    $status,
                    $item->total_stock,
                    $item->effective_stock_used,
                    $item->active_lent_out ?? 0,
                    $item->storage_location ?? '-',
                    $item->storage_section ?? '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $units = \App\Models\Unit::orderBy('name')->get();
        $locations = Location::orderBy('name')->get();
        $storageLocations = $locations->where('type', 'storage');
        $storageSections = $locations->where('type', 'section');
        return view('items.create', compact('categories', 'units', 'storageLocations', 'storageSections'));
    }

    public function store(Request $request)
    {
        if ($request->filled('category_id') && !is_numeric($request->category_id)) {
            $cat = Category::firstOrCreate(
                ['name' => trim($request->category_id)],
                ['item_type' => $request->input('item_type', 'consumable')]
            );
            $request->merge(['category_id' => $cat->id]);
        }

        if ($request->filled('unit')) {
            \App\Models\Unit::firstOrCreate(['name' => trim($request->unit)]);
        }

        if ($request->input('item_type') === 'device') {
            $request->merge([
                'name' => trim($request->input('brand') . ' ' . $request->input('model'))
            ]);
        }

        $validated = $request->validate([
            'item_type'      => 'required|in:device,consumable',
            'brand'          => 'required_if:item_type,device|nullable|string|max:255',
            'model'          => 'required_if:item_type,device|nullable|string|max:255',
            'name'           => 'required|string|max:255',
            'category_id'   => 'required|exists:categories,id',
            'description'   => 'nullable|string',
            'unit'          => 'required|string|max:50',
            'is_expirable'  => 'nullable|boolean',
            'storage_location' => 'nullable|string|max:255',
            'storage_section'  => 'nullable|string|max:255',
        ]);
        $validated['unit_price'] = 0;

        $itemData = $validated;
        // Devices never expire; consumables follow the form toggle
        $itemData['is_expirable'] = $request->input('item_type') === 'device' ? false : $request->boolean('is_expirable');
        $itemData['condition'] = 'new';
        $itemData['stock_used'] = 0;
        
        $item = Item::create($itemData);

        return redirect()->route('items.index')
            ->with('success', 'Item created successfully.');
    }

    public function show(Item $item)
    {
        $item->load([
            'category', 
            'stockEntries' => fn ($q) => $q->orderByDesc('received_date'),
            'usageLogs' => fn ($q) => $q->orderByDesc('used_at'),
        ]);

        return view('items.show', compact('item'));
    }

    public function edit(Item $item)
    {
        $categories = Category::orderBy('name')->get();
        $units = \App\Models\Unit::orderBy('name')->get();
        $locations = Location::orderBy('name')->get();
        $storageLocations = $locations->where('type', 'storage');
        $storageSections = $locations->where('type', 'section');
        return view('items.edit', compact('item', 'categories', 'units', 'storageLocations', 'storageSections'));
    }

    public function update(Request $request, Item $item)
    {
        if ($request->filled('category_id') && !is_numeric($request->category_id)) {
            $cat = Category::firstOrCreate(
                ['name' => trim($request->category_id)],
                ['item_type' => $request->input('item_type', 'consumable')]
            );
            $request->merge(['category_id' => $cat->id]);
        }

        if ($request->filled('unit')) {
            \App\Models\Unit::firstOrCreate(['name' => trim($request->unit)]);
        }

        if ($request->input('item_type') === 'device') {
            $request->merge([
                'name' => trim($request->input('brand') . ' ' . $request->input('model'))
            ]);
        }

        $validated = $request->validate([
            'item_type'      => 'required|in:device,consumable',
            'brand'          => 'required_if:item_type,device|nullable|string|max:255',
            'model'          => 'required_if:item_type,device|nullable|string|max:255',
            'name'           => 'required|string|max:255',
            'category_id'   => 'required|exists:categories,id',
            'description'   => 'nullable|string',
            'unit'          => 'required|string|max:50',
            'is_expirable'  => 'nullable|boolean',
            'storage_location' => 'nullable|string|max:255',
            'storage_section'  => 'nullable|string|max:255',
        ]);
        $validated['unit_price'] = 0;

        $itemData = $validated;
        // Devices never expire; consumables follow the form toggle
        $itemData['is_expirable'] = $request->input('item_type') === 'device' ? false : $request->boolean('is_expirable');

        $item->update($itemData);

        return redirect()->route('items.show', $item)
            ->with('success', 'Item updated successfully.');
    }

    public function destroy(Item $item)
    {
        // Force-delete the item and cascade all related records first
        $item->stockEntries()->delete();
        $item->usageLogs()->delete();
        $item->borrows()->delete();
        $item->transfers()->delete();
        $item->disposals()->delete();
        $item->delete();

        return redirect()->route('items.index')
            ->with('success', 'Item and all related records deleted successfully.');
    }
}
