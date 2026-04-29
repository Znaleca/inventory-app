@extends('layouts.app')

@section('title', 'New Transfer')

@section('actions')
    <a href="{{ route('in-out.index', ['tab' => 'transfer']) }}"
        class="inline-flex items-center gap-2 border border-sky-100 bg-white px-4 py-2 text-[11px] font-mono font-bold text-slate-600 uppercase tracking-widest hover:bg-sky-50 transition-colors">
        ← Back to Transfers
    </a>
@endsection

@section('content')
<div class="bg-white rounded-2xl border border-sky-100">

    {{-- Page Header --}}
    <div class="p-6 border-b border-sky-100 flex items-center justify-between shrink-0 mb-6">
        <div>
            <p class="font-mono text-[10px] font-bold uppercase tracking-widest text-sky-500 mb-1">Transfers://New</p>
            <h3 class="text-xl font-black text-[#0f172a] tracking-tight">New Transfer</h3>
            <p class="text-xs text-slate-400 font-mono mt-1">Record an inward or outward movement of items.</p>
        </div>
    </div>

    <div class="p-6 pt-0">

        @if ($errors->any())
            <div class="mb-5 bg-rose-50 border border-rose-200 relative px-5 py-4">
                <div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-rose-400 to-rose-600"></div>
                <p class="font-mono text-[10px] text-rose-600 uppercase tracking-widest font-bold mb-2">// Errors</p>
                <ul class="space-y-1">
                    @foreach ($errors->all() as $error)
                        <li class="text-sm text-rose-700">— {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('transfers.store') }}" method="POST" x-data="transferForm()">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                {{-- LEFT COLUMN: Config --}}
                <div class="lg:col-span-12 space-y-4">

                    {{-- ======================== --}}
                    {{-- SECTION 1: Direction --}}
                    {{-- ======================== --}}
                    <div class="bg-white border border-sky-100 relative">
                        <div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-sky-400 to-sky-600"></div>
                        <div class="px-5 py-4">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="h-2 w-2 bg-sky-500 inline-block"></span>
                                <p class="text-[10px] font-mono font-bold text-sky-500 uppercase tracking-widest">01 //
                                    Transfer Type</p>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <label @click="direction = 'out'; isNewItem = false;"
                                    :class="direction === 'out' ? 'border-amber-500 bg-amber-50' : 'border-sky-100 bg-slate-50 hover:bg-sky-50'"
                                    class="flex items-center gap-3 border p-3 cursor-pointer transition-colors">
                                    <input type="radio" name="type" value="out" class="accent-amber-600 w-4 h-4"
                                        :checked="direction === 'out'">
                                    <div>
                                        <p class="text-sm font-bold text-[#0f172a]">Transfer Out</p>
                                        <p class="text-[10px] font-mono text-slate-500 mt-0.5">Send stock to another
                                            facility/department.</p>
                                    </div>
                                </label>
                                <label @click="direction = 'in'; selectedEntries = [];"
                                    :class="direction === 'in' ? 'border-emerald-500 bg-emerald-50' : 'border-sky-100 bg-slate-50 hover:bg-sky-50'"
                                    class="flex items-center gap-3 border p-3 cursor-pointer transition-colors">
                                    <input type="radio" name="type" value="in" class="accent-emerald-600 w-4 h-4"
                                        :checked="direction === 'in'">
                                    <div>
                                        <p class="text-sm font-bold text-[#0f172a]">Transfer In</p>
                                        <p class="text-[10px] font-mono text-slate-500 mt-0.5">Receive stock from another
                                            source.</p>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- ======================== --}}
                    {{-- SECTION 2: Item Select --}}
                    {{-- ======================== --}}
                    <div class="bg-white border border-sky-100 relative">
                        <div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-sky-400 to-sky-600"></div>
                        <div class="px-5 py-4">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="h-2 w-2 bg-sky-500 inline-block"></span>
                                <p class="text-[10px] font-mono font-bold text-sky-600 uppercase tracking-widest">02 // Item
                                    Selection</p>
                            </div>

                            {{-- Inline New Item Toggle (Only for IN) --}}
                            <div x-show="direction === 'in'" x-cloak class="mb-4">
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" name="is_new_item" value="1" x-model="isNewItem"
                                        class="w-4 h-4 accent-sky-600 rounded">
                                    <span class="text-sm font-bold text-slate-700">Register this as a completely new item in
                                        the catalog.</span>
                                </label>
                            </div>

                            {{-- Option A: Select Existing Item --}}
                            <div x-show="!isNewItem" x-collapse>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Existing Item <span
                                        class="text-rose-500">*</span></label>
                                <select name="item_id" :required="!isNewItem" x-model="selectedItem"
                                    @change="onItemChange()"
                                    x-init="
                                        $nextTick(() => {
                                            const ts = new TomSelect($el, {
                                                create: false,
                                                sortField: { field: 'text', direction: 'asc' },
                                                dropdownParent: 'body'
                                            });
                                            ts.on('change', (val) => {
                                                selectedItem = val;
                                                onItemChange();
                                            });
                                            $watch('selectedItem', value => {
                                                if (ts.getValue() != value) {
                                                    ts.setValue(value, true);
                                                }
                                            });
                                        });
                                    "
                                    class="block w-full border border-sky-100 bg-slate-50 focus:bg-white focus:border-sky-500 focus:outline-none py-2.5 px-3 text-sm text-[#0f172a] font-mono transition-colors">
                                    <option value="">-- Choose an item --</option>
                                    @foreach($items as $i)
                                        <option value="{{ $i->id }}">
                                            [{{ strtoupper($i->item_type) }}] {{ $i->name }} · {{ $i->total_stock }} new,
                                            {{ $i->effective_stock_used }} used
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Option B: Register New Item --}}
                            <div x-show="isNewItem" x-cloak x-collapse
                                class="pt-2 border-t border-dashed border-sky-100 mt-2">
                                <p class="text-[10px] font-mono text-slate-500 uppercase tracking-widest mb-4">// New Item
                                    Registration Fields</p>

                                {{-- Item Type --}}
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Item Tracking Type <span
                                        class="text-rose-500">*</span></label>
                                <div class="grid grid-cols-2 gap-3 mb-4">
                                    <label @click="newItemType = 'consumable'"
                                        :class="newItemType === 'consumable' ? 'border-indigo-500 bg-indigo-50' : 'border-sky-100 bg-slate-50 hover:bg-sky-50'"
                                        class="flex items-center gap-3 border p-3 cursor-pointer transition-colors">
                                        <input type="radio" name="new_item_type" value="consumable"
                                            class="accent-indigo-600 w-4 h-4" :checked="newItemType === 'consumable'">
                                        <div>
                                            <p class="text-sm font-bold text-[#0f172a]">Consumable</p>
                                        </div>
                                    </label>
                                    <label @click="newItemType = 'device'"
                                        :class="newItemType === 'device' ? 'border-violet-500 bg-violet-50' : 'border-sky-100 bg-slate-50 hover:bg-sky-50'"
                                        class="flex items-center gap-3 border p-3 cursor-pointer transition-colors">
                                        <input type="radio" name="new_item_type" value="device"
                                            class="accent-violet-600 w-4 h-4" :checked="newItemType === 'device'">
                                        <div>
                                            <p class="text-sm font-bold text-[#0f172a]">Device / Eqpt</p>
                                        </div>
                                    </label>
                                </div>

                                {{-- Name / Brand / Model Fields --}}
                                <div x-show="newItemType === 'consumable'" class="mb-4">
                                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Item Name <span
                                            class="text-rose-500">*</span></label>
                                    <input type="text" name="new_item_name" value="{{ old('new_item_name') }}"
                                        class="block w-full border border-sky-100 bg-white focus:border-sky-500 focus:outline-none py-2.5 px-3 text-sm font-mono text-[#0f172a] transition-colors"
                                        :required="isNewItem && newItemType === 'consumable'"
                                        placeholder="e.g. Printer Paper">
                                </div>

                                <div x-show="newItemType === 'device'" style="display:none;" class="mb-4">
                                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                        <div>
                                            <label class="block text-sm font-bold text-slate-700 mb-1.5">Brand <span
                                                    class="text-rose-500">*</span></label>
                                            <input type="text" name="new_item_brand" value="{{ old('new_item_brand') }}"
                                                class="block w-full border border-sky-100 bg-white focus:border-indigo-500 focus:outline-none py-2.5 px-3 text-sm font-mono text-[#0f172a] transition-colors"
                                                placeholder="e.g. Epson" :required="isNewItem && newItemType === 'device'">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-slate-700 mb-1.5">Model <span
                                                    class="text-rose-500">*</span></label>
                                            <input type="text" name="new_item_model" value="{{ old('new_item_model') }}"
                                                class="block w-full border border-sky-100 bg-white focus:border-indigo-500 focus:outline-none py-2.5 px-3 text-sm font-mono text-[#0f172a] transition-colors"
                                                placeholder="e.g. L3210" :required="isNewItem && newItemType === 'device'">
                                        </div>
                                    </div>
                                </div>

                                {{-- Taxonomy --}}
                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 mb-4">
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Category <span
                                                class="text-rose-500">*</span></label>
                                        <select name="new_item_category_id"
                                            class="block w-full border border-sky-100 bg-white focus:border-sky-500 focus:outline-none py-2.5 px-3 text-sm text-[#0f172a] font-mono transition-colors"
                                            :required="isNewItem">
                                            <option value="">-- Choose Category --</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Tracking Unit <span
                                                class="text-rose-500">*</span></label>
                                        <select name="new_item_unit_id"
                                            class="block w-full border border-sky-100 bg-white focus:border-sky-500 focus:outline-none py-2.5 px-3 text-sm text-[#0f172a] font-mono transition-colors"
                                            :required="isNewItem" x-model="newUnitLabel">
                                            <option value="">-- Choose Unit --</option>
                                            @foreach($units as $unit)
                                                <option value="{{ $unit->id }}" data-symbol="{{ $unit->symbol }}">
                                                    {{ $unit->name }} ({{ $unit->symbol }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- ======================================== --}}
                    {{-- SECTION 3A: Device Selection (OUT ONLY) --}}
                    {{-- ======================================== --}}
                    <div class="bg-white border border-indigo-300 relative" x-show="direction === 'out' && isDevice() && getBatches().length > 0"
                        x-cloak x-transition>
                        <div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-sky-400 to-sky-600"></div>
                        <div class="ml-1">
                            <div
                                class="px-5 py-4 border-b border-dashed border-slate-100 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                                <div>
                                    <div class="flex items-center gap-2 mb-0.5">
                                        <span class="h-2 w-2 bg-indigo-500 inline-block"></span>
                                        <p
                                            class="text-[10px] font-mono font-bold text-indigo-600 uppercase tracking-widest">
                                            03 // Select Devices to Send</p>
                                    </div>
                                    <p class="text-xs text-slate-500">Devices must be explicitly selected to transfer stock.</p>
                                </div>
                                <div class="shrink-0 flex items-center md:text-right gap-3">
                                    <div class="text-right">
                                        <p class="text-2xl font-black font-mono text-indigo-600 leading-none"
                                            x-text="selectedEntries.length"></p>
                                        <p class="text-[10px] font-mono text-slate-400 uppercase tracking-widest">Qty out
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- Checkbox list --}}
                            <div class="px-3 py-2 border-b border-slate-100 bg-slate-50/50" x-show="getBatches(false).length > 5" x-cloak>
                                <div class="relative">
                                    <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                    </svg>
                                    <input type="text" x-model="searchQuery" placeholder="Search serial numbers..." class="block w-full pl-8 pr-3 py-1.5 text-xs font-mono border-0 bg-white ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-inset focus:ring-indigo-600 transition-all placeholder:text-slate-400 text-[#0f172a] outline-none">
                                </div>
                            </div>
                            <div class="divide-y divide-slate-50 max-h-72 overflow-y-auto">
                                <template x-for="batch in getBatches()" :key="batch.id">
                                    <label :for="'entry_' + batch.id"
                                        :class="selectedEntries.includes(String(batch.id)) ? 'bg-indigo-50 border-l-2 border-l-indigo-500' : 'hover:bg-slate-50'"
                                        class="flex items-center gap-4 px-5 py-3 cursor-pointer transition-colors border-l-2 border-transparent">
                                        <input type="checkbox" :id="'entry_' + batch.id" name="selected_entries[]"
                                            :value="String(batch.id)" x-model="selectedEntries"
                                            class="h-4 w-4 accent-indigo-600 shrink-0">
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-bold font-mono text-[#0f172a]"
                                                x-text="batch.serial_number ? 'SN: ' + batch.serial_number : 'Lot: ' + (batch.lot_number || 'N/A')">
                                            </p>
                                            <div class="flex items-center gap-2 mt-0.5">
                                                <span x-show="!batch.is_used" class="flex items-center gap-1 text-[9px] font-mono font-bold uppercase tracking-widest text-teal-700 bg-teal-100 px-1.5 py-0.5 border border-teal-200" title="This is a New device from stock">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-2.5 h-2.5"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-11.25a.75.75 0 00-1.5 0v2.5h-2.5a.75.75 0 000 1.5h2.5v2.5a.75.75 0 001.5 0v-2.5h2.5a.75.75 0 000-1.5h-2.5v-2.5z" clip-rule="evenodd" /></svg>
                                                    NEW
                                                </span>
                                                <span x-show="batch.is_used" class="flex items-center gap-1 text-[9px] font-mono font-bold uppercase tracking-widest text-amber-700 bg-amber-100 px-1.5 py-0.5 border border-amber-200" title="This is a Used device from stock">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-2.5 h-2.5"><path fill-rule="evenodd" d="M15.312 11.424a5.5 5.5 0 01-9.201 2.466l-.312-.311h2.433a.75.75 0 000-1.5H3.989a.75.75 0 00-.75.75v4.242a.75.75 0 001.5 0v-2.43l.31.31a7 7 0 0011.712-3.138.75.75 0 00-1.449-.39zm1.23-3.723a.75.75 0 00.219-.53V2.929a.75.75 0 00-1.5 0V5.36l-.31-.31A7 7 0 003.239 8.188a.75.75 0 101.448.389A5.5 5.5 0 0113.89 6.11l.311.31h-2.432a.75.75 0 000 1.5h4.243a.75.75 0 00.53-.219z" clip-rule="evenodd" /></svg>
                                                    USED
                                                </span>
                                                <p class="text-[10px] font-mono text-slate-400"
                                                    x-text="'Remaining: ' + batch.remaining"></p>
                                            </div>
                                        </div>
                                    </label>
                                </template>
                                <div x-show="getBatches().length === 0" class="px-5 py-6 text-center">
                                    <p class="text-[11px] font-mono text-slate-400">// No device stock available to transfer</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ============================================= --}}
                    {{-- SECTION 3B: Incoming Devices (Serials)        --}}
                    {{-- ============================================= --}}
                    <div class="bg-white border border-teal-300 relative mb-4" x-show="direction === 'in' && isDevice()" x-cloak x-transition>
                        <div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-teal-400 to-teal-600"></div>
                        <div class="px-5 py-4">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="h-2 w-2 bg-teal-500 inline-block"></span>
                                <p class="text-[10px] font-mono font-bold text-teal-600 uppercase tracking-widest">
                                    03 // Incoming Devices
                                </p>
                            </div>
                            <p class="text-xs text-slate-500 mb-4">Enter serial numbers (one per line). The quantity is calculated automatically.</p>
                            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1.5 flex justify-between">
                                        <span>New Stock Serials</span>
                                        <span class="text-xs font-mono text-teal-600" x-text="manualNewQty + ' new'"></span>
                                    </label>
                                    <textarea name="new_serial_number" id="incoming_new_serial" rows="4" 
                                        class="block w-full border border-sky-100 bg-slate-50 focus:bg-white focus:border-teal-500 focus:outline-none py-2 px-3 text-xs font-mono text-[#0f172a] transition-colors leading-relaxed"
                                        placeholder="1. SN-123&#10;2. SN-124"
                                        @focus="initSerial($event)" @keydown.enter.prevent="handleSerialEnter($event)" @input="recalculateConditionQty()"></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1.5 flex justify-between">
                                        <span>Used Stock Serials</span>
                                        <span class="text-xs font-mono text-amber-600" x-text="manualUsedQty + ' used'"></span>
                                    </label>
                                    <textarea name="used_serial_number" id="incoming_used_serial" rows="4"
                                        class="block w-full border border-sky-100 bg-slate-50 focus:bg-white focus:border-amber-500 focus:outline-none py-2 px-3 text-xs font-mono text-[#0f172a] transition-colors leading-relaxed"
                                        placeholder="1. SN-OLD1&#10;2. SN-OLD2"
                                        @focus="initSerial($event)" @keydown.enter.prevent="handleSerialEnter($event)" @input="recalculateConditionQty()"></textarea>
                                </div>
                            </div>
                            @error('new_serial_number') <p class="mt-1.5 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Hidden inputs to submit device quantities correctly --}}
                    <input type="hidden" name="new_quantity" :value="manualNewQty" :disabled="!isDevice() || direction === 'out'">
                    <input type="hidden" name="used_quantity" :value="manualUsedQty" :disabled="!isDevice() || direction === 'out'">

                    {{-- ============================================= --}}
                    {{-- SECTION 3C: Quantity & Specifics --}}
                    {{-- ============================================= --}}
                    <div class="bg-white border border-sky-100 relative" x-show="isNewItem || selectedItem" x-cloak
                        x-transition>
                        <div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-teal-400 to-teal-600"></div>
                        <div class="px-5 py-4">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="h-2 w-2 bg-teal-500 inline-block"></span>
                                <p class="text-[10px] font-mono font-bold text-teal-600 uppercase tracking-widest">
                                    Quantities & Details
                                </p>
                            </div>

                            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                                {{-- Quantities --}}
                                <div x-show="!isDevice() || (direction === 'out' && getBatches().length === 0)" x-cloak class="mb-4 md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-1.5 flex justify-between">
                                            <span>New Stock Quantity</span>
                                            <span class="text-xs font-mono text-teal-600" x-text="direction === 'out' && items[selectedItem] ? items[selectedItem].new + ' avail.' : ''"></span>
                                        </label>
                                        <div class="relative">
                                            <input type="number" name="new_quantity" x-model="manualNewQty" min="0" value="{{ old('new_quantity', 0) }}"
                                                class="block w-full border border-sky-100 bg-slate-50 focus:bg-white focus:border-sky-500 focus:outline-none py-2.5 pl-3 pr-16 text-sm font-mono text-[#0f172a] transition-colors">
                                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                                <span class="text-[10px] font-mono font-bold text-slate-400 uppercase" x-text="getUnit()"></span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div x-show="direction === 'in' || hasUsedStock()">
                                        <label class="block text-sm font-bold text-slate-700 mb-1.5 flex justify-between">
                                            <span>Used Stock Quantity</span>
                                            <span class="text-xs font-mono text-amber-600" x-text="direction === 'out' && items[selectedItem] ? items[selectedItem].used + ' avail.' : ''"></span>
                                        </label>
                                        <div class="relative">
                                            <input type="number" name="used_quantity" x-model="manualUsedQty" min="0" value="{{ old('used_quantity', 0) }}"
                                                class="block w-full border border-sky-100 bg-slate-50 focus:bg-white focus:border-sky-500 focus:outline-none py-2.5 pl-3 pr-16 text-sm font-mono text-[#0f172a] transition-colors">
                                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                                <span class="text-[10px] font-mono font-bold text-slate-400 uppercase" x-text="getUnit()"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Transfer Date &amp; Time
                                        <span class="text-rose-500">*</span></label>
                                    <input type="datetime-local" name="transferred_at"
                                        value="{{ old('transferred_at', now()->format('Y-m-d\TH:i')) }}"
                                        class="block w-full border border-sky-100 bg-slate-50 focus:bg-white focus:border-sky-500 focus:outline-none py-2.5 px-3 text-sm font-mono text-[#0f172a] transition-colors"
                                        required>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- ======================== --}}
                    {{-- SECTION 4: Party Info --}}
                    {{-- ======================== --}}
                    <div class="bg-white border border-sky-100 relative" x-show="isNewItem || selectedItem" x-cloak
                        x-transition>
                        <div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-slate-300 to-slate-500"></div>
                        <div class="px-5 py-4 space-y-4">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="h-2 w-2 bg-slate-400 inline-block"></span>
                                <p class="text-[10px] font-mono font-bold text-slate-500 uppercase tracking-widest">
                                    Party Details
                                </p>
                            </div>

                            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-bold text-slate-700 mb-1.5">
                                        <span
                                            x-text="direction === 'out' ? 'Transferred To (Full Name)' : 'Sender / Source Name'"></span>
                                        <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" name="transferred_to" value="{{ old('transferred_to') }}" required
                                        class="block w-full border border-sky-100 bg-slate-50 focus:bg-white focus:border-sky-500 focus:outline-none py-2.5 px-3 text-sm text-[#0f172a] transition-colors">
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1.5">
                                        <span
                                            x-text="direction === 'out' ? 'Destination Department' : 'Source Department'"></span>
                                        <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" name="department" value="{{ old('department') }}" required
                                        class="block w-full border border-sky-100 bg-slate-50 focus:bg-white focus:border-sky-500 focus:outline-none py-2.5 px-3 text-sm text-[#0f172a] transition-colors">
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Bio ID <span
                                            class="text-rose-500">*</span></label>
                                    <input type="text" name="bio_id" value="{{ old('bio_id') }}" required
                                        class="block w-full border border-sky-100 bg-slate-50 focus:bg-white focus:border-sky-500 focus:outline-none py-2.5 px-3 text-sm text-[#0f172a] transition-colors">
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Notes <span
                                            class="font-normal text-slate-400">(Optional)</span></label>
                                    <textarea name="notes" rows="3"
                                        class="block w-full border border-sky-100 bg-slate-50 focus:bg-white focus:border-sky-500 focus:outline-none py-2.5 px-3 text-sm text-[#0f172a] transition-colors placeholder:text-slate-400"
                                        placeholder="Reason for transfer, conditions...">{{ old('notes') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            {{-- Submit --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-sky-100 mt-4">
                <a href="{{ route('in-out.index', ['tab' => 'transfer']) }}"
                    class="px-5 py-2.5 text-sm font-mono font-bold text-slate-500 hover:text-[#0f172a] transition-colors border border-sky-100 hover:border-slate-300">
                    Cancel
                </a>

                {{-- Dynamic validation handling --}}
                <div x-data="{ 
                                canSubmit() {
                                    if (!selectedItem && !isNewItem) return false;
                                    if (direction === 'out' && isDevice()) {
                                        return selectedEntries.length > 0 || String(manualUsedQty) > 0;
                                    }
                                    const n = parseInt(manualNewQty) || 0;
                                    const u = parseInt(manualUsedQty) || 0;
                                    return (n + u) > 0;
                                } 
                            }">
                    <button type="submit" :disabled="!canSubmit()"
                        :class="!canSubmit() ? 'opacity-40 cursor-not-allowed bg-slate-400 border-slate-400' : 'bg-sky-500 hover:bg-sky-600 border-sky-600'"
                        class="inline-flex items-center gap-2 text-white px-6 py-2.5 text-[11px] font-mono font-bold uppercase tracking-[0.15em] transition-colors border">
                        <span x-text="direction === 'out' ? 'Log Transfer Out' : 'Log Transfer In'">Record Transfer</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                            <path fill-rule="evenodd"
                                d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>

        </form>
    </div>

    @php
        $itemsJs = [];
        foreach ($items as $i) {
            $itemsJs[$i->id] = [
                'new' => $i->total_stock,
                'used' => $i->effective_stock_used,
                'unit' => $i->unit,
                'type' => $i->item_type,
                'batches' => array_values(array_merge($i->batches_breakdown ?? [], $i->used_devices_breakdown ?? [])),
            ];
        }
    @endphp

    <script>
        function transferForm() {
            return {
                direction: '{{ old('type', 'out') }}',
                isNewItem: {{ old('is_new_item') ? 'true' : 'false' }},
                newItemType: '{{ old('new_item_type', 'consumable') }}',
                stockType: 'new',
                selectedItem: {{ (old('item_id') ?? request('item_id')) ? (int) (old('item_id') ?? request('item_id')) : 'null' }},
                selectedEntries: [],
                searchQuery: '',
                manualNewQty: {{ old('new_quantity', 0) }},
                manualUsedQty: {{ old('used_quantity', 0) }},
                newUnitLabel: '',
                items: {!! json_encode($itemsJs) !!},

                isDevice() {
                    if (this.isNewItem) return this.newItemType === 'device';
                    return this.selectedItem && this.items[this.selectedItem] && this.items[this.selectedItem].type === 'device';
                },

                getUnit() {
                    if (this.isNewItem) {
                        return 'units';
                    }
                    return this.selectedItem && this.items[this.selectedItem] ? this.items[this.selectedItem].unit : 'units';
                },

                getBatches(applySearch = true) {
                    if (this.isNewItem) return [];
                    if (!this.selectedItem || !this.items[this.selectedItem]) return [];
                    let batches = this.items[this.selectedItem].batches || [];
                    
                    if (applySearch && this.searchQuery) {
                        const q = this.searchQuery.toLowerCase();
                        batches = batches.filter(b => {
                            const serialStr = b.serial_number ? b.serial_number.toLowerCase() : '';
                            return serialStr.includes(q);
                        });
                    }
                    
                    return batches;
                },

                hasUsedStock() {
                    if (this.isNewItem) return false;
                    return this.selectedItem && this.items[this.selectedItem] && this.items[this.selectedItem].used > 0;
                },

                onItemChange() {
                    this.selectedEntries = [];
                    this.manualNewQty = 0;
                    this.manualUsedQty = 0;
                },

                initSerial(e) {
                    if (!e.target.value.trim()) {
                        e.target.value = '1. ';
                    }
                },

                handleSerialEnter(e) {
                    const el = e.target;
                    const cursor = el.selectionStart;
                    const val = el.value;
                    const linesBefore = val.slice(0, cursor).split('\n');
                    const nextLineNum = linesBefore.length + 1;
                    const insert = '\n' + nextLineNum + '. ';
                    el.value = val.slice(0, cursor) + insert + val.slice(cursor);
                    el.selectionStart = el.selectionEnd = cursor + insert.length;

                    this.recalculateConditionQty();
                },

                recalculateConditionQty() {
                    const elNew = document.getElementById('incoming_new_serial');
                    const elUsed = document.getElementById('incoming_used_serial');
                    
                    const valNew = elNew ? elNew.value : '';
                    const valUsed = elUsed ? elUsed.value : '';
                    
                    const newLines = valNew.split('\n').filter(l => l.replace(/^\d+\.\s*/, '').trim().length > 0);
                    const usedLines = valUsed.split('\n').filter(l => l.replace(/^\d+\.\s*/, '').trim().length > 0);
                    
                    this.manualNewQty = newLines.length;
                    this.manualUsedQty = usedLines.length;
                }
            };
        }
    </script>
@endsection