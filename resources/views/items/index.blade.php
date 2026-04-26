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
            class="inline-flex items-center gap-2 bg-white hover:bg-sky-50 text-slate-700 px-4 py-2 text-[11px] font-mono font-bold uppercase tracking-[0.15em] transition-colors border border-sky-100"
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
                <div class="px-4 py-3 border-b border-sky-100 flex items-center justify-between">
                    <div class="flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5 text-slate-400">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        <p class="text-[9px] font-mono font-bold text-slate-500 uppercase tracking-widest">Select Presets</p>
                    </div>
                </div>

                {{-- Select All --}}
                <label @click.prevent="toggleAll(!allSelected)" class="flex items-center gap-3 px-4 py-3 border-b border-sky-100 cursor-pointer hover:bg-sky-50 transition-colors group">
                    <div class="h-4 w-4 border-2 flex items-center justify-center flex-shrink-0 transition-colors"
                        :class="allSelected ? 'bg-blue-500 border-blue-500' : 'border-slate-300 group-hover:border-blue-400'">
                        <svg x-show="allSelected" class="h-2.5 w-2.5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                    </div>
                    <span class="text-[11px] font-mono font-bold text-slate-700 group-hover:text-sky-500 transition-colors">Select All</span>
                    <span class="ml-auto text-[9px] font-mono text-slate-400">ALL</span>
                </label>

                {{-- Presets --}}
                <div class="py-1">
                    <p class="px-4 pt-2 pb-1 text-[9px] font-mono font-bold text-slate-400 uppercase tracking-widest">Status</p>

                    <label class="flex items-center gap-3 px-4 py-2.5 cursor-pointer hover:bg-sky-50 transition-colors group" @click.stop>
                        <input type="checkbox" x-model="presets.low_stock" class="sr-only">
                        <div class="h-4 w-4 border-2 flex items-center justify-center flex-shrink-0 transition-colors"
                            :class="presets.low_stock ? 'bg-amber-500 border-amber-500' : 'border-slate-300 group-hover:border-amber-400'">
                            <svg x-show="presets.low_stock" class="h-2.5 w-2.5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                        </div>
                        <span class="text-[11px] font-mono font-bold text-slate-600 group-hover:text-amber-600 transition-colors">Low Stock / Reorder</span>
                        <span class="ml-auto h-1.5 w-1.5 bg-amber-400 block flex-shrink-0"></span>
                    </label>

                    <label class="flex items-center gap-3 px-4 py-2.5 cursor-pointer hover:bg-sky-50 transition-colors group" @click.stop>
                        <input type="checkbox" x-model="presets.out_of_stock" class="sr-only">
                        <div class="h-4 w-4 border-2 flex items-center justify-center flex-shrink-0 transition-colors"
                            :class="presets.out_of_stock ? 'bg-rose-500 border-rose-500' : 'border-slate-300 group-hover:border-rose-400'">
                            <svg x-show="presets.out_of_stock" class="h-2.5 w-2.5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                        </div>
                        <span class="text-[11px] font-mono font-bold text-slate-600 group-hover:text-rose-600 transition-colors">Out of Stock</span>
                        <span class="ml-auto h-1.5 w-1.5 bg-rose-500 block flex-shrink-0"></span>
                    </label>

                    <label class="flex items-center gap-3 px-4 py-2.5 cursor-pointer hover:bg-sky-50 transition-colors group" @click.stop>
                        <input type="checkbox" x-model="presets.expired" class="sr-only">
                        <div class="h-4 w-4 border-2 flex items-center justify-center flex-shrink-0 transition-colors"
                            :class="presets.expired ? 'bg-orange-500 border-orange-500' : 'border-slate-300 group-hover:border-orange-400'">
                            <svg x-show="presets.expired" class="h-2.5 w-2.5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                        </div>
                        <span class="text-[11px] font-mono font-bold text-slate-600 group-hover:text-orange-600 transition-colors">Expired / Near Expiry</span>
                        <span class="ml-auto h-1.5 w-1.5 bg-orange-400 block flex-shrink-0"></span>
                    </label>

                    <p class="px-4 pt-3 pb-1 text-[9px] font-mono font-bold text-slate-400 uppercase tracking-widest border-t border-slate-50 mt-1">Item Type</p>

                    <label class="flex items-center gap-3 px-4 py-2.5 cursor-pointer hover:bg-sky-50 transition-colors group" @click.stop>
                        <input type="checkbox" x-model="presets.devices" class="sr-only">
                        <div class="h-4 w-4 border-2 flex items-center justify-center flex-shrink-0 transition-colors"
                            :class="presets.devices ? 'bg-sky-500 border-indigo-500' : 'border-slate-300 group-hover:border-indigo-400'">
                            <svg x-show="presets.devices" class="h-2.5 w-2.5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                        </div>
                        <span class="text-[11px] font-mono font-bold text-slate-600 group-hover:text-sky-600 transition-colors">Devices Only</span>
                        <span class="ml-auto h-1.5 w-1.5 bg-sky-500 block flex-shrink-0"></span>
                    </label>

                    <label class="flex items-center gap-3 px-4 py-2.5 cursor-pointer hover:bg-sky-50 transition-colors group" @click.stop>
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
                <div class="px-4 py-3 border-t border-sky-100">
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
        class="inline-flex items-center gap-2 bg-sky-500 hover:bg-sky-600 text-white px-4 py-2 text-[11px] font-mono font-bold uppercase tracking-[0.15em] transition-colors border border-sky-600">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-3.5 w-3.5">
            <path d="M8.75 3.75a.75.75 0 00-1.5 0v3.5h-3.5a.75.75 0 000 1.5h3.5v3.5a.75.75 0 001.5 0v-3.5h3.5a.75.75 0 000-1.5h-3.5v-3.5z" />
        </svg>
        New_Item
    </a>
</div>
@endsection

@section('content')

<div class="bg-white rounded-2xl overflow-hidden border border-sky-100">

@php
    $totalCount = $items->count();
    $deviceCount = $items->where('item_type', 'device')->count();
    $consumableCount = $items->where('item_type', 'consumable')->count();
    
    $outOfStockCount = $items->filter(fn($i) => $i->total_stock <= 0)->count();
    $reorderCount = $items->filter(fn($i) => $i->total_stock > 0 && $i->total_stock <= $i->reorder_level)->count();
    $healthyCount = $items->filter(fn($i) => $i->total_stock > $i->reorder_level)->count();

    $devicePct = $totalCount > 0 ? round(($deviceCount / $totalCount) * 100) : 0;
    $consumablePct = $totalCount > 0 ? round(($consumableCount / $totalCount) * 100) : 0;

    $healthyPct = $totalCount > 0 ? round(($healthyCount / $totalCount) * 100) : 0;
    $reorderPct = $totalCount > 0 ? round(($reorderCount / $totalCount) * 100) : 0;
    $outOfStockPct = $totalCount > 0 ? round(($outOfStockCount / $totalCount) * 100) : 0;
@endphp

{{-- Analytics Overview (Maximizing Space) --}}
<div class="border-b border-sky-100 bg-white grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-sky-100 shrink-0">
    
    {{-- Metric: Total --}}
    <div class="p-4 flex items-center justify-between">
        <div>
            <p class="font-mono text-[10px] font-bold uppercase tracking-widest text-sky-500 mb-1">Registry Overview</p>
            <div class="flex items-baseline gap-2">
                <h3 class="text-3xl font-black text-[#0f172a] tracking-tight">{{ $totalCount }}</h3>
                <span class="text-xs font-mono text-slate-400 font-bold uppercase tracking-widest">Items</span>
            </div>
        </div>
        <div class="h-10 w-10 rounded-full bg-sky-50 flex items-center justify-center border border-sky-100">
            <svg class="h-5 w-5 text-sky-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" /></svg>
        </div>
    </div>

    {{-- Chart: Stock Health --}}
    <div class="p-4 flex flex-col justify-center">
        <div class="flex justify-between items-end mb-2">
            <p class="font-mono text-[10px] font-bold uppercase tracking-widest text-slate-400">Stock Health Tracker</p>
        </div>
        <div class="w-full h-2.5 bg-slate-100 flex overflow-hidden rounded-sm border border-slate-200">
            <div style="width: {{ $healthyPct }}%" class="bg-emerald-400" title="Healthy: {{ $healthyCount }}"></div>
            <div style="width: {{ $reorderPct }}%" class="bg-amber-400" title="Reorder: {{ $reorderCount }}"></div>
            <div style="width: {{ $outOfStockPct }}%" class="bg-rose-500" title="Out of Stock: {{ $outOfStockCount }}"></div>
        </div>
        <div class="flex justify-between mt-2">
            <div class="flex items-center gap-1"><span class="h-1.5 w-1.5 bg-emerald-400 rounded-full"></span><span class="text-[9px] font-mono font-bold text-slate-500">{{ $healthyCount }} OK</span></div>
            <div class="flex items-center gap-1"><span class="h-1.5 w-1.5 bg-amber-400 rounded-full"></span><span class="text-[9px] font-mono font-bold text-slate-500">{{ $reorderCount }} Low</span></div>
            <div class="flex items-center gap-1"><span class="h-1.5 w-1.5 bg-rose-500 rounded-full animate-pulse"></span><span class="text-[9px] font-mono font-black text-rose-600">{{ $outOfStockCount }} Empty</span></div>
        </div>
    </div>

    {{-- Chart: Category Distribution --}}
    <div class="p-4 flex flex-col justify-center bg-slate-50/50">
        <div class="flex justify-between items-end mb-2">
            <p class="font-mono text-[10px] font-bold uppercase tracking-widest text-slate-400">Type Distribution</p>
        </div>
        <div class="w-full h-2.5 bg-slate-100 flex overflow-hidden rounded-sm border border-slate-200">
            <div style="width: {{ $consumablePct }}%" class="bg-sky-400" title="Consumables: {{ $consumableCount }}"></div>
            <div style="width: {{ $devicePct }}%" class="bg-violet-400" title="Devices: {{ $deviceCount }}"></div>
        </div>
        <div class="flex justify-between mt-2">
            <div class="flex items-center gap-1"><span class="h-1.5 w-1.5 bg-sky-400 rounded-full"></span><span class="text-[9px] font-mono font-bold text-slate-500">{{ $consumableCount }} Consumable</span></div>
            <div class="flex items-center gap-1"><span class="h-1.5 w-1.5 bg-violet-400 rounded-full"></span><span class="text-[9px] font-mono font-bold text-slate-500">{{ $deviceCount }} Device</span></div>
        </div>
    </div>
</div>

{{-- Search & Filter Bar --}}
<div class="bg-sky-50/50 border-b border-sky-100 p-4 relative">
    <div class="absolute top-0 left-0 w-1 h-full bg-sky-500"></div>
    <div>
        <form method="GET" action="{{ route('items.index') }}" class="flex flex-wrap items-center gap-3 pl-2">

            {{-- Search Input --}}
            <div class="relative flex-1 sm:min-w-[280px]">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4 text-slate-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </div>
                <input type="text" name="search"
                    value="{{ request('search') }}"
                    class="block w-full border border-sky-100 bg-white py-2 pl-9 pr-4 text-sm font-mono text-[#0f172a] placeholder:text-slate-400 focus:bg-white focus:border-sky-500 focus:ring-1 focus:ring-sky-500 focus:outline-none transition-colors rounded-none"
                    placeholder="Search items...">
            </div>

            {{-- Category Filter --}}
            <select name="category"
                class="block w-full sm:w-44 border border-sky-100 bg-white px-3 py-2 text-sm font-mono text-slate-700 focus:bg-white focus:border-sky-500 focus:ring-1 focus:ring-sky-500 focus:outline-none transition-colors rounded-none">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>

            {{-- Type Filter --}}
            <select name="type"
                class="block w-full sm:w-40 border border-sky-100 bg-white px-3 py-2 text-sm font-mono text-slate-700 focus:bg-white focus:border-sky-500 focus:ring-1 focus:ring-sky-500 focus:outline-none transition-colors rounded-none">
                <option value="">All Types</option>
                <option value="device" {{ request('type') === 'device' ? 'selected' : '' }}>Device</option>
                <option value="consumable" {{ request('type') === 'consumable' ? 'selected' : '' }}>Consumable</option>
            </select>

            {{-- Actions --}}
            <div class="flex gap-2">
                <button type="submit"
                    class="flex items-center gap-2 bg-[#0f172a] hover:bg-sky-600 text-white px-4 py-2 text-[11px] font-mono font-bold uppercase tracking-widest transition-colors border border-transparent rounded-none">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-3.5 w-3.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                    Search
                </button>

                @if(request('search') || request('category') || request('type'))
                <a href="{{ route('items.index') }}"
                    class="flex items-center gap-2 border border-sky-200 bg-sky-50 text-sky-600 hover:text-sky-800 hover:bg-sky-100 px-4 py-2 text-[11px] font-mono font-bold uppercase tracking-widest transition-colors rounded-none">
                    Clear
                </a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- Main Table --}}
<div class="bg-white border border-sky-100 relative overflow-hidden" x-data="{
    search: '{{ request('search') }}',
    category: '{{ request('category') }}',
    type: '{{ request('type') }}',
    items: {{ Js::from($items->map(fn($item) => [
        'id' => $item->id,
        'name' => $item->name,
        'brand' => $item->brand,
        'model' => $item->model,
        'description' => $item->description,
        'category_id' => $item->category_id,
        'category_name' => $item->category->name,
        'item_type' => $item->item_type,
        'storage_location' => $item->storage_location,
        'storage_section' => $item->storage_section,
        'unit' => $item->unit,
        'total_stock' => $item->total_stock,
        'effective_stock_used' => $item->effective_stock_used,
        'reorder_level' => $item->reorder_level,
    ])) }},
    get filteredItems() {
        return this.items.filter(item => {
            const matchesSearch = this.search === '' || 
                item.name.toLowerCase().includes(this.search.toLowerCase()) ||
                (item.brand && item.brand.toLowerCase().includes(this.search.toLowerCase()));
            const matchesCategory = this.category === '' || item.category_id.toString() === this.category;
            const matchesType = this.type === '' || item.item_type === this.type;
            return matchesSearch && matchesCategory && matchesType;
        });
    }
}">
    <div class="absolute top-0 left-0 w-1 h-full bg-sky-500"></div>

    <template x-if="filteredItems.length > 0">
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="border-b border-sky-100 bg-sky-50/80">
                    <th scope="col" class="whitespace-nowrap pl-5 pr-3 py-3 text-left text-[10px] font-mono font-bold uppercase tracking-[0.2em] text-slate-400">Item</th>
                    <th scope="col" class="whitespace-nowrap px-3 py-3 text-left text-[10px] font-mono font-bold uppercase tracking-[0.2em] text-slate-400">Category</th>
                    <th scope="col" class="whitespace-nowrap px-3 py-3 text-left text-[10px] font-mono font-bold uppercase tracking-[0.2em] text-slate-400">Location</th>
                    <th scope="col" class="whitespace-nowrap px-3 py-3 text-left text-[10px] font-mono font-bold uppercase tracking-[0.2em] text-slate-400">Stock / Expiry</th>
                    <th scope="col" class="whitespace-nowrap px-3 py-3 text-left text-[10px] font-mono font-bold uppercase tracking-[0.2em] text-slate-400">Unit</th>
                    <th scope="col" class="whitespace-nowrap px-3 py-3 text-left text-[10px] font-mono font-bold uppercase tracking-[0.2em] text-slate-400">Status</th>
                    <th scope="col" class="whitespace-nowrap px-3 py-3 text-right pr-5 text-[10px] font-mono font-bold uppercase tracking-[0.2em] text-slate-400">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                <template x-for="item in filteredItems" :key="item.id">
                <tr class="group hover:bg-sky-50 transition-colors">

                    {{-- Item Name --}}
                    <td class="pl-5 pr-3 py-3">
                        <div class="flex flex-col gap-0.5">
                            <span class="text-sm font-bold text-[#0f172a]" x-text="item.name"></span>
                            <div class="flex items-center gap-1.5 flex-wrap mt-0.5">
                                <span class="text-[9px] font-mono font-bold uppercase tracking-wider px-1.5 py-0.5 border" :class="item.item_type === 'device' ? 'text-violet-600 bg-violet-50 border-violet-200' : 'text-sky-600 bg-sky-50 border-sky-200'" x-text="item.item_type === 'device' ? 'Device' : 'Consumable'"></span>
                                <template x-if="item.brand">
                                    <span class="text-[10px] font-mono text-slate-400" x-text="item.brand + (item.model ? ' · ' + item.model : '')"></span>
                                </template>
                                <template x-if="!item.brand && item.description">
                                    <span class="text-[10px] font-mono text-slate-400 line-clamp-1 max-w-[150px]" x-text="item.description"></span>
                                </template>
                            </div>
                        </div>
                    </td>

                    {{-- Category --}}
                    <td class="whitespace-nowrap px-3 py-3">
                        <span class="font-mono text-[11px] font-bold text-slate-600 bg-sky-50 border border-sky-100 px-2.5 py-1.5" x-text="item.category_name"></span>
                    </td>

                    {{-- Location --}}
                    <td class="whitespace-nowrap px-3 py-3">
                        <template x-if="item.storage_location">
                            <span class="font-mono text-[11px] font-bold text-slate-600 bg-sky-50 border border-sky-100 px-2.5 py-1.5" x-text="item.storage_location + (item.storage_section ? ' / ' + item.storage_section : '')"></span>
                        </template>
                        <template x-if="!item.storage_location">
                            <span class="font-mono text-[11px] text-slate-400">—</span>
                        </template>
                    </td>

                    {{-- Stock & Expiry --}}
                    <td class="px-3 py-3">
                        <div class="flex flex-wrap gap-1.5">
                            <span class="flex items-center gap-1.5 bg-teal-50 border border-teal-200 px-2 py-1 text-xs font-bold font-mono text-teal-700">
                                <span class="h-1.5 w-1.5 bg-teal-400 inline-block"></span>
                                <span x-text="item.total_stock"></span> New
                            </span>
                            <template x-if="item.effective_stock_used > 0">
                                <span class="flex items-center gap-1.5 bg-amber-50 border border-amber-200 px-2 py-1 text-xs font-bold font-mono text-amber-700" x-text="item.effective_stock_used + ' Used'"></span>
                            </template>
                        </div>
                        {{-- Stock bar --}}
                        <div class="mt-2 h-1 w-28 bg-slate-100 border border-sky-100">
                            <div class="h-full transition-all duration-500" :style="`width: ${Math.min(100, (item.total_stock / 20) * 100)}%`" :class="item.total_stock > 5 ? 'bg-emerald-400' : (item.total_stock > 0 ? 'bg-amber-400' : 'bg-rose-400')"></div>
                        </div>
                    </td>

                    {{-- Unit --}}
                    <td class="whitespace-nowrap px-3 py-3">
                        <template x-if="item.unit">
                            <span class="font-mono text-[11px] font-bold text-slate-600 bg-sky-50 border border-sky-100 px-2.5 py-1.5" x-text="item.unit"></span>
                        </template>
                        <template x-if="!item.unit">
                            <span class="font-mono text-[11px] text-slate-400">—</span>
                        </template>
                    </td>

                    {{-- Status --}}
                    <td class="px-3 py-3">
                        <template x-if="item.total_stock <= 0">
                            <span class="inline-flex items-center gap-1.5 font-mono text-[11px] font-bold text-rose-600 bg-rose-50 border border-rose-200 px-2.5 py-1.5">
                                <span class="h-1.5 w-1.5 bg-rose-500 animate-pulse inline-block"></span>
                                Out_of_Stock
                            </span>
                        </template>
                        <template x-if="item.total_stock > 0 && item.total_stock <= item.reorder_level">
                            <span class="inline-flex items-center gap-1.5 font-mono text-[11px] font-bold text-amber-600 bg-amber-50 border border-amber-200 px-2.5 py-1.5">
                                <span class="h-1.5 w-1.5 bg-amber-400 inline-block"></span>
                                Reorder
                            </span>
                        </template>
                        <template x-if="item.total_stock > item.reorder_level">
                            <span class="inline-flex items-center gap-1.5 font-mono text-[11px] font-bold text-emerald-600 bg-emerald-50 border border-emerald-200 px-2.5 py-1.5">
                                <span class="h-1.5 w-1.5 bg-emerald-400 inline-block"></span>
                                In_Stock
                            </span>
                        </template>
                    </td>

                    {{-- Actions --}}
                    <td class="whitespace-nowrap px-3 pr-5 py-3 text-right">
                        <div class="flex items-center justify-end gap-1.5">
                            <a :href="`/items/${item.id}`"
                                class="inline-flex items-center gap-1 border border-sky-100 bg-white px-2.5 py-1.5 text-[10px] font-mono font-bold text-slate-700 hover:bg-slate-100 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-3 w-3">
                                    <path d="M10 12.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z" />
                                    <path fill-rule="evenodd" d="M.664 10.59a1.651 1.651 0 010-1.186A10.004 10.004 0 0110 3c4.257 0 7.893 2.66 9.336 6.41.147.381.146.804 0 1.186A10.004 10.004 0 0110 17c-4.257 0-7.893-2.66-9.336-6.41zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                </svg>
                                View
                            </a>
                            <template x-if="item.total_stock <= 0">
                                <button disabled
                                    class="inline-flex items-center gap-1 border border-sky-100 bg-slate-100 px-2.5 py-1.5 text-[10px] font-mono font-bold text-slate-400 cursor-not-allowed opacity-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-3 w-3">
                                        <path fill-rule="evenodd" d="M8 2a.75.75 0 01.75.75v8.69l3.22-3.22a.75.75 0 111.06 1.06l-4.5 4.5a.75.75 0 01-1.06 0l-4.5-4.5a.75.75 0 111.06-1.06L7.25 11.44V2.75A.75.75 0 018 2z" clip-rule="evenodd" />
                                    </svg>
                                    Use
                                </button>
                            </template>
                            <template x-if="item.total_stock > 0">
                                <a :href="`/usage/create?item_id=${item.id}`"
                                    class="inline-flex items-center gap-1 border border-rose-200 bg-rose-50 px-2.5 py-1.5 text-[10px] font-mono font-bold text-rose-600 hover:bg-rose-500 hover:text-white hover:border-rose-500 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-3 w-3">
                                        <path fill-rule="evenodd" d="M8 2a.75.75 0 01.75.75v8.69l3.22-3.22a.75.75 0 111.06 1.06l-4.5 4.5a.75.75 0 01-1.06 0l-4.5-4.5a.75.75 0 111.06-1.06L7.25 11.44V2.75A.75.75 0 018 2z" clip-rule="evenodd" />
                                    </svg>
                                    Use
                                </a>
                            </template>
                            <a :href="`/items/${item.id}/stock/create`"
                                class="inline-flex items-center gap-1 border border-sky-200 bg-sky-50 px-2.5 py-1.5 text-[10px] font-mono font-bold text-sky-600 hover:bg-sky-600 hover:text-white hover:border-sky-600 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-3 w-3">
                                    <path d="M8.75 3.75a.75.75 0 00-1.5 0v3.5h-3.5a.75.75 0 000 1.5h3.5v3.5a.75.75 0 001.5 0v-3.5h3.5a.75.75 0 000-1.5h-3.5v-3.5z" />
                                </svg>
                                Stock
                            </a>
                            <a :href="`/items/${item.id}/edit`"
                                class="inline-flex items-center border border-sky-100 bg-sky-50 px-2.5 py-1.5 text-[10px] font-mono font-bold text-slate-600 hover:bg-slate-800 hover:text-white hover:border-slate-800 transition-colors">
                                Edit
                            </a>
                        </div>
                    </td>
                </tr>
                </template>
            </tbody>
        </table>
    </div>
    </template>

    {{-- Empty State --}}
    <template x-if="filteredItems.length === 0">
    <div class="flex flex-col items-center justify-center py-20 text-center ml-1">
        <div class="h-14 w-14 border border-sky-100 bg-sky-50 flex items-center justify-center text-slate-400 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-7 w-7">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
            </svg>
        </div>
        <p class="font-mono text-[10px] text-slate-400 uppercase tracking-widest mb-1">// No records found</p>
        <p class="text-sm font-semibold text-slate-500 mt-1">
            <template x-if="search || category || type">Try adjusting your filters.</template>
            <template x-if="!search && !category && !type">No items in inventory yet.</template>
        </p>
        <template x-if="!search && !category && !type">
            <a href="{{ route('items.create') }}"
                class="mt-6 inline-flex items-center gap-2 bg-sky-500 hover:bg-sky-600 text-white px-5 py-2.5 text-[11px] font-mono font-bold uppercase tracking-widest transition-colors border border-sky-600">
                + Add First Item
            </a>
        </template>
    </div>
    </template>
</div>

</div>

<style>
    .items-theme [class*="border-sky-100"] { border-color: #dbeafe !important; }
    .items-theme [class*="bg-sky-50"] { background-color: #f8fbff !important; }
    .items-theme [class*="text-sky-500"] { color: #0284c7 !important; }
</style>

@endsection