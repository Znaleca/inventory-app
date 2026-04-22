@extends('layouts.app')

@section('title', 'Items')

@section('actions')
<div class="flex items-center gap-2">
    <div x-data="{
        open: false,
        presets: {
            all: false,
            low_stock: false,
            out_of_stock: false,
            expired: false,
            devices: false,
            consumables: false,
        },
        get allSelected() {
            const keys = Object.keys(this.presets).filter(k => k !== 'all');
            return keys.every(k => this.presets[k]);
        },
        get anySelected() { return Object.values(this.presets).some(v => v); },
        get selectedList() {
            if (this.allSelected || this.presets.all) return ['all'];
            return Object.keys(this.presets).filter(k => k !== 'all' && this.presets[k]);
        },
        toggleAll(val) {
            Object.keys(this.presets).forEach(k => this.presets[k] = val);
        },
        get exportUrl() {
            return '{{ route('items.export') }}?presets=' + this.selectedList.join(',');
        },
        get exportLabel() {
            const names = {all:'All Items',low_stock:'Low Stock',out_of_stock:'Out of Stock',expired:'Expired',devices:'Devices',consumables:'Consumables'};
            return this.selectedList.map(k => names[k] || k).join(' + ');
        }
    }" class="relative" @click.outside="open = false">
        <button @click="open = !open"
            class="inline-flex items-center gap-2 bg-white hover:bg-slate-50 text-slate-700 px-4 py-2 text-[11px] font-mono font-bold uppercase tracking-[0.15em] transition-colors border border-slate-200"
            :class="anySelected ? 'border-emerald-400 text-emerald-700 bg-emerald-50' : ''">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-3.5 w-3.5 text-emerald-600">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
            </svg>
            Export_Excel
            <span x-show="anySelected" x-text="'(' + selectedList.length + ')'" class="text-emerald-600 font-black"></span>
        </button>

        <div x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-2"
            class="absolute right-0 mt-2 w-72 paper-box z-50 shadow-2xl"
            style="display: none;">
            <div class="paper-box-top"></div>
            <div class="paper-box-accent" style="background: linear-gradient(90deg, #10b981, #3b82f6);"></div>
            <div class="relative z-10 bg-white flex flex-col">

                {{-- Header --}}
                <div class="px-4 py-3 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5 text-slate-400">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        <p class="text-[9px] font-mono font-bold text-slate-500 uppercase tracking-widest">Select Presets</p>
                    </div>
                </div>

                {{-- Select All --}}
                <label @click.prevent="toggleAll(!allSelected)" class="flex items-center gap-3 px-4 py-3 border-b border-slate-100 cursor-pointer hover:bg-slate-50 transition-colors group">
                    <div class="h-4 w-4 border-2 flex items-center justify-center flex-shrink-0 transition-colors"
                        :class="allSelected ? 'bg-blue-500 border-blue-500' : 'border-slate-300 group-hover:border-blue-400'">
                        <svg x-show="allSelected" class="h-2.5 w-2.5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                    </div>
                    <span class="text-[11px] font-mono font-bold text-slate-700 group-hover:text-blue-600 transition-colors">Select All</span>
                    <span class="ml-auto text-[9px] font-mono text-slate-400">ALL</span>
                </label>

                {{-- Presets --}}
                <div class="py-1">
                    <p class="px-4 pt-2 pb-1 text-[9px] font-mono font-bold text-slate-400 uppercase tracking-widest">Status</p>

                    <label class="flex items-center gap-3 px-4 py-2.5 cursor-pointer hover:bg-slate-50 transition-colors group" @click.stop>
                        <input type="checkbox" x-model="presets.low_stock" class="sr-only">
                        <div class="h-4 w-4 border-2 flex items-center justify-center flex-shrink-0 transition-colors"
                            :class="presets.low_stock ? 'bg-amber-500 border-amber-500' : 'border-slate-300 group-hover:border-amber-400'">
                            <svg x-show="presets.low_stock" class="h-2.5 w-2.5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                        </div>
                        <span class="text-[11px] font-mono font-bold text-slate-600 group-hover:text-amber-600 transition-colors">Low Stock / Reorder</span>
                        <span class="ml-auto h-1.5 w-1.5 bg-amber-400 block flex-shrink-0"></span>
                    </label>

                    <label class="flex items-center gap-3 px-4 py-2.5 cursor-pointer hover:bg-slate-50 transition-colors group" @click.stop>
                        <input type="checkbox" x-model="presets.out_of_stock" class="sr-only">
                        <div class="h-4 w-4 border-2 flex items-center justify-center flex-shrink-0 transition-colors"
                            :class="presets.out_of_stock ? 'bg-rose-500 border-rose-500' : 'border-slate-300 group-hover:border-rose-400'">
                            <svg x-show="presets.out_of_stock" class="h-2.5 w-2.5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                        </div>
                        <span class="text-[11px] font-mono font-bold text-slate-600 group-hover:text-rose-600 transition-colors">Out of Stock</span>
                        <span class="ml-auto h-1.5 w-1.5 bg-rose-500 block flex-shrink-0"></span>
                    </label>

                    <label class="flex items-center gap-3 px-4 py-2.5 cursor-pointer hover:bg-slate-50 transition-colors group" @click.stop>
                        <input type="checkbox" x-model="presets.expired" class="sr-only">
                        <div class="h-4 w-4 border-2 flex items-center justify-center flex-shrink-0 transition-colors"
                            :class="presets.expired ? 'bg-orange-500 border-orange-500' : 'border-slate-300 group-hover:border-orange-400'">
                            <svg x-show="presets.expired" class="h-2.5 w-2.5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                        </div>
                        <span class="text-[11px] font-mono font-bold text-slate-600 group-hover:text-orange-600 transition-colors">Expired / Near Expiry</span>
                        <span class="ml-auto h-1.5 w-1.5 bg-orange-400 block flex-shrink-0"></span>
                    </label>

                    <p class="px-4 pt-3 pb-1 text-[9px] font-mono font-bold text-slate-400 uppercase tracking-widest border-t border-slate-50 mt-1">Item Type</p>

                    <label class="flex items-center gap-3 px-4 py-2.5 cursor-pointer hover:bg-slate-50 transition-colors group" @click.stop>
                        <input type="checkbox" x-model="presets.devices" class="sr-only">
                        <div class="h-4 w-4 border-2 flex items-center justify-center flex-shrink-0 transition-colors"
                            :class="presets.devices ? 'bg-indigo-500 border-indigo-500' : 'border-slate-300 group-hover:border-indigo-400'">
                            <svg x-show="presets.devices" class="h-2.5 w-2.5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                        </div>
                        <span class="text-[11px] font-mono font-bold text-slate-600 group-hover:text-indigo-600 transition-colors">Devices Only</span>
                        <span class="ml-auto h-1.5 w-1.5 bg-indigo-500 block flex-shrink-0"></span>
                    </label>

                    <label class="flex items-center gap-3 px-4 py-2.5 cursor-pointer hover:bg-slate-50 transition-colors group" @click.stop>
                        <input type="checkbox" x-model="presets.consumables" class="sr-only">
                        <div class="h-4 w-4 border-2 flex items-center justify-center flex-shrink-0 transition-colors"
                            :class="presets.consumables ? 'bg-teal-500 border-teal-500' : 'border-slate-300 group-hover:border-teal-400'">
                            <svg x-show="presets.consumables" class="h-2.5 w-2.5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                        </div>
                        <span class="text-[11px] font-mono font-bold text-slate-600 group-hover:text-teal-600 transition-colors">Consumables Only</span>
                        <span class="ml-auto h-1.5 w-1.5 bg-teal-500 block flex-shrink-0"></span>
                    </label>
                </div>

                {{-- Single Combined Download --}}
                <div class="px-4 py-3 border-t border-slate-100">
                    <template x-if="anySelected">
                        <a :href="'{{ route('items.export') }}?presets=' + selectedList.join(',') "
                            class="flex items-center justify-between w-full px-3 py-2.5 text-[11px] font-mono font-bold bg-emerald-600 hover:bg-emerald-700 text-white transition-colors">
                            <div class="flex flex-col">
                                <span>Download Combined Report</span>
                                <span x-text="selectedList.map(k => ({all:'All Items',low_stock:'Low Stock',out_of_stock:'Out of Stock',expired:'Expired',devices:'Devices',consumables:'Consumables'})[k]||k).join(' + ')" class="text-[9px] font-mono font-normal opacity-80 mt-0.5"></span>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-3.5 w-3.5 flex-shrink-0">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                        </a>
                    </template>
                    <template x-if="!anySelected">
                        <p class="text-center text-[10px] font-mono text-slate-400 py-1">Select a preset above to export</p>
                    </template>
                </div>
            </div>
        </div>
    </div>
    <a href="{{ route('items.create') }}"
        class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 text-[11px] font-mono font-bold uppercase tracking-[0.15em] transition-colors border border-blue-700">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-3.5 w-3.5">
            <path d="M8.75 3.75a.75.75 0 00-1.5 0v3.5h-3.5a.75.75 0 000 1.5h3.5v3.5a.75.75 0 001.5 0v-3.5h3.5a.75.75 0 000-1.5h-3.5v-3.5z" />
        </svg>
        New_Item
    </a>
</div>
@endsection

@section('content')

{{-- Page Header --}}
<div class="mb-5 flex items-end justify-between">
    <div>
        <p class="text-[10px] font-mono font-semibold text-blue-600 uppercase tracking-[0.25em] mb-1">Inventory://Items</p>
        <h1 class="text-xl font-bold text-slate-800 tracking-tight">Item Registry</h1>
    </div>
    <span class="text-[10px] font-mono text-slate-400">{{ $items->count() }} records</span>
</div>

{{-- Search & Filter Bar --}}
<div class="bg-white border border-slate-200 mb-5 relative">
    <div class="absolute top-0 left-0 right-0 h-[2px] bg-gradient-to-r from-blue-500 to-indigo-400"></div>
    <div class="absolute top-0 left-0 w-1 h-full bg-blue-500"></div>
    <form method="GET" action="{{ route('items.index') }}" class="flex flex-wrap items-center gap-3 p-3 pl-4">

        {{-- Search Input --}}
        <div class="relative flex-1 sm:min-w-[280px]">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4 text-slate-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
            </div>
            <input type="text" name="search" value="{{ request('search') }}"
                class="block w-full border border-slate-200 bg-slate-50 py-2 pl-9 pr-4 text-sm font-mono text-slate-800 placeholder:text-slate-400 focus:bg-white focus:border-blue-500 focus:outline-none transition-colors"
                placeholder="Search items...">
        </div>

        {{-- Category Filter --}}
        <select name="category"
            class="block w-full sm:w-44 border border-slate-200 bg-slate-50 px-3 py-2 text-sm font-mono text-slate-700 focus:bg-white focus:border-blue-500 focus:outline-none transition-colors">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>

        {{-- Type Filter --}}
        <select name="type"
            class="block w-full sm:w-40 border border-slate-200 bg-slate-50 px-3 py-2 text-sm font-mono text-slate-700 focus:bg-white focus:border-blue-500 focus:outline-none transition-colors">
            <option value="">All Types</option>
            <option value="device"     {{ request('type') === 'device'     ? 'selected' : '' }}>Device</option>
            <option value="consumable" {{ request('type') === 'consumable' ? 'selected' : '' }}>Consumable</option>
        </select>

        {{-- Actions --}}
        <div class="flex gap-2">
            <button type="submit"
                class="flex items-center gap-2 bg-slate-800 hover:bg-blue-600 text-white px-4 py-2 text-[11px] font-mono font-bold uppercase tracking-widest transition-colors border border-slate-700 hover:border-blue-700">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-3.5 w-3.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
                Search
            </button>

            @if(request('search') || request('category') || request('type'))
            <a href="{{ route('items.index') }}"
                class="flex items-center gap-2 border border-slate-200 bg-white text-slate-500 hover:text-slate-800 hover:border-slate-300 px-4 py-2 text-[11px] font-mono font-bold uppercase tracking-widest transition-colors">
                Clear
            </a>
            @endif
        </div>
    </form>
</div>

{{-- Main Table --}}
<div class="bg-white border border-slate-200 relative overflow-hidden">
    <div class="absolute top-0 left-0 w-1 h-full bg-indigo-500"></div>

    @if($items->count() > 0)
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="border-b border-slate-100 bg-slate-50/80">
                    <th scope="col" class="whitespace-nowrap pl-5 pr-3 py-3 text-left text-[10px] font-mono font-bold uppercase tracking-[0.2em] text-slate-400">Item</th>
                    <th scope="col" class="whitespace-nowrap px-3 py-3 text-left text-[10px] font-mono font-bold uppercase tracking-[0.2em] text-slate-400">Category</th>
                    <th scope="col" class="whitespace-nowrap px-3 py-3 text-left text-[10px] font-mono font-bold uppercase tracking-[0.2em] text-slate-400">Stock / Expiry</th>
                    <th scope="col" class="whitespace-nowrap px-3 py-3 text-left text-[10px] font-mono font-bold uppercase tracking-[0.2em] text-slate-400">Status</th>
                    <th scope="col" class="whitespace-nowrap px-3 py-3 text-right pr-5 text-[10px] font-mono font-bold uppercase tracking-[0.2em] text-slate-400">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($items as $item)
                @php
                    $newStock  = max(0, $item->total_stock);
                    $usedStock = max(0, $item->effective_stock_used);
                    $totalQty  = $newStock + $usedStock;
                    
                    $level = $totalQty <= 0 ? 'low' : ($totalQty <= 5 ? 'medium' : 'high');
                    $barWidth = $totalQty <= 0 ? 0 : min(100, ($totalQty / 20) * 100);
                    $barColor = match ($level) {
                        'high'   => 'bg-emerald-400',
                        'medium' => 'bg-amber-400',
                        'low'    => 'bg-rose-400',
                    };
                    $today = now()->startOfDay();
                    $expiredCount = 0;
                    $nearExpiryCount = 0;
                    foreach ($item->stockEntries as $entry) {
                        if (!$entry->expiry_date) continue;
                        $exp = \Carbon\Carbon::parse($entry->expiry_date)->startOfDay();
                        if ($exp->isBefore($today)) $expiredCount++;
                        elseif ($today->diffInDays($exp) <= 30) $nearExpiryCount++;
                    }
                @endphp
                <tr class="group hover:bg-slate-50 transition-colors">

                    {{-- Item Name --}}
                    <td class="pl-5 pr-3 py-3">
                        <div class="flex flex-col gap-0.5">
                            <span class="text-sm font-bold text-slate-800">{{ $item->name }}</span>
                            <div class="flex items-center gap-1.5 flex-wrap mt-0.5">
                                @if($item->item_type === 'device')
                                <span class="text-[9px] font-mono font-bold uppercase tracking-wider text-violet-600 bg-violet-50 px-1.5 py-0.5 border border-violet-200">Device</span>
                                @else
                                <span class="text-[9px] font-mono font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-1.5 py-0.5 border border-indigo-200">Consumable</span>
                                @endif

                                @if($item->brand)
                                <span class="text-[10px] font-mono text-slate-400">{{ $item->brand }}{{ $item->model ? ' · '.$item->model : '' }}</span>
                                @elseif($item->description)
                                <span class="text-[10px] font-mono text-slate-400 line-clamp-1 max-w-[150px]">{{ $item->description }}</span>
                                @endif
                            </div>
                        </div>
                    </td>

                    {{-- Category --}}
                    <td class="whitespace-nowrap px-3 py-3">
                        <span class="font-mono text-[11px] font-bold text-slate-600 bg-slate-50 border border-slate-200 px-2.5 py-1.5">
                            {{ $item->category->name }}
                        </span>
                    </td>

                    {{-- Stock & Expiry --}}
                    <td class="px-3 py-3">
                        <div class="flex flex-wrap gap-1.5">
                            <span class="flex items-center gap-1.5 bg-teal-50 border border-teal-200 px-2 py-1 text-xs font-bold font-mono text-teal-700">
                                <span class="h-1.5 w-1.5 bg-teal-400 inline-block"></span>
                                {{ $item->total_stock }} New
                            </span>
                            @if($item->effective_stock_used > 0)
                            <span class="flex items-center gap-1.5 bg-amber-50 border border-amber-200 px-2 py-1 text-xs font-bold font-mono text-amber-700">
                                {{ $item->effective_stock_used }} Used
                            </span>
                            @endif
                            @if($item->active_lent_out > 0)
                            <span class="flex items-center gap-1.5 bg-indigo-50 border border-indigo-200 px-2 py-1 text-xs font-bold font-mono text-indigo-700" title="Actively Lent Out">
                                ↑ {{ $item->active_lent_out }} Lent Out
                            </span>
                            @endif


                        </div>
                        {{-- Stock bar --}}
                        <div class="mt-2 h-1 w-28 bg-slate-100 border border-slate-200">
                            <div class="h-full {{ $barColor }} transition-all duration-500" style="width: {{ $barWidth }}%"></div>
                        </div>
                    </td>

                    {{-- Status --}}
                    <td class="px-3 py-3">
                        @if($totalQty <= 0)
                        <span class="inline-flex items-center gap-1.5 font-mono text-[11px] font-bold text-rose-600 bg-rose-50 border border-rose-200 px-2.5 py-1.5">
                            <span class="h-1.5 w-1.5 bg-rose-500 animate-pulse inline-block"></span>
                            Out_of_Stock
                        </span>
                        @elseif($item->total_stock <= $item->reorder_level)
                        <span class="inline-flex items-center gap-1.5 font-mono text-[11px] font-bold text-amber-600 bg-amber-50 border border-amber-200 px-2.5 py-1.5">
                            <span class="h-1.5 w-1.5 bg-amber-400 inline-block"></span>
                            Reorder
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1.5 font-mono text-[11px] font-bold text-emerald-600 bg-emerald-50 border border-emerald-200 px-2.5 py-1.5">
                            <span class="h-1.5 w-1.5 bg-emerald-400 inline-block"></span>
                            In_Stock
                        </span>
                        @endif
                    </td>

                    {{-- Actions --}}
                    <td class="whitespace-nowrap px-3 pr-5 py-3 text-right">
                        <div class="flex items-center justify-end gap-1.5">
                            <a href="{{ route('items.show', $item) }}"
                                class="inline-flex items-center gap-1 border border-slate-200 bg-white px-2.5 py-1.5 text-[10px] font-mono font-bold text-slate-700 hover:bg-slate-100 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-3 w-3">
                                    <path d="M10 12.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z" />
                                    <path fill-rule="evenodd" d="M.664 10.59a1.651 1.651 0 010-1.186A10.004 10.004 0 0110 3c4.257 0 7.893 2.66 9.336 6.41.147.381.146.804 0 1.186A10.004 10.004 0 0110 17c-4.257 0-7.893-2.66-9.336-6.41zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                </svg>
                                View
                            </a>
                            <a href="{{ route('usage.create', ['item_id' => $item->id]) }}"
                                class="inline-flex items-center gap-1 border border-rose-200 bg-rose-50 px-2.5 py-1.5 text-[10px] font-mono font-bold text-rose-600 hover:bg-rose-500 hover:text-white hover:border-rose-500 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-3 w-3">
                                    <path fill-rule="evenodd" d="M8 2a.75.75 0 01.75.75v8.69l3.22-3.22a.75.75 0 111.06 1.06l-4.5 4.5a.75.75 0 01-1.06 0l-4.5-4.5a.75.75 0 111.06-1.06L7.25 11.44V2.75A.75.75 0 018 2z" clip-rule="evenodd" />
                                </svg>
                                Use
                            </a>
                            <a href="{{ route('stock.create', $item) }}"
                                class="inline-flex items-center gap-1 border border-indigo-200 bg-indigo-50 px-2.5 py-1.5 text-[10px] font-mono font-bold text-indigo-600 hover:bg-indigo-600 hover:text-white hover:border-indigo-600 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-3 w-3">
                                    <path d="M8.75 3.75a.75.75 0 00-1.5 0v3.5h-3.5a.75.75 0 000 1.5h3.5v3.5a.75.75 0 001.5 0v-3.5h3.5a.75.75 0 000-1.5h-3.5v-3.5z" />
                                </svg>
                                Stock
                            </a>
                            <a href="{{ route('items.edit', $item) }}"
                                class="inline-flex items-center border border-slate-200 bg-slate-50 px-2.5 py-1.5 text-[10px] font-mono font-bold text-slate-600 hover:bg-slate-800 hover:text-white hover:border-slate-800 transition-colors">
                                Edit
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>


    @else
    {{-- Empty State --}}
    <div class="flex flex-col items-center justify-center py-20 text-center ml-1">
        <div class="h-14 w-14 border border-slate-200 bg-slate-50 flex items-center justify-center text-slate-400 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-7 w-7">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
            </svg>
        </div>
        <p class="font-mono text-[10px] text-slate-400 uppercase tracking-widest mb-1">// No records found</p>
        <p class="text-sm font-semibold text-slate-500 mt-1">
            {{ request('search') || request('category') || request('type') ? 'Try adjusting your filters.' : 'No items in inventory yet.' }}
        </p>
        @unless(request('search') || request('category') || request('type'))
        <a href="{{ route('items.create') }}"
            class="mt-6 inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 text-[11px] font-mono font-bold uppercase tracking-widest transition-colors border border-blue-700">
            + Add First Item
        </a>
        @endunless
    </div>
    @endif
</div>

@endsection