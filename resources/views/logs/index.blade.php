@extends('layouts.app')

@section('title', 'Transaction History')

@section('content')

@php
    $totalIn  = $transactions->where('type', 'in')->sum('quantity');
    $totalOut = $transactions->where('type', 'out')->sum('quantity');
    $count    = $transactions->count();

    $catCounts = [
        'Stock Entries' => $rawStockEntries->count(),
        'Usage Logs' => $rawUsageLogs->count(),
        'Borrows' => $rawBorrows->count(),
        'Returns' => $rawReturns->count(),
        'Transfers' => $rawTransfers->count(),
        'Disposals' => $rawDisposals->count(),
        'Items' => $items->count(),
    ];
@endphp

<div x-data="{ activeTab: 'stock-entries', search: '' }">

    {{-- Page Header --}}
    <div class="mb-5 flex items-end justify-between">
        <div>
            <p class="text-[10px] font-mono font-semibold text-slate-500 uppercase tracking-[0.25em] mb-1">System://Logs</p>
            <h1 class="text-xl font-bold text-slate-800 tracking-tight">Transaction History</h1>
            <p class="text-xs text-slate-400 font-mono mt-0.5">Read-only view of all inward and outward movements.</p>
        </div>
        <span class="inline-flex items-center border border-slate-200 bg-white px-3 py-1 text-[10px] font-mono font-bold text-slate-600 tracking-widest uppercase shrink-0">
            {{ $count }} records
        </span>
    </div>

    {{-- Filter Bar --}}
    <div class="paper-box mb-6 z-20 relative">
        <div class="paper-box-top"></div>
        <div class="paper-box-accent" style="background: linear-gradient(90deg, #3b82f6, #6366f1);"></div>
        <div class="relative z-10 bg-white rounded-lg py-4 px-5">
            <form method="GET" action="{{ route('logs.index') }}" class="flex flex-wrap items-end gap-3">
                <div class="flex-1 min-w-[200px]" x-data="{
                    init() {
                        if (typeof TomSelect !== 'undefined') {
                            new TomSelect(this.$refs.itemSelect, {
                                create: false,
                                sortField: { field: 'text', direction: 'asc' },
                                placeholder: 'Search for an item...',
                            });
                        }
                    }
                }">
                    <label class="block text-[10px] font-mono font-bold text-slate-400 uppercase tracking-widest mb-1.5">Item Filter</label>
                    <select x-ref="itemSelect" name="item" class="block w-full border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:outline-none py-2 px-3 text-xs text-slate-800 font-mono transition-colors" onchange="this.form.submit()">
                        <option value="">All Items</option>
                        @foreach($items as $item)
                        <option value="{{ $item->id }}" {{ request('item') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-mono font-bold text-slate-400 uppercase tracking-widest mb-1.5">From</label>
                    <input type="date" name="from" value="{{ request('from') }}" class="block border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:outline-none py-2 px-3 text-xs text-slate-800 font-mono transition-colors">
                </div>
                <div>
                    <label class="block text-[10px] font-mono font-bold text-slate-400 uppercase tracking-widest mb-1.5">To</label>
                    <input type="date" name="to" value="{{ request('to') }}" class="block border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:outline-none py-2 px-3 text-xs text-slate-800 font-mono transition-colors">
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="inline-flex items-center gap-2 bg-slate-900 px-5 py-2 text-[10px] font-mono font-bold text-white uppercase tracking-widest hover:bg-slate-800 transition-colors border border-slate-900">
                        Apply
                    </button>
                    @if(request('item') || request('type') || request('from') || request('to'))
                    <a href="{{ route('logs.index') }}" class="inline-flex items-center gap-2 border border-slate-200 bg-slate-50 px-5 py-2 text-[10px] font-mono font-bold text-slate-600 uppercase tracking-widest hover:bg-slate-100 transition-colors">
                        Clear
                    </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Charts Top Row --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-5 mb-8">
        {{-- Doughnut Chart: Flow Types --}}
        <div class="paper-box mt-2">
            <div class="paper-box-top"></div>
            <div class="paper-box-accent" style="background: linear-gradient(90deg, #10b981, #3b82f6);"></div>
            <div class="relative z-10 bg-white rounded-lg overflow-hidden flex flex-col h-full">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-mono text-blue-600 uppercase tracking-widest mb-0.5">Chart.01</p>
                        <p class="text-sm font-bold text-slate-800">Directional Flow Volume</p>
                    </div>
                </div>
                <div class="p-5 flex-1 relative">
                    <div class="h-[220px]">
                        <canvas id="flowDoughnutChart"></canvas>
                    </div>
                    <div class="mt-4 flex flex-col items-center justify-center border-t border-slate-100 pt-3">
                        <span class="text-[10px] font-mono text-slate-400">Total Recorded Units</span>
                        <span class="text-2xl font-black text-slate-700 leading-tight">{{ number_format($totalIn + $totalOut) }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bar Chart: Categories --}}
        <div class="paper-box mt-2">
            <div class="paper-box-top"></div>
            <div class="paper-box-accent" style="background: linear-gradient(90deg, #f59e0b, #ec4899);"></div>
            <div class="relative z-10 bg-white rounded-lg overflow-hidden flex flex-col h-full">
                <div class="px-5 py-4 border-b border-slate-100">
                    <p class="text-[10px] font-mono text-rose-500 uppercase tracking-widest mb-0.5">Chart.02</p>
                    <p class="text-sm font-bold text-slate-800">Records Breakdown By Category</p>
                </div>
                <div class="p-5 flex-1">
                    <div class="h-[220px]">
                        <canvas id="categoryBarChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- Tab Navigation --}}
    @php
        $tabs = [
            ['id' => 'stock-entries', 'label' => 'Stock Entries',  'count' => $catCounts['Stock Entries'], 'active' => 'border-emerald-500 text-emerald-700 bg-emerald-50'],
            ['id' => 'usage-logs',   'label' => 'Usage Logs',     'count' => $catCounts['Usage Logs'],    'active' => 'border-rose-500 text-rose-700 bg-rose-50'],
            ['id' => 'borrows',      'label' => 'Borrows',        'count' => $catCounts['Borrows'],      'active' => 'border-blue-500 text-blue-700 bg-blue-50'],
            ['id' => 'returns',      'label' => 'Returns',        'count' => $catCounts['Returns'],      'active' => 'border-teal-500 text-teal-700 bg-teal-50'],
            ['id' => 'transfers',    'label' => 'Transfers',      'count' => $catCounts['Transfers'],    'active' => 'border-amber-500 text-amber-700 bg-amber-50'],
            ['id' => 'disposals',    'label' => 'Disposals',      'count' => $catCounts['Disposals'],    'active' => 'border-slate-600 text-slate-700 bg-slate-100'],
            ['id' => 'items',        'label' => 'Items',          'count' => $catCounts['Items'],        'active' => 'border-violet-500 text-violet-700 bg-violet-50'],
        ];
    @endphp
    
    <div class="flex items-center justify-between mb-3 border-b border-slate-200 pb-2">
        <div class="flex flex-wrap gap-1.5 ">
            @foreach($tabs as $tab)
            <button @click="activeTab = '{{ $tab['id'] }}'"
                :class="activeTab === '{{ $tab['id'] }}' ? '{{ $tab['active'] }}' : 'bg-white text-slate-500 hover:bg-slate-50'"
                class="inline-flex items-center gap-2 border border-slate-200 px-4 py-2 text-[10px] font-mono font-bold uppercase tracking-widest transition-colors rounded-t-lg">
                {{ $tab['label'] }}
                <span class="font-black">{{ $tab['count'] }}</span>
            </button>
            @endforeach
        </div>
        
        {{-- Search (Local tab filtering) --}}
        <div class="relative w-64 hidden md:block">
            <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/></svg>
            </span>
            <input type="text" x-model="search" placeholder="Search item in tab..." class="w-full border border-slate-200 bg-white pl-9 pr-4 py-1.5 text-xs font-mono text-slate-700 placeholder-slate-400 focus:outline-none focus:border-slate-400 transition-colors">
        </div>
    </div>


    {{-- ══ STOCK ENTRIES TAB ══ --}}
    <div x-show="activeTab === 'stock-entries'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" style="display:none;" class="paper-box mt-0">
        <div class="relative z-10 bg-white rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <span class="h-2 w-2 bg-emerald-500 inline-block font-mono"></span>
                    <p class="text-[10px] font-mono font-bold text-emerald-600 uppercase tracking-widest">Stock Entries</p>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead><tr class="bg-slate-50/50 border-b border-slate-200">
                        <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Item</th>
                        <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Qty</th>
                        <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Lot / SN #</th>
                        <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Expiry</th>
                        <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Received</th>
                        <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Notes</th>
                    </tr></thead>
                    <tbody class="divide-y divide-slate-100">
                    @forelse($rawStockEntries as $entry)
                    <tr class="hover:bg-slate-50 transition-colors" x-show="search === '' || '{{ strtolower($entry->item->name ?? '') }}'.includes(search.toLowerCase())">
                        <td class="px-6 py-4 font-bold text-slate-800">{{ $entry->item->name ?? '—' }}</td>
                        <td class="whitespace-nowrap px-6 py-4"><span class="font-mono font-black text-emerald-600">+{{ $entry->quantity }}</span></td>
                        <td class="whitespace-nowrap px-6 py-4 font-mono text-xs text-slate-500">
                            @if(($entry->item->item_type ?? '') === 'device')
                                <span class="text-[10px] text-slate-400">SN:</span> {{ $entry->serial_number ?? '—' }}
                            @else
                                {{ $entry->lot_number ?? '—' }}
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-xs text-slate-500">{{ $entry->expiry_date?->format('M d, Y') ?? '—' }}</td>
                        <td class="whitespace-nowrap px-6 py-4 text-xs text-slate-500">{{ $entry->received_date?->format('M d, Y') ?? '—' }}</td>
                        <td class="px-6 py-4 text-xs text-slate-500 max-w-[200px] truncate" title="{{ $entry->notes }}">{{ $entry->notes ?: '—' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-6 py-12 text-center"><p class="font-mono text-[10px] text-slate-400 uppercase tracking-widest">// No stock entries found</p></td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ══ USAGE LOGS TAB ══ --}}
    <div x-show="activeTab === 'usage-logs'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" style="display:none;" class="paper-box mt-0">
        <div class="relative z-10 bg-white rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <span class="h-2 w-2 bg-rose-500 inline-block font-mono"></span>
                    <p class="text-[10px] font-mono font-bold text-rose-600 uppercase tracking-widest">Usage Logs</p>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead><tr class="bg-slate-50/50 border-b border-slate-200">
                        <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Item</th>
                        <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Qty Used</th>
                        <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Used By</th>
                        <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Date</th>
                        <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Notes</th>
                    </tr></thead>
                    <tbody class="divide-y divide-slate-100">
                    @forelse($rawUsageLogs as $log)
                    <tr class="hover:bg-slate-50 transition-colors" x-show="search === '' || '{{ strtolower($log->item->name ?? '') }}'.includes(search.toLowerCase())">
                        <td class="px-6 py-4 font-bold text-slate-800">{{ $log->item->name ?? '—' }}</td>
                        <td class="whitespace-nowrap px-6 py-4"><span class="font-mono font-black text-rose-600">-{{ $log->quantity_used }}</span></td>
                        <td class="whitespace-nowrap px-6 py-4 text-xs text-slate-600">{{ $log->used_by ?? '—' }}</td>
                        <td class="whitespace-nowrap px-6 py-4 text-xs text-slate-500">{{ $log->used_at?->format('M d, Y') ?? '—' }}</td>
                        <td class="px-6 py-4 text-xs text-slate-500 max-w-[200px] truncate" title="{{ $log->notes }}">{{ $log->notes ?: '—' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-12 text-center"><p class="font-mono text-[10px] text-slate-400 uppercase tracking-widest">// No usage logs found</p></td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ══ BORROWS TAB ══ --}}
    <div x-show="activeTab === 'borrows'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" style="display:none;" class="paper-box mt-0">
        <div class="relative z-10 bg-white rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <span class="h-2 w-2 bg-blue-500 inline-block font-mono"></span>
                    <p class="text-[10px] font-mono font-bold text-blue-600 uppercase tracking-widest">Borrow Records</p>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead><tr class="bg-slate-50/50 border-b border-slate-200">
                        <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Item</th>
                        <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Borrower</th>
                        <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Borrowed / Used</th>
                        <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Status</th>
                        <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Date</th>
                        <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Notes</th>
                    </tr></thead>
                    <tbody class="divide-y divide-slate-100">
                    @forelse($rawBorrows as $borrow)
                    <tr class="hover:bg-slate-50 transition-colors" x-show="search === '' || '{{ strtolower($borrow->item->name ?? '') }}'.includes(search.toLowerCase())">
                        <td class="px-6 py-4 font-bold text-slate-800">{{ $borrow->item->name ?? '—' }}</td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-800 text-xs">{{ $borrow->borrower_name ?? $borrow->staff?->display_name ?? 'Unknown' }}</div>
                            @if($borrow->department)<div class="font-mono text-[10px] text-slate-400">{{ $borrow->department }}</div>@endif
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <span class="font-mono font-black text-blue-600" title="Borrowed">{{ $borrow->quantity_borrowed }}</span> / 
                            <span class="font-mono font-black text-rose-500" title="Used">{{ $borrow->quantity_used }}</span>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            @if($borrow->status === 'active')
                            <span class="inline-flex items-center gap-1.5 border border-amber-200 bg-amber-50 px-2 py-0.5 text-[9px] font-mono font-bold tracking-widest uppercase text-amber-700">Active</span>
                            @elseif($borrow->status === 'partial')
                            <span class="inline-flex items-center gap-1.5 border border-blue-200 bg-blue-50 px-2 py-0.5 text-[9px] font-mono font-bold tracking-widest uppercase text-blue-700">Partial</span>
                            @else
                            <span class="inline-flex items-center gap-1.5 border border-emerald-200 bg-emerald-50 px-2 py-0.5 text-[9px] font-mono font-bold tracking-widest uppercase text-emerald-700">Returned</span>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 font-mono text-xs text-slate-500">{{ $borrow->borrowed_at?->format('M d, Y') ?? '—' }}</td>
                        <td class="px-6 py-4 text-xs text-slate-500 max-w-[200px] truncate" title="{{ $borrow->notes }}">{{ $borrow->notes ?: '—' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-6 py-12 text-center"><p class="font-mono text-[10px] text-slate-400 uppercase tracking-widest">// No borrow records found</p></td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ══ RETURNS TAB ══ --}}
    <div x-show="activeTab === 'returns'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" style="display:none;" class="paper-box mt-0">
        <div class="relative z-10 bg-white rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <span class="h-2 w-2 bg-teal-500 inline-block font-mono"></span>
                    <p class="text-[10px] font-mono font-bold text-teal-600 uppercase tracking-widest">Return Records</p>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead><tr class="bg-slate-50/50 border-b border-slate-200">
                        <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Returned On</th>
                        <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Staff</th>
                        <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Item</th>
                        <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Returned / Used</th>
                        <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Notes</th>
                    </tr></thead>
                    <tbody class="divide-y divide-slate-100">
                    @forelse($rawReturns as $return)
                    <tr class="hover:bg-slate-50 transition-colors" x-show="search === '' || '{{ strtolower($return->item->name ?? '') }}'.includes(search.toLowerCase())">
                        <td class="whitespace-nowrap px-6 py-4 text-xs font-bold text-slate-700">{{ $return->returned_at?->format('M d, Y') ?? '—' }}</td>
                        <td class="px-6 py-4 text-xs font-semibold text-slate-700">{{ $return->borrower_name ?? $return->staff?->display_name ?? 'Unknown' }}</td>
                        <td class="px-6 py-4 font-bold text-slate-800">{{ $return->item->name ?? '—' }}</td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <span class="font-mono font-black text-teal-600">+{{ $return->quantity_returned }}</span> / 
                            <span class="font-mono font-black text-rose-500">{{ $return->quantity_used }}</span>
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-500 max-w-[200px] truncate" title="{{ $return->notes }}">{{ $return->notes ?: '—' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-12 text-center"><p class="font-mono text-[10px] text-slate-400 uppercase tracking-widest">// No return records found</p></td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    {{-- ══ TRANSFERS TAB ══ --}}
    <div x-show="activeTab === 'transfers'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" style="display:none;" class="paper-box mt-0">
        <div class="relative z-10 bg-white rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <span class="h-2 w-2 bg-amber-500 inline-block font-mono"></span>
                    <p class="text-[10px] font-mono font-bold text-amber-600 uppercase tracking-widest">Transfer Records</p>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead><tr class="bg-slate-50/50 border-b border-slate-200">
                        <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Item</th>
                        <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Qty</th>
                        <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Destination</th>
                        <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">By / BioID</th>
                        <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Date</th>
                        <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Notes</th>
                    </tr></thead>
                    <tbody class="divide-y divide-slate-100">
                    @forelse($rawTransfers as $transfer)
                    <tr class="hover:bg-slate-50 transition-colors" x-show="search === '' || '{{ strtolower($transfer->item->name ?? '') }}'.includes(search.toLowerCase())">
                        <td class="px-6 py-4 font-bold text-slate-800">{{ $transfer->item->name ?? '—' }}</td>
                        <td class="whitespace-nowrap px-6 py-4"><span class="font-mono font-black text-amber-600">{{ $transfer->quantity }}</span></td>
                        <td class="px-6 py-4 text-xs font-semibold text-slate-700">{{ $transfer->destination }}</td>
                        <td class="px-6 py-4 text-xs text-slate-600">{{ $transfer->transferred_by ?? '—' }} @if($transfer->bio_id)<div class="font-mono text-[10px] text-slate-400 mt-0.5">Bio: {{ $transfer->bio_id }}</div>@endif</td>
                        <td class="whitespace-nowrap px-6 py-4 text-xs text-slate-500">{{ $transfer->transferred_at?->format('M d, Y') ?? '—' }}</td>
                        <td class="px-6 py-4 text-xs text-slate-500 max-w-[200px] truncate" title="{{ $transfer->notes }}">{{ $transfer->notes ?: '—' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-6 py-12 text-center"><p class="font-mono text-[10px] text-slate-400 uppercase tracking-widest">// No transfer records found</p></td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    {{-- ══ DISPOSALS TAB ══ --}}
    <div x-show="activeTab === 'disposals'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" style="display:none;" class="paper-box mt-0">
        <div class="relative z-10 bg-white rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <span class="h-2 w-2 bg-slate-600 inline-block font-mono"></span>
                    <p class="text-[10px] font-mono font-bold text-slate-700 uppercase tracking-widest">Disposal Records</p>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead><tr class="bg-slate-50/50 border-b border-slate-200">
                        <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Item</th>
                        <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Qty</th>
                        <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Reason</th>
                        <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Disposed By</th>
                        <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Date</th>
                    </tr></thead>
                    <tbody class="divide-y divide-slate-100">
                    @forelse($rawDisposals as $disposal)
                    <tr class="hover:bg-slate-50 transition-colors" x-show="search === '' || '{{ strtolower($disposal->item->name ?? '') }}'.includes(search.toLowerCase())">
                        <td class="px-6 py-4 font-bold text-slate-800">{{ $disposal->item->name ?? '—' }}</td>
                        <td class="whitespace-nowrap px-6 py-4"><span class="font-mono font-black text-rose-600">{{ $disposal->quantity }}</span></td>
                        <td class="px-6 py-4 text-xs text-slate-700">{{ $disposal->reason }}</td>
                        <td class="whitespace-nowrap px-6 py-4 text-xs text-slate-600">{{ $disposal->disposed_by ?? '—' }}</td>
                        <td class="whitespace-nowrap px-6 py-4 text-xs text-slate-500">{{ $disposal->disposed_at?->format('M d, Y') ?? '—' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-12 text-center"><p class="font-mono text-[10px] text-slate-400 uppercase tracking-widest">// No disposal records found</p></td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    {{-- ══ ITEMS TAB ══ --}}
    <div x-show="activeTab === 'items'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" style="display:none;" class="paper-box mt-0">
        <div class="relative z-10 bg-white rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <span class="h-2 w-2 bg-violet-500 inline-block font-mono"></span>
                    <p class="text-[10px] font-mono font-bold text-violet-600 uppercase tracking-widest">Master Item List</p>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead><tr class="bg-slate-50/50 border-b border-slate-200">
                        <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Name</th>
                        <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Category</th>
                        <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Stock</th>
                    </tr></thead>
                    <tbody class="divide-y divide-slate-100">
                    @forelse($items as $item)
                    <tr class="hover:bg-slate-50 transition-colors" x-show="search === '' || '{{ strtolower($item->name) }}'.includes(search.toLowerCase())">
                        <td class="px-6 py-4 font-bold text-slate-800">{{ $item->name }}</td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <span class="inline-flex items-center border border-slate-200 bg-slate-50 px-2 py-0.5 text-[9px] font-mono font-bold tracking-widest uppercase text-slate-600">
                                {{ $item->category->name ?? 'Uncategorized' }}
                            </span>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <span class="font-mono font-black text-slate-700">{{ $item->stock_quantity }}</span>
                            <span class="font-mono text-[10px] text-slate-400 ml-0.5">{{ $item->unit }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="px-6 py-12 text-center"><p class="font-mono text-[10px] text-slate-400 uppercase tracking-widest">// No items found</p></td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>


</div>


{{-- Charts --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Doughnut Chart (In vs Out)
    const ctxFlow = document.getElementById('flowDoughnutChart');
    if (ctxFlow) {
        new Chart(ctxFlow.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Units In', 'Units Out'],
                datasets: [{
                    data: [{{ $totalIn }}, {{ $totalOut }}],
                    backgroundColor: ['#10b981', '#f43f5e'],
                    borderWidth: 4,
                    borderColor: '#ffffff',
                    hoverOffset: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '76%',
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'rectRot',
                            color: '#64748b',
                            font: { family: "'Plus Jakarta Sans', sans-serif", size: 11, weight: '600' }
                        }
                    }
                }
            }
        });
    }

    // Bar Chart (Category Breakdown)
    const ctxCat = document.getElementById('categoryBarChart');
    if (ctxCat) {
        new Chart(ctxCat.getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['Stock In', 'Usage', 'Borrows', 'Returns', 'Transfers', 'Disposals', 'Items'],
                datasets: [{
                    label: 'Records',
                    data: [
                        {{ $catCounts['Stock Entries'] }}, 
                        {{ $catCounts['Usage Logs'] }}, 
                        {{ $catCounts['Borrows'] }}, 
                        {{ $catCounts['Returns'] }}, 
                        {{ $catCounts['Transfers'] }}, 
                        {{ $catCounts['Disposals'] }},
                        {{ $catCounts['Items'] }}
                    ],
                    backgroundColor: ['#10b981', '#f43f5e', '#3b82f6', '#14b8a6', '#f59e0b', '#475569', '#8b5cf6'],
                    borderRadius: 4,
                    borderSkipped: false,
                    barThickness: 28,
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
                        border: { display: false, dash: [4,4] },
                        ticks: { color: '#94a3b8', font: { family: "'Fira Code', monospace", size: 10 } }
                    },
                    x: {
                        grid: { display: false },
                        border: { display: false },
                        ticks: { color: '#64748b', font: { family: "'Plus Jakarta Sans', sans-serif", size: 11, weight: '600' } }
                    }
                }
            }
        });
    }
});
</script>

@endsection