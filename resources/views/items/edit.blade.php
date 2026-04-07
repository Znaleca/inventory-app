@extends('layouts.app')

@section('title', 'Edit Item — ' . $item->name)

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
    <form action="{{ route('items.update', $item) }}" method="POST"
        class="overflow-hidden rounded-[2rem] bg-white ring-1 ring-slate-200 shadow-[0_8px_30px_-12px_rgba(0,0,0,0.1)]">
        @csrf
        @method('PUT')

        {{-- Header Section --}}
        <div class="border-b border-slate-100 bg-slate-50/50 px-8 py-6">
            <div class="flex items-center gap-4">
                <div
                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600 ring-1 ring-inset ring-indigo-500/20">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="h-6 w-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Edit Item Details</h2>
                    <p class="text-sm text-slate-500 mt-0.5">Updating <span class="font-semibold text-slate-700">{{
                            $item->name }}</span> ({{ $item->sku }})</p>
                </div>
            </div>
        </div>

        <div class="px-8 py-8 space-y-8">

            {{-- SECTION: General Information --}}
            <div>
                <h3 class="mb-4 flex items-center gap-2 text-sm font-bold uppercase tracking-widest text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                        <path fill-rule="evenodd"
                            d="M4.5 2A1.5 1.5 0 003 3.5v13A1.5 1.5 0 004.5 18h11a1.5 1.5 0 001.5-1.5V7.621a1.5 1.5 0 00-.44-1.06l-4.12-4.122A1.5 1.5 0 0011.378 2H4.5zm2.25 8.5a.75.75 0 000 1.5h6.5a.75.75 0 000-1.5h-6.5zm0 3a.75.75 0 000 1.5h6.5a.75.75 0 000-1.5h-6.5z"
                            clip-rule="evenodd" />
                    </svg>
                    General Details
                </h3>

                <div class="space-y-6">
                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Item Name <span
                                class="text-rose-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $item->name) }}"
                            class="block w-full rounded-xl border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 transition-all"
                            required>
                        @error('name') <p class="mt-1.5 text-xs font-bold text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-700">Category <span
                                    class="text-rose-500">*</span></label>
                            <div x-data="customDropdown(
                                [
                                    @foreach($categories as $cat)
                                    { id: '{{ $cat->id }}', name: '{{ addslashes($cat->name) }}' },
                                    @endforeach
                                ], 
                                '{{ old('category_id', $item->category_id) }}', 
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
                        </div>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Description</label>
                        <textarea name="description" rows="3"
                            class="block w-full rounded-xl border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 transition-all"
                            placeholder="Add specific details or item variations...">{{ old('description', $item->description) }}</textarea>
                    </div>
                </div>
            </div>

            <hr class="border-slate-100">

            {{-- SECTION: Inventory & Pricing --}}
            <div>
                <h3 class="mb-4 flex items-center gap-2 text-sm font-bold uppercase tracking-widest text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                        <path
                            d="M10 2a.75.75 0 01.75.75v5.59l1.95-2.1a.75.75 0 111.1 1.02l-3.25 3.5a.75.75 0 01-1.1 0L6.2 7.26a.75.75 0 111.1-1.02l1.95 2.1V2.75A.75.75 0 0110 2z" />
                        <path
                            d="M4 10a.75.75 0 01.75.75v4.5a.75.75 0 00.75.75h9a.75.75 0 00.75-.75v-4.5a.75.75 0 011.5 0v4.5a2.25 2.25 0 01-2.25 2.25h-9A2.25 2.25 0 012 15.25v-4.5A.75.75 0 014 10z" />
                    </svg>
                    Inventory & Pricing
                </h3>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">SKU / Item Code <span
                                class="text-rose-500">*</span></label>
                        <input type="text" name="sku" value="{{ old('sku', $item->sku) }}"
                            class="block w-full rounded-xl border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 transition-all"
                            required>
                        @error('sku') <p class="mt-1.5 text-xs font-bold text-rose-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Tracking Unit <span
                                class="text-rose-500">*</span></label>
                        <div x-data="customDropdown(
                            [
                                @foreach($units as $u)
                                { id: '{{ addslashes($u->name) }}', name: '{{ addslashes($u->name) }}' },
                                @endforeach
                            ], 
                            '{{ old('unit', $item->unit) }}', 
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
                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Unit Price <span
                                class="text-slate-400 font-normal ml-1">(₱)</span> <span
                                class="text-rose-500">*</span></label>
                        <input type="number" step="0.01" name="unit_price"
                            value="{{ old('unit_price', $item->unit_price) }}"
                            class="block w-full rounded-xl border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 transition-all"
                            required>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Reorder Level <span
                                class="text-rose-500">*</span></label>
                        <input type="number" name="reorder_level"
                            value="{{ old('reorder_level', $item->reorder_level) }}"
                            class="block w-full rounded-xl border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 transition-all"
                            required min="0">
                    </div>
                </div>
            </div>

            <hr class="border-slate-100">

            {{-- SECTION: Item Behavior --}}
            <div>
                <h3 class="mb-4 flex items-center gap-2 text-sm font-bold uppercase tracking-widest text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                        <path fill-rule="evenodd"
                            d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z"
                            clip-rule="evenodd" />
                    </svg>
                    Item Behavior
                </h3>

                <label
                    class="relative flex cursor-pointer items-start gap-4 rounded-2xl border border-slate-200 bg-slate-50 p-5 transition-all hover:bg-slate-100 has-[:checked]:border-indigo-500/50 has-[:checked]:bg-indigo-50/50 has-[:checked]:ring-1 has-[:checked]:ring-indigo-500/50 group">
                    <div class="flex h-6 items-center">
                        <input type="checkbox" name="is_one_time_use" value="1" id="is_one_time_use" {{
                            old('is_one_time_use', $item->is_one_time_use) ? 'checked' : '' }} class="h-5 w-5 rounded
                        border-slate-300 text-indigo-600 transition-all focus:ring-indigo-600
                        group-hover:border-indigo-400">
                    </div>
                    <div class="min-w-0 flex-1 text-sm leading-6">
                        <span class="block font-bold text-slate-900 group-has-[:checked]:text-indigo-900">One-Time Use
                            (Disposable)</span>
                        <span
                            class="block mt-0.5 text-xs font-medium text-slate-500 group-has-[:checked]:text-indigo-700/80">If
                            checked, the item is completely consumed upon use. If unchecked, it moves to the 'Used
                            Stock' pool after usage.</span>
                    </div>
                </label>
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
                <span class="relative">Save Changes</span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                    class="relative h-4 w-4 transition-transform duration-300 group-hover:translate-x-0.5">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                        clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </form>
</div>


@endsection