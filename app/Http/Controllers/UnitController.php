<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Item;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::orderBy('name')->get();
        return view('units.index', compact('units'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:units',
        ]);

        Unit::create($validated);

        return redirect()->route('units.index')
            ->with('success', 'Unit created successfully.');
    }

    public function edit(Unit $unit)
    {
        return view('units.edit', compact('unit'));
    }

    public function update(Request $request, Unit $unit)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:units,name,' . $unit->id,
        ]);

        $oldName = $unit->name;
        $newName = $validated['name'];

        $unit->update($validated);

        // Update all items that used the old unit string
        if ($oldName !== $newName) {
            Item::where('unit', $oldName)->update(['unit' => $newName]);
        }

        return redirect()->route('units.index')
            ->with('success', 'Unit updated successfully. Associated items have been updated.');
    }

    public function destroy(Unit $unit)
    {
        // Don't allow deletion if items are using it 
        if (Item::where('unit', $unit->name)->exists()) {
            return redirect()->route('units.index')
                ->with('error', 'Cannot delete unit because it is currently assigned to items.');
        }

        $unit->delete();

        return redirect()->route('units.index')
            ->with('success', 'Unit deleted successfully.');
    }
}
