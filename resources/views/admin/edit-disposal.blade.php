@extends('layouts.app')

@section('title', 'Edit Disposal')

@section('content')
<div class="mx-auto max-w-2xl">
    <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-[0_4px_24px_-8px_rgba(0,0,0,0.06)]">
        <div class="border-b border-slate-100 bg-slate-50/50 px-8 py-5">
            <h3 class="text-sm font-semibold text-slate-800">Edit Disposal Record</h3>
            <p class="mt-0.5 text-xs text-slate-500">Item: <span class="font-semibold text-slate-700">{{ $disposal->item->name ?? '—' }}</span></p>
        </div>

        <form action="{{ route('admin.disposals.update', $disposal) }}" method="POST" class="space-y-6 p-8">
            @csrf @method('PATCH')

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <label for="quantity" class="block text-sm font-medium text-slate-700 mb-1.5">Quantity</label>
                    <input type="number" name="quantity" id="quantity" value="{{ old('quantity', $disposal->quantity) }}" min="0"
                           class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-4">
                    @error('quantity') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="disposed_by" class="block text-sm font-medium text-slate-700 mb-1.5">Disposed By</label>
                    <input type="text" name="disposed_by" id="disposed_by" value="{{ old('disposed_by', $disposal->disposed_by) }}"
                           class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-4">
                    @error('disposed_by') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>
                <div class="sm:col-span-2">
                    <label for="disposed_at" class="block text-sm font-medium text-slate-700 mb-1.5">Date</label>
                    <input type="date" name="disposed_at" id="disposed_at" value="{{ old('disposed_at', $disposal->disposed_at?->format('Y-m-d')) }}"
                           class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-4">
                    @error('disposed_at') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="reason" class="block text-sm font-medium text-slate-700 mb-1.5">Reason</label>
                <textarea name="reason" id="reason" rows="3"
                          class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-4">{{ old('reason', $disposal->reason) }}</textarea>
                @error('reason') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.records.index', ['tab' => 'disposals']) }}" class="inline-flex items-center rounded-xl bg-slate-100 px-5 py-2.5 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-200">Cancel</a>
                <button type="submit" class="inline-flex items-center rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:bg-indigo-700 hover:shadow-md">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection
