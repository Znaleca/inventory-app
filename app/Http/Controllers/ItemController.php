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
