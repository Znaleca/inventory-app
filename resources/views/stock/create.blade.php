@extends('layouts.app')

@section('title', 'Receive Stock — ' . $item->name)

@section('actions')
<a href="{{ route('items.show', $item) }}"
    class="group inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2 text-sm font-semibold text-slate-600 shadow-sm ring-1 ring-inset ring-slate-300 transition-all hover:bg-slate-50 hover:text-slate-900">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
        class="h-4 w-4 transition-transform duration-300 group-hover:-translate-x-1">
        <path fill-rule="evenodd"
            d="M17 10a.75.75 0 01-.75.75H5.66l4.22 4.22a.75.75 0 11-1.06 1.06l-5.5-5.5a.75.75 0 010-1.06l5.5-5.5a.75.75 0 111.06 1.06l-4.22 4.22h10.59a.75.75 0 01.75.75z"
            clip-rule="evenodd" />
    </svg>
    Back to Item
</a>
@endsection

@section('content')
<div class="mx-auto max-w-2xl">
    <form action="{{ route('stock.store', $item) }}" method="POST" id="stock-form"
        class="overflow-hidden rounded-[2rem] bg-white ring-1 ring-slate-200 shadow-[0_8px_30px_-12px_rgba(0,0,0,0.1)]">
        @csrf

        {{-- Header / Item Context Banner --}}
        <div class="border-b border-slate-100 bg-slate-50/50 px-8 py-6">
            <div class="flex items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div
                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600 ring-1 ring-inset ring-indigo-500/20">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="h-6 w-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Receive Incoming Stock</h2>
                        <p class="text-sm text-slate-500 font-medium mt-0.5">{{ $item->name }}</p>
                    </div>
                </div>

                {{-- Current Stock Badge --}}
                <div class="flex flex-col items-end rounded-xl bg-white px-3 py-2 ring-1 ring-slate-200 shadow-sm">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Current Stock</span>
                    <span class="text-sm font-bold text-indigo-600">{{ $item->total_stock }} <span
                            class="text-slate-500 text-xs">{{ $item->unit }}</span></span>
                </div>
            </div>
        </div>

        <div class="px-8 py-8 space-y-8">

            {{-- SECTION: Stock Condition --}}
            <div>
                <h3 class="mb-4 flex items-center gap-2 text-sm font-bold uppercase tracking-widest text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                        <path fill-rule="evenodd"
                            d="M10 2a.75.75 0 01.75.75v1.5a.75.75 0 01-1.5 0v-1.5A.75.75 0 0110 2zM10 15.25a.75.75 0 01.75.75v1.5a.75.75 0 01-1.5 0v-1.5a.75.75 0 01.75-.75zM4.75 10a.75.75 0 01-.75.75h-1.5a.75.75 0 010-1.5h1.5a.75.75 0 01.75.75zM17.5 10a.75.75 0 01-.75.75h-1.5a.75.75 0 010-1.5h1.5a.75.75 0 01.75.75zM5.05 5.05a.75.75 0 011.06 0l1.062 1.06a.75.75 0 11-1.061 1.061L5.05 6.11a.75.75 0 010-1.06zM13.828 13.828a.75.75 0 011.06 0l1.061 1.06a.75.75 0 11-1.06 1.061l-1.061-1.06a.75.75 0 010-1.06zM5.05 14.889a.75.75 0 010-1.061l1.06-1.061a.75.75 0 111.061 1.06l-1.06 1.061a.75.75 0 01-1.06 0zM13.828 6.11a.75.75 0 010-1.06l1.06-1.061a.75.75 0 111.061 1.06l-1.06 1.061a.75.75 0 01-1.06 0z"
                            clip-rule="evenodd" />
                    </svg>
                    Stock Condition <span class="text-rose-500">*</span>
                </h3>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    {{-- New Items Card --}}
                    <label
                        class="relative flex cursor-pointer overflow-hidden rounded-2xl border border-slate-200 bg-slate-50 p-4 transition-all hover:bg-slate-100 has-[:checked]:border-indigo-500/50 has-[:checked]:bg-indigo-50/50 has-[:checked]:ring-1 has-[:checked]:ring-indigo-500/50 group">
                        <input type="radio" name="condition" value="new" class="sr-only" checked
                            onchange="toggleBatchDetails(true)">
                        <div class="flex items-start gap-4 w-full">
                            <div
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-white ring-1 ring-slate-200 transition-colors group-has-[:checked]:bg-indigo-600 group-has-[:checked]:ring-indigo-600">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                    class="h-5 w-5 text-slate-400 transition-colors group-has-[:checked]:text-white">
                                    <path fill-rule="evenodd"
                                        d="M10 2a.75.75 0 01.75.75v5.59l1.95-2.1a.75.75 0 111.1 1.02l-3.25 3.5a.75.75 0 01-1.1 0L6.2 7.26a.75.75 0 111.1-1.02l1.95 2.1V2.75A.75.75 0 0110 2z"
                                        clip-rule="evenodd" />
                                    <path fill-rule="evenodd"
                                        d="M4 10a.75.75 0 01.75.75v4.5a.75.75 0 00.75.75h9a.75.75 0 00.75-.75v-4.5a.75.75 0 011.5 0v4.5a2.25 2.25 0 01-2.25 2.25h-9A2.25 2.25 0 012 15.25v-4.5A.75.75 0 014 10z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <span
                                    class="block text-sm font-bold text-slate-900 group-has-[:checked]:text-indigo-900">New
                                    Items</span>
                                <span
                                    class="block mt-0.5 text-xs font-medium text-slate-500 group-has-[:checked]:text-indigo-700/70">Adds
                                    to main stock with batch tracking.</span>
                            </div>
                        </div>
                    </label>

                    {{-- Used Items Card --}}
                    <label
                        class="relative flex cursor-pointer overflow-hidden rounded-2xl border border-slate-200 bg-slate-50 p-4 transition-all hover:bg-slate-100 has-[:checked]:border-amber-500/50 has-[:checked]:bg-amber-50/50 has-[:checked]:ring-1 has-[:checked]:ring-amber-500/50 group">
                        <input type="radio" name="condition" value="used" class="sr-only"
                            onchange="toggleBatchDetails(false)">
                        <div class="flex items-start gap-4 w-full">
                            <div
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-white ring-1 ring-slate-200 transition-colors group-has-[:checked]:bg-amber-500 group-has-[:checked]:ring-amber-500">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                    class="h-5 w-5 text-slate-400 transition-colors group-has-[:checked]:text-white">
                                    <path fill-rule="evenodd"
                                        d="M15.312 11.424a5.5 5.5 0 01-9.201 2.466l-.312-.311h2.433a.75.75 0 000-1.5H3.989a.75.75 0 00-.75.75v4.242a.75.75 0 001.5 0v-2.43l.31.31a7 7 0 0011.712-3.138.75.75 0 00-1.449-.39zm1.23-3.723a.75.75 0 00.219-.53V2.929a.75.75 0 00-1.5 0V5.36l-.31-.31A7 7 0 003.239 8.188a.75.75 0 101.448.389A5.5 5.5 0 0113.89 6.11l.311.31h-2.432a.75.75 0 000 1.5h4.243a.75.75 0 00.53-.219z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <span
                                    class="block text-sm font-bold text-slate-900 group-has-[:checked]:text-amber-900">Used
                                    Items</span>
                                <span
                                    class="block mt-0.5 text-xs font-medium text-slate-500 group-has-[:checked]:text-amber-700/70">Increments
                                    'Used Stock' counter only.</span>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            <hr class="border-slate-100">

            {{-- SECTION: Delivery Details --}}
            <div>
                <h3 class="mb-4 flex items-center gap-2 text-sm font-bold uppercase tracking-widest text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                        <path d="M10 12.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z" />
                        <path fill-rule="evenodd"
                            d="M.664 10.59a1.651 1.651 0 010-1.186A10.004 10.004 0 0110 3c4.257 0 7.893 2.66 9.336 6.41.147.381.146.804 0 1.186A10.004 10.004 0 0110 17c-4.257 0-7.893-2.66-9.336-6.41zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                            clip-rule="evenodd" />
                    </svg>
                    Delivery Details
                </h3>

                <div class="space-y-6">
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-700">Quantity <span
                                    class="text-rose-500">*</span></label>
                            <div class="relative">
                                <input type="number" name="quantity" value="{{ old('quantity') }}"
                                    class="block w-full rounded-xl border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 transition-all"
                                    required min="1" placeholder="e.g. 50">
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4">
                                    <span class="text-slate-400 text-xs font-semibold uppercase">{{ $item->unit
                                        }}</span>
                                </div>
                            </div>
                            @error('quantity') <p class="mt-1.5 text-xs font-bold text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-700">Received Date <span
                                    class="text-rose-500">*</span></label>
                            <input type="date" name="received_date" value="{{ old('received_date', date('Y-m-d')) }}"
                                class="block w-full rounded-xl border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 transition-all"
                                required>
                        </div>
                    </div>

                    {{-- Batch Fields (Hidden when 'Used' is selected) --}}
                    <div id="batch-details-container"
                        class="grid grid-cols-1 gap-5 sm:grid-cols-2 rounded-2xl bg-slate-50 p-5 ring-1 ring-inset ring-slate-200/50">
                        <div class="batch-field">
                            <label class="mb-2 block text-sm font-bold text-slate-700">Lot Number</label>
                            <input type="text" name="lot_number" value="{{ old('lot_number') }}"
                                class="block w-full rounded-xl border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 transition-all bg-white"
                                placeholder="e.g. LOT-2026-0301">
                        </div>
                        <div class="batch-field">
                            <label class="mb-2 block text-sm font-bold text-slate-700">Expiry Date</label>
                            <input type="date" name="expiry_date" value="{{ old('expiry_date') }}"
                                class="block w-full rounded-xl border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 transition-all bg-white">
                            <p class="mt-1.5 text-xs font-medium text-slate-500">Leave blank if item does not expire.
                            </p>
                            @error('expiry_date') <p class="mt-1.5 text-xs font-bold text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Delivery Notes</label>
                        <textarea name="notes" rows="3"
                            class="block w-full rounded-xl border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 transition-all"
                            placeholder="Add tracking numbers or condition issues...">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer / Submit Area --}}
        <div class="bg-slate-50 px-8 py-5 flex items-center justify-end gap-3 border-t border-slate-100">
            <a href="{{ route('items.show', $item) }}"
                class="rounded-xl px-5 py-2.5 text-sm font-bold text-slate-500 transition-colors hover:bg-slate-200 hover:text-slate-900">
                Cancel
            </a>
            <button type="submit"
                class="group relative inline-flex items-center justify-center gap-2 overflow-hidden rounded-xl bg-slate-900 px-6 py-2.5 text-sm font-bold text-white shadow-md transition-all duration-300 hover:bg-indigo-600 hover:shadow-lg hover:shadow-indigo-500/30 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2">
                <span class="relative">Add to Inventory</span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                    class="relative h-4 w-4 transition-transform duration-300 group-hover:translate-x-0.5">
                    <path fill-rule="evenodd"
                        d="M10 2a.75.75 0 01.75.75v5.59l1.95-2.1a.75.75 0 111.1 1.02l-3.25 3.5a.75.75 0 01-1.1 0L6.2 7.26a.75.75 0 111.1-1.02l1.95 2.1V2.75A.75.75 0 0110 2z"
                        clip-rule="evenodd" />
                    <path fill-rule="evenodd"
                        d="M4 10a.75.75 0 01.75.75v4.5a.75.75 0 00.75.75h9a.75.75 0 00.75-.75v-4.5a.75.75 0 011.5 0v4.5a2.25 2.25 0 01-2.25 2.25h-9A2.25 2.25 0 012 15.25v-4.5A.75.75 0 014 10z"
                        clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </form>
</div>

<script>
    function toggleBatchDetails(show) {
        const batchContainer = document.getElementById('batch-details-container');
        const batchFields = document.querySelectorAll('.batch-field input');

        if (show) {
            // Show the container and enable inputs
            batchContainer.classList.remove('hidden');
            batchFields.forEach(i => i.disabled = false);
        } else {
            // Hide the container and disable inputs so they don't submit empty values
            batchContainer.classList.add('hidden');
            batchFields.forEach(i => {
                i.disabled = true;
                i.value = ''; // Optional: clear out the values if they switch to "Used"
            });
        }
    }

    // Initialize state on page load based on what is checked
    document.addEventListener('DOMContentLoaded', () => {
        const selectedCondition = document.querySelector('input[name="condition"]:checked').value;
        toggleBatchDetails(selectedCondition === 'new');
    });
</script>
@endsection