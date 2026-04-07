@extends('layouts.app')

@section('title', 'Edit Stock Entry')

@section('content')
<div class="mx-auto max-w-2xl">
    <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-[0_4px_24px_-8px_rgba(0,0,0,0.06)]">
        <div class="border-b border-slate-100 bg-slate-50/50 px-8 py-5">
            <h3 class="text-sm font-semibold text-slate-800">Edit Stock Entry</h3>
            <p class="mt-0.5 text-xs text-slate-500">Item: <span class="font-semibold text-slate-700">{{ $stockEntry->item->name ?? '—' }}</span></p>
        </div>

        <form action="{{ route('admin.stock-entries.update', $stockEntry) }}" method="POST" class="space-y-6 p-8">
            @csrf @method('PATCH')

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <label for="quantity" class="block text-sm font-medium text-slate-700 mb-1.5">Quantity</label>
                    <input type="number" name="quantity" id="quantity" value="{{ old('quantity', $stockEntry->quantity) }}" min="0"
                           class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-4">
                    @error('quantity') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="lot_number" class="block text-sm font-medium text-slate-700 mb-1.5">Lot Number</label>
                    <input type="text" name="lot_number" id="lot_number" value="{{ old('lot_number', $stockEntry->lot_number) }}"
                           class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-4">
                    @error('lot_number') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="expiry_date" class="block text-sm font-medium text-slate-700 mb-1.5">Expiry Date</label>
                    <input type="date" name="expiry_date" id="expiry_date" value="{{ old('expiry_date', $stockEntry->expiry_date?->format('Y-m-d')) }}"
                           class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-4">
                    @error('expiry_date') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="received_date" class="block text-sm font-medium text-slate-700 mb-1.5">Received Date</label>
                    <input type="date" name="received_date" id="received_date" value="{{ old('received_date', $stockEntry->received_date?->format('Y-m-d')) }}"
                           class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-4">
                    @error('received_date') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="notes" class="block text-sm font-medium text-slate-700 mb-1.5">Notes</label>
                <textarea name="notes" id="notes" rows="3"
                          class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-4">{{ old('notes', $stockEntry->notes) }}</textarea>
                @error('notes') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.records.index', ['tab' => 'stock-entries']) }}" class="inline-flex items-center rounded-xl bg-slate-100 px-5 py-2.5 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-200">Cancel</a>
                <button type="submit" class="inline-flex items-center rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:bg-indigo-700 hover:shadow-md">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection
