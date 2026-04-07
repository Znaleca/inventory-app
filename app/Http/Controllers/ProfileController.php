<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function show()
    {
        return $this->buildProfileView(auth()->user());
    }

    /**
     * Build the profile activity data for any given user.
     */
    public function buildProfileView(\App\Models\User $user)
    {
        $name   = $user->name;
        $bioId  = $user->bio_id;

        // Usage Logs — matched on used_by field
        $usageLogs = \App\Models\UsageLog::with('item')
            ->where(function ($q) use ($name, $bioId) {
                $q->where('used_by', $name)
                  ->orWhere('used_by', 'like', "%{$bioId}%");
            })
            ->latest('used_at')
            ->get()->toBase()
            ->map(fn($u) => [
                'type'   => 'Usage',
                'label'  => 'Item Usage',
                'date'   => $u->used_at,
                'item'   => $u->item->name ?? 'Unknown Item',
                'qty'    => $u->quantity_used,
                'notes'  => $u->notes,
                'detail' => $u->patient_id ? "Patient: {$u->patient_id}" . ($u->procedure_type ? " · {$u->procedure_type}" : '') : ($u->procedure_type ?? null),
            ]);

        // Borrows — matched on borrower_name / bio_id or approved_by
        $borrows = \App\Models\Borrow::with('item')
            ->where(function ($q) use ($name, $bioId) {
                $q->where('borrower_name', $name)
                  ->orWhere('bio_id', $bioId)
                  ->orWhere('approved_by', $name);
            })
            ->latest('borrowed_at')
            ->get()->toBase()
            ->map(function ($b) use ($name, $bioId) {
                $isBorrowIn = ($b->type === 'in');

                if ($isBorrowIn) {
                    $detail = "From: " . ($b->source_department ?? 'Another Dept');
                    if ($b->approved_by) $detail .= " (Processed by: {$b->approved_by})";
                    $label = 'Borrow In';
                } else {
                    $detail = "To: " . ($b->borrower_name ?? 'Unknown') . ($b->department ? " · {$b->department}" : '');
                    $label = 'Borrow Out';
                }

                return [
                    'type'   => 'Borrow',
                    'label'  => $label,
                    'date'   => $b->borrowed_at,
                    'item'   => $b->item->name ?? 'Unknown Item',
                    'qty'    => $b->quantity_borrowed,
                    'notes'  => $b->notes,
                    'detail' => $detail,
                ];
            });

        // Returns — same Borrow records that have been returned
        $returns = \App\Models\Borrow::with('item')
            ->where(function ($q) use ($name, $bioId) {
                $q->where('borrower_name', $name)
                  ->orWhere('bio_id', $bioId);
            })
            ->where(function ($q) {
                $q->where('quantity_returned', '>', 0)
                  ->orWhere('quantity_used', '>', 0);
            })
            ->latest('returned_at')
            ->get()->toBase()
            ->map(fn($r) => [
                'type'   => 'Return',
                'label'  => 'Item Returned',
                'date'   => $r->returned_at ?? $r->updated_at,
                'item'   => $r->item->name ?? 'Unknown Item',
                'qty'    => $r->quantity_returned + ($r->item->is_one_time_use ? 0 : ($r->quantity_used ?? 0)),
                'notes'  => $r->notes,
                'detail' => $r->quantity_used > 0 ? "{$r->quantity_used} used during borrow" : null,
            ]);

        // Transfers — matched on transferred_by / transferred_to / bio_id
        $transfers = \App\Models\Transfer::with('item')
            ->where(function ($q) use ($name, $bioId) {
                $q->where('transferred_by', $name)
                  ->orWhere('transferred_to', $name)
                  ->orWhere('approved_by', $name)
                  ->orWhere('bio_id', $bioId);
            })
            ->latest('transferred_at')
            ->get()->toBase()
            ->map(function ($t) use ($name, $bioId) {
                $isIncoming = ($t->type === 'in');

                if ($isIncoming) {
                    // Received — show where it came FROM
                    $from = $t->destination ?? $t->transferred_by ?? $t->approved_by ?? null;
                    $detail = $from ? "From: {$from}" : 'Incoming Transfer';
                } else {
                    // Sent — show where it goes TO
                    $to = $t->destination ?? $t->transferred_to ?? null;
                    $detail = $to ? "To: {$to}" . ($t->department && $t->department !== $to ? " · {$t->department}" : '') : null;
                }

                return [
                    'type'   => 'Transfer',
                    'label'  => $isIncoming ? 'Transfer In' : 'Transfer Out',
                    'date'   => $t->transferred_at,
                    'item'   => $t->item->name ?? 'Unknown Item',
                    'qty'    => $t->quantity,
                    'notes'  => $t->notes,
                    'detail' => $detail,
                ];
            });

        // Disposals — matched on disposed_by
        $disposals = \App\Models\Disposal::with('item')
            ->where('disposed_by', $name)
            ->latest('disposed_at')
            ->get()->toBase()
            ->map(fn($d) => [
                'type'   => 'Disposal',
                'label'  => 'Item Disposal',
                'date'   => $d->disposed_at,
                'item'   => $d->item->name ?? 'Unknown Item',
                'qty'    => $d->quantity,
                'notes'  => $d->reason,
                'detail' => $d->type ?? null,
            ]);

        // Stock Entries — matched on notes or simply all (stock entries don't track who added)
        // We skip stock entries for per-user profiles as they have no "created_by" field.

        $activityLog = $usageLogs
            ->merge($borrows)
            ->merge($returns)
            ->merge($transfers)
            ->merge($disposals)
            ->sortByDesc('date')
            ->values();

        $counts = [
            'total'    => $activityLog->count(),
            'usage'    => $usageLogs->count(),
            'borrow'   => $borrows->count(),
            'return'   => $returns->count(),
            'transfer' => $transfers->count(),
            'disposal' => $disposals->count(),
        ];

        return view('profile.show', compact('user', 'activityLog', 'counts'));
    }

    public function edit()
    {
        return view('profile.edit', [
            'user' => auth()->user(),
        ]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'current_password' => ['nullable', 'required_with:password', 'current_password'],
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        $user->name = $validated['name'];

        if ($request->hasFile('photo')) {
            if ($user->profile_photo_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->profile_photo_path);
            }
            $user->profile_photo_path = $request->file('photo')->store('profile-photos', 'public');
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profile settings updated successfully.');
    }
}
