@extends('layouts.app')

@section('title', 'New Borrow')

@section('actions')
    <a href="{{ route('in-out.index', ['tab' => 'borrow']) }}"
        class="inline-flex items-center gap-2 border border-sky-100 bg-white px-4 py-2 text-[11px] font-mono font-bold text-slate-600 uppercase tracking-widest hover:bg-sky-50 transition-colors">
        ← Back to Borrows
    </a>
@endsection

@section('content')
<div class="bg-white rounded-2xl overflow-hidden border border-sky-100">

    {{-- Page Header --}}
    <div class="p-6 border-b border-sky-100 flex items-center justify-between shrink-0 mb-6">
        <div>
            <p class="font-mono text-[10px] font-bold uppercase tracking-widest text-sky-500 mb-1">Borrows://Record</p>
            <h3 class="text-xl font-black text-[#0f172a] tracking-tight">Record Borrow</h3>
            <p class="text-xs text-slate-400 font-mono mt-1">Track items lent to or returned from staff.</p>
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

        <form action="{{ route('borrows.store') }}" method="POST" x-data="borrowForm()">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                {{-- FULL COLUMN --}}
                <div class="lg:col-span-12 space-y-4">
                    
                    {{-- ======================== --}}
                    {{-- SECTION 1: Item Select  --}}
                    {{-- ======================== --}}
                    <div class="bg-white border border-sky-100 relative overflow-hidden">
                        <div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-sky-400 to-sky-600"></div>
                        <div class="px-5 py-4">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="h-2 w-2 bg-sky-500 inline-block"></span>
                                <p class="text-[10px] font-mono font-bold text-sky-600 uppercase tracking-widest">01 // Item Selection</p>
                            </div>

                            <div x-show="true">
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Existing Item <span class="text-rose-500">*</span></label>
                                <select name="item_id" required x-model="selectedItem" @change="onItemChange()"
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
                                    class="block w-full border border-sky-100 bg-slate-50 focus:bg-white focus:border-sky-500 focus:outline-none py-2.5 px-3 text-sm text-[#0f172a] font-mono transition-colors">
                                    <option value="">-- Choose an item --</option>
                                    @foreach($items as $i)
                                        <option value="{{ $i->id }}">
                                            [{{ strtoupper($i->item_type) }}] {{ $i->name }} · {{ $i->total_stock }} new, {{ $i->effective_stock_used }} used
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div x-show="selectedItem" class="mt-4 pt-4 border-t border-slate-100" x-cloak>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Stock to Borrow <span class="text-rose-500">*</span></label>
                                <div class="grid grid-cols-2 gap-3 sm:w-2/3">
                                    <label :class="stockType === 'new' ? 'border-sky-500 bg-sky-50' : 'border-sky-100 bg-slate-50 hover:bg-sky-50'"
                                        class="flex items-center gap-3 border p-3 cursor-pointer transition-colors">
                                        <input type="radio" name="stock_type" value="new" x-model="stockType"
                                            @change="selectedEntries = []" class="accent-blue-600">
                                        <div>
                                            <p class="text-sm font-bold text-[#0f172a]">New Stock</p>
                                            <p class="text-xs font-mono text-slate-500"
                                                x-text="(items[selectedItem] ? items[selectedItem].new : 0) + ' ' + (items[selectedItem] ? items[selectedItem].unit : '')">
                                            </p>
                                        </div>
                                    </label>
                                    <label x-show="items[selectedItem] && items[selectedItem].used > 0"
                                        :class="stockType === 'used' ? 'border-amber-500 bg-amber-50' : 'border-sky-100 bg-slate-50 hover:bg-sky-50'"
                                        class="flex items-center gap-3 border p-3 cursor-pointer transition-colors">
                                        <input type="radio" name="stock_type" value="used" x-model="stockType"
                                            @change="selectedEntries = []" class="accent-amber-500">
                                        <div>
                                            <p class="text-sm font-bold text-[#0f172a]">Used Stock</p>
                                            <p class="text-xs font-mono text-slate-500"
                                                x-text="(items[selectedItem] ? items[selectedItem].used : 0) + ' ' + (items[selectedItem] ? items[selectedItem].unit : '')">
                                            </p>
                                        </div>
                                    </label>
                                </div>
                                @error('stock_type') <p class="mt-1.5 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror
                            </div>

                        </div>
                    </div>

                    {{-- ======================================== --}}
                    {{-- SECTION 3A: Device Selection (OUT ONLY) --}}
                    {{-- ======================================== --}}
                    <div class="bg-white border border-indigo-300 relative" x-show="isDevice() && getBatches().length > 0" x-cloak x-transition>
                        <div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-sky-400 to-sky-600"></div>
                        <div class="ml-1">
                            <div class="px-5 py-4 border-b border-dashed border-slate-100 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                                <div>
                                    <div class="flex items-center gap-2 mb-0.5">
                                        <span class="h-2 w-2 bg-indigo-500 inline-block"></span>
                                        <p class="text-[10px] font-mono font-bold text-indigo-600 uppercase tracking-widest">02 // Select Devices to Lend</p>
                                    </div>
                                    <p class="text-xs text-slate-500">Choose the exact serial numbers to lend — new or used.</p>
                                </div>
                                <div class="shrink-0 flex items-center md:text-right gap-3">
                                    <div class="text-right">
                                        <p class="text-2xl font-black font-mono text-indigo-600 leading-none" x-text="selectedEntries.length"></p>
                                        <p class="text-[10px] font-mono text-slate-400 uppercase tracking-widest">Qty selected</p>
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
                                                x-text="batch.serial_number ? 'SN: ' + batch.serial_number : 'Lot: ' + (batch.lot_number || 'N/A')"></p>
                                            <div class="flex items-center gap-2 mt-0.5">
                                                <span x-show="!batch.is_used" class="flex items-center gap-1 text-[9px] font-mono font-bold uppercase tracking-widest text-teal-700 bg-teal-100 px-1.5 py-0.5 border border-teal-200">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-2.5 h-2.5"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-11.25a.75.75 0 00-1.5 0v2.5h-2.5a.75.75 0 000 1.5h2.5v2.5a.75.75 0 001.5 0v-2.5h2.5a.75.75 0 000-1.5h-2.5v-2.5z" clip-rule="evenodd" /></svg>
                                                    NEW
                                                </span>
                                                <span x-show="batch.is_used" class="flex items-center gap-1 text-[9px] font-mono font-bold uppercase tracking-widest text-amber-700 bg-amber-100 px-1.5 py-0.5 border border-amber-200">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-2.5 h-2.5"><path fill-rule="evenodd" d="M15.312 11.424a5.5 5.5 0 01-9.201 2.466l-.312-.311h2.433a.75.75 0 000-1.5H3.989a.75.75 0 00-.75.75v4.242a.75.75 0 001.5 0v-2.43l.31.31a7 7 0 0011.712-3.138.75.75 0 00-1.449-.39zm1.23-3.723a.75.75 0 00.219-.53V2.929a.75.75 0 00-1.5 0V5.36l-.31-.31A7 7 0 003.239 8.188a.75.75 0 101.448.389A5.5 5.5 0 0113.89 6.11l.311.31h-2.432a.75.75 0 000 1.5h4.243a.75.75 0 00.53-.219z" clip-rule="evenodd" /></svg>
                                                    USED
                                                </span>
                                                <p class="text-[10px] font-mono text-slate-400" x-text="'Remaining: ' + batch.remaining"></p>
                                            </div>
                                        </div>
                                    </label>
                                </template>
                                <div x-show="getBatches().length === 0" class="px-5 py-6 text-center">
                                    <p class="text-[11px] font-mono text-slate-400">// No device stock available to lend</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ============================================= --}}
                    {{-- SECTION 3B: Quantity & Specifics            --}}
                    {{-- ============================================= --}}
                    <div class="bg-white border border-sky-100 relative" x-show="selectedItem" x-cloak x-transition>
                        <div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-teal-400 to-teal-600"></div>
                        <div class="px-5 py-4">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="h-2 w-2 bg-teal-500 inline-block"></span>
                                <p class="text-[10px] font-mono font-bold text-teal-600 uppercase tracking-widest">
                                    0<span x-text="isDevice() ? '3' : '2'"></span> // Quantities & Dates
                                </p>
                            </div>
                            
                            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                                


                                {{-- Quantities --}}
                                <div x-show="!isDevice()" x-cloak>
                                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Borrow Quantity <span class="text-rose-500">*</span></label>
                                    <div class="relative">
                                        <input type="number" name="quantity_borrowed" x-model="manualQty" min="1" value="{{ old('quantity_borrowed', 1) }}"
                                            class="block w-full border border-sky-100 bg-slate-50 focus:bg-white focus:border-sky-500 focus:outline-none py-2.5 pl-3 pr-16 text-sm font-mono text-[#0f172a] transition-colors">
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                            <span class="text-[10px] font-mono font-bold text-slate-400 uppercase" x-text="getUnit()"></span>
                                        </div>
                                    </div>
                                    @error('quantity_borrowed') <p class="mt-1.5 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror
                                </div>
                                

                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Borrow Date &amp; Time <span class="text-rose-500">*</span></label>
                                    <input type="datetime-local" name="borrowed_at" value="{{ old('borrowed_at', now()->format('Y-m-d\TH:i')) }}"
                                        class="block w-full border border-sky-100 bg-slate-50 focus:bg-white focus:border-sky-500 focus:outline-none py-2.5 px-3 text-sm font-mono text-[#0f172a] transition-colors"
                                        required>
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Expected Return Date <span class="text-slate-400 font-normal">(Optional)</span></label>
                                    <input type="date" name="return_date" value="{{ old('return_date') }}"
                                        class="block w-full border border-sky-100 bg-slate-50 focus:bg-white focus:border-sky-500 focus:outline-none py-2.5 px-3 text-sm font-mono text-[#0f172a] transition-colors">
                                </div>
                                
                            </div>
                        </div>
                    </div>

                    {{-- ======================== --}}
                    {{-- SECTION 4: Party Info   --}}
                    {{-- ======================== --}}
                    <div class="bg-white border border-sky-100 relative" x-show="selectedItem" x-cloak x-transition>
                        <div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-slate-300 to-slate-500"></div>
                        <div class="px-5 py-4 space-y-4">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="h-2 w-2 bg-slate-400 inline-block"></span>
                                <p class="text-[10px] font-mono font-bold text-slate-500 uppercase tracking-widest">
                                    0<span x-text="isDevice() ? '4' : '3'"></span> // Borrower Details
                                </p>
                            </div>

                            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-bold text-slate-700 mb-1.5">
                                        Name of Borrower <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" name="borrower_name" value="{{ old('borrower_name') }}" required
                                        class="block w-full border border-sky-100 bg-slate-50 focus:bg-white focus:border-sky-500 focus:outline-none py-2.5 px-3 text-sm text-[#0f172a] transition-colors">
                                    @error('borrower_name') <p class="mt-1.5 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Borrower Bio ID <span class="text-rose-500">*</span></label>
                                    <input type="text" name="bio_id" value="{{ old('bio_id') }}" required
                                        class="block w-full border border-sky-100 bg-slate-50 focus:bg-white focus:border-sky-500 focus:outline-none py-2.5 px-3 text-sm text-[#0f172a] transition-colors">
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1.5">
                                        Borrower Department <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" name="department" value="{{ old('department') }}" required
                                        class="block w-full border border-sky-100 bg-slate-50 focus:bg-white focus:border-sky-500 focus:outline-none py-2.5 px-3 text-sm text-[#0f172a] transition-colors">
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Notes <span class="font-normal text-slate-400">(Optional)</span></label>
                                    <textarea name="notes" rows="3"
                                        class="block w-full border border-sky-100 bg-slate-50 focus:bg-white focus:border-sky-500 focus:outline-none py-2.5 px-3 text-sm text-[#0f172a] transition-colors placeholder:text-slate-400"
                                        placeholder="Condition of item, context...">{{ old('notes') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            {{-- Submit --}}
            <div class="flex items-center justify-end gap-3 pt-4">
                <a href="{{ route('in-out.index', ['tab' => 'borrow']) }}"
                    class="px-5 py-2.5 text-sm font-mono font-bold text-slate-500 hover:text-[#0f172a] transition-colors border border-sky-100 hover:border-slate-300">
                    Cancel
                </a>
                
                <div x-data="{ 
                    canSubmit() {
                        if (!selectedItem) return false;
                        if (isDevice() && stockType === 'new') {
                            return selectedEntries.length > 0;
                        }
                        return (parseInt(manualQty) || 0) > 0;
                    } 
                }">
                    <button type="submit" :disabled="!canSubmit()"
                        :class="!canSubmit() ? 'opacity-40 cursor-not-allowed bg-slate-400 border-slate-400' : 'bg-sky-500 hover:bg-sky-600 border-sky-600'"
                        class="inline-flex items-center gap-2 text-white px-6 py-2.5 text-[11px] font-mono font-bold uppercase tracking-[0.15em] transition-colors border">
                        <span>Record Borrow</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                            <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>

        </form>
    </div>

    @php
        // Mirroring transfer logic exactly
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
        function borrowForm() {
            return {
                selectedItem: {{ (old('item_id') ?? request('item_id')) ? (int) (old('item_id') ?? request('item_id')) : 'null' }},
                stockType: 'new',
                selectedEntries: [],
                searchQuery: '',
                manualQty: {{ old('quantity_borrowed', 1) }},
                newUnitLabel: '',
                items: {!! json_encode($itemsJs) !!},

                isDevice() {
                    return this.selectedItem && this.items[this.selectedItem] && this.items[this.selectedItem].type === 'device';
                },

                getUnit() {
                    return this.selectedItem && this.items[this.selectedItem] ? this.items[this.selectedItem].unit : 'units';
                },

                getBatches(applySearch = true) {
                    if (!this.selectedItem || !this.items[this.selectedItem]) return [];
                    const allBatches = this.items[this.selectedItem].batches || [];
                    let batches = allBatches.filter(b => this.stockType === 'new' ? !b.is_used : b.is_used);
                    
                    if (applySearch && this.searchQuery) {
                        const q = this.searchQuery.toLowerCase();
                        batches = batches.filter(b => {
                            const serialStr = b.serial_number ? b.serial_number.toLowerCase() : '';
                            return serialStr.includes(q);
                        });
                    }
                    
                    return batches;
                },

                onItemChange() {
                    this.selectedEntries = [];
                    this.manualQty = 1;
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
                    
                    this.handleSerialInput({target: el});
                },

                handleSerialInput(e) {
                    const val = e.target.value;
                    const lines = val.split('\n').map(l => l.replace(/^\d+\.\s*/, '').trim()).filter(l => l.length > 0);
                    // Borrow quantity usually must be at least 1, but we map it up actively
                    this.manualQty = lines.length === 0 ? 1 : lines.length;
                }
            };
        }
    </script>
@endsection