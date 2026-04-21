@extends('layouts.app')

@section('title', $item->name)

@section('actions')
    <a href="{{ route('items.index') }}"
        class="inline-flex items-center gap-2 border border-slate-200 bg-white px-4 py-2 text-[11px] font-mono font-bold text-slate-600 uppercase tracking-widest hover:bg-slate-50 transition-colors">
        ← Back to Items
    </a>
    <a href="{{ route('items.edit', $item) }}"
        class="inline-flex items-center gap-2 border border-slate-200 bg-white px-4 py-2 text-[11px] font-mono font-bold text-slate-600 uppercase tracking-widest hover:bg-slate-50 transition-colors">
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
            class="absolute right-0 mt-1 w-44 bg-white border border-slate-200 shadow-lg z-50 overflow-hidden" style="display:none">
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
        class="inline-flex items-center gap-2 border border-indigo-200 bg-indigo-50 px-4 py-2 text-[11px] font-mono font-bold text-indigo-600 uppercase tracking-widest hover:bg-indigo-600 hover:text-white hover:border-indigo-600 transition-colors">
        + Receive Stock
    </a>
@endsection

@section('content')
    <div class="mx-auto max-w-5xl">

        {{-- Page Header --}}
        <div class="mb-5 flex items-end justify-between">
            <div>
                <p class="text-[10px] font-mono font-semibold text-blue-600 uppercase tracking-[0.25em] mb-1">Items://{{ $item->id }}</p>
                <h1 class="text-xl font-bold text-slate-800 tracking-tight">{{ $item->name }}</h1>
                @if($item->brand)
                    <p class="text-xs text-slate-400 font-mono mt-0.5">{{ $item->brand }}{{ $item->model ? ' · ' . $item->model : '' }}</p>
                @endif
            </div>
            <div class="flex items-center gap-2">
                @if($item->item_type === 'device')
                    <span class="text-[9px] font-mono font-bold uppercase tracking-wider text-violet-600 bg-violet-50 px-2 py-1 border border-violet-200">Device</span>
                @else
                    <span class="text-[9px] font-mono font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-2 py-1 border border-indigo-200">Consumable</span>
                @endif
            </div>
        </div>

        {{-- Stock Summary Cards --}}
        <div class="grid grid-cols-2 gap-3 mb-5 lg:grid-cols-5">
            {{-- New Stock --}}
            <div class="bg-white border border-slate-200 relative">
                <div class="absolute top-0 left-0 w-1 h-full bg-emerald-400"></div>
                <div class="p-4 pl-5">
                    <p class="text-[10px] font-mono font-bold text-slate-400 uppercase tracking-widest mb-1">New Stock</p>
                    <p class="text-2xl font-black font-mono text-emerald-600">{{ $item->total_stock }}</p>
                    <p class="text-[10px] font-mono text-slate-500 mt-0.5">{{ $item->unit }}</p>
                </div>
            </div>
            {{-- Used Stock --}}
            <div class="bg-white border border-slate-200 relative">
                <div class="absolute top-0 left-0 w-1 h-full bg-amber-400"></div>
                <div class="p-4 pl-5">
                    <p class="text-[10px] font-mono font-bold text-slate-400 uppercase tracking-widest mb-1">Used Stock</p>
                    <p class="text-2xl font-black font-mono text-amber-500">{{ $item->effective_stock_used }}</p>
                    <p class="text-[10px] font-mono text-slate-500 mt-0.5">{{ $item->unit }}</p>
                </div>
            </div>
            {{-- Lent Out --}}
            <div class="bg-white border border-slate-200 relative">
                <div class="absolute top-0 left-0 w-1 h-full bg-indigo-400"></div>
                <div class="p-4 pl-5">
                    <p class="text-[10px] font-mono font-bold text-slate-400 uppercase tracking-widest mb-1">Lent Out</p>
                    <p class="text-2xl font-black font-mono text-indigo-600">{{ $item->active_lent_out }}</p>
                    <p class="text-[10px] font-mono text-slate-500 mt-0.5">{{ $item->unit }}</p>
                </div>
            </div>

            {{-- Category --}}
            <div class="bg-white border border-slate-200 relative">
                <div class="absolute top-0 left-0 w-1 h-full bg-fuchsia-400"></div>
                <div class="p-4 pl-5">
                    <p class="text-[10px] font-mono font-bold text-slate-400 uppercase tracking-widest mb-1">Category</p>
                    <p class="text-sm font-bold font-mono text-fuchsia-600 truncate">{{ $item->category?->name ?? 'Uncategorized' }}</p>
                </div>
            </div>
            {{-- Status --}}
            <div class="bg-white border border-slate-200 relative">
                @php
                    $newStock  = max(0, $item->total_stock);
                    $usedStock = max(0, $item->effective_stock_used);
                    $totalQty  = $newStock + $usedStock;
                @endphp
                <div class="absolute top-0 left-0 w-1 h-full
                    @if($totalQty <= 0) bg-rose-500 @elseif($item->is_low_stock && $totalQty <= $item->reorder_level) bg-amber-400 @else bg-emerald-400 @endif"></div>
                <div class="p-4 pl-5">
                    <p class="text-[10px] font-mono font-bold text-slate-400 uppercase tracking-widest mb-1">Status</p>
                    @if($totalQty <= 0)
                        <span class="text-[10px] font-mono font-bold uppercase tracking-wider text-rose-600 bg-rose-50 px-2 py-1 border border-rose-200">Out_of_Stock</span>
                    @elseif($item->total_stock <= $item->reorder_level)
                        <span class="text-[10px] font-mono font-bold uppercase tracking-wider text-amber-600 bg-amber-50 px-2 py-1 border border-amber-200">Reorder</span>
                    @else
                        <span class="text-[10px] font-mono font-bold uppercase tracking-wider text-emerald-600 bg-emerald-50 px-2 py-1 border border-emerald-200">In_Stock</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Specs & Batch --}}
        <div class="grid grid-cols-1 gap-4 mb-4 lg:grid-cols-2">

            {{-- Item Specifications --}}
            <div class="bg-white border border-slate-200 relative">
                <div class="absolute top-0 left-0 w-1 h-full bg-sky-500"></div>
                <div class="ml-1">
                    <div class="px-4 py-3 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <span class="h-2 w-2 bg-sky-500 inline-block"></span>
                            <p class="text-[10px] font-mono font-bold text-sky-600 uppercase tracking-widest">Item Specifications</p>
                        </div>
                    </div>
                    <table class="w-full text-sm">
                        <tbody class="divide-y divide-slate-50">
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="w-36 px-4 py-2.5 text-[10px] font-mono font-bold uppercase tracking-widest text-slate-400 whitespace-nowrap">Type</td>
                                <td class="px-4 py-2.5">
                                    @if($item->item_type === 'device')
                                        <span class="text-[9px] font-mono font-bold uppercase tracking-wider text-violet-600 bg-violet-50 px-2 py-1 border border-violet-200">Device</span>
                                    @else
                                        <span class="text-[9px] font-mono font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-2 py-1 border border-indigo-200">Consumable</span>
                                    @endif
                                </td>
                            </tr>
                            @if($item->item_type === 'device')
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-2.5 text-[10px] font-mono font-bold uppercase tracking-widest text-slate-400 whitespace-nowrap">Brand</td>
                                <td class="px-4 py-2.5 text-sm font-mono font-bold text-slate-700">{{ $item->brand ?? '—' }}</td>
                            </tr>
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-2.5 text-[10px] font-mono font-bold uppercase tracking-widest text-slate-400 whitespace-nowrap">Model</td>
                                <td class="px-4 py-2.5 text-sm font-mono font-bold text-slate-700">{{ $item->model ?? '—' }}</td>
                            </tr>
                            @endif
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-2.5 text-[10px] font-mono font-bold uppercase tracking-widest text-slate-400 whitespace-nowrap">Unit</td>
                                <td class="px-4 py-2.5 text-sm font-mono text-slate-600">{{ $item->unit }}</td>
                            </tr>
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-2.5 text-[10px] font-mono font-bold uppercase tracking-widest text-slate-400 whitespace-nowrap">Location</td>
                                <td class="px-4 py-2.5 text-sm font-mono text-slate-600">
                                    @if($item->storage_location || $item->storage_section)
                                        {{ $item->storage_location ?? 'Any' }}@if($item->storage_section) · {{ $item->storage_section }}@endif
                                    @else
                                        <span class="text-slate-400">Not assigned</span>
                                    @endif
                                </td>
                            </tr>
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-2.5 text-[10px] font-mono font-bold uppercase tracking-widest text-slate-400 whitespace-nowrap">Reorder Level</td>
                                <td class="px-4 py-2.5">
                                    <span class="text-sm font-mono font-bold text-amber-600">{{ $item->reorder_level ?? 10 }}</span>
                                    <span class="text-[10px] font-mono text-slate-400 ml-1">{{ $item->unit }} threshold</span>
                                </td>
                            </tr>

                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-2.5 text-[10px] font-mono font-bold uppercase tracking-widest text-slate-400 whitespace-nowrap">Expiry Tracking</td>
                                <td class="px-4 py-2.5">
                                    @if($item->is_expirable)
                                        <span class="text-[9px] font-mono font-bold uppercase tracking-wider text-amber-600 bg-amber-50 px-2 py-1 border border-amber-200">Tracked</span>
                                    @else
                                        <span class="text-[9px] font-mono font-bold uppercase tracking-wider text-slate-400 bg-slate-50 px-2 py-1 border border-slate-200">None</span>
                                    @endif
                                </td>
                            </tr>
                            @if($item->description)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-2.5 text-[10px] font-mono font-bold uppercase tracking-widest text-slate-400 whitespace-nowrap align-top">Notes</td>
                                <td class="px-4 py-2.5 text-xs font-mono text-slate-600">{{ $item->description }}</td>
                            </tr>
                            @endif
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-2.5 text-[10px] font-mono font-bold uppercase tracking-widest text-slate-400 whitespace-nowrap">Created</td>
                                <td class="px-4 py-2.5 text-xs font-mono text-slate-500">{{ $item->created_at->format('M d, Y') }}</td>
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
            <div class="bg-white border border-slate-200 relative">
                <div class="absolute top-0 left-0 w-1 h-full bg-indigo-500"></div>
                <div class="ml-1">
                    <div class="px-4 py-3 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <span class="h-2 w-2 bg-indigo-500 inline-block"></span>
                            <p class="text-[10px] font-mono font-bold text-indigo-600 uppercase tracking-widest">Stock Overview</p>
                        </div>
                    </div>

                    <div class="px-5 py-5">

                        @if($chartTotal > 0)
                        {{-- Donut Chart --}}
                        <div class="flex items-center justify-center mb-6">
                            <div class="relative w-40 h-40">
                                <canvas id="stockDonut" width="160" height="160"></canvas>
                                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                                    <span class="text-2xl font-black font-mono text-slate-800">{{ $chartTotal }}</span>
                                    <span class="text-[9px] font-mono text-slate-400 uppercase tracking-widest">{{ $item->unit }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Legend --}}
                        <div class="grid grid-cols-3 gap-2 mb-5">
                            <div class="text-center">
                                <div class="flex items-center justify-center gap-1.5 mb-0.5">
                                    <span class="h-2 w-2 bg-emerald-400 inline-block"></span>
                                    <span class="text-[9px] font-mono font-bold text-slate-400 uppercase tracking-widest">New</span>
                                </div>
                                <p class="text-xl font-black font-mono text-emerald-600">{{ $chartNew }}</p>
                                <p class="text-[9px] font-mono text-slate-400">{{ $pctNew }}%</p>
                            </div>
                            <div class="text-center">
                                <div class="flex items-center justify-center gap-1.5 mb-0.5">
                                    <span class="h-2 w-2 bg-amber-400 inline-block"></span>
                                    <span class="text-[9px] font-mono font-bold text-slate-400 uppercase tracking-widest">Used</span>
                                </div>
                                <p class="text-xl font-black font-mono text-amber-500">{{ $chartUsed }}</p>
                                <p class="text-[9px] font-mono text-slate-400">{{ $pctUsed }}%</p>
                            </div>
                            <div class="text-center">
                                <div class="flex items-center justify-center gap-1.5 mb-0.5">
                                    <span class="h-2 w-2 bg-indigo-400 inline-block"></span>
                                    <span class="text-[9px] font-mono font-bold text-slate-400 uppercase tracking-widest">Lent</span>
                                </div>
                                <p class="text-xl font-black font-mono text-indigo-500">{{ $chartLent }}</p>
                                <p class="text-[9px] font-mono text-slate-400">{{ $pctLent }}%</p>
                            </div>
                        </div>
                        @else
                        <div class="flex flex-col items-center justify-center py-10">
                            <p class="text-[11px] font-mono text-slate-400">// No stock recorded yet</p>
                        </div>
                        @endif

                        {{-- Throughput bars --}}
                        <div class="space-y-3 border-t border-slate-100 pt-4">
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-[10px] font-mono font-bold text-slate-400 uppercase tracking-widest">Total Received</span>
                                    <span class="text-[10px] font-mono font-bold text-teal-600">{{ $totalReceived }} {{ $item->unit }}</span>
                                </div>
                                <div class="h-1.5 bg-slate-100 border border-slate-200">
                                    <div class="h-full bg-teal-400" style="width: 100%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-[10px] font-mono font-bold text-slate-400 uppercase tracking-widest">Total Used</span>
                                    <span class="text-[10px] font-mono font-bold text-rose-600">{{ $totalUsedLogs }} {{ $item->unit }}</span>
                                </div>
                                <div class="h-1.5 bg-slate-100 border border-slate-200">
                                    @php $usedPct = $totalReceived > 0 ? min(100, round(($totalUsedLogs / $totalReceived) * 100)) : 0; @endphp
                                    <div class="h-full bg-rose-400" style="width: {{ $usedPct }}%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-[10px] font-mono font-bold text-slate-400 uppercase tracking-widest">Available</span>
                                    <span class="text-[10px] font-mono font-bold text-emerald-600">{{ $chartNew }} {{ $item->unit }}</span>
                                </div>
                                <div class="h-1.5 bg-slate-100 border border-slate-200">
                                    @php $availPct = $totalReceived > 0 ? min(100, round(($chartNew / $totalReceived) * 100)) : 0; @endphp
                                    <div class="h-full bg-emerald-400" style="width: {{ $availPct }}%"></div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- History: Usage + Stock Received --}}
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">

            {{-- Usage History --}}
            <div class="bg-white border border-slate-200 relative">
                <div class="absolute top-0 left-0 w-1 h-full bg-rose-500"></div>
                <div class="ml-1">
                    <div class="px-4 py-3 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <span class="h-2 w-2 bg-rose-500 inline-block"></span>
                            <p class="text-[10px] font-mono font-bold text-rose-600 uppercase tracking-widest">Usage History</p>
                        </div>
                    </div>
                    @if($item->usageLogs->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-slate-100 bg-slate-50/50">
                                    <th class="px-4 py-2 text-left text-[10px] font-mono font-bold uppercase tracking-widest text-slate-400 whitespace-nowrap">Qty</th>
                                    <th class="px-4 py-2 text-left text-[10px] font-mono font-bold uppercase tracking-widest text-slate-400 whitespace-nowrap">Used By</th>
                                    <th class="px-4 py-2 text-left text-[10px] font-mono font-bold uppercase tracking-widest text-slate-400 whitespace-nowrap">Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($item->usageLogs as $log)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-4 py-2.5 font-black font-mono text-rose-600 whitespace-nowrap">{{ $log->quantity_used }}</td>
                                    <td class="px-4 py-2.5 text-xs font-mono text-slate-600 whitespace-nowrap">{{ $log->used_by ?? '—' }}</td>
                                    <td class="px-4 py-2.5 text-[10px] font-mono text-slate-400 whitespace-nowrap">{{ $log->used_at->format('M d, Y') }}<br><span class="text-[9px]">{{ $log->used_at->format('h:i A') }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="py-10 text-center">
                        <p class="text-[11px] font-mono text-slate-400">// No usage logged yet</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Stock Received History --}}
            <div class="bg-white border border-slate-200 relative">
                <div class="absolute top-0 left-0 w-1 h-full bg-teal-500"></div>
                <div class="ml-1">
                    <div class="px-4 py-3 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <span class="h-2 w-2 bg-teal-500 inline-block"></span>
                            <p class="text-[10px] font-mono font-bold text-teal-600 uppercase tracking-widest">Stock Received</p>
                        </div>
                    </div>
                    @if($item->stockEntries->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-slate-100 bg-slate-50/50">
                                    <th class="px-4 py-2 text-left text-[10px] font-mono font-bold uppercase tracking-widest text-slate-400 whitespace-nowrap">Qty</th>
                                    <th class="px-4 py-2 text-left text-[10px] font-mono font-bold uppercase tracking-widest text-slate-400 whitespace-nowrap">SN / Lot</th>
                                    <th class="px-4 py-2 text-left text-[10px] font-mono font-bold uppercase tracking-widest text-slate-400 whitespace-nowrap">Expiry</th>
                                    <th class="px-4 py-2 text-left text-[10px] font-mono font-bold uppercase tracking-widest text-slate-400 whitespace-nowrap">Received</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($item->stockEntries as $entry)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-4 py-2.5 font-black font-mono text-teal-600 whitespace-nowrap">{{ $entry->quantity }}</td>
                                    <td class="px-4 py-2.5 font-mono text-xs text-slate-600 whitespace-nowrap">{{ $entry->serial_number ?? ($entry->lot_number ?? '—') }}</td>
                                    <td class="px-4 py-2.5 whitespace-nowrap">
                                        @if($entry->expiry_date)
                                            @php
                                                $entryExpiry = \Carbon\Carbon::parse($entry->expiry_date)->startOfDay();
                                                $today = now()->startOfDay();
                                            @endphp
                                            @if($entryExpiry->isBefore($today))
                                                <span class="text-[9px] font-mono font-bold uppercase tracking-wider text-rose-600 bg-rose-50 px-1.5 py-0.5 border border-rose-200">EXPIRED</span>
                                            @elseif($today->diffInDays($entryExpiry) <= 30)
                                                <span class="text-[9px] font-mono font-bold uppercase tracking-wider text-amber-600 bg-amber-50 px-1.5 py-0.5 border border-amber-200">{{ $entryExpiry->format('M d') }}</span>
                                            @else
                                                <span class="text-[9px] font-mono text-slate-500">{{ $entryExpiry->format('M d, Y') }}</span>
                                            @endif
                                        @else
                                            <span class="text-slate-300 text-xs font-mono">N/A</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2.5 text-[10px] font-mono text-slate-400 whitespace-nowrap">{{ $entry->received_date->format('M d, Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="py-10 text-center">
                        <p class="text-[11px] font-mono text-slate-400">// No stock received yet</p>
                    </div>
                    @endif
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
        new Chart(canvas, {
            type: 'doughnut',
            data: {
                labels: ['New Stock', 'Used Stock', 'Lent Out'],
                datasets: [{
                    data: [chartNew, chartUsed, chartLent],
                    backgroundColor: ['#34d399', '#fbbf24', '#818cf8'],
                    borderColor:     ['#ffffff',  '#ffffff',  '#ffffff'],
                    borderWidth: 3,
                    hoverOffset: 6,
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
    </script>
@endsection