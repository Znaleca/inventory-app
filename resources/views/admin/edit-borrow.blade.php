@extends('layouts.app')

@section('title', 'Edit Borrow Record')

@section('content')
<div class="mx-auto max-w-2xl">
    <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-[0_4px_24px_-8px_rgba(0,0,0,0.06)]">
        <div class="border-b border-slate-100 bg-slate-50/50 px-8 py-5">
            <h3 class="text-sm font-semibold text-slate-800">Edit Borrow Record</h3>
            <p class="mt-0.5 text-xs text-slate-500">
                Item: <span class="font-semibold text-slate-700">{{ $borrow->item->name ?? '—' }}</span>
                · Staff: <span class="font-semibold text-slate-700">{{ $borrow->staff->name ?? '—' }}</span>
            </p>
        </div>

        <form action="{{ route('admin.borrows.update', $borrow) }}" method="POST" class="space-y-6 p-8">
            @csrf @method('PATCH')

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                <div>
                    <label for="quantity_borrowed" class="block text-sm font-medium text-slate-700 mb-1.5">Qty Borrowed</label>
                    <input type="number" name="quantity_borrowed" id="quantity_borrowed" value="{{ old('quantity_borrowed', $borrow->quantity_borrowed) }}" min="0"
                           class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-4">
                    @error('quantity_borrowed') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="quantity_returned" class="block text-sm font-medium text-slate-700 mb-1.5">Qty Returned</label>
                    <input type="number" name="quantity_returned" id="quantity_returned" value="{{ old('quantity_returned', $borrow->quantity_returned) }}" min="0"
                           class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-4">
                    @error('quantity_returned') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="quantity_used" class="block text-sm font-medium text-slate-700 mb-1.5">Qty Used</label>
                    <input type="number" name="quantity_used" id="quantity_used" value="{{ old('quantity_used', $borrow->quantity_used) }}" min="0"
                           class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-4">
                    @error('quantity_used') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                <div>
                    <label for="status" class="block text-sm font-medium text-slate-700 mb-1.5">Status</label>
                    <select name="status" id="status"
                            class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-4">
                        <option value="active" {{ old('status', $borrow->status) === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="partial" {{ old('status', $borrow->status) === 'partial' ? 'selected' : '' }}>Partial</option>
                        <option value="returned" {{ old('status', $borrow->status) === 'returned' ? 'selected' : '' }}>Returned</option>
                    </select>
                    @error('status') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="borrowed_at" class="block text-sm font-medium text-slate-700 mb-1.5">Borrowed At</label>
                    <input type="date" name="borrowed_at" id="borrowed_at" value="{{ old('borrowed_at', $borrow->borrowed_at?->format('Y-m-d')) }}"
                           class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-4">
                    @error('borrowed_at') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="returned_at" class="block text-sm font-medium text-slate-700 mb-1.5">Returned At</label>
                    <input type="date" name="returned_at" id="returned_at" value="{{ old('returned_at', $borrow->returned_at?->format('Y-m-d')) }}"
                           class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-4">
                    @error('returned_at') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <label for="department" class="block text-sm font-medium text-slate-700 mb-1.5">Department</label>
                    <input type="text" name="department" id="department" value="{{ old('department', $borrow->department) }}"
                           class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-4">
                    @error('department') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="bio_id" class="block text-sm font-medium text-slate-700 mb-1.5">Bio ID</label>
                    <input type="text" name="bio_id" id="bio_id" value="{{ old('bio_id', $borrow->bio_id) }}"
                           class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-4">
                    @error('bio_id') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="notes" class="block text-sm font-medium text-slate-700 mb-1.5">Notes</label>
                <textarea name="notes" id="notes" rows="3"
                          class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-4">{{ old('notes', $borrow->notes) }}</textarea>
                @error('notes') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.records.index', ['tab' => 'borrows']) }}" class="inline-flex items-center rounded-xl bg-slate-100 px-5 py-2.5 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-200">Cancel</a>
                <button type="submit" class="inline-flex items-center rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:bg-indigo-700 hover:shadow-md">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection
