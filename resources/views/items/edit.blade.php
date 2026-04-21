@extends('layouts.app')

@section('title', 'Edit Item — ' . $item->name)

@section('actions')
    <a href="{{ route('items.index') }}"
        class="inline-flex items-center gap-2 border border-slate-200 bg-white px-4 py-2 text-[11px] font-mono font-bold text-slate-600 uppercase tracking-widest hover:bg-slate-50 transition-colors">
        ← Back to Items
    </a>
@endsection

@section('content')
    <div class="mx-auto max-w-3xl">

        {{-- Page Header --}}
        <div class="mb-5">
            <p class="text-[10px] font-mono font-semibold text-blue-600 uppercase tracking-[0.25em] mb-1">Items://Edit</p>
            <h1 class="text-xl font-bold text-slate-800 tracking-tight">Edit Item Details</h1>
            <p class="text-xs text-slate-400 font-mono mt-0.5">Updating <strong class="text-slate-600">{{ $item->name }}</strong></p>
        </div>

        @if ($errors->any())
            <div class="mb-5 bg-rose-50 border border-rose-200 relative px-5 py-4">
                <div class="absolute top-0 left-0 w-1 h-full bg-rose-500"></div>
                <p class="font-mono text-[10px] text-rose-600 uppercase tracking-widest font-bold mb-2 ml-1">// Errors</p>
                <ul class="ml-1 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li class="text-sm text-rose-700">— {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('items.update', $item) }}" method="POST"
            x-data="{
                itemType: '{{ old('item_type', $item->item_type ?? 'consumable') }}',
                isExpirable: {{ old('is_expirable', $item->is_expirable ? '1' : '0') == '1' ? 'true' : 'false' }},
                allCategories: {{ Js::from($categories->map(fn($c) => ['id' => $c->id, 'name' => $c->name, 'item_type' => $c->item_type])) }},
                get filteredCategories() { return this.allCategories.filter(c => c.item_type === this.itemType); },
                selectedCategoryId: '{{ old('category_id', $item->category_id) }}',
                selectedCategoryName: '{{ old('category_id') ? optional($categories->firstWhere('id', old('category_id')))->name : optional($item->category)->name }}',
                catSearch: '',
                catOpen: false,
                catAdding: false,
                catNewValue: '',
                get catFiltered() { return this.filteredCategories.filter(c => c.name.toLowerCase().includes(this.catSearch.toLowerCase())); },
                selectCat(cat) { this.selectedCategoryId = cat.id; this.selectedCategoryName = cat.name; this.catOpen = false; this.catSearch = ''; },
                saveCat() {
                    if (!this.catNewValue.trim()) return;
                    this.selectedCategoryId = this.catNewValue.trim();
                    this.selectedCategoryName = this.catNewValue.trim();
                    this.catAdding = false; this.catNewValue = ''; this.catOpen = false;
                }
            }">
            @csrf
            @method('PUT')

            {{-- ======================== --}}
            {{-- SECTION 1: Item Type    --}}
            {{-- ======================== --}}
            <div class="bg-white border border-slate-200 mb-4 relative">
                <div class="absolute top-0 left-0 w-1 h-full bg-blue-500"></div>
                <div class="px-5 py-4 ml-1">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="h-2 w-2 bg-blue-500 inline-block"></span>
                        <p class="text-[10px] font-mono font-bold text-blue-600 uppercase tracking-widest">01 // Item Type</p>
                    </div>

                    <input type="hidden" name="item_type" :value="itemType">

                    <div class="grid grid-cols-2 gap-3 mt-1">
                        <label @click="itemType = 'consumable'; selectedCategoryId = ''; selectedCategoryName = ''"
                            :class="itemType === 'consumable' ? 'border-indigo-500 bg-indigo-50' : 'border-slate-200 bg-slate-50 hover:bg-slate-100'"
                            class="flex items-center gap-3 border p-3 cursor-pointer transition-colors">
                            <input type="radio" name="_item_type_radio" value="consumable" class="accent-indigo-600 w-4 h-4" :checked="itemType === 'consumable'">
                            <div>
                                <p class="text-sm font-bold text-slate-800">Consumable</p>
                                <p class="text-[10px] font-mono text-slate-500 mt-0.5">Paper Clip, Gloves, Tape.</p>
                            </div>
                        </label>
                        <label @click="itemType = 'device'; selectedCategoryId = ''; selectedCategoryName = ''"
                            :class="itemType === 'device' ? 'border-violet-500 bg-violet-50' : 'border-slate-200 bg-slate-50 hover:bg-slate-100'"
                            class="flex items-center gap-3 border p-3 cursor-pointer transition-colors">
                            <input type="radio" name="_item_type_radio" value="device" class="accent-violet-600 w-4 h-4" :checked="itemType === 'device'">
                            <div>
                                <p class="text-sm font-bold text-slate-800">Device / Equipment</p>
                                <p class="text-[10px] font-mono text-slate-500 mt-0.5">Tracked with brand & serial.</p>
                            </div>
                        </label>
                    </div>
                    @error('item_type') <p class="mt-2 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror

                    {{-- Expiry Toggle (only for consumables) --}}
                    <div x-show="itemType === 'consumable'" class="mt-4 border-t border-dashed border-slate-100 pt-4">
                        <p class="text-[10px] font-mono font-bold text-slate-500 uppercase tracking-widest mb-3">// Expiry Behaviour</p>
                        <div class="grid grid-cols-2 gap-3">
                            <label @click="isExpirable = true"
                                :class="isExpirable ? 'border-amber-500 bg-amber-50' : 'border-slate-200 bg-slate-50 hover:bg-slate-100'"
                                class="flex items-center gap-3 border p-3 cursor-pointer transition-colors">
                                <input type="radio" name="_expiry_radio" class="accent-amber-500 w-4 h-4" :checked="isExpirable">
                                <div>
                                    <p class="text-sm font-bold text-slate-800">Has Expiry Date</p>
                                    <p class="text-[10px] font-mono text-slate-500 mt-0.5">Batches flagged near expiry on dashboard.</p>
                                </div>
                            </label>
                            <label @click="isExpirable = false"
                                :class="!isExpirable ? 'border-slate-500 bg-slate-100' : 'border-slate-200 bg-slate-50 hover:bg-slate-100'"
                                class="flex items-center gap-3 border p-3 cursor-pointer transition-colors">
                                <input type="radio" name="_expiry_radio" class="accent-slate-600 w-4 h-4" :checked="!isExpirable">
                                <div>
                                    <p class="text-sm font-bold text-slate-800">Does Not Expire</p>
                                    <p class="text-[10px] font-mono text-slate-500 mt-0.5">No expiry tracking, e.g. cables, clips.</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Hidden input — devices always false, consumables use toggle --}}
                    <input type="hidden" name="is_expirable" :value="itemType === 'device' ? '0' : (isExpirable ? '1' : '0')">
                </div>
            </div>

            {{-- ========================== --}}
            {{-- SECTION 2: General Info   --}}
            {{-- ========================== --}}
            <div class="bg-white border border-slate-200 mb-4 relative">
                <div class="absolute top-0 left-0 w-1 h-full bg-sky-500"></div>
                <div class="px-5 py-4 ml-1">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="h-2 w-2 bg-sky-500 inline-block"></span>
                        <p class="text-[10px] font-mono font-bold text-sky-600 uppercase tracking-widest">02 // General Details</p>
                    </div>

                    <div class="space-y-4 mt-1">
                        {{-- Consumable: name only --}}
                        <div x-show="itemType === 'consumable'">
                            <label class="block text-sm font-bold text-slate-700 mb-1.5">Item Name <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $item->name) }}"
                                class="block w-full border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:outline-none py-2.5 px-3 text-sm font-mono text-slate-800 transition-colors"
                                :required="itemType === 'consumable'">
                            @error('name') <p class="mt-1.5 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror
                        </div>

                        {{-- Device: brand, model, name --}}
                        <div x-show="itemType === 'device'" style="display:none;">
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 mb-4">
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Brand <span class="text-rose-500">*</span></label>
                                    <input type="text" name="brand" value="{{ old('brand', $item->brand) }}"
                                        class="block w-full border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:outline-none py-2.5 px-3 text-sm font-mono text-slate-800 transition-colors"
                                        placeholder="e.g. Medtronic"
                                        :required="itemType === 'device'">
                                    @error('brand') <p class="mt-1.5 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Model <span class="text-rose-500">*</span></label>
                                    <input type="text" name="model" value="{{ old('model', $item->model) }}"
                                        class="block w-full border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:outline-none py-2.5 px-3 text-sm font-mono text-slate-800 transition-colors"
                                        placeholder="e.g. Endeavor Sprint"
                                        :required="itemType === 'device'">
                                    @error('model') <p class="mt-1.5 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Category --}}
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1.5">Category <span class="text-rose-500">*</span></label>
                            <input type="hidden" name="category_id" :value="selectedCategoryId" required>

                            <div class="relative w-full">
                                <button type="button" @click="catOpen = !catOpen"
                                    class="block w-full border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:outline-none py-2.5 px-3 text-sm font-mono text-slate-800 transition-colors text-left flex justify-between items-center"
                                    :class="{'text-slate-400': !selectedCategoryId}">
                                    <span x-text="selectedCategoryName || 'Select category...'" class="truncate block"></span>
                                    <svg class="h-4 w-4 text-slate-400 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" /></svg>
                                </button>

                                <div x-show="catOpen" x-transition.opacity.duration.200ms @click.away="catOpen = false"
                                    class="absolute z-50 mt-1 w-full bg-white border border-slate-200 overflow-hidden flex flex-col"
                                    style="display:none">
                                    <div class="px-2 pt-2 pb-1 border-b border-slate-100">
                                        <span class="text-[10px] font-mono font-bold uppercase tracking-widest"
                                            :class="itemType === 'device' ? 'text-violet-500' : 'text-indigo-500'"
                                            x-text="itemType === 'device' ? '⚙ Device Categories' : '⚡ Consumable Categories'"></span>
                                    </div>
                                    <div class="px-2 py-1.5">
                                        <input type="text" x-model="catSearch" @click.stop placeholder="Search categories..."
                                            class="block w-full border border-slate-200 bg-slate-50 focus:outline-none py-2 px-3 text-sm font-mono text-slate-800 transition-colors">
                                    </div>
                                    <ul class="max-h-52 overflow-y-auto p-1">
                                        <template x-for="cat in catFiltered" :key="cat.id">
                                            <li @click="selectCat(cat)"
                                                class="relative cursor-pointer px-3 py-2 text-sm font-mono text-slate-700 hover:bg-slate-50 transition-colors flex items-center justify-between"
                                                :class="{'bg-slate-50 font-bold text-slate-900': selectedCategoryId == cat.id}">
                                                <span x-text="cat.name" class="block truncate"></span>
                                                <svg x-show="selectedCategoryId == cat.id" class="h-3.5 w-3.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                            </li>
                                        </template>
                                        <li x-show="catFiltered.length === 0" class="px-3 py-4 text-center text-xs font-mono text-slate-400">
                                            <span x-text="filteredCategories.length === 0 ? 'No ' + itemType + ' categories yet.' : 'No matching categories.'"></span>
                                        </li>
                                    </ul>
                                    <div class="border-t border-slate-100 bg-slate-50 p-2">
                                        <button x-show="!catAdding" @click="catAdding = true; $nextTick(() => $refs.catInput.focus())" type="button"
                                            class="flex w-full items-center gap-2 px-3 py-2 text-xs font-mono font-bold text-indigo-600 hover:bg-white border border-transparent hover:border-slate-200 transition-all">
                                            + Add new category
                                        </button>
                                        <div x-show="catAdding" class="flex items-center gap-2">
                                            <input x-ref="catInput" type="text" x-model="catNewValue" @click.stop @keydown.enter.prevent="saveCat()"
                                                placeholder="New category name..."
                                                class="block w-full flex-1 border border-slate-200 bg-white py-2 px-3 text-sm font-mono text-slate-800 focus:outline-none">
                                            <button type="button" @click="saveCat()" class="flex h-8 w-8 items-center justify-center bg-emerald-500 text-white hover:bg-emerald-600 transition-colors shrink-0">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                            </button>
                                            <button type="button" @click="catAdding = false; catNewValue = ''" class="flex h-8 w-8 items-center justify-center border border-slate-200 bg-white text-slate-500 hover:bg-slate-100 transition-colors shrink-0">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @error('category_id') <p class="mt-1.5 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- ========================== --}}
            {{-- SECTION 3: Stock Settings --}}
            {{-- ========================== --}}
            <div class="bg-white border border-slate-200 mb-4 relative">
                <div class="absolute top-0 left-0 w-1 h-full bg-teal-500"></div>
                <div class="px-5 py-4 ml-1">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="h-2 w-2 bg-teal-500 inline-block"></span>
                        <p class="text-[10px] font-mono font-bold text-teal-600 uppercase tracking-widest">03 // Stock Settings</p>
                    </div>

                    <div class="mt-1">
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Tracking Unit <span class="text-rose-500">*</span></label>
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
                            <button type="button" @click="toggle()"
                                class="block w-full border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:outline-none py-2.5 px-3 text-sm font-mono text-slate-800 transition-colors text-left flex justify-between items-center"
                                :class="{'text-slate-400': !selectedId}">
                                <span x-text="selectedName || 'Select or type unit...'" class="truncate block"></span>
                                <svg class="h-4 w-4 text-slate-400 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" /></svg>
                            </button>
                            <div x-show="isOpen" x-transition.opacity.duration.200ms @click.away="close()"
                                class="absolute z-50 mt-1 w-full bg-white border border-slate-200 overflow-hidden flex flex-col" style="display: none;">
                                <div class="px-2 pt-2 pb-1">
                                    <input type="text" x-ref="searchInput" x-model="search" placeholder="Search units..."
                                        class="block w-full border border-slate-200 bg-slate-50 focus:outline-none py-2 px-3 text-sm font-mono text-slate-800 transition-colors">
                                </div>
                                <ul class="max-h-60 overflow-y-auto p-1">
                                    <template x-for="option in filteredOptions" :key="option.id">
                                        <li @click="selectOption(option)"
                                            class="relative cursor-pointer px-3 py-2 text-sm font-mono text-slate-700 hover:bg-slate-50 transition-colors flex items-center justify-between"
                                            :class="{'bg-slate-50 font-bold text-slate-900': selectedId == option.id}">
                                            <span x-text="option.name" class="block truncate"></span>
                                            <svg x-show="selectedId == option.id" class="h-3.5 w-3.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                        </li>
                                    </template>
                                    <li x-show="filteredOptions.length === 0" class="px-3 py-4 text-center text-xs font-mono text-slate-400">No matching units found.</li>
                                </ul>
                                <div class="border-t border-slate-100 bg-slate-50 p-2">
                                    <button x-show="!isAdding" @click="isAdding = true; $nextTick(() => $refs.newInput.focus())" type="button"
                                        class="flex w-full items-center gap-2 px-3 py-2 text-xs font-mono font-bold text-indigo-600 hover:bg-white border border-transparent hover:border-slate-200 transition-all">
                                        + Add another option
                                    </button>
                                    <div x-show="isAdding" class="flex items-center gap-2">
                                        <input x-ref="newInput" type="text" x-model="newValue" @keydown.enter.prevent="saveNewOption()" placeholder="Type new unit..."
                                            class="block w-full flex-1 border border-slate-200 bg-white py-2 px-3 text-sm font-mono text-slate-800 focus:outline-none">
                                        <button type="button" @click="saveNewOption()" class="flex h-8 w-8 items-center justify-center bg-emerald-500 text-white hover:bg-emerald-600 transition-colors shrink-0">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                        </button>
                                        <button type="button" @click="isAdding = false; newValue = ''" class="flex h-8 w-8 items-center justify-center border border-slate-200 bg-white text-slate-500 hover:bg-slate-100 transition-colors shrink-0">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Reorder Level --}}
                    <div class="mt-4 border-t border-dashed border-slate-100 pt-4">
                        <label class="block text-sm font-bold text-slate-700 mb-1">Reorder Level <span class="font-normal text-slate-400">(Alert threshold)</span></label>
                        <p class="text-[10px] font-mono text-slate-400 mb-2">When new stock falls to or below this number, the item will be flagged as <span class="font-bold text-amber-500">Reorder</span>.</p>
                        <input type="number" name="reorder_level" value="{{ old('reorder_level', $item->reorder_level ?? 10) }}" min="0"
                            class="block w-full sm:w-40 border border-slate-200 bg-slate-50 focus:bg-white focus:border-amber-500 focus:outline-none py-2.5 px-3 text-sm font-mono text-slate-800 transition-colors"
                            placeholder="10">
                        @error('reorder_level') <p class="mt-1.5 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- ========================== --}}
            {{-- SECTION 4: Location       --}}
            {{-- ========================== --}}
            <div class="bg-white border border-slate-200 mb-4 relative">
                <div class="absolute top-0 left-0 w-1 h-full bg-violet-500"></div>
                <div class="px-5 py-4 ml-1">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="h-2 w-2 bg-violet-500 inline-block"></span>
                        <p class="text-[10px] font-mono font-bold text-violet-600 uppercase tracking-widest">04 // Storage Location</p>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 mt-1">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1.5">Storage Location</label>
                            <input type="text" name="storage_location" value="{{ old('storage_location', $item->storage_location) }}" list="storage-locations"
                                class="block w-full border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:outline-none py-2.5 px-3 text-sm font-mono text-slate-800 transition-colors"
                                placeholder="e.g. Storage 1, Supply Room A...">
                            <datalist id="storage-locations">
                                @foreach($storageLocations as $loc)
                                    <option value="{{ $loc->name }}"></option>
                                @endforeach
                            </datalist>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1.5">Section / Bin</label>
                            <input type="text" name="storage_section" value="{{ old('storage_section', $item->storage_section) }}" list="storage-sections"
                                class="block w-full border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:outline-none py-2.5 px-3 text-sm font-mono text-slate-800 transition-colors"
                                placeholder="e.g. Section 1, Shelf B...">
                            <datalist id="storage-sections">
                                @foreach($storageSections as $loc)
                                    <option value="{{ $loc->name }}"></option>
                                @endforeach
                            </datalist>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ========================== --}}
            {{-- SECTION 5: Description    --}}
            {{-- ========================== --}}
            <div class="bg-white border border-slate-200 mb-4 relative">
                <div class="absolute top-0 left-0 w-1 h-full bg-slate-400"></div>
                <div class="px-5 py-4 ml-1">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="h-2 w-2 bg-slate-400 inline-block"></span>
                        <p class="text-[10px] font-mono font-bold text-slate-500 uppercase tracking-widest">05 // Additional Details</p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Description <span class="font-normal text-slate-400">(Optional)</span></label>
                        <textarea name="description" rows="4"
                            class="block w-full border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:outline-none py-2.5 px-3 text-sm font-mono text-slate-800 transition-colors placeholder:text-slate-400"
                            placeholder="Enter detailed specifications or notes...">{{ old('description', $item->description) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('items.show', $item) }}"
                    class="px-5 py-2.5 text-sm font-mono font-bold text-slate-500 hover:text-slate-800 transition-colors border border-slate-200 hover:border-slate-300">
                    Cancel
                </a>
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 text-[11px] font-mono font-bold uppercase tracking-[0.15em] transition-colors border border-blue-700">
                    <span>Save Changes</span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                        <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </form>
    </div>
@endsection