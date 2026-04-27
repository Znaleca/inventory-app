@extends('layouts.app')

@section('title', $item->name)

@section('actions')
    <a href="{{ route('items.index') }}"
        class="inline-flex items-center gap-2 border border-sky-100 bg-white px-4 py-2 text-[11px] font-mono font-bold text-slate-600 uppercase tracking-widest hover:bg-sky-50 transition-colors">
        ← Back to Items
    </a>
    <a href="{{ route('items.edit', $item) }}"
        class="inline-flex items-center gap-2 border border-sky-100 bg-white px-4 py-2 text-[11px] font-mono font-bold text-slate-600 uppercase tracking-widest hover:bg-sky-50 transition-colors">
        Edit
    </a>
    <a href="{{ route('usage.create', ['item_id' => $item->id]) }}"
        class="inline-flex items-center gap-2 border border-rose-200 bg-rose-50 px-4 py-2 text-[11px] font-mono font-bold text-rose-600 uppercase tracking-widest hover:bg-rose-500 hover:text-white hover:border-rose-500 transition-colors">
        Log Usage
    </a>
    @php
        $hasExpired = $item->stockEntries->filter(fn($e) =>
            $e->expiry_date && \Carbon\Carbon::parse($e->expiry_date)->startOfDay()->isBefore(now()->startOfDay())
        )->count() > 0;
        $showDispose = $item->stock_used > 0 || $hasExpired;
    @endphp
    @if($showDispose)
    <div x-data="{ open: false }" class="relative inline-block z-20">
        <button @click="open = !open" @click.away="open = false" type="button"
            class="inline-flex items-center gap-2 border border-amber-300 bg-amber-50 px-4 py-2 text-[11px] font-mono font-bold text-amber-700 uppercase tracking-widest hover:bg-amber-500 hover:text-white hover:border-amber-500 transition-colors">
            Dispose
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-3.5 w-3.5" :class="{ 'rotate-180': open }">
                <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
            </svg>
        </button>
        <div x-show="open" x-cloak x-transition
            class="absolute right-0 mt-1 w-44 bg-white border border-sky-100 shadow-lg z-50 overflow-hidden" style="display:none">
            @if($item->stock_used > 0)
            <a href="{{ route('disposals.create', ['item_id' => $item->id, 'disposal_type' => 'used']) }}"
                class="flex items-center gap-2 px-4 py-2.5 text-xs font-mono font-bold text-slate-700 hover:bg-amber-50 hover:text-amber-700 transition-colors">
                Dispose Used
            </a>
            @endif
            @if($hasExpired)
            <a href="{{ route('disposals.create', ['item_id' => $item->id, 'disposal_type' => 'new']) }}"
                class="flex items-center gap-2 px-4 py-2.5 text-xs font-mono font-bold text-slate-700 hover:bg-rose-50 hover:text-rose-700 transition-colors">
                Dispose Expired
            </a>
            @endif
        </div>
    </div>
    @endif
    <a href="{{ route('stock.create', $item) }}"
        class="inline-flex items-center gap-2 border border-sky-200 bg-sky-50 px-4 py-2 text-[11px] font-mono font-bold text-sky-600 uppercase tracking-widest hover:bg-sky-600 hover:text-white hover:border-sky-600 transition-colors">
        + Receive Stock
    </a>
@endsection

@section('content')
    <div class="bg-white rounded-2xl overflow-hidden border border-sky-100">

        {{-- Page Header --}}
        <div class="p-6 border-b border-sky-100 bg-white flex items-center justify-between shrink-0 mb-6">
            <div>
                <p class="font-mono text-[10px] font-bold uppercase tracking-widest text-sky-500 mb-1">Items://{{ $item->id }}</p>
                <h3 class="text-xl font-black text-[#0f172a] tracking-tight">{{ $item->name }}</h3>
                @if($item->brand)
                    <p class="text-xs text-slate-400 font-mono mt-1">{{ $item->brand }}{{ $item->model ? ' · ' . $item->model : '' }}</p>
                @endif
            </div>
            <div class="flex items-center gap-2">
                @if($item->item_type === 'device')
                    <span class="text-[9px] font-mono font-bold uppercase tracking-wider text-violet-600 bg-violet-50 px-2 py-1 border border-violet-200">Device</span>
                @else
                    <span class="text-[9px] font-mono font-bold uppercase tracking-wider text-sky-600 bg-sky-50 px-2 py-1 border border-sky-200">Consumable</span>
                @endif
            </div>
        </div>

        <div class="p-6 pt-0">

        {{-- Item Search Bar --}}
        <div x-data="{ 
            search: '', 
            results: [], 
            open: false,
            async fetchResults() {
                if (this.search.length < 1) {
                    this.results = [];
                    return;
                }
                try {
                    const response = await fetch(`/api/items/search?q=${encodeURIComponent(this.search)}`);
                    this.results = await response.json();
                } catch (error) {
                    this.results = [];
                }
            }
        }" class="relative mb-5 max-w-md">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4 text-slate-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
            </div>
            <input 
                type="text" 
                x-model="search"
                @input="fetchResults()"
                @focus="open = true"
                @blur="setTimeout(() => open = false, 200)"
                placeholder="Jump to item..."
                class="block w-full border border-sky-100 bg-sky-50 py-2 pl-9 pr-4 text-sm font-mono text-[#0f172a] placeholder:text-slate-400 focus:bg-white focus:border-blue-500 focus:outline-none transition-colors"
            />
            
            {{-- Suggestions Dropdown --}}
            <div 
                x-show="open && results.length > 0" 
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 translate-y-2"
                class="absolute top-full left-0 right-0 mt-1 bg-white border border-sky-100 shadow-lg z-50 max-h-96 overflow-y-auto"
                style="display: none;">
                <template x-for="item in results" :key="item.id">
                    <a :href="`/items/${item.id}`" class="block px-4 py-3 border-b border-sky-100 hover:bg-blue-50 transition-colors group">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <p class="text-sm font-bold text-[#0f172a] group-hover:text-sky-500" x-text="item.name"></p>
                                <p class="text-xs text-slate-400 font-mono mt-0.5" x-text="item.category"></p>
                            </div>
                            <span class="text-xs font-mono font-bold text-slate-500 ml-2" x-text="`${item.stock}/${item.unit}`"></span>
                        </div>
                    </a>
                </template>
            </div>
        </div>

        {{-- Stock Summary Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 mb-6 bg-white rounded-2xl overflow-hidden border border-sky-100 shadow-sm">
            
            {{-- New Stock --}}
            <div class="group relative overflow-hidden bg-white p-5 border-r border-b sm:border-b lg:border-b-0 border-sky-100 transition-all hover:bg-sky-50/50 lg:[&:nth-child(5n)]:border-r-0 sm:[&:nth-child(2n)]:border-r-0">
                <div class="flex items-center justify-between mb-3">
                    <p class="font-semibold text-xs text-sky-600 uppercase tracking-wider">New Stock</p>
                    <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-50 text-emerald-500 transition-colors group-hover:bg-emerald-100">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                    </div>
                </div>
                <div class="flex items-end justify-between">
                    <div class="z-10">
                        <p class="text-2xl font-black tracking-tight text-slate-800">{{ $item->total_stock }}</p>
                        <p class="mt-0.5 text-[10px] font-medium text-slate-400">{{ $item->unit }}</p>
                    </div>
                    <div class="w-16 h-8 opacity-40 group-hover:opacity-80 transition-opacity duration-300">
                        <svg viewBox="0 0 100 30" class="w-full h-full stroke-emerald-400">
                            <path d="M0,20 L15,10 L30,15 L45,5 L60,18 L75,8 L90,12 L100,0" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" vector-effect="non-scaling-stroke"/>
                        </svg>
                    </div>
                </div>
            </div>
            
            {{-- Used Stock --}}
            <div class="group relative overflow-hidden bg-white p-5 border-r border-b sm:border-b lg:border-b-0 border-sky-100 transition-all hover:bg-sky-50/50 lg:[&:nth-child(5n)]:border-r-0 sm:[&:nth-child(2n)]:border-r-0">
                <div class="flex items-center justify-between mb-3">
                    <p class="font-semibold text-xs text-amber-600 uppercase tracking-wider">Used Stock</p>
                    <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-amber-50 text-amber-500 transition-colors group-hover:bg-amber-100">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                        </svg>
                    </div>
                </div>
                <div class="flex items-end justify-between">
                    <div class="z-10">
                        <p class="text-2xl font-black tracking-tight text-slate-800">{{ $item->effective_stock_used }}</p>
                        <p class="mt-0.5 text-[10px] font-medium text-slate-400">{{ $item->unit }}</p>
                    </div>
                    <div class="w-16 h-8 opacity-40 group-hover:opacity-80 transition-opacity duration-300">
                        <svg viewBox="0 0 100 30" class="w-full h-full stroke-amber-400">
                            <path d="M0,5 L15,15 L30,8 L45,18 L60,10 L75,20 L90,15 L100,25" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" vector-effect="non-scaling-stroke"/>
                        </svg>
                    </div>
                </div>
            </div>
            
            {{-- Lent Out --}}
            <div class="group relative overflow-hidden bg-white p-5 border-r border-b sm:border-b lg:border-b-0 border-sky-100 transition-all hover:bg-sky-50/50 lg:[&:nth-child(5n)]:border-r-0 sm:[&:nth-child(2n)]:border-r-0">
                <div class="flex items-center justify-between mb-3">
                    <p class="font-semibold text-xs text-indigo-600 uppercase tracking-wider">Lent Out</p>
                    <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-indigo-50 text-indigo-500 transition-colors group-hover:bg-indigo-100">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                    </div>
                </div>
                <div class="flex items-end justify-between">
                    <div class="z-10">
                        <p class="text-2xl font-black tracking-tight text-slate-800">{{ $item->active_lent_out }}</p>
                        <p class="mt-0.5 text-[10px] font-medium text-slate-400">{{ $item->unit }}</p>
                    </div>
                    <div class="w-16 h-8 opacity-40 group-hover:opacity-80 transition-opacity duration-300">
                        <svg viewBox="0 0 100 30" class="w-full h-full stroke-indigo-400">
                            <path d="M0,20 Q15,5 30,15 T60,5 T90,15 L100,10" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" vector-effect="non-scaling-stroke"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Category --}}
            <div class="group relative overflow-hidden bg-white p-5 border-r border-b sm:border-b lg:border-b-0 border-sky-100 transition-all hover:bg-sky-50/50 lg:[&:nth-child(5n)]:border-r-0 sm:[&:nth-child(2n)]:border-r-0">
                <div class="flex items-center justify-between mb-3">
                    <p class="font-semibold text-xs text-fuchsia-600 uppercase tracking-wider">Category</p>
                    <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-fuchsia-50 text-fuchsia-500 transition-colors group-hover:bg-fuchsia-100">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3v3m-6-6v6a2 2 0 002 2h10a2 2 0 002-2v-6m-8-3a2 2 0 00-2 2v3H7a2 2 0 01-2-2v-3a2 2 0 012-2h10a2 2 0 012 2v3" />
                        </svg>
                    </div>
                </div>
                <div class="flex items-end justify-between">
                    <div class="z-10">
                        <p class="text-base font-black tracking-tight text-slate-800 truncate">{{ $item->category?->name ?? 'Uncategorized' }}</p>
                        <p class="mt-0.5 text-[10px] font-medium text-slate-400">Classification</p>
                    </div>
                </div>
            </div>

            {{-- Status --}}
            @php
                $newStock  = max(0, $item->total_stock);
                $usedStock = max(0, $item->effective_stock_used);
                $totalQty  = $newStock + $usedStock;
                if ($totalQty <= 0) {
                    $statusTone = 'text-rose-500';
                    $statusBg = 'bg-rose-50';
                    $statusLabel = 'Out_of_Stock';
                } elseif ($item->is_low_stock && $totalQty <= $item->reorder_level) {
                    $statusTone = 'text-amber-500';
                    $statusBg = 'bg-amber-50';
                    $statusLabel = 'Reorder';
                } else {
                    $statusTone = 'text-emerald-500';
                    $statusBg = 'bg-emerald-50';
                    $statusLabel = 'In_Stock';
                }
            @endphp
            <div class="group relative overflow-hidden bg-white p-5 transition-all hover:bg-sky-50/50">
                <div class="flex items-center justify-between mb-3">
                    <p class="font-semibold text-xs text-slate-600 uppercase tracking-wider">Status</p>
                    <div class="flex h-7 w-7 items-center justify-center rounded-lg {{ $statusBg }} {{ $statusTone }} transition-colors group-hover:bg-slate-100">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="flex items-end justify-between">
                    <div class="z-10">
                        <span class="text-[10px] font-mono font-bold uppercase tracking-wider {{ $statusTone }} {{ $statusBg }} px-2 py-1 border border-current rounded-sm">{{ $statusLabel }}</span>
                        <p class="mt-1 text-[10px] font-medium text-slate-400">Overall</p>
                    </div>
                </div>
            </div>
            
        </div>

        {{-- Analytics Charts --}}
        @php
            $trendDates = collect(range(13, 0))->map(fn($daysAgo) => now()->subDays($daysAgo)->startOfDay());
            $trendLabels = $trendDates->map(fn($d) => $d->format('M d'))->values();

            $receivedByDay = $item->stockEntries
                ->groupBy(fn($e) => optional($e->received_date)->format('Y-m-d'))
                ->map(fn($rows) => $rows->sum('quantity'));

            $usedByDay = $item->usageLogs
                ->groupBy(fn($l) => optional($l->used_at)->format('Y-m-d'))
                ->map(fn($rows) => $rows->sum('quantity_used'));

            $trendReceived = $trendDates->map(fn($d) => (int) ($receivedByDay[$d->format('Y-m-d')] ?? 0))->values();
            $trendUsed = $trendDates->map(fn($d) => (int) ($usedByDay[$d->format('Y-m-d')] ?? 0))->values();

            $metricLabels = ['Received', 'Used', 'Available New', 'Available Used', 'Lent Out'];
            $metricValues = [
                (int) $item->stockEntries->sum('quantity'),
                (int) $item->usageLogs->sum('quantity_used'),
                (int) max(0, $item->total_stock),
                (int) max(0, $item->effective_stock_used),
                (int) max(0, $item->active_lent_out),
            ];
        @endphp

        <div class="grid grid-cols-1 gap-4 mb-4 lg:grid-cols-2">
            {{-- Line Trend --}}
            <div class="bg-white border border-sky-100 rounded-2xl overflow-hidden shadow-sm">
                <div class="p-5 border-b border-sky-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-semibold uppercase tracking-widest text-teal-600 mb-0.5">Chart.01</p>
                            <h3 class="text-sm font-bold text-slate-800">14-Day Movement Trend</h3>
                        </div>
                        <span class="flex items-center gap-1.5 text-[10px] font-semibold text-emerald-600 bg-emerald-50 border border-emerald-200 px-2.5 py-1 rounded">
                            <span class="h-1.5 w-1.5 bg-emerald-500 inline-block animate-pulse"></span>
                            LIVE
                        </span>
                    </div>
                </div>
                <div class="p-5">
                    <div class="h-[240px]">
                        <canvas id="itemTrendLineChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Bar Summary --}}
            <div class="bg-white border border-sky-100 rounded-2xl overflow-hidden shadow-sm">
                <div class="p-5 border-b border-sky-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-semibold uppercase tracking-widest text-sky-600 mb-0.5">Chart.02</p>
                            <h3 class="text-sm font-bold text-slate-800">Stock Analytics Summary</h3>
                        </div>
                        <span class="flex items-center gap-1.5 text-[10px] font-semibold text-emerald-600 bg-emerald-50 border border-emerald-200 px-2.5 py-1 rounded">
                            <span class="h-1.5 w-1.5 bg-emerald-500 inline-block animate-pulse"></span>
                            LIVE
                        </span>
                    </div>
                </div>
                <div class="p-5">
                    <div class="h-[240px]">
                        <canvas id="itemMetricsBarChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Specs & Batch --}}
        <div class="grid grid-cols-1 gap-4 mb-4 lg:grid-cols-2">

            {{-- Item Specifications --}}
            <div class="bg-white border border-sky-100 rounded-2xl overflow-hidden shadow-sm">
                <div class="p-5 border-b border-sky-100">
                    <div class="flex items-center gap-2">
                        <span class="h-2 w-2 bg-sky-500 rounded-full inline-block"></span>
                        <p class="text-[10px] font-semibold text-sky-600 uppercase tracking-widest">Item Specifications</p>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <tbody class="divide-y divide-slate-50">
                            <tr class="hover:bg-sky-50 transition-colors">
                                <td class="w-36 px-4 py-2.5 text-[10px] font-semibold uppercase tracking-widest text-slate-400 whitespace-nowrap">Type</td>
                                <td class="px-4 py-2.5">
                                    @if($item->item_type === 'device')
                                        <span class="text-[9px] font-semibold uppercase tracking-wider text-violet-600 bg-violet-50 px-2 py-1 border border-violet-200 rounded">Device</span>
                                    @else
                                        <span class="text-[9px] font-semibold uppercase tracking-wider text-sky-600 bg-sky-50 px-2 py-1 border border-sky-200 rounded">Consumable</span>
                                    @endif
                                </td>
                            </tr>
                            @if($item->item_type === 'device')
                            <tr class="hover:bg-sky-50 transition-colors">
                                <td class="px-4 py-2.5 text-[10px] font-semibold uppercase tracking-widest text-slate-400 whitespace-nowrap">Brand</td>
                                <td class="px-4 py-2.5 text-sm font-semibold text-slate-700">{{ $item->brand ?? '—' }}</td>
                            </tr>
                            <tr class="hover:bg-sky-50 transition-colors">
                                <td class="px-4 py-2.5 text-[10px] font-semibold uppercase tracking-widest text-slate-400 whitespace-nowrap">Model</td>
                                <td class="px-4 py-2.5 text-sm font-semibold text-slate-700">{{ $item->model ?? '—' }}</td>
                            </tr>
                            @endif
                            <tr class="hover:bg-sky-50 transition-colors">
                                <td class="px-4 py-2.5 text-[10px] font-semibold uppercase tracking-widest text-slate-400 whitespace-nowrap">Unit</td>
                                <td class="px-4 py-2.5 text-sm text-slate-600">{{ $item->unit }}</td>
                            </tr>
                            <tr class="hover:bg-sky-50 transition-colors">
                                <td class="px-4 py-2.5 text-[10px] font-semibold uppercase tracking-widest text-slate-400 whitespace-nowrap">Location</td>
                                <td class="px-4 py-2.5 text-sm text-slate-600">
                                    @if($item->storage_location || $item->storage_section)
                                        {{ $item->storage_location ?? 'Any' }}@if($item->storage_section) · {{ $item->storage_section }}@endif
                                    @else
                                        <span class="text-slate-400">Not assigned</span>
                                    @endif
                                </td>
                            </tr>
                            <tr class="hover:bg-sky-50 transition-colors">
                                <td class="px-4 py-2.5 text-[10px] font-semibold uppercase tracking-widest text-slate-400 whitespace-nowrap">Reorder Level</td>
                                <td class="px-4 py-2.5">
                                    <span class="text-sm font-semibold text-amber-600">{{ $item->reorder_level ?? 10 }}</span>
                                    <span class="text-[10px] text-slate-400 ml-1">{{ $item->unit }} threshold</span>
                                </td>
                            </tr>

                            <tr class="hover:bg-sky-50 transition-colors">
                                <td class="px-4 py-2.5 text-[10px] font-semibold uppercase tracking-widest text-slate-400 whitespace-nowrap">Expiry Tracking</td>
                                <td class="px-4 py-2.5">
                                    @if($item->is_expirable)
                                        <span class="text-[9px] font-semibold uppercase tracking-wider text-amber-600 bg-amber-50 px-2 py-1 border border-amber-200 rounded">Tracked</span>
                                    @else
                                        <span class="text-[9px] font-semibold uppercase tracking-wider text-slate-400 bg-sky-50 px-2 py-1 border border-sky-100 rounded">None</span>
                                    @endif
                                </td>
                            </tr>
                            @if($item->description)
                            <tr class="hover:bg-sky-50 transition-colors">
                                <td class="px-4 py-2.5 text-[10px] font-semibold uppercase tracking-widest text-slate-400 whitespace-nowrap align-top">Notes</td>
                                <td class="px-4 py-2.5 text-xs text-slate-600">{{ $item->description }}</td>
                            </tr>
                            @endif
                            <tr class="hover:bg-sky-50 transition-colors">
                                <td class="px-4 py-2.5 text-[10px] font-semibold uppercase tracking-widest text-slate-400 whitespace-nowrap">Created</td>
                                <td class="px-4 py-2.5 text-xs text-slate-500">{{ $item->created_at->format('M d, Y') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Stock Overview Chart --}}
            @php
                $chartNew  = max(0, $item->total_stock);
                $chartUsed = max(0, $item->effective_stock_used);
                $chartLent = max(0, $item->active_lent_out);
                $chartTotal = $chartNew + $chartUsed + $chartLent;
                $totalReceived = $item->stockEntries->sum('quantity');
                $totalUsedLogs = $item->usageLogs->sum('quantity_used');
                $pctNew  = $chartTotal > 0 ? round(($chartNew  / $chartTotal) * 100) : 0;
                $pctUsed = $chartTotal > 0 ? round(($chartUsed / $chartTotal) * 100) : 0;
                $pctLent = $chartTotal > 0 ? round(($chartLent / $chartTotal) * 100) : 0;
            @endphp
            <div class="bg-white border border-sky-100 rounded-2xl overflow-hidden shadow-sm">
                <div class="p-5 border-b border-sky-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-semibold uppercase tracking-widest text-sky-600 mb-0.5">Chart.00</p>
                            <h3 class="text-sm font-bold text-slate-800">Stock Overview</h3>
                        </div>
                        <span class="flex items-center gap-1.5 text-[10px] font-semibold text-emerald-600 bg-emerald-50 border border-emerald-200 px-2.5 py-1 rounded">
                            <span class="h-1.5 w-1.5 bg-emerald-500 inline-block animate-pulse"></span>
                            LIVE
                        </span>
                    </div>
                </div>

                <div class="p-5">

                    @if($chartTotal > 0)
                    {{-- Donut Chart --}}
                    <div class="flex items-center justify-center mb-6">
                        <div class="relative w-44 h-44">
                            <canvas id="stockDonut" width="160" height="160"></canvas>
                            <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                                <span class="text-2xl font-black text-slate-800">{{ $chartTotal }}</span>
                                <span class="text-[9px] text-slate-400 uppercase tracking-widest">{{ $item->unit }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Legend --}}
                    <div class="grid grid-cols-3 gap-2 mb-5">
                        <div class="text-center">
                            <div class="flex items-center justify-center gap-1.5 mb-0.5">
                                <span class="h-2 w-2 bg-emerald-400 rounded-full inline-block"></span>
                                <span class="text-[9px] font-semibold text-slate-400 uppercase tracking-widest">New</span>
                            </div>
                            <p class="text-xl font-black text-emerald-600">{{ $chartNew }}</p>
                            <p class="text-[9px] text-slate-400">{{ $pctNew }}%</p>
                        </div>
                        <div class="text-center">
                            <div class="flex items-center justify-center gap-1.5 mb-0.5">
                                <span class="h-2 w-2 bg-amber-400 rounded-full inline-block"></span>
                                <span class="text-[9px] font-semibold text-slate-400 uppercase tracking-widest">Used</span>
                            </div>
                            <p class="text-xl font-black text-amber-500">{{ $chartUsed }}</p>
                            <p class="text-[9px] text-slate-400">{{ $pctUsed }}%</p>
                        </div>
                        <div class="text-center">
                            <div class="flex items-center justify-center gap-1.5 mb-0.5">
                                <span class="h-2 w-2 bg-indigo-400 rounded-full inline-block"></span>
                                <span class="text-[9px] font-semibold text-slate-400 uppercase tracking-widest">Lent</span>
                            </div>
                            <p class="text-xl font-black text-indigo-500">{{ $chartLent }}</p>
                            <p class="text-[9px] text-slate-400">{{ $pctLent }}%</p>
                        </div>
                    </div>
                    @else
                    <div class="flex flex-col items-center justify-center py-10">
                        <p class="text-[11px] text-slate-400">// No stock recorded yet</p>
                    </div>
                    @endif

                    {{-- Throughput bars --}}
                    <div class="space-y-3 border-t border-sky-100 pt-4">
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-widest">Total Received</span>
                                <span class="text-[10px] font-semibold text-teal-600">{{ $totalReceived }} {{ $item->unit }}</span>
                            </div>
                            <div class="h-1.5 bg-slate-100 border border-sky-100 rounded-full overflow-hidden">
                                <div class="h-full bg-teal-400" style="width: 100%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-widest">Total Used</span>
                                <span class="text-[10px] font-semibold text-rose-600">{{ $totalUsedLogs }} {{ $item->unit }}</span>
                            </div>
                            <div class="h-1.5 bg-slate-100 border border-sky-100 rounded-full overflow-hidden">
                                @php $usedPct = $totalReceived > 0 ? min(100, round(($totalUsedLogs / $totalReceived) * 100)) : 0; @endphp
                                <div class="h-full bg-rose-400" style="width: {{ $usedPct }}%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-widest">Available</span>
                                <span class="text-[10px] font-semibold text-emerald-600">{{ $chartNew }} {{ $item->unit }}</span>
                            </div>
                            <div class="h-1.5 bg-slate-100 border border-sky-100 rounded-full overflow-hidden">
                                @php $availPct = $totalReceived > 0 ? min(100, round(($chartNew / $totalReceived) * 100)) : 0; @endphp
                                <div class="h-full bg-emerald-400" style="width: {{ $availPct }}%"></div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- History Tables --}}
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
            {{-- Usage Logs --}}
            <div class="bg-white border border-sky-100 rounded-2xl overflow-hidden shadow-sm">
                <div class="p-5 border-b border-sky-100">
                    <div class="flex items-center gap-2">
                        <span class="h-2 w-2 bg-rose-500 rounded-full inline-block"></span>
                        <p class="text-[10px] font-semibold text-rose-600 uppercase tracking-widest">Usage Logs (Recent 10)</p>
                    </div>
                </div>
                <div class="overflow-x-auto max-h-80 overflow-y-auto">
                    <table class="w-full text-xs">
                        <thead class="bg-sky-50 border-b border-sky-100 sticky top-0">
                            <tr>
                                <th class="px-4 py-2 text-left font-semibold text-slate-600 uppercase tracking-widest">Date</th>
                                <th class="px-4 py-2 text-left font-semibold text-slate-600 uppercase tracking-widest">Qty</th>
                                <th class="px-4 py-2 text-left font-semibold text-slate-600 uppercase tracking-widest">Person</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($item->usageLogs->sortByDesc('created_at')->take(10) as $log)
                                <tr class="hover:bg-sky-50 transition-colors">
                                    <td class="px-4 py-2 text-slate-600">{{ $log->created_at->format('M d, H:i') }}</td>
                                    <td class="px-4 py-2 font-semibold text-rose-500">{{ $log->quantity_used }}</td>
                                    <td class="px-4 py-2">
                                        <div class="font-bold text-[#0f172a] text-xs">{{ $log->used_by ?? 'System' }}</div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-8 text-center text-[10px] text-slate-400">// No usage records</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Stock Received --}}
            <div class="bg-white border border-sky-100 rounded-2xl overflow-hidden shadow-sm">
                <div class="p-5 border-b border-sky-100">
                    <div class="flex items-center gap-2">
                        <span class="h-2 w-2 bg-teal-500 rounded-full inline-block"></span>
                        <p class="text-[10px] font-semibold text-teal-600 uppercase tracking-widest">Stock Received (Recent 10)</p>
                    </div>
                </div>
                <div class="overflow-x-auto max-h-80 overflow-y-auto">
                    <table class="w-full text-xs">
                        <thead class="bg-sky-50 border-b border-sky-100 sticky top-0">
                            <tr>
                                <th class="px-4 py-2 text-left font-semibold text-slate-600 uppercase tracking-widest">Date</th>
                                <th class="px-4 py-2 text-left font-semibold text-slate-600 uppercase tracking-widest">Qty</th>
                                <th class="px-4 py-2 text-left font-semibold text-slate-600 uppercase tracking-widest">Exp. Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($item->stockEntries->sortByDesc('created_at')->take(10) as $entry)
                                <tr class="hover:bg-sky-50 transition-colors">
                                    <td class="px-4 py-2 text-slate-600">{{ $entry->created_at->format('M d, Y') }}</td>
                                    <td class="px-4 py-2 font-semibold text-teal-500">+{{ $entry->quantity }}</td>
                                    <td class="px-4 py-2 text-slate-600">
                                        @if($entry->expiration_date)
                                            {{ \Carbon\Carbon::parse($entry->expiration_date)->format('M d, Y') }}
                                        @else
                                            <span class="text-slate-400">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-8 text-center text-[10px] text-slate-400">// No stock entries</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
    (function() {
        const canvas = document.getElementById('stockDonut');
        if (!canvas) return;
        const chartNew  = {{ $chartNew ?? 0 }};
        const chartUsed = {{ $chartUsed ?? 0 }};
        const chartLent = {{ $chartLent ?? 0 }};
        const total = chartNew + chartUsed + chartLent;
        if (total === 0) return;
        const ctx = canvas.getContext('2d');
        const createGradient = (ctx, startColor, endColor) => {
            const gradient = ctx.createLinearGradient(0, 0, 0, 220);
            gradient.addColorStop(0, startColor);
            gradient.addColorStop(1, endColor);
            return gradient;
        };
        new Chart(canvas, {
            type: 'doughnut',
            data: {
                labels: ['New Stock', 'Used Stock', 'Lent Out'],
                datasets: [{
                    data: [chartNew, chartUsed, chartLent],
                    backgroundColor: [
                        createGradient(ctx, 'rgba(52, 211, 153, 0.95)', 'rgba(52, 211, 153, 0.35)'),
                        createGradient(ctx, 'rgba(251, 191, 36, 0.95)', 'rgba(251, 191, 36, 0.35)'),
                        createGradient(ctx, 'rgba(129, 140, 248, 0.95)', 'rgba(129, 140, 248, 0.35)')
                    ],
                    borderColor: ['#22c55e', '#f59e0b', '#6366f1'],
                    borderWidth: 2,
                    hoverOffset: 0,
                    hoverBorderWidth: 2,
                }]
            },
            options: {
                cutout: '72%',
                animation: { animateScale: true, duration: 600 },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => ` ${ctx.label}: ${ctx.parsed} (${Math.round(ctx.parsed / total * 100)}%)`
                        },
                        bodyFont: { family: 'monospace', size: 11 },
                        padding: 8,
                    }
                }
            }
        });
    })();

    (function() {
        const lineCanvas = document.getElementById('itemTrendLineChart');
        if (!lineCanvas) return;
        const lineCtx = lineCanvas.getContext('2d');
        const createGradient = (ctx, startColor, endColor) => {
            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, startColor);
            gradient.addColorStop(1, endColor);
            return gradient;
        };
        const gradientIn = createGradient(lineCtx, 'rgba(20, 184, 166, 0.30)', 'rgba(20, 184, 166, 0.02)');
        const gradientOut = createGradient(lineCtx, 'rgba(244, 63, 94, 0.30)', 'rgba(244, 63, 94, 0.02)');

        new Chart(lineCtx, {
            type: 'line',
            data: {
                labels: @json($trendLabels),
                datasets: [
                    {
                        label: 'Received',
                        data: @json($trendReceived),
                        borderColor: '#14b8a6',
                        backgroundColor: gradientIn,
                        fill: true,
                        tension: 0.35,
                        borderWidth: 2,
                        pointRadius: 2.5,
                        pointHoverRadius: 4,
                    },
                    {
                        label: 'Used',
                        data: @json($trendUsed),
                        borderColor: '#f43f5e',
                        backgroundColor: gradientOut,
                        fill: true,
                        tension: 0.35,
                        borderWidth: 2,
                        pointRadius: 2.5,
                        pointHoverRadius: 4,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            color: '#64748b',
                            font: { family: "'Plus Jakarta Sans', sans-serif", size: 11, weight: '600' },
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.04)' },
                        border: { display: false },
                        ticks: { color: '#94a3b8', font: { family: "'Fira Code', monospace", size: 10 } }
                    },
                    x: {
                        grid: { display: false },
                        border: { display: false },
                        ticks: { color: '#64748b', font: { family: "'Fira Code', monospace", size: 10 } }
                    }
                }
            }
        });
    })();

    (function() {
        const barCanvas = document.getElementById('itemMetricsBarChart');
        if (!barCanvas) return;
        const barCtx = barCanvas.getContext('2d');
        const createGradient = (ctx, startColor, endColor) => {
            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, startColor);
            gradient.addColorStop(1, endColor);
            return gradient;
        };

        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: @json($metricLabels),
                datasets: [{
                    label: 'Units',
                    data: @json($metricValues),
                    backgroundColor: [
                        createGradient(barCtx, 'rgba(20, 184, 166, 0.8)', 'rgba(20, 184, 166, 0.1)'),
                        createGradient(barCtx, 'rgba(244, 63, 94, 0.8)', 'rgba(244, 63, 94, 0.1)'),
                        createGradient(barCtx, 'rgba(16, 185, 129, 0.8)', 'rgba(16, 185, 129, 0.1)'),
                        createGradient(barCtx, 'rgba(245, 158, 11, 0.8)', 'rgba(245, 158, 11, 0.1)'),
                        createGradient(barCtx, 'rgba(99, 102, 241, 0.8)', 'rgba(99, 102, 241, 0.1)')
                    ],
                    borderColor: ['#0f766e', '#be123c', '#047857', '#b45309', '#4338ca'],
                    borderWidth: { top: 2, right: 2, bottom: 0, left: 2 },
                    borderRadius: 4,
                    borderSkipped: false,
                    barThickness: 32,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.04)' },
                        border: { display: false },
                        ticks: { color: '#94a3b8', font: { family: "'Fira Code', monospace", size: 10 } }
                    },
                    x: {
                        grid: { display: false },
                        border: { display: false },
                        ticks: { color: '#64748b', font: { family: "'Fira Code', monospace", size: 10 } }
                    }
                }
            }
        });
    })();
    </script>
        </div>
    </div>
@endsection