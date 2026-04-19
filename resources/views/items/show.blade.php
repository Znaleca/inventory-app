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
                @if($item->item_type === 'consumable')
                    <span class="text-[9px] font-mono font-bold uppercase tracking-wider text-rose-500 bg-rose-50 px-2 py-1 border border-rose-200">Disposable</span>
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
                    @elseif($item->is_low_stock && $totalQty <= $item->reorder_level)
                        <span class="text-[10px] font-mono font-bold uppercase tracking-wider text-amber-600 bg-amber-50 px-2 py-1 border border-amber-200">Low_Stock</span>
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
                                <td class="px-4 py-2.5 text-[10px] font-mono font-bold uppercase tracking-widest text-slate-400 whitespace-nowrap">Usage Type</td>
                                <td class="px-4 py-2.5">
                                    @if($item->item_type === 'consumable')
                                        <span class="text-[9px] font-mono font-bold uppercase tracking-wider text-rose-600 bg-rose-50 px-2 py-1 border border-rose-200">Disposable</span>
                                    @else
                                        <span class="text-[9px] font-mono font-bold uppercase tracking-wider text-emerald-600 bg-emerald-50 px-2 py-1 border border-emerald-200">Reusable</span>
                                    @endif
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

            {{-- Batch Breakdown --}}
            <div class="bg-white border border-slate-200 relative">
                <div class="absolute top-0 left-0 w-1 h-full bg-indigo-500"></div>
                <div class="ml-1">
                    <div class="px-4 py-3 border-b border-slate-100 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="h-2 w-2 bg-indigo-500 inline-block"></span>
                            <p class="text-[10px] font-mono font-bold text-indigo-600 uppercase tracking-widest">Stock Batches</p>
                        </div>
                        <span class="text-[9px] font-mono font-bold text-slate-400 uppercase tracking-widest">FIFO Tracked</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-slate-100 bg-slate-50/50">
                                    <th class="px-4 py-2 text-left text-[10px] font-mono font-bold uppercase tracking-widest text-slate-400 whitespace-nowrap">SN / Lot</th>
                                    <th class="px-4 py-2 text-left text-[10px] font-mono font-bold uppercase tracking-widest text-slate-400 whitespace-nowrap">Expiry</th>
                                    <th class="px-4 py-2 text-left text-[10px] font-mono font-bold uppercase tracking-widest text-slate-400 whitespace-nowrap">Received</th>
                                    <th class="px-4 py-2 text-right text-[10px] font-mono font-bold uppercase tracking-widest text-slate-400 whitespace-nowrap">Qty</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($item->batches_breakdown as $batch)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-4 py-2.5 font-mono text-xs font-bold text-slate-700 whitespace-nowrap">
                                        {{ $batch['serial_number'] ?? ($batch['lot_number'] ?? '—') }}
                                    </td>
                                    <td class="px-4 py-2.5 whitespace-nowrap">
                                        @if($batch['expiry_date'])
                                            @php
                                                $expiry = \Carbon\Carbon::parse($batch['expiry_date'])->startOfDay();
                                                $today = now()->startOfDay();
                                            @endphp
                                            @if($expiry->isBefore($today))
                                                <span class="text-[9px] font-mono font-bold uppercase tracking-wider text-rose-600 bg-rose-50 px-1.5 py-0.5 border border-rose-200">EXPIRED</span>
                                            @elseif($today->diffInDays($expiry) <= 30)
                                                <span class="text-[9px] font-mono font-bold uppercase tracking-wider text-amber-600 bg-amber-50 px-1.5 py-0.5 border border-amber-200">{{ $expiry->format('M d') }}</span>
                                            @else
                                                <span class="text-[9px] font-mono text-slate-500">{{ $expiry->format('M d, Y') }}</span>
                                            @endif
                                        @else
                                            <span class="text-slate-300 font-mono text-xs">—</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2.5 text-xs font-mono text-slate-500 whitespace-nowrap">{{ \Carbon\Carbon::parse($batch['received_date'])->format('M d, Y') }}</td>
                                    <td class="px-4 py-2.5 text-right whitespace-nowrap">
                                        <span class="text-sm font-black font-mono text-slate-800">{{ $batch['remaining'] }}</span>
                                        <span class="text-[9px] font-mono text-slate-400 ml-1">{{ $item->unit }}</span>
                                    </td>
                                </tr>
                                @endforeach
                                @if($item->stock_used > 0)
                                <tr class="bg-amber-50/50 border-t-2 border-dashed border-amber-100">
                                    <td colspan="3" class="px-4 py-2.5 text-[10px] font-mono font-bold text-amber-700 whitespace-nowrap uppercase tracking-widest">Accumulated Used Stock</td>
                                    <td class="px-4 py-2.5 text-right whitespace-nowrap">
                                        <span class="text-sm font-black font-mono text-amber-600">{{ $item->stock_used }}</span>
                                        <span class="text-[9px] font-mono text-amber-400 ml-1">{{ $item->unit }}</span>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
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
@endsection