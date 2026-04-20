@extends('layouts.app')

@section('title', 'New Transfer')

@section('actions')
    <a href="{{ route('in-out.index', ['tab' => 'transfer']) }}"
        class="inline-flex items-center gap-2 border border-slate-200 bg-white px-4 py-2 text-[11px] font-mono font-bold text-slate-600 uppercase tracking-widest hover:bg-slate-50 transition-colors">
        ← Back to Transfers
    </a>
@endsection

@section('content')
    <div class="mx-auto max-w-4xl">

        {{-- Page Header --}}
        <div class="mb-5">
            <p class="text-[10px] font-mono font-semibold text-blue-600 uppercase tracking-[0.25em] mb-1">Transfers://Record
            </p>
            <h1 class="text-xl font-bold text-slate-800 tracking-tight">Record Transfer</h1>
            <p class="text-xs text-slate-400 font-mono mt-0.5">Move items between departments securely.</p>
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

        <form action="{{ route('transfers.store') }}" method="POST" x-data="transferForm()">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                {{-- LEFT COLUMN: Config --}}
                <div class="lg:col-span-12 space-y-4">

                    {{-- ======================== --}}
                    {{-- SECTION 1: Direction --}}
                    {{-- ======================== --}}
                    <div class="bg-white border border-slate-200 relative">
                        <div class="absolute top-0 left-0 w-1 h-full bg-blue-500"></div>
                        <div class="px-5 py-4 ml-1">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="h-2 w-2 bg-blue-500 inline-block"></span>
                                <p class="text-[10px] font-mono font-bold text-blue-600 uppercase tracking-widest">01 //
                                    Transfer Type</p>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <label @click="direction = 'out'; isNewItem = false;"
                                    :class="direction === 'out' ? 'border-amber-500 bg-amber-50' : 'border-slate-200 bg-slate-50 hover:bg-slate-100'"
                                    class="flex items-center gap-3 border p-3 cursor-pointer transition-colors">
                                    <input type="radio" name="type" value="out" class="accent-amber-600 w-4 h-4"
                                        :checked="direction === 'out'">
                                    <div>
                                        <p class="text-sm font-bold text-slate-800">Transfer Out</p>
                                        <p class="text-[10px] font-mono text-slate-500 mt-0.5">Send stock to another
                                            facility/department.</p>
                                    </div>
                                </label>
                                <label @click="direction = 'in'; selectedEntries = [];"
                                    :class="direction === 'in' ? 'border-emerald-500 bg-emerald-50' : 'border-slate-200 bg-slate-50 hover:bg-slate-100'"
                                    class="flex items-center gap-3 border p-3 cursor-pointer transition-colors">
                                    <input type="radio" name="type" value="in" class="accent-emerald-600 w-4 h-4"
                                        :checked="direction === 'in'">
                                    <div>
                                        <p class="text-sm font-bold text-slate-800">Transfer In</p>
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
                    <div class="bg-white border border-slate-200 relative">
                        <div class="absolute top-0 left-0 w-1 h-full bg-sky-500"></div>
                        <div class="px-5 py-4 ml-1">
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
                                                sortField: { field: 'text', direction: 'asc' }
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
                                    class="block w-full border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:outline-none py-2.5 px-3 text-sm text-slate-800 font-mono transition-colors">
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
                                class="pt-2 border-t border-dashed border-slate-200 mt-2">
                                <p class="text-[10px] font-mono text-slate-500 uppercase tracking-widest mb-4">// New Item
                                    Registration Fields</p>

                                {{-- Item Type --}}
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Item Tracking Type <span
                                        class="text-rose-500">*</span></label>
                                <div class="grid grid-cols-2 gap-3 mb-4">
                                    <label @click="newItemType = 'consumable'"
                                        :class="newItemType === 'consumable' ? 'border-indigo-500 bg-indigo-50' : 'border-slate-200 bg-slate-50 hover:bg-slate-100'"
                                        class="flex items-center gap-3 border p-3 cursor-pointer transition-colors">
                                        <input type="radio" name="new_item_type" value="consumable"
                                            class="accent-indigo-600 w-4 h-4" :checked="newItemType === 'consumable'">
                                        <div>
                                            <p class="text-sm font-bold text-slate-800">Consumable</p>
                                        </div>
                                    </label>
                                    <label @click="newItemType = 'device'"
                                        :class="newItemType === 'device' ? 'border-violet-500 bg-violet-50' : 'border-slate-200 bg-slate-50 hover:bg-slate-100'"
                                        class="flex items-center gap-3 border p-3 cursor-pointer transition-colors">
                                        <input type="radio" name="new_item_type" value="device"
                                            class="accent-violet-600 w-4 h-4" :checked="newItemType === 'device'">
                                        <div>
                                            <p class="text-sm font-bold text-slate-800">Device / Eqpt</p>
                                        </div>
                                    </label>
                                </div>

                                {{-- Name / Brand / Model Fields --}}
                                <div x-show="newItemType === 'consumable'" class="mb-4">
                                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Item Name <span
                                            class="text-rose-500">*</span></label>
                                    <input type="text" name="new_item_name" value="{{ old('new_item_name') }}"
                                        class="block w-full border border-slate-200 bg-white focus:border-blue-500 focus:outline-none py-2.5 px-3 text-sm font-mono text-slate-800 transition-colors"
                                        :required="isNewItem && newItemType === 'consumable'"
                                        placeholder="e.g. Printer Paper">
                                </div>

                                <div x-show="newItemType === 'device'" style="display:none;" class="mb-4">
                                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                        <div>
                                            <label class="block text-sm font-bold text-slate-700 mb-1.5">Brand <span
                                                    class="text-rose-500">*</span></label>
                                            <input type="text" name="new_item_brand" value="{{ old('new_item_brand') }}"
                                                class="block w-full border border-slate-200 bg-white focus:border-indigo-500 focus:outline-none py-2.5 px-3 text-sm font-mono text-slate-800 transition-colors"
                                                placeholder="e.g. Epson" :required="isNewItem && newItemType === 'device'">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-slate-700 mb-1.5">Model <span
                                                    class="text-rose-500">*</span></label>
                                            <input type="text" name="new_item_model" value="{{ old('new_item_model') }}"
                                                class="block w-full border border-slate-200 bg-white focus:border-indigo-500 focus:outline-none py-2.5 px-3 text-sm font-mono text-slate-800 transition-colors"
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
                                            class="block w-full border border-slate-200 bg-white focus:border-blue-500 focus:outline-none py-2.5 px-3 text-sm text-slate-800 font-mono transition-colors"
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
                                            class="block w-full border border-slate-200 bg-white focus:border-blue-500 focus:outline-none py-2.5 px-3 text-sm text-slate-800 font-mono transition-colors"
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
                    <div class="bg-white border border-indigo-300 relative" x-show="direction === 'out' && isDevice()"
                        x-cloak x-transition>
                        <div class="absolute top-0 left-0 w-1 h-full bg-indigo-500"></div>
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
                            <div class="divide-y divide-slate-50 max-h-72 overflow-y-auto">
                                <template x-for="batch in getBatches()" :key="batch.id">
                                    <label :for="'entry_' + batch.id"
                                        :class="selectedEntries.includes(String(batch.id)) ? 'bg-indigo-50 border-l-2 border-l-indigo-500' : 'hover:bg-slate-50'"
                                        class="flex items-center gap-4 px-5 py-3 cursor-pointer transition-colors border-l-2 border-transparent">
                                        <input type="checkbox" :id="'entry_' + batch.id" name="selected_entries[]"
                                            :value="String(batch.id)" x-model="selectedEntries"
                                            class="h-4 w-4 accent-indigo-600 shrink-0">
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-bold font-mono text-slate-800"
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
                    {{-- SECTION 3B: Quantity & Specifics --}}
                    {{-- ============================================= --}}
                    <div class="bg-white border border-slate-200 relative" x-show="isNewItem || selectedItem" x-cloak
                        x-transition>
                        <div class="absolute top-0 left-0 w-1 h-full bg-teal-500"></div>
                        <div class="px-5 py-4 ml-1">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="h-2 w-2 bg-teal-500 inline-block"></span>
                                <p class="text-[10px] font-mono font-bold text-teal-600 uppercase tracking-widest">
                                    0<span x-text="(direction === 'out' && isDevice()) ? '4' : '3'"></span> // Quantities &
                                    Details
                                </p>
                            </div>

                            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                                {{-- Incoming Device Serial Manual input --}}
                                <div x-show="direction === 'in' && isDevice()" x-cloak class="md:col-span-2">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                        {{-- New Serials --}}
                                        <div>
                                            <div class="flex items-center justify-between mb-1.5">
                                                <label class="block text-sm font-bold text-slate-700">Incoming Serial Numbers
                                                    <span class="text-rose-500">*</span>
                                                </label>
                                                <span class="flex items-center gap-1 text-[10px] font-mono font-bold uppercase tracking-widest text-teal-700 bg-teal-100 px-1.5 py-0.5 border border-teal-200">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-2.5 h-2.5"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-11.25a.75.75 0 00-1.5 0v2.5h-2.5a.75.75 0 000 1.5h2.5v2.5a.75.75 0 001.5 0v-2.5h2.5a.75.75 0 000-1.5h-2.5v-2.5z" clip-rule="evenodd" /></svg>
                                                    NEW STOCK
                                                </span>
                                            </div>
                                            <textarea name="new_serial_number" rows="4" @focus="initSerial" id="incoming_new_serial"
                                                @keydown.enter.prevent="handleSerialEnter" @input="recalculateConditionQty"
                                                class="block w-full border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:outline-none py-2.5 px-3 text-sm font-mono text-slate-800 transition-colors placeholder:text-slate-400"
                                                placeholder="1. "
                                                x-bind:disabled="direction !== 'in'">{{ old('new_serial_number') }}</textarea>
                                            <p class="mt-1.5 text-[10px] font-mono text-[9px] text-slate-400">Lines typed here map to New Quantity.</p>
                                        </div>

                                        {{-- Used Serials --}}
                                        <div>
                                            <div class="flex items-center justify-between mb-1.5">
                                                <label class="block text-sm font-bold text-slate-700">Incoming Serial Numbers
                                                    <span class="text-rose-500">*</span>
                                                </label>
                                                <span class="flex items-center gap-1 text-[10px] font-mono font-bold uppercase tracking-widest text-amber-700 bg-amber-100 px-1.5 py-0.5 border border-amber-200">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-2.5 h-2.5"><path fill-rule="evenodd" d="M15.312 11.424a5.5 5.5 0 01-9.201 2.466l-.312-.311h2.433a.75.75 0 000-1.5H3.989a.75.75 0 00-.75.75v4.242a.75.75 0 001.5 0v-2.43l.31.31a7 7 0 0011.712-3.138.75.75 0 00-1.449-.39zm1.23-3.723a.75.75 0 00.219-.53V2.929a.75.75 0 00-1.5 0V5.36l-.31-.31A7 7 0 003.239 8.188a.75.75 0 101.448.389A5.5 5.5 0 0113.89 6.11l.311.31h-2.432a.75.75 0 000 1.5h4.243a.75.75 0 00.53-.219z" clip-rule="evenodd" /></svg>
                                                    USED STOCK
                                                </span>
                                            </div>
                                            <textarea name="used_serial_number" rows="4" @focus="initSerial" id="incoming_used_serial"
                                                @keydown.enter.prevent="handleSerialEnter" @input="recalculateConditionQty"
                                                class="block w-full border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:outline-none py-2.5 px-3 text-sm font-mono text-slate-800 transition-colors placeholder:text-slate-400"
                                                placeholder="1. "
                                                x-bind:disabled="direction !== 'in'">{{ old('used_serial_number') }}</textarea>
                                            <p class="mt-1.5 text-[10px] font-mono text-[9px] text-slate-400">Lines typed here map to Used Quantity.</p>
                                        </div>
                                    </div>
                                    
                                    @error('new_serial_number')
                                    <p class="mt-2 text-[11px] font-mono font-bold text-rose-500">// {{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Quantities --}}
                                <div x-show="!isDevice()" x-cloak>
                                    <label class="block text-sm font-bold text-slate-700 mb-1.5 flex items-center justify-between">
                                        <span x-text="direction === 'out' ? 'Quantity to Transfer Out (New Stock)' : 'New Stock Transferred In'"></span>
                                        <span x-show="direction === 'out' && selectedItem && items[selectedItem]" 
                                            class="text-[10px] font-mono text-teal-600 bg-teal-50 px-2 py-0.5 border border-teal-200"
                                            x-text="'Avail: ' + (items[selectedItem] ? items[selectedItem].new : 0)"></span>
                                    </label>
                                    <div class="relative">
                                        <input type="number" name="new_quantity" x-model="manualNewQty" min="0"
                                            value="{{ old('new_quantity', 0) }}"
                                            class="block w-full border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:outline-none py-2.5 pl-3 pr-16 text-sm font-mono text-slate-800 transition-colors">
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                            <span class="text-[10px] font-mono font-bold text-slate-400 uppercase"
                                                x-text="getUnit()"></span>
                                        </div>
                                    </div>
                                </div>

                                <div x-show="!isNewItem && !isDevice() && direction === 'out' && hasUsedStock()" x-cloak>
                                    <label class="block text-sm font-bold text-slate-700 mb-1.5 flex items-center justify-between">
                                        <span x-text="'Quantity to Transfer Out (Used Stock)'"></span>
                                        <span x-show="selectedItem && items[selectedItem]" 
                                            class="text-[10px] font-mono text-amber-600 bg-amber-50 px-2 py-0.5 border border-amber-200"
                                            x-text="'Avail: ' + (items[selectedItem] ? items[selectedItem].used : 0)"></span>
                                    </label>
                                    <div class="relative">
                                        <input type="number" name="used_quantity" x-model="manualUsedQty" min="0"
                                            value="{{ old('used_quantity', 0) }}"
                                            class="block w-full border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:outline-none py-2.5 pl-3 pr-16 text-sm font-mono text-slate-800 transition-colors">
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                            <span class="text-[10px] font-mono font-bold text-slate-400 uppercase"
                                                x-text="getUnit()"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Transfer Date &amp; Time
                                        <span class="text-rose-500">*</span></label>
                                    <input type="datetime-local" name="transferred_at"
                                        value="{{ old('transferred_at', now()->format('Y-m-d\TH:i')) }}"
                                        class="block w-full border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:outline-none py-2.5 px-3 text-sm font-mono text-slate-800 transition-colors"
                                        required>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- ======================== --}}
                    {{-- SECTION 4: Party Info --}}
                    {{-- ======================== --}}
                    <div class="bg-white border border-slate-200 relative" x-show="isNewItem || selectedItem" x-cloak
                        x-transition>
                        <div class="absolute top-0 left-0 w-1 h-full bg-slate-400"></div>
                        <div class="px-5 py-4 ml-1 space-y-4">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="h-2 w-2 bg-slate-400 inline-block"></span>
                                <p class="text-[10px] font-mono font-bold text-slate-500 uppercase tracking-widest">
                                    0<span x-text="(direction === 'out' && isDevice()) ? '5' : '4'"></span> // Party Details
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
                                        class="block w-full border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:outline-none py-2.5 px-3 text-sm text-slate-800 transition-colors">
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1.5">
                                        <span
                                            x-text="direction === 'out' ? 'Destination Department' : 'Source Department'"></span>
                                        <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" name="department" value="{{ old('department') }}" required
                                        class="block w-full border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:outline-none py-2.5 px-3 text-sm text-slate-800 transition-colors">
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Bio ID <span
                                            class="text-rose-500">*</span></label>
                                    <input type="text" name="bio_id" value="{{ old('bio_id') }}" required
                                        class="block w-full border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:outline-none py-2.5 px-3 text-sm text-slate-800 transition-colors">
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Notes <span
                                            class="font-normal text-slate-400">(Optional)</span></label>
                                    <textarea name="notes" rows="3"
                                        class="block w-full border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:outline-none py-2.5 px-3 text-sm text-slate-800 transition-colors placeholder:text-slate-400"
                                        placeholder="Reason for transfer, conditions...">{{ old('notes') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            {{-- Submit --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200 mt-4">
                <a href="{{ route('in-out.index', ['tab' => 'transfer']) }}"
                    class="px-5 py-2.5 text-sm font-mono font-bold text-slate-500 hover:text-slate-800 transition-colors border border-slate-200 hover:border-slate-300">
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
                        :class="!canSubmit() ? 'opacity-40 cursor-not-allowed bg-slate-400 border-slate-400' : 'bg-blue-600 hover:bg-blue-700 border-blue-700'"
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
                selectedItem: {{ (old('item_id') ?? request('item_id')) ? (int) (old('item_id') ?? request('item_id')) : 'null' }},
                selectedEntries: [],
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

                getBatches() {
                    if (this.isNewItem) return [];
                    if (!this.selectedItem || !this.items[this.selectedItem]) return [];
                    return this.items[this.selectedItem].batches || [];
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