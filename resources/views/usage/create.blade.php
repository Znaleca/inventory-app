@extends('layouts.app')

@section('title', 'Log Usage')

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
            <p class="text-[10px] font-mono font-semibold text-blue-600 uppercase tracking-[0.25em] mb-1">Usage://Log</p>
            <h1 class="text-xl font-bold text-slate-800 tracking-tight">Log Item Usage</h1>
            <p class="text-xs text-slate-400 font-mono mt-0.5">Record inventory items consumed or used during a procedure.
            </p>
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

        <form action="{{ route('usage.store') }}" method="POST" x-data="usageForm()">
            @csrf

            {{-- ======================== --}}
            {{-- SECTION 1: Item Select --}}
            {{-- ======================== --}}
            <div class="bg-white border border-slate-200 mb-4 relative">
                <div class="absolute top-0 left-0 w-1 h-full bg-blue-500"></div>
                <div class="px-5 py-4 ml-1">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="h-2 w-2 bg-blue-500 inline-block"></span>
                        <p class="text-[10px] font-mono font-bold text-blue-600 uppercase tracking-widest">01 // Select Item
                        </p>
                    </div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Item <span
                            class="text-rose-500">*</span></label>
                    <select name="item_id" required x-model="selectedItem" @change="onItemChange()"
                        class="block w-full border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:outline-none py-2.5 px-3 text-sm text-slate-800 font-mono transition-colors">
                        <option value="">-- Choose an item --</option>
                        @foreach($items as $i)
                            @php $total = $i->total_stock + $i->effective_stock_used; @endphp
                            <option value="{{ $i->id }}">
                                [{{ strtoupper($i->item_type) }}] {{ $i->name }} · {{ $i->total_stock }} new,
                                {{ $i->effective_stock_used }} used
                            </option>
                        @endforeach
                    </select>
                    @error('item_id') <p class="mt-1.5 text-xs font-mono font-bold text-rose-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- ========================== --}}
            {{-- SECTION 2: Stock Type --}}
            {{-- ========================== --}}
            <div class="bg-white border border-slate-200 mb-4 relative" x-show="selectedItem" x-cloak x-transition>
                <div class="absolute top-0 left-0 w-1 h-full bg-sky-500"></div>
                <div class="px-5 py-4 ml-1">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="h-2 w-2 bg-sky-500 inline-block"></span>
                        <p class="text-[10px] font-mono font-bold text-sky-600 uppercase tracking-widest">02 // Stock Source
                        </p>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <label x-show="items[selectedItem] && items[selectedItem].new > 0"
                            :class="stockType === 'new' ? 'border-blue-500 bg-blue-50' : 'border-slate-200 bg-slate-50 hover:bg-slate-100'"
                            class="flex items-center gap-3 border p-3 cursor-pointer transition-colors">
                            <input type="radio" name="stock_type" value="new" x-model="stockType"
                                @change="selectedEntries = []" class="accent-blue-600">
                            <div>
                                <p class="text-sm font-bold text-slate-800">New Stock</p>
                                <p class="text-xs font-mono text-slate-500"
                                    x-text="(items[selectedItem] ? items[selectedItem].new : 0) + ' ' + (items[selectedItem] ? items[selectedItem].unit : '')">
                                </p>
                            </div>
                        </label>
                        <label x-show="items[selectedItem] && items[selectedItem].used > 0"
                            :class="stockType === 'used' ? 'border-amber-500 bg-amber-50' : 'border-slate-200 bg-slate-50 hover:bg-slate-100'"
                            class="flex items-center gap-3 border p-3 cursor-pointer transition-colors">
                            <input type="radio" name="stock_type" value="used" x-model="stockType"
                                @change="selectedEntries = []" class="accent-amber-500">
                            <div>
                                <p class="text-sm font-bold text-slate-800">Used Stock</p>
                                <p class="text-xs font-mono text-slate-500"
                                    x-text="(items[selectedItem] ? items[selectedItem].used : 0) + ' ' + (items[selectedItem] ? items[selectedItem].unit : '')">
                                </p>
                            </div>
                        </label>
                    </div>
                    @error('stock_type') <p class="mt-1.5 text-xs font-mono font-bold text-rose-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- ======================================== --}}
            {{-- SECTION 3A: Serial Number Checkboxes --}}
            {{-- Only for devices using New Stock --}}
            {{-- ======================================== --}}
            <div class="bg-white border border-indigo-300 mb-4 relative" x-show="isDevice() && stockType === 'new'" x-cloak
                x-transition>
                <div class="absolute top-0 left-0 w-1 h-full bg-indigo-500"></div>
                <div class="ml-1">
                    {{-- Header --}}
                    <div class="px-5 py-4 border-b border-dashed border-slate-100 flex items-center justify-between">
                        <div>
                            <div class="flex items-center gap-2 mb-0.5">
                                <span class="h-2 w-2 bg-indigo-500 inline-block"></span>
                                <p class="text-[10px] font-mono font-bold text-indigo-600 uppercase tracking-widest">03 //
                                    Select Devices to Use</p>
                            </div>
                            <p class="text-xs text-slate-500">Check each serial/lot number you are taking out of inventory.
                            </p>
                        </div>
                        <div class="shrink-0 ml-4 text-right">
                            <p class="text-2xl font-black font-mono text-indigo-600" x-text="selectedEntries.length"></p>
                            <p class="text-[10px] font-mono text-slate-400 uppercase tracking-widest">selected</p>
                        </div>
                    </div>

                    {{-- Checkbox list --}}
                    <div class="divide-y divide-slate-50 max-h-72 overflow-y-auto">
                        <template x-for="batch in getBatches()" :key="batch.id">
                            <label :for="'entry_' + batch.id"
                                :class="selectedEntries.includes(String(batch.id)) ? 'bg-indigo-50 border-l-2 border-l-indigo-500' : 'hover:bg-slate-50'"
                                class="flex items-center gap-4 px-5 py-3 cursor-pointer transition-colors">
                                <input type="checkbox" :id="'entry_' + batch.id" name="selected_entries[]"
                                    :value="String(batch.id)" x-model="selectedEntries"
                                    class="h-4 w-4 accent-indigo-600 shrink-0">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-bold font-mono text-slate-800"
                                        x-text="batch.serial_number ? 'SN: ' + batch.serial_number : 'Lot: ' + (batch.lot_number || 'N/A')">
                                    </p>
                                    <p class="text-[10px] font-mono text-slate-400 mt-0.5"
                                        x-text="batch.received_date ? 'Added: ' + String(batch.received_date).substring(0, 10) : ''">
                                    </p>
                                </div>
                                <div class="shrink-0 text-right">
                                    <span class="text-xs font-mono font-bold text-teal-600"
                                        x-text="batch.remaining + ' avail.'"></span>
                                </div>
                            </label>
                        </template>
                        <div x-show="getBatches().length === 0" class="px-5 py-6 text-center">
                            <p class="text-[11px] font-mono text-slate-400">// No available units in stock</p>
                        </div>
                    </div>

                    {{-- Auto-computed hidden quantity_used --}}
                    <input type="hidden" name="quantity_used" :value="selectedEntries.length">

                    @error('selected_entries') <p class="px-5 pb-3 text-xs font-mono font-bold text-rose-500">{{ $message }}
                    </p> @enderror
                </div>
            </div>

            {{-- ============================================== --}}
            {{-- SECTION 3C: Batch Selector (expirable items) --}}
            {{-- ============================================== --}}
            <div class="bg-white border border-amber-300 mb-4 relative"
                x-show="selectedItem && isExpirableConsumable() && stockType === 'new'" x-cloak x-transition>
                <div class="absolute top-0 left-0 w-1 h-full bg-amber-500"></div>
                <div class="ml-1">
                    <div class="px-5 py-4 border-b border-dashed border-slate-100 flex items-center justify-between">
                        <div>
                            <div class="flex items-center gap-2 mb-0.5">
                                <span class="h-2 w-2 bg-amber-500 inline-block"></span>
                                <p class="text-[10px] font-mono font-bold text-amber-600 uppercase tracking-widest">03 // Select Batch (FEFO)</p>
                            </div>
                            <p class="text-xs text-slate-500">Choose the batch to deduct from. Nearest expiry is recommended first.</p>
                        </div>
                        <div class="shrink-0 ml-4 text-right">
                            <p class="text-[10px] font-mono text-slate-400 uppercase tracking-widest">Batch selected</p>
                            <p class="text-sm font-black font-mono text-amber-600" x-text="selectedLot || '—'"></p>
                        </div>
                    </div>
                    <div class="divide-y divide-slate-50 max-h-72 overflow-y-auto">
                        <template x-for="(batch, index) in getExpirableBatches()" :key="batch.id">
                            <label :for="'lot_' + batch.id"
                                :class="selectedLot === batch.lot_number ? 'bg-amber-50 border-l-2 border-l-amber-500' : 'hover:bg-slate-50'"
                                class="flex items-center gap-4 px-5 py-3 cursor-pointer transition-colors">
                                <input type="radio" :id="'lot_' + batch.id" name="lot_number"
                                    :value="batch.lot_number" x-model="selectedLot"
                                    class="h-4 w-4 accent-amber-500 shrink-0">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2">
                                        <p class="text-sm font-bold font-mono text-slate-800"
                                            x-text="'LOT: ' + (batch.lot_number || 'N/A')"></p>
                                        <span x-show="index === 0"
                                            class="text-[8px] font-mono font-bold uppercase tracking-wider text-amber-700 bg-amber-100 px-1.5 py-0.5 border border-amber-300">⚡ Recommended</span>
                                    </div>
                                    <p class="text-[10px] font-mono mt-0.5"
                                        :class="batch.expiry_date && isNearExpiry(batch.expiry_date) ? 'text-rose-500 font-bold' : 'text-slate-400'"
                                        x-text="batch.expiry_date ? 'Expires: ' + batch.expiry_date : 'No expiry date'"></p>
                                </div>
                                <div class="shrink-0 text-right">
                                    <span class="text-xs font-mono font-bold text-teal-600"
                                        x-text="batch.remaining + ' avail.'"></span>
                                </div>
                            </label>
                        </template>
                        <div x-show="getExpirableBatches().length === 0" class="px-5 py-6 text-center">
                            <p class="text-[11px] font-mono text-slate-400">// No batches available</p>
                        </div>
                    </div>
                    @error('lot_number') <p class="px-5 pb-3 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- ============================================= --}}
            {{-- SECTION 3B: Quantity (consumables / used) --}}
            {{-- ============================================= --}}
            <div class="bg-white border border-slate-200 mb-4 relative"
                x-show="selectedItem && !(isDevice() && stockType === 'new')" x-cloak x-transition>
                <div class="absolute top-0 left-0 w-1 h-full bg-teal-500"></div>
                <div class="px-5 py-4 ml-1">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="h-2 w-2 bg-teal-500 inline-block"></span>
                        <p class="text-[10px] font-mono font-bold text-teal-600 uppercase tracking-widest">03 // Quantity
                            &amp; Time</p>
                    </div>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1.5">Quantity Used <span
                                    class="text-rose-500">*</span></label>
                            <div class="relative">
                                <input type="number" name="quantity_used" value="{{ old('quantity_used', 1) }}"
                                    class="block w-full border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:outline-none py-2.5 pl-3 pr-16 text-sm font-mono text-slate-800 transition-colors"
                                    min="1">
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                    <span class="text-[10px] font-mono font-bold text-slate-400 uppercase"
                                        x-text="items[selectedItem] ? items[selectedItem].unit : 'units'"></span>
                                </div>
                            </div>
                            @error('quantity_used') <p class="mt-1 text-xs font-mono font-bold text-rose-500">{{ $message }}
                            </p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1.5">Date &amp; Time <span
                                    class="text-rose-500">*</span></label>
                            <input type="datetime-local" name="used_at"
                                value="{{ old('used_at', now()->format('Y-m-d\TH:i')) }}"
                                class="block w-full border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:outline-none py-2.5 px-3 text-sm font-mono text-slate-800 transition-colors"
                                required>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Date for device/new mode (shown separately) --}}
            <div class="bg-white border border-slate-200 mb-4 relative" x-show="isDevice() && stockType === 'new'" x-cloak>
                <div class="absolute top-0 left-0 w-1 h-full bg-teal-500"></div>
                <div class="px-5 py-4 ml-1">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="h-2 w-2 bg-teal-500 inline-block"></span>
                        <p class="text-[10px] font-mono font-bold text-teal-600 uppercase tracking-widest">04 // Date &amp;
                            Time</p>
                    </div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Date &amp; Time <span
                            class="text-rose-500">*</span></label>
                    <input type="datetime-local" name="used_at" value="{{ old('used_at', now()->format('Y-m-d\TH:i')) }}"
                        class="block w-full border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:outline-none py-2.5 px-3 text-sm font-mono text-slate-800 transition-colors"
                        required>
                </div>
            </div>

            {{-- ======================== --}}
            {{-- SECTION: Clinical Info --}}
            {{-- ======================== --}}
            <div class="bg-white border border-slate-200 mb-4 relative" x-show="selectedItem" x-cloak>
                <div class="absolute top-0 left-0 w-1 h-full bg-slate-400"></div>
                <div class="px-5 py-4 ml-1 space-y-4">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="h-2 w-2 bg-slate-400 inline-block"></span>
                        <p class="text-[10px] font-mono font-bold text-slate-500 uppercase tracking-widest">Staff Member</p>
                    </div>

                    {{-- Used By --}}
                    <div
                        x-data="{ isOther: {{ old('_used_by_other') ? 'true' : 'false' }}, staffValue: '{{ old('_used_by_staff', '') }}', otherValue: '{{ old('used_by', '') }}' }">
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Used By</label>
                        <select
                            @change="isOther = ($event.target.value === '__other__'); if (!isOther) staffValue = $event.target.value"
                            class="block w-full border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:outline-none py-2.5 px-3 text-sm text-slate-800 transition-colors">
                            <option value="">— Select staff member —</option>
                            @php $grouped = $staffList->groupBy('type'); @endphp
                            @foreach(['doctor' => 'Doctors', 'nurse' => 'Nurses', 'technician' => 'Technicians', 'other' => 'Other'] as $type => $label)
                                @if($grouped->has($type))
                                    <optgroup label="{{ $label }}">
                                        @foreach($grouped[$type] as $member)
                                            <option value="{{ $member->display_name }}" {{ old('_used_by_staff') == $member->display_name ? 'selected' : '' }}>{{ $member->display_name }}</option>
                                        @endforeach
                                    </optgroup>
                                @endif
                            @endforeach
                            <option value="__other__" {{ old('_used_by_other') ? 'selected' : '' }}>— Others (type a name) —
                            </option>
                        </select>
                        <div x-show="isOther" x-cloak class="mt-2">
                            <input type="text" x-model="otherValue" placeholder="Enter full name..."
                                class="block w-full border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:outline-none py-2.5 px-3 text-sm text-slate-800 transition-colors">
                        </div>
                        <input type="hidden" name="used_by" x-bind:value="isOther ? otherValue : staffValue">
                        <input type="hidden" name="_used_by_other" x-bind:value="isOther ? '1' : ''">
                        <input type="hidden" name="_used_by_staff" x-bind:value="staffValue">
                    </div>

                    {{-- Notes --}}
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Notes <span
                                class="font-normal text-slate-400">(Optional)</span></label>
                        <textarea name="notes" rows="3"
                            class="block w-full border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:outline-none py-2.5 px-3 text-sm text-slate-800 transition-colors placeholder:text-slate-400"
                            placeholder="Procedure, anomalies, or context...">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ request('item_id') ? route('items.show', request('item_id')) : route('items.index') }}"
                    class="px-5 py-2.5 text-sm font-mono font-bold text-slate-500 hover:text-slate-800 transition-colors border border-slate-200 hover:border-slate-300">
                    Cancel
                </a>
                <button type="submit" :disabled="isDevice() && stockType === 'new' && selectedEntries.length === 0"
                    :class="(isDevice() && stockType === 'new' && selectedEntries.length === 0) ? 'opacity-40 cursor-not-allowed bg-slate-400 border-slate-400' : 'bg-blue-600 hover:bg-blue-700 border-blue-700'"
                    class="inline-flex items-center gap-2 text-white px-6 py-2.5 text-[11px] font-mono font-bold uppercase tracking-[0.15em] transition-colors border">
                    <span
                        x-text="isDevice() && stockType === 'new' ? 'Log ' + selectedEntries.length + ' Device(s)' : 'Log Usage'">Log
                        Usage</span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
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

    @php
        $itemsJs = [];
        foreach ($items as $i) {
            // Build batches sorted FEFO: expirable items sort by expiry asc, non-expirable by received_date asc
            $batches = array_values($i->batches_breakdown);
            if ($i->is_expirable) {
                usort($batches, fn($a, $b) =>
                    ($a['expiry_date'] ?? '9999-12-31') <=> ($b['expiry_date'] ?? '9999-12-31')
                );
            }
            $itemsJs[$i->id] = [
                'new'         => $i->total_stock,
                'used'        => $i->effective_stock_used,
                'unit'        => $i->unit,
                'type'        => $i->item_type,
                'is_expirable'=> (bool) $i->is_expirable,
                'batches'     => $batches,
            ];
        }
    @endphp

    <script>
        function usageForm() {
            return {
                selectedItem: {{ (old('item_id') ?? request('item_id')) ? (int) (old('item_id') ?? request('item_id')) : 'null' }},
                stockType: '{{ old('stock_type', 'new') }}',
                selectedEntries: [],
                selectedLot: '{{ old('lot_number', '') }}',
                items: {!! json_encode($itemsJs) !!},

                isDevice() {
                    return this.selectedItem && this.items[this.selectedItem] && this.items[this.selectedItem].type === 'device';
                },

                isExpirableConsumable() {
                    return this.selectedItem && this.items[this.selectedItem] && 
                           this.items[this.selectedItem].type === 'consumable' && 
                           this.items[this.selectedItem].is_expirable;
                },

                getBatches() {
                    if (!this.selectedItem || !this.items[this.selectedItem]) return [];
                    return this.items[this.selectedItem].batches || [];
                },

                getExpirableBatches() {
                    let batches = this.getBatches().filter(b => b.remaining > 0);
                    let grouped = {};
                    batches.forEach(b => {
                        let lot = b.lot_number || 'N/A';
                        if (!grouped[lot]) {
                            grouped[lot] = { ...b };
                        } else {
                            grouped[lot].remaining += b.remaining;
                        }
                    });
                    return Object.values(grouped);
                },

                isNearExpiry(dateStr) {
                    if (!dateStr) return false;
                    const expiry = new Date(dateStr);
                    const now = new Date();
                    const diffTime = expiry.getTime() - now.getTime();
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                    return diffDays <= 90; // Warn if expiring within 90 days
                },

                onItemChange() {
                    this.selectedEntries = [];
                    this.selectedLot = '';
                    const it = this.items[this.selectedItem];
                    if (!it) return;
                    if (it.new > 0 && it.used === 0) this.stockType = 'new';
                    else if (it.used > 0 && it.new === 0) this.stockType = 'used';
                    else if (it.new === 0 && it.used === 0) this.stockType = '';
                    
                    // Auto-select first (recommended FEFO) batch if expirable
                    if (this.isExpirableConsumable()) {
                        const batches = this.getExpirableBatches();
                        if (batches.length > 0) {
                            this.selectedLot = batches[0].lot_number;
                        }
                    }
                }
            };
        }
    </script>

@endsection