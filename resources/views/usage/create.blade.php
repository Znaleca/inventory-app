@extends('layouts.app')

@section('title', 'Log Usage (Out)')

@section('actions')
<a href="{{ route('items.index') }}"
    class="group inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2 text-sm font-semibold text-slate-600 shadow-sm ring-1 ring-inset ring-slate-300 transition-all hover:bg-slate-50 hover:text-slate-900">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
        class="h-4 w-4 transition-transform duration-300 group-hover:-translate-x-1">
        <path fill-rule="evenodd"
            d="M17 10a.75.75 0 01-.75.75H5.66l4.22 4.22a.75.75 0 11-1.06 1.06l-5.5-5.5a.75.75 0 010-1.06l5.5-5.5a.75.75 0 111.06 1.06l-4.22 4.22h10.59a.75.75 0 01.75.75z"
            clip-rule="evenodd" />
    </svg>
    Back to Items
</a>
@endsection

@section('content')
<div class="mx-auto max-w-3xl">
    <form action="{{ route('usage.store') }}" method="POST"
        class="overflow-hidden rounded-[2rem] bg-white ring-1 ring-slate-200 shadow-[0_8px_30px_-12px_rgba(0,0,0,0.1)]">
        @csrf

        {{-- Header Section --}}
        <div class="border-b border-slate-100 bg-slate-50/50 px-8 py-6">
            <div class="flex items-center gap-4">
                <div
                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-blue-50 text-blue-600 ring-1 ring-inset ring-blue-500/20">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="h-6 w-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Log Item Usage</h2>
                    <p class="text-sm text-slate-500 mt-0.5">Record inventory items consumed during a procedure.</p>
                </div>
            </div>
        </div>

        {{-- Main Alpine Component wrapper --}}
        <div class="px-8 py-8 space-y-8" x-data="{ 
                selectedItem: {{ (old('item_id') ?? request('item_id')) ? (int)(old('item_id') ?? request('item_id')) : 'null' }},
                stockType: '{{ old('stock_type', 'new') }}',
                items: {
                    @foreach($items as $i)
                        {{ $i->id }}: { new: {{ $i->total_stock }}, used: {{ $i->stock_used }}, unit: '{{ $i->unit }}' },
                    @endforeach
                }
            }">

            {{-- SECTION: Inventory Selection --}}
            <div>
                <h3 class="mb-4 flex items-center gap-2 text-sm font-bold uppercase tracking-widest text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                        <path
                            d="M10 2a.75.75 0 01.75.75v5.59l1.95-2.1a.75.75 0 111.1 1.02l-3.25 3.5a.75.75 0 01-1.1 0L6.2 7.26a.75.75 0 111.1-1.02l1.95 2.1V2.75A.75.75 0 0110 2z" />
                        <path
                            d="M4 10a.75.75 0 01.75.75v4.5a.75.75 0 00.75.75h9a.75.75 0 00.75-.75v-4.5a.75.75 0 011.5 0v4.5a2.25 2.25 0 01-2.25 2.25h-9A2.25 2.25 0 012 15.25v-4.5A.75.75 0 014 10z" />
                    </svg>
                    Inventory Source
                </h3>

                <div class="space-y-6">
                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Select Item to Use <span
                                class="text-rose-500">*</span></label>
                        <select name="item_id" required x-model="selectedItem"
                            @change="
                                if (selectedItem && items[selectedItem]) {
                                    if (items[selectedItem].new > 0 && items[selectedItem].used == 0) {
                                        stockType = 'new';
                                    } else if (items[selectedItem].used > 0 && items[selectedItem].new == 0) {
                                        stockType = 'used';
                                    } else if (items[selectedItem].new == 0 && items[selectedItem].used == 0) {
                                        stockType = '';
                                    }
                                }
                            "
                            class="block w-full rounded-xl border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all bg-white">
                            <option value="">-- Choose an item --</option>
                            @foreach($items as $i)
                            @php $total = $i->total_stock + $i->stock_used; @endphp
                            <option value="{{ $i->id }}">
                                {{ $i->name }} ({{ $i->sku }}) - Total of {{ $total }} {{ $i->unit }} ({{ $i->stock_used
                                }} used, {{ $i->total_stock }} new)
                            </option>
                            @endforeach
                        </select>
                        @error('item_id') <p class="mt-1.5 text-xs font-bold text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Stock Type Selection Cards --}}
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2" x-show="selectedItem" x-cloak>
                        {{-- New Stock Card --}}
                        <label x-show="items[selectedItem] && items[selectedItem].new > 0"
                            class="relative flex cursor-pointer overflow-hidden rounded-2xl border border-slate-200 bg-slate-50 p-4 transition-all hover:bg-slate-100 has-[:checked]:border-blue-500/50 has-[:checked]:bg-blue-50/50 has-[:checked]:ring-1 has-[:checked]:ring-blue-500/50 group">
                            <input type="radio" name="stock_type" value="new" x-model="stockType" class="sr-only">
                            <div class="flex items-start gap-4 w-full">
                                <div
                                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-white ring-1 ring-slate-200 transition-colors group-has-[:checked]:bg-blue-600 group-has-[:checked]:ring-blue-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                        class="h-5 w-5 text-slate-400 transition-colors group-has-[:checked]:text-white">
                                        <path fill-rule="evenodd"
                                            d="M10 2a8 8 0 100 16 8 8 0 000-16zm3.857 5.191a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <span
                                        class="block text-sm font-bold text-slate-900 group-has-[:checked]:text-blue-900">New
                                        Stock</span>
                                    <span
                                        class="block mt-0.5 text-xs font-medium text-slate-500 group-has-[:checked]:text-blue-700/80">
                                        Available: <span class="font-bold text-blue-600"
                                            x-text="items[selectedItem] ? items[selectedItem].new + ' ' + items[selectedItem].unit : '0'"></span>
                                    </span>
                                </div>
                            </div>
                        </label>

                        {{-- Used Stock Card --}}
                        <label x-show="items[selectedItem] && items[selectedItem].used > 0"
                            class="relative flex cursor-pointer overflow-hidden rounded-2xl border border-slate-200 bg-slate-50 p-4 transition-all hover:bg-slate-100 has-[:checked]:border-amber-500/50 has-[:checked]:bg-amber-50/50 has-[:checked]:ring-1 has-[:checked]:ring-amber-500/50 group">
                            <input type="radio" name="stock_type" value="used" x-model="stockType" class="sr-only">
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
                                        Stock</span>
                                    <span
                                        class="block mt-0.5 text-xs font-medium text-slate-500 group-has-[:checked]:text-amber-700/80">
                                        Available: <span class="font-bold text-amber-600"
                                            x-text="items[selectedItem] ? items[selectedItem].used + ' ' + items[selectedItem].unit : '0'"></span>
                                    </span>
                                </div>
                            </div>
                        </label>
                    </div>
                    @error('stock_type') <p class="mt-1.5 text-xs font-bold text-rose-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <hr class="border-slate-100">

            {{-- SECTION: Usage Details --}}
            <div>
                <h3 class="mb-4 flex items-center gap-2 text-sm font-bold uppercase tracking-widest text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-13a.75.75 0 00-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 000-1.5h-3.25V5z"
                            clip-rule="evenodd" />
                    </svg>
                    Amount & Time
                </h3>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Quantity Used <span
                                class="text-rose-500">*</span></label>
                        <div class="relative">
                            <input type="number" name="quantity_used" value="{{ old('quantity_used', 1) }}"
                                class="block w-full rounded-xl border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all"
                                required min="1">
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4">
                                <span class="text-slate-400 text-xs font-semibold uppercase"
                                    x-text="selectedItem && items[selectedItem] ? items[selectedItem].unit : 'units'"></span>
                            </div>
                        </div>
                        @error('quantity_used') <p class="mt-1.5 text-xs font-bold text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Date & Time <span
                                class="text-rose-500">*</span></label>
                        <input type="datetime-local" name="used_at"
                            value="{{ old('used_at', now()->format('Y-m-d\TH:i')) }}"
                            class="block w-full rounded-xl border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all"
                            required>
                    </div>
                </div>
            </div>

            <hr class="border-slate-100">

            {{-- SECTION: Clinical Context --}}
            <div>
                <h3 class="mb-4 flex items-center gap-2 text-sm font-bold uppercase tracking-widest text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                        <path
                            d="M10 9a3 3 0 100-6 3 3 0 000 6zM6 8a2 2 0 11-4 0 2 2 0 014 0zM1.49 15.326a.78.78 0 01-.358-.442 3 3 0 014.308-3.516 6.484 6.484 0 00-1.905 3.959c-.023.222-.014.442.025.654a4.97 4.97 0 01-2.07-.655zM16.44 15.98a4.97 4.97 0 002.07-.654.78.78 0 00.357-.442 3 3 0 00-4.308-3.517 6.484 6.484 0 011.907 3.96 2.32 2.32 0 01-.026.654zM7.214 14.222A5.99 5.99 0 0010 13a5.99 5.99 0 002.786 1.222 5.036 5.036 0 00-.312 2.277c-.044.37-.367.653-.74.653H8.266c-.373 0-.696-.282-.74-.653a5.036 5.036 0 00-.312-2.277z" />
                    </svg>
                    Clinical Context
                </h3>

                <div class="space-y-6">
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-700">Patient ID</label>
                            <input type="text" name="patient_id" value="{{ old('patient_id') }}"
                                class="block w-full rounded-xl border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all"
                                placeholder="e.g. PT-2026-0001">
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-700">Procedure Type</label>
                            <select name="procedure_type"
                                class="block w-full rounded-xl border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all bg-white">
                                <option value="">Select procedure</option>
                                @foreach(['Coronary Angiography', 'PCI (Angioplasty)', 'Pacemaker Implant', 'EP Study',
                                'TAVI', 'Peripheral Angiography', 'Other'] as $proc)
                                <option value="{{ $proc }}" {{ old('procedure_type')==$proc ? 'selected' : '' }}>{{
                                    $proc }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Used By Selection --}}
                    <div x-data="{ isOther: false, staffValue: '', otherValue: '' }"
                        x-init="let sel = $el.querySelector('select'); staffValue = sel ? sel.value : '';">
                        <label class="mb-2 block text-sm font-bold text-slate-700">Used By</label>

                        <select
                            x-on:change="isOther = ($event.target.value === '__other__'); if (!isOther) staffValue = $event.target.value"
                            class="block w-full rounded-xl border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all bg-white">
                            <option value="">— Select staff member —</option>
                            @php $grouped = $staffList->groupBy('type'); @endphp
                            @foreach(['doctor' => 'Doctors', 'nurse' => 'Nurses', 'technician' => 'Technicians', 'other'
                            => 'Other'] as $type => $label)
                            @if($grouped->has($type))
                            <optgroup label="{{ $label }}">
                                @foreach($grouped[$type] as $member)
                                <option value="{{ $member->display_name }}" {{ old('_used_by_staff')==$member->
                                    display_name ? 'selected' : '' }}>{{ $member->display_name }}</option>
                                @endforeach
                            </optgroup>
                            @endif
                            @endforeach
                            <option value="__other__" {{ old('_used_by_other') ? 'selected' : '' }}>— Others (type a
                                name) —</option>
                        </select>

                        <div x-show="isOther" x-cloak class="mt-3">
                            <input type="text" x-model="otherValue" placeholder="Enter full name..."
                                class="block w-full rounded-xl border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all">
                        </div>

                        <input type="hidden" name="used_by" x-bind:value="isOther ? otherValue : staffValue">
                        <input type="hidden" name="_used_by_other" x-bind:value="isOther ? '1' : ''">
                        <input type="hidden" name="_used_by_staff" x-bind:value="staffValue">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Notes <span
                                class="text-slate-400 font-normal ml-1">(Optional)</span></label>
                        <textarea name="notes" rows="3"
                            class="block w-full rounded-xl border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all"
                            placeholder="Enter complications, anomalies, or reasons for usage...">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer / Submit Area --}}
        <div class="bg-slate-50 px-8 py-5 flex items-center justify-end gap-3 border-t border-slate-100">
            <a href="{{ request('item_id') ? route('items.show', request('item_id')) : route('items.index') }}"
                class="rounded-xl px-5 py-2.5 text-sm font-bold text-slate-500 transition-colors hover:bg-slate-200 hover:text-slate-900">
                Cancel
            </a>
            <button type="submit"
                class="group relative inline-flex items-center justify-center gap-2 overflow-hidden rounded-xl bg-slate-900 px-6 py-2.5 text-sm font-bold text-white shadow-md transition-all duration-300 hover:bg-blue-600 hover:shadow-lg hover:shadow-blue-500/30 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2">
                <span class="relative">Log Usage</span>
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
@endsection