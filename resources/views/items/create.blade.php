@extends('layouts.app')

@section('title', 'Add New Item')

@section('actions')
<a href="{{ route('items.index') }}"
    class="group inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2 text-sm font-semibold text-slate-600 shadow-sm ring-1 ring-inset ring-slate-300 transition-all hover:bg-slate-50 hover:text-slate-900">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
        class="h-4 w-4 transition-transform duration-300 group-hover:-translate-x-1">
        <path fill-rule="evenodd"
            d="M17 10a.75.75 0 01-.75.75H5.66l4.22 4.22a.75.75 0 11-1.06 1.06l-5.5-5.5a.75.75 0 010-1.06l5.5-5.5a.75.75 0 111.06 1.06l-4.22 4.22h10.59a.75.75 0 01.75.75z"
            clip-rule="evenodd" />
    </svg>
    Back to Inventory
</a>
@endsection

@section('content')
<div class="mx-auto max-w-3xl">
    <form action="{{ route('items.store') }}" method="POST"
        class="overflow-hidden rounded-[2rem] bg-white ring-1 ring-slate-200 shadow-[0_8px_30px_-12px_rgba(0,0,0,0.1)]">
        @csrf

        {{-- Header Section --}}
        <div class="border-b border-slate-100 bg-slate-50/50 px-8 py-6">
            <div class="flex items-center gap-4">
                <div
                    class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600 ring-1 ring-inset ring-indigo-500/20">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="h-6 w-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Create Inventory Item</h2>
                    <p class="text-sm text-slate-500">Fill in the details below to add a new item to your catalog.</p>
                </div>
            </div>
        </div>

        <div class="px-8 py-6 space-y-8">

            {{-- SECTION: Basic Information --}}
            <div>
                <h3 class="mb-4 flex items-center gap-2 text-sm font-bold uppercase tracking-widest text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                        <path fill-rule="evenodd"
                            d="M4.25 2A2.25 2.25 0 002 4.25v2.5A2.25 2.25 0 004.25 9h2.5A2.25 2.25 0 009 6.75v-2.5A2.25 2.25 0 006.75 2h-2.5zm0 9A2.25 2.25 0 002 13.25v2.5A2.25 2.25 0 004.25 18h2.5A2.25 2.25 0 009 15.75v-2.5A2.25 2.25 0 006.75 11h-2.5zm9-9A2.25 2.25 0 0011 4.25v2.5A2.25 2.25 0 0013.25 9h2.5A2.25 2.25 0 0018 6.75v-2.5A2.25 2.25 0 0015.75 2h-2.5zm0 9A2.25 2.25 0 0011 13.25v2.5A2.25 2.25 0 0013.25 18h2.5A2.25 2.25 0 0018 15.75v-2.5A2.25 2.25 0 0015.75 11h-2.5z"
                            clip-rule="evenodd" />
                    </svg>
                    Basic Info
                </h3>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label class="mb-2 block text-sm font-bold text-slate-700">Item Name <span
                                class="text-rose-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}"
                            class="block w-full rounded-xl border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 transition-all"
                            required placeholder="e.g. Guiding Catheter 6F JL4">
                        @error('name') <p class="mt-1.5 text-xs font-bold text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Category <span
                                class="text-rose-500">*</span></label>
                        <div x-data="customDropdown(
                            [
                                @foreach($categories as $cat)
                                { id: '{{ $cat->id }}', name: '{{ addslashes($cat->name) }}' },
                                @endforeach
                            ], 
                            '{{ old('category_id') }}', 
                            'category_id', 
                            false
                        )" class="relative w-full">
                            <input type="hidden" :name="inputName" x-model="selectedId" required>
                            
                            <button type="button" @click="toggle()" class="block w-full rounded-xl border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 transition-all bg-white text-left flex justify-between items-center" :class="{'text-slate-400': !selectedId}">
                                <span x-text="selectedName || 'Select category...'" class="truncate block"></span>
                                <svg class="h-4 w-4 text-slate-400 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" /></svg>
                            </button>
                            
                            <div x-show="isOpen" x-transition.opacity.duration.200ms @click.away="close()" class="absolute z-50 mt-2 w-full origin-top-right rounded-xl bg-white shadow-[0_10px_40px_-10px_rgba(0,0,0,0.15)] ring-1 ring-slate-200 overflow-hidden flex flex-col" style="display: none;">
                                <div class="px-2 pt-2 pb-1 bg-white">
                                    <input type="text" x-ref="searchInput" x-model="search" placeholder="Search categories..." class="block w-full rounded-lg border-0 py-2 px-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm transition-all bg-slate-50">
                                </div>
                                
                                <ul class="max-h-60 overflow-y-auto mt-1 p-1 bg-white">
                                    <template x-for="option in filteredOptions" :key="option.id">
                                        <li @click="selectOption(option)" class="relative cursor-pointer select-none rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors flex items-center justify-between" :class="{'bg-slate-50 font-bold text-slate-900 ring-1 ring-slate-200/50': selectedId == option.id}">
                                            <span x-text="option.name" class="block truncate"></span>
                                            <svg x-show="selectedId == option.id" class="h-4 w-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                        </li>
                                    </template>
                                    <li x-show="filteredOptions.length === 0" class="px-3 py-4 text-center text-sm text-slate-400">
                                        No matching options found.
                                    </li>
                                </ul>
                                
                                <div class="border-t border-slate-100 bg-slate-50 p-2">
                                    <button x-show="!isAdding" @click="isAdding = true; $nextTick(() => $refs.newInput.focus())" type="button" class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-sm font-semibold text-indigo-600 hover:bg-white ring-1 ring-transparent hover:ring-slate-200 transition-all">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                        Add another option
                                    </button>
                                    
                                    <div x-show="isAdding" class="flex items-center gap-2">
                                        <input x-ref="newInput" type="text" x-model="newValue" @keydown.enter.prevent="saveNewOption()" placeholder="Type new option..." class="block w-full flex-1 rounded-lg border-0 py-2 px-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm bg-white">
                                        <button type="button" @click="saveNewOption()" class="flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-500 text-white hover:bg-emerald-600 shadow-sm transition-colors shrink-0">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                        </button>
                                        <button type="button" @click="isAdding = false; newValue = ''" class="flex h-9 w-9 items-center justify-center rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 ring-1 ring-rose-200/50 shadow-sm transition-colors shrink-0">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @error('category_id') <p class="mt-1.5 text-xs font-bold text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <hr class="border-slate-100">

            {{-- SECTION: Inventory Details --}}
            <div>
                <h3 class="mb-4 flex items-center gap-2 text-sm font-bold uppercase tracking-widest text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-13a.75.75 0 00-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 000-1.5h-3.25V5z"
                            clip-rule="evenodd" />
                    </svg>
                    Stock & Pricing
                </h3>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="sm:col-span-2">
                        <label class="mb-2 block text-sm font-bold text-slate-700">SKU <span
                                class="text-rose-500">*</span></label>
                        <input type="text" name="sku" value="{{ old('sku') }}"
                            class="block w-full rounded-xl border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 font-mono placeholder:font-sans placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 transition-all"
                            required placeholder="e.g. CATH-GC-6F-001">
                        @error('sku') <p class="mt-1.5 text-xs font-bold text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Unit Price <span
                                class="text-rose-500">*</span></label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                <span class="text-slate-500 font-bold sm:text-sm">₱</span>
                            </div>
                            <input type="number" step="0.01" name="unit_price" value="{{ old('unit_price', '0.00') }}"
                                class="block w-full rounded-xl border-0 py-3 pl-10 pr-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 transition-all font-mono"
                                required>
                        </div>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Unit <span
                                class="text-rose-500">*</span></label>
                        <div x-data="customDropdown(
                            [
                                @foreach($units as $u)
                                { id: '{{ addslashes($u->name) }}', name: '{{ addslashes($u->name) }}' },
                                @endforeach
                            ], 
                            '{{ old('unit') }}', 
                            'unit', 
                            true
                        )" class="relative w-full">
                            <input type="hidden" :name="inputName" x-model="selectedId" required>
                            
                            <button type="button" @click="toggle()" class="block w-full rounded-xl border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 transition-all bg-white text-left flex justify-between items-center" :class="{'text-slate-400': !selectedId}">
                                <span x-text="selectedName || 'Select or type unit...'" class="truncate block"></span>
                                <svg class="h-4 w-4 text-slate-400 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" /></svg>
                            </button>
                            
                            <div x-show="isOpen" x-transition.opacity.duration.200ms @click.away="close()" class="absolute z-50 mt-2 w-full origin-top-right rounded-xl bg-white shadow-[0_10px_40px_-10px_rgba(0,0,0,0.15)] ring-1 ring-slate-200 overflow-hidden flex flex-col" style="display: none;">
                                
                                <div class="px-2 pt-2 pb-1 bg-white">
                                    <input type="text" x-ref="searchInput" x-model="search" placeholder="Search units..." class="block w-full rounded-lg border-0 py-2 px-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm transition-all bg-slate-50">
                                </div>
                                
                                <ul class="max-h-60 overflow-y-auto mt-1 p-1 bg-white">
                                    <template x-for="option in filteredOptions" :key="option.id">
                                        <li @click="selectOption(option)" class="relative cursor-pointer select-none rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors flex items-center justify-between" :class="{'bg-slate-50 font-bold text-slate-900 ring-1 ring-slate-200/50': selectedId == option.id}">
                                            <span x-text="option.name" class="block truncate"></span>
                                            <svg x-show="selectedId == option.id" class="h-4 w-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                        </li>
                                    </template>
                                    <li x-show="filteredOptions.length === 0" class="px-3 py-4 text-center text-sm text-slate-400">
                                        No matching units found.
                                    </li>
                                </ul>
                                
                                <div class="border-t border-slate-100 bg-slate-50 p-2">
                                    <button x-show="!isAdding" @click="isAdding = true; $nextTick(() => $refs.newInput.focus())" type="button" class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-sm font-semibold text-indigo-600 hover:bg-white ring-1 ring-transparent hover:ring-slate-200 transition-all">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                        Add another option
                                    </button>
                                    
                                    <div x-show="isAdding" class="flex items-center gap-2">
                                        <input x-ref="newInput" type="text" x-model="newValue" @keydown.enter.prevent="saveNewOption()" placeholder="Type new unit..." class="block w-full flex-1 rounded-lg border-0 py-2 px-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm bg-white">
                                        <button type="button" @click="saveNewOption()" class="flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-500 text-white hover:bg-emerald-600 shadow-sm transition-colors shrink-0">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                        </button>
                                        <button type="button" @click="isAdding = false; newValue = ''" class="flex h-9 w-9 items-center justify-center rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 ring-1 ring-rose-200/50 shadow-sm transition-colors shrink-0">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="mb-2 block text-sm font-bold text-slate-700">Reorder Level <span
                                class="text-rose-500">*</span></label>
                        <div class="relative">
                            <input type="number" name="reorder_level" value="{{ old('reorder_level', 10) }}"
                                class="block w-full rounded-xl border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 transition-all"
                                required min="0">
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4">
                                <span class="text-slate-400 text-xs font-semibold uppercase">Units</span>
                            </div>
                        </div>
                        <p class="mt-1.5 text-xs text-slate-500">Alert triggers when stock falls below this number.</p>
                    </div>
                </div>
            </div>

            <hr class="border-slate-100">

            {{-- SECTION: Behavior & Details --}}
            <div>
                <h3 class="mb-4 flex items-center gap-2 text-sm font-bold uppercase tracking-widest text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z"
                            clip-rule="evenodd" />
                    </svg>
                    Additional Details
                </h3>

                {{-- Standard Native Checkbox --}}
                <div class="mb-6 flex items-start gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-5">
                    <div class="flex h-6 items-center">
                        <input id="is_one_time_use" name="is_one_time_use" type="checkbox" value="1" {{
                            old('is_one_time_use') ? 'checked' : '' }}
                            class="h-5 w-5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-600 cursor-pointer">
                    </div>
                    <div class="text-sm leading-6">
                        <label for="is_one_time_use" class="font-bold text-slate-900 cursor-pointer">One-Time Use
                            (Disposable)</label>
                        <p class="text-slate-500 font-medium mt-0.5">Item is consumed upon use. Uncheck if it moves to
                            'Used' stock.</p>
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Description</label>
                    <textarea name="description" rows="4"
                        class="block w-full rounded-xl border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 transition-all"
                        placeholder="Enter detailed specifications or notes...">{{ old('description') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Footer / Submit Area --}}
        <div class="bg-slate-50 px-8 py-5 flex items-center justify-end gap-3 border-t border-slate-100">
            <a href="{{ route('items.index') }}"
                class="rounded-xl px-5 py-2.5 text-sm font-bold text-slate-500 transition-colors hover:bg-slate-200 hover:text-slate-900">
                Cancel
            </a>
            <button type="submit"
                class="group relative inline-flex items-center justify-center gap-2 overflow-hidden rounded-xl bg-slate-900 px-6 py-2.5 text-sm font-bold text-white shadow-md transition-all duration-300 hover:bg-indigo-600 hover:shadow-lg hover:shadow-indigo-500/30 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2">
                <span class="relative">Save Item Details</span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                    class="relative h-4 w-4 transition-transform duration-300 group-hover:translate-x-0.5">
                    <path fill-rule="evenodd"
                        d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                        clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </form>
</div>


@endsection