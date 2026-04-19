<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function index()
    {
        $staff = Staff::orderBy('type')->orderBy('name')->get();

        return view('staff.index', compact('staff'));
    }

    public function create()
    {
        return view('staff.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'title' => ['nullable', 'string', 'max:50'],
            'type' => ['required', 'in:programmer,tech support,supervisor,head'],
        ]);

        Staff::create($validated);

        return redirect()->route('staff.index')->with('success', 'Staff member added successfully.');
    }

    public function edit(Staff $staff)
    {
        return view('staff.edit', compact('staff'));
    }

    public function update(Request $request, Staff $staff)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'title' => ['nullable', 'string', 'max:50'],
            'type' => ['required', 'in:programmer,tech support'],
        ]);

        $staff->update($validated);

        return redirect()->route('staff.index')->with('success', 'Staff member updated.');
    }

    public function destroy(Staff $staff)
    {
        $staff->delete();

        return redirect()->route('staff.index')->with('success', 'Staff member removed.');
    }
}
