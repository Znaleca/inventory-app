@extends('layouts.app')

@section('title', 'Edit Usage Log')

@section('content')
<div class="mx-auto max-w-2xl">
    <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-[0_4px_24px_-8px_rgba(0,0,0,0.06)]">
        <div class="border-b border-slate-100 bg-slate-50/50 px-8 py-5">
            <h3 class="text-sm font-semibold text-slate-800">Edit Usage Log</h3>
            <p class="mt-0.5 text-xs text-slate-500">Item: <span class="font-semibold text-slate-700">{{ $usageLog->item->name ?? '—' }}</span></p>
        </div>

        <form action="{{ route('admin.usage-logs.update', $usageLog) }}" method="POST" class="space-y-6 p-8">
            @csrf @method('PATCH')

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <label for="quantity_used" class="block text-sm font-medium text-slate-700 mb-1.5">Quantity Used</label>
                    <input type="number" name="quantity_used" id="quantity_used" value="{{ old('quantity_used', $usageLog->quantity_used) }}" min="0"
                           class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-4">
                    @error('quantity_used') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="patient_id" class="block text-sm font-medium text-slate-700 mb-1.5">Patient ID</label>
                    <input type="text" name="patient_id" id="patient_id" value="{{ old('patient_id', $usageLog->patient_id) }}"
                           class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-4">
                    @error('patient_id') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="procedure_type" class="block text-sm font-medium text-slate-700 mb-1.5">Procedure Type</label>
                    <input type="text" name="procedure_type" id="procedure_type" value="{{ old('procedure_type', $usageLog->procedure_type) }}"
                           class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-4">
                    @error('procedure_type') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="used_by" class="block text-sm font-medium text-slate-700 mb-1.5">Used By</label>
                    <input type="text" name="used_by" id="used_by" value="{{ old('used_by', $usageLog->used_by) }}"
                           class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-4">
                    @error('used_by') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="used_at" class="block text-sm font-medium text-slate-700 mb-1.5">Date Used</label>
                    <input type="date" name="used_at" id="used_at" value="{{ old('used_at', $usageLog->used_at?->format('Y-m-d')) }}"
                           class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-4">
                    @error('used_at') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="notes" class="block text-sm font-medium text-slate-700 mb-1.5">Notes</label>
                <textarea name="notes" id="notes" rows="3"
                          class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-4">{{ old('notes', $usageLog->notes) }}</textarea>
                @error('notes') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.records.index', ['tab' => 'usage-logs']) }}" class="inline-flex items-center rounded-xl bg-slate-100 px-5 py-2.5 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-200">Cancel</a>
                <button type="submit" class="inline-flex items-center rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:bg-indigo-700 hover:shadow-md">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection
