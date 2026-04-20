@extends('layouts.app')

@section('title', 'Receive Stock — ' . $item->name)

@section('actions')
    <a href="{{ route('items.index') }}"
        class="inline-flex items-center gap-2 border border-slate-200 bg-white px-4 py-2 text-[11px] font-mono font-bold text-slate-600 uppercase tracking-widest hover:bg-slate-50 transition-colors">
        ← Back to Items
    </a>
@endsection

@section('content')
    <div class="mx-auto max-w-3xl">

        {{-- Page Header --}}
        <div class="mb-5 flex items-end justify-between">
            <div>
                <p class="text-[10px] font-mono font-semibold text-indigo-600 uppercase tracking-[0.25em] mb-1">Stock://Add</p>
                <h1 class="text-xl font-bold text-slate-800 tracking-tight">Receive Incoming Stock</h1>
                <p class="text-xs text-slate-400 font-mono mt-0.5">Add new "{{ $item->name }}" to inventory.</p>
            </div>
            <div class="text-right border border-slate-200 bg-white px-3 py-2">
                <p class="text-[10px] font-mono text-slate-400 uppercase tracking-widest mb-0.5">Current Stock</p>
                <p class="text-sm font-bold text-indigo-600 font-mono">{{ $item->total_stock }} <span class="text-[10px] font-normal text-slate-500">{{ $item->unit }}</span></p>
            </div>
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

        <form action="{{ route('stock.store', $item) }}" method="POST" id="stock-form" x-data="{ qty: {{ old('quantity', 1) }} }">
            @csrf

            {{-- ========================== --}}
            {{-- SECTION 1: Stock Condition --}}
            {{-- ========================== --}}
            <div class="bg-white border border-slate-200 mb-4 relative">
                <div class="absolute top-0 left-0 w-1 h-full bg-indigo-500"></div>
                <div class="px-5 py-4 ml-1">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="h-2 w-2 bg-indigo-500 inline-block"></span>
                        <p class="text-[10px] font-mono font-bold text-indigo-600 uppercase tracking-widest">01 // Stock Condition</p>
                    </div>

                    <div class="grid grid-cols-2 gap-3 mt-4">
                        <label class="flex items-center gap-3 border p-3 cursor-pointer transition-colors border-indigo-500 bg-indigo-50 hover:bg-slate-100 group has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50 relative">
                            <input type="radio" name="condition" value="new" class="accent-indigo-600 w-4 h-4" checked>
                            <div>
                                <p class="text-sm font-bold text-slate-800">New Items</p>
                                <p class="text-[10px] font-mono text-slate-500 mt-0.5">Adds to main stock.</p>
                            </div>
                        </label>
                        @if($item->item_type !== 'consumable')
                        <label class="flex items-center gap-3 border p-3 cursor-pointer transition-colors border-slate-200 bg-slate-50 hover:bg-slate-100 group has-[:checked]:border-amber-500 has-[:checked]:bg-amber-50 relative">
                            <input type="radio" name="condition" value="used" class="accent-amber-500 w-4 h-4">
                            <div>
                                <p class="text-sm font-bold text-slate-800">Used Items</p>
                                <p class="text-[10px] font-mono text-slate-500 mt-0.5">Increments Used Stock.</p>
                            </div>
                        </label>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ============================== --}}
            {{-- SECTION 2: Delivery Details   --}}
            {{-- ============================== --}}
            <div class="bg-white border border-slate-200 mb-4 relative">
                <div class="absolute top-0 left-0 w-1 h-full bg-teal-500"></div>
                <div class="px-5 py-4 ml-1">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="h-2 w-2 bg-teal-500 inline-block"></span>
                        <p class="text-[10px] font-mono font-bold text-teal-600 uppercase tracking-widest">02 // Quantities & Dates</p>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 mt-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1.5">Quantity <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <input type="number" name="quantity" x-model.number="qty"
                                    class="block w-full border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:outline-none py-2.5 pl-3 pr-16 text-sm font-mono text-slate-800 transition-colors"
                                    required min="1" placeholder="e.g. 50">
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                    <span class="text-[10px] font-mono font-bold text-slate-400 uppercase">{{ $item->unit }}</span>
                                </div>
                            </div>
                            @error('quantity') <p class="mt-1.5 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1.5">Received Date <span class="text-rose-500">*</span></label>
                            <input type="date" name="received_date" value="{{ old('received_date', date('Y-m-d')) }}"
                                class="block w-full border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:outline-none py-2.5 px-3 text-sm font-mono text-slate-800 transition-colors"
                                required>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ============================== --}}
            {{-- SECTION 3: Batch/Device Data  --}}
            {{-- ============================== --}}
            <div id="batch-details-container" class="bg-white border border-slate-200 mb-4 relative">
                <div class="absolute top-0 left-0 w-1 h-full bg-violet-500"></div>
                <div class="px-5 py-4 ml-1">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="h-2 w-2 bg-violet-500 inline-block"></span>
                        <p class="text-[10px] font-mono font-bold text-violet-600 uppercase tracking-widest">03 // Batch Tracking</p>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 mt-4">
                        @if($item->item_type === 'device')
                            <div class="batch-field sm:col-span-2">
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Serial Numbers <span class="text-slate-400 font-normal ml-1">(Optional)</span></label>
                                <p class="mb-3 text-[10px] font-mono text-slate-500">Enter a unique serial number for each individual unit being added. Leave blank to mark as N/A.</p>
                                
                                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 max-h-72 overflow-y-auto">
                                    <template x-for="i in (qty > 0 ? qty : 1)" :key="i">
                                        <div class="relative">
                                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                                <span class="text-[10px] font-mono font-bold text-slate-400" x-text="'#' + i"></span>
                                            </div>
                                            <input type="text" name="serial_numbers[]"
                                                class="block w-full border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:outline-none py-2.5 pl-9 pr-3 text-sm font-mono text-slate-800 transition-colors"
                                                placeholder="Enter SN... (Leave blank for N/A)">
                                        </div>
                                    </template>
                                </div>
                                @error('serial_numbers.*') <p class="mt-1.5 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror
                            </div>
                        @endif

                        @if($item->item_type === 'consumable')
                            <div x-data="stockEntryForm()">
                                <div class="batch-field sm:col-span-2 bg-indigo-50 border border-indigo-200 p-4 mb-2">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="h-2 w-2 bg-indigo-400 inline-block"></span>
                                    <p class="text-[10px] font-mono font-bold text-indigo-600 uppercase tracking-widest">Consumable Batch Tracking</p>
                                </div>
                                <p class="text-xs font-mono text-indigo-700">Serial numbers are not tracked per unit for consumables. You can optionally assign a <strong>Lot / Batch Number</strong>.</p>
                            </div>
                            <div class="batch-field">
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Batch / Lot Number
                                    @if($item->is_expirable)
                                        <span class="text-rose-500">*</span>
                                    @else
                                        <span class="text-slate-400 font-normal ml-1">(Optional)</span>
                                    @endif
                                </label>
                                <input type="text" name="lot_number" x-model="lotNumber" @input="checkExistingLot()" list="existing-lots"
                                    class="block w-full border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:outline-none py-2.5 px-3 text-sm font-mono text-slate-800 transition-colors placeholder:text-slate-400"
                                    placeholder="e.g. LOT-2026A" {{ $item->is_expirable ? 'required' : '' }} autocomplete="off">
                                
                                <datalist id="existing-lots">
                                    <template x-for="l in existingLots" :key="l.lot">
                                        <option :value="l.lot"></option>
                                    </template>
                                </datalist>

                                @if($item->is_expirable)
                                    <p class="mt-1.5 text-[10px] font-mono text-slate-500">Identifies this batch for expiry tracking.</p>
                                @endif
                                @error('lot_number') <p class="mt-1.5 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror
                            </div>
                            @if($item->is_expirable)
                            <div class="batch-field">
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Expiry Date <span class="text-rose-500">*</span></label>
                                <input type="date" name="expiry_date" x-model="expiryDate"
                                    class="block w-full border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:outline-none py-2.5 px-3 text-sm font-mono text-slate-800 transition-colors" required>
                                <p class="mt-1.5 text-[10px] font-mono text-slate-500">Must be a future date. Auto-filled if lot exists.</p>
                                @error('expiry_date') <p class="mt-1.5 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror
                            </div>
                            @else
                            <div class="batch-field sm:col-span-2 bg-slate-50 border border-slate-200 p-3">
                                <p class="text-[10px] font-mono text-slate-400 uppercase tracking-widest">// No Expiry Tracking</p>
                                <p class="text-xs font-mono text-slate-500 mt-0.5">This item was registered as non-expirable. No expiry date is needed.</p>
                            </div>
                            @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ============================== --}}
            {{-- SECTION 4: Notes              --}}
            {{-- ============================== --}}
            <div class="bg-white border border-slate-200 mb-4 relative">
                <div class="absolute top-0 left-0 w-1 h-full bg-slate-400"></div>
                <div class="px-5 py-4 ml-1">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="h-2 w-2 bg-slate-400 inline-block"></span>
                        <p class="text-[10px] font-mono font-bold text-slate-500 uppercase tracking-widest">04 // Context</p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Delivery Notes</label>
                        <textarea name="notes" rows="3"
                            class="block w-full border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:outline-none py-2.5 px-3 text-sm font-mono text-slate-800 transition-colors placeholder:text-slate-400"
                            placeholder="Add tracking numbers or condition issues...">{{ old('notes') }}</textarea>
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
                    <span>Add to Inventory</span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                        <path fill-rule="evenodd"
                            d="M10 2a.75.75 0 01.75.75v5.59l1.95-2.1a.75.75 0 111.1 1.02l-3.25 3.5a.75.75 0 01-1.1 0L6.2 7.26a.75.75 0 111.1-1.02l1.95 2.1V2.75A.75.75 0 0110 2z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </form>
    </div>

    <script>
        // Initialize state on page load based on what is checked
        document.addEventListener('DOMContentLoaded', () => {
            
            // Add custom visual styling logic to radio buttons
            const radios = document.querySelectorAll('input[name="condition"]');
            radios.forEach(radio => {
                radio.addEventListener('change', (e) => {
                    document.querySelectorAll('input[name="condition"]').forEach(r => {
                        const parent = r.closest('label');
                        if (r.value === 'new') {
                            if (r.checked) {
                                parent.classList.replace('border-slate-200', 'border-indigo-500');
                                parent.classList.replace('bg-slate-50', 'bg-indigo-50');
                            } else {
                                parent.classList.replace('border-indigo-500', 'border-slate-200');
                                parent.classList.replace('bg-indigo-50', 'bg-slate-50');
                            }
                        } else {
                            if (r.checked) {
                                parent.classList.replace('border-slate-200', 'border-amber-500');
                                parent.classList.replace('bg-slate-50', 'bg-amber-50');
                            } else {
                                parent.classList.replace('border-amber-500', 'border-slate-200');
                                parent.classList.replace('bg-amber-50', 'bg-slate-50');
                            }
                        }
                    });
                });
            });
        });
    </script>
    {{-- Pass existing lots to Alpine --}}
    @php
        $existingLots = collect($item->batches_breakdown)
            ->filter(fn($b) => !empty($b['lot_number']))
            ->map(fn($b) => [
                'lot' => $b['lot_number'],
                'expiry' => $b['expiry_date'] ? mb_substr($b['expiry_date'], 0, 10) : null
            ])->values()->toJson();
    @endphp

    <script>
        function stockEntryForm() {
            return {
                lotNumber: '{{ old('lot_number') }}',
                expiryDate: '{{ old('expiry_date') }}',
                existingLots: {!! $existingLots !!},
                checkExistingLot() {
                    let match = this.existingLots.find(l => l.lot === this.lotNumber);
                    if (match && match.expiry) {
                        this.expiryDate = match.expiry;
                    }
                }
            };
        }
    </script>
@endsection