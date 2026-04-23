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

        // Parse presets — supports both ?preset=x (legacy) and ?presets=x,y (new combined)
        $selectedPresets = [];
        if ($request->filled('presets')) {
            $selectedPresets = array_filter(explode(',', $request->presets));
        } elseif ($request->filled('preset')) {
            $selectedPresets = [$request->preset];
        }

        // Apply TYPE filter at query level (device/consumable)
        $typePresets = array_intersect($selectedPresets, ['devices', 'consumables']);
        if (count($typePresets) === 1) {
            $type = in_array('devices', $typePresets) ? 'device' : 'consumable';
            $query->where('item_type', $type);
        }

        $items = $query->orderBy('name')->get();

        // Apply STATUS filters at collection level (OR logic within status group)
        $statusPresets = array_intersect($selectedPresets, ['all', 'low_stock', 'out_of_stock', 'expired']);
        if (!empty($statusPresets) && !in_array('all', $statusPresets)) {
            $items = $items->filter(function($item) use ($statusPresets) {
                foreach ($statusPresets as $preset) {
                    if ($preset === 'low_stock' && $item->total_stock > 0 && $item->total_stock <= ($item->reorder_level ?? 10)) return true;
                    if ($preset === 'out_of_stock' && $item->total_stock <= 0) return true;
                    if ($preset === 'expired') {
                        foreach ($item->stockEntries as $entry) {
                            if ($entry->expiry_date) {
                                $days = now()->diffInDays(\Carbon\Carbon::parse($entry->expiry_date), false);
                                if ($days <= 30) return true;
                            }
                        }
                    }
                }
                return false;
            });
        }

        $filenamePresets = !empty($selectedPresets) ? implode('_', array_slice($selectedPresets, 0, 3)) : 'all';
        $filename = 'inventory_export_' . $filenamePresets . '_' . date('Y-m-d') . '.xlsx';

        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\ItemsExport($items, $selectedPresets), $filename);
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
            'reorder_level'    => 'nullable|integer|min:0',
        ]);
        $validated['reorder_level'] = $validated['reorder_level'] ?? 10;
        $validated['unit_price'] = 0;

        $itemData = $validated;
        // Devices never expire; consumables follow the form toggle
        $itemData['is_expirable'] = $request->input('item_type') === 'device' ? false : $request->boolean('is_expirable');
        $itemData['condition'] = 'new';
        $itemData['stock_used'] = 0;
        
        $item = Item::create($itemData);

        return redirect()->route('items.show', $item)
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
            'reorder_level'    => 'nullable|integer|min:0',
        ]);
        $validated['reorder_level'] = $validated['reorder_level'] ?? 10;
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

        // Check if request came from admin records page, if so redirect back there
        if (request()->referrer && str_contains(request()->referrer, '/admin')) {
            return redirect()->route('admin.index', ['tab' => 'items'])
                ->with('success', 'Item and all related records deleted successfully.');
        }

        return redirect()->route('items.index')
            ->with('success', 'Item and all related records deleted successfully.');
    }

    public function apiSearch(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 1) {
            return response()->json([]);
        }

        $items = Item::with('category')
            ->where('name', 'like', "%{$query}%")
            ->orWhere('brand', 'like', "%{$query}%")
            ->orderBy('name')
            ->limit(10)
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'category' => $item->category?->name ?? 'Uncategorized',
                    'stock' => $item->total_stock,
                    'unit' => $item->unit ?? 'units',
                ];
            });

        return response()->json($items);
    }
}
