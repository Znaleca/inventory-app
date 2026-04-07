<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
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
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $items = $query->orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('items.index', compact('items', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $units = \App\Models\Unit::orderBy('name')->get();
        return view('items.create', compact('categories', 'units'));
    }

    public function store(Request $request)
    {
        if ($request->filled('category_id') && !is_numeric($request->category_id)) {
            $cat = Category::firstOrCreate(['name' => trim($request->category_id)]);
            $request->merge(['category_id' => $cat->id]);
        }

        if ($request->filled('unit')) {
            \App\Models\Unit::firstOrCreate(['name' => trim($request->unit)]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:255|unique:items',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'unit' => 'required|string|max:50',
            'unit_price' => 'required|numeric|min:0',
            'reorder_level' => 'required|integer|min:0',
            'is_one_time_use' => 'nullable|boolean',
        ]);

        $itemData = $validated;
        $itemData['is_one_time_use'] = $request->boolean('is_one_time_use');
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
        return view('items.edit', compact('item', 'categories', 'units'));
    }

    public function update(Request $request, Item $item)
    {
        if ($request->filled('category_id') && !is_numeric($request->category_id)) {
            $cat = Category::firstOrCreate(['name' => trim($request->category_id)]);
            $request->merge(['category_id' => $cat->id]);
        }

        if ($request->filled('unit')) {
            \App\Models\Unit::firstOrCreate(['name' => trim($request->unit)]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:255|unique:items,sku,'.$item->id,
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'unit' => 'required|string|max:50',
            'unit_price' => 'required|numeric|min:0',
            'reorder_level' => 'required|integer|min:0',
            'is_one_time_use' => 'nullable|boolean',
        ]);

        $itemData = $validated;
        $itemData['is_one_time_use'] = $request->boolean('is_one_time_use');

        $item->update($itemData);

        return redirect()->route('items.show', $item)
            ->with('success', 'Item updated successfully.');
    }

    public function destroy(Item $item)
    {
        if (!$item->can_be_deleted) {
            return redirect()->back()
                ->with('error', 'Cannot delete item: It has associated stock, logs, or borrows. Dispose or return items first.');
        }

        $item->delete();

        return redirect()->route('items.index')
            ->with('success', 'Item deleted successfully.');
    }
}
