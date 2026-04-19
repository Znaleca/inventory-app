<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Location::orderBy('name')->get();
        $storages = $locations->where('type', 'storage');
        $sections = $locations->where('type', 'section');
        
        return view('locations.index', compact('storages', 'sections'));
    }

    public function create()
    {
        return view('locations.create');
    }

    public function edit(Location $location)
    {
        return view('locations.edit', compact('location'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:storage,section',
        ]);

        Location::create($validated);

        return redirect()->route('locations.index')
            ->with('success', ucfirst($validated['type']) . ' added successfully.');
    }

    public function update(Request $request, Location $location)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $location->update($validated);

        return redirect()->route('locations.index')
            ->with('success', 'Location updated successfully.');
    }

    public function destroy(Location $location)
    {
        $type = $location->type;
        $location->delete();

        return redirect()->route('locations.index')
            ->with('success', ucfirst($type) . ' deleted successfully.');
    }
}
