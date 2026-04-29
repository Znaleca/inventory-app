@extends('layouts.app')

@section('title', 'Record Management')

@section('content')
<div x-data="{ activeTab: '{{ $tab }}', search: '' }">

    {{-- Page Header --}}
    

    {{-- Chart Data Calculation --}}
    @php
        $totalIn = $stockEntries->sum('quantity') + $returns->sum('quantity_returned');
        $totalOut = $usageLogs->sum('quantity_used') + $borrows->sum('quantity_borrowed') + $transfers->sum('quantity') + $disposals->sum('quantity');
        
        $count = $stockEntries->count() + $usageLogs->count() + $borrows->count() + $returns->count() + $transfers->count() + $disposals->count();

        $tabs = [
            ['id' => 'stock-entries', 'label' => 'Stock Entries',  'count' => $stockEntries->count(), 'bar' => 'bg-emerald-500', 'active' => 'border-emerald-500 text-emerald-700 bg-emerald-50'],
            ['id' => 'usage-logs',   'label' => 'Usage Logs',     'count' => $usageLogs->count(),    'bar' => 'bg-rose-500',    'active' => 'border-rose-500 text-rose-700 bg-rose-50'],
            ['id' => 'borrows',      'label' => 'Borrows',        'count' => $borrows->count(),      'bar' => 'bg-blue-500',    'active' => 'border-blue-500 text-blue-700 bg-blue-50'],
            ['id' => 'returns',      'label' => 'Returns',        'count' => $returns->count(),      'bar' => 'bg-teal-500',    'active' => 'border-teal-500 text-teal-700 bg-teal-50'],
            ['id' => 'transfers',    'label' => 'Transfers',      'count' => $transfers->count(),    'bar' => 'bg-amber-500',   'active' => 'border-amber-500 text-amber-700 bg-amber-50'],
            ['id' => 'disposals',    'label' => 'Disposals',      'count' => $disposals->count(),    'bar' => 'bg-slate-600',   'active' => 'border-slate-600 text-slate-700 bg-sky-50'],
            ['id' => 'items',        'label' => 'Items',          'count' => $items->count(),        'bar' => 'bg-violet-500',  'active' => 'border-violet-500 text-violet-700 bg-violet-50'],
        ];

        // KPI Card stats - Main overview
        $logStats = [
            [
                'label' => 'Total Records', 'value' => number_format($count), 'sub' => 'All transactions', 
                'icon' => 'M9 12h3.75M9 15h3.75M9 18h3.75m3-6H9m0 0h.008v.008H9V9zm0 3h.008v.008H9V12zm0 3h.008v.008H9V15zM12 9h3.75m-3.75 3h3.75m-3.75 3h3.75M9 9h.008V9H9zm0 3h.008v.008H9V12zm0 3h.008v.008H9V15zM9 9v6m0 0v6m3-6h6m0 0h6',
                'trend_color' => 'text-sky-600', 'bgColor' => 'bg-sky-50', 'sparkline' => 'M0,20 Q10,15 20,20 T40,10 T60,15 T80,5 T100,0'
            ],
            [
                'label' => 'Units In', 'value' => number_format($totalIn), 'sub' => 'Inbound flow', 
                'icon' => 'M12 19.5v-15m0 0l-6.75 6.75M12 4.5l6.75 6.75',
                'trend_color' => 'text-emerald-600', 'bgColor' => 'bg-emerald-50', 'sparkline' => 'M0,30 L20,20 L40,25 L60,10 L80,15 L100,5'
            ],
            [
                'label' => 'Units Out', 'value' => number_format($totalOut), 'sub' => 'Outbound flow', 
                'icon' => 'M12 4.5v15m0 0l6.75-6.75M12 19.5l-6.75-6.75',
                'trend_color' => 'text-rose-600', 'bgColor' => 'bg-rose-50', 'sparkline' => 'M0,10 L20,15 L40,5 L60,20 L80,10 L100,25'
            ],
            [
                'label' => 'Net Balance', 'value' => number_format($totalIn - $totalOut), 'sub' => 'In - Out', 
                'icon' => 'M3 4.5h7.5M3 9h7.5m0 0L6 12m4.5-4.5l-4.5 3M12 6l4.5 3.75M12 6v12m0 0l-4.5-3.75M12 18l4.5-3.75',
                'trend_color' => 'text-indigo-600', 'bgColor' => 'bg-indigo-50', 'sparkline' => 'M0,15 Q15,5 30,15 T60,5 T90,15 L100,10'
            ],
        ];

        // Detailed category cards
        $categoryCards = [
            [
                'label' => 'Total Stock', 'value' => number_format($totalStock), 'sub' => 'Units received',
                'icon' => 'M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375M3.75 16.125v4.125C3.75 22.653 7.444 24.75 12 24.75s8.25-2.097 8.25-4.625v-4.125',
                'trend_color' => 'text-emerald-600', 'bgColor' => 'bg-emerald-50', 'borderColor' => 'border-emerald-200'
            ],
            [
                'label' => 'Total Usage', 'value' => number_format($totalUsage), 'sub' => 'Units consumed',
                'icon' => 'M21 12a9 9 0 11-18 0 9 9 0 0118 0zM9 9.75h6M9 12h6m-6 2.25h6',
                'trend_color' => 'text-rose-600', 'bgColor' => 'bg-rose-50', 'borderColor' => 'border-rose-200'
            ],
            [
                'label' => 'Total Borrow', 'value' => number_format($totalBorrow), 'sub' => 'Units borrowed',
                'icon' => 'M12 3v2.25m6.364.386l-1.591 1.591M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-6 0a3 3 0 11-6 0 3 3 0 016 0z',
                'trend_color' => 'text-blue-600', 'bgColor' => 'bg-blue-50', 'borderColor' => 'border-blue-200'
            ],
            [
                'label' => 'Total Return', 'value' => number_format($totalReturn), 'sub' => 'Units returned',
                'icon' => 'M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3',
                'trend_color' => 'text-teal-600', 'bgColor' => 'bg-teal-50', 'borderColor' => 'border-teal-200'
            ],
            [
                'label' => 'Total Transfer', 'value' => number_format($totalTransfer), 'sub' => 'Units transferred',
                'icon' => 'M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5a1.5 1.5 0 010 3H3m12.75-3L21 8.25m0 0L16.5 3.75M21 8.25v13.5a1.5 1.5 0 01-1.5 1.5h-13.5',
                'trend_color' => 'text-amber-600', 'bgColor' => 'bg-amber-50', 'borderColor' => 'border-amber-200'
            ],
            [
                'label' => 'Total Disposal', 'value' => number_format($totalDisposal), 'sub' => 'Units disposed',
                'icon' => 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                'trend_color' => 'text-slate-600', 'bgColor' => 'bg-slate-50', 'borderColor' => 'border-slate-200'
            ],
            [
                'label' => 'Total Items', 'value' => number_format($totalItems), 'sub' => 'Unique items',
                'icon' => 'M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375',
                'trend_color' => 'text-purple-600', 'bgColor' => 'bg-purple-50', 'borderColor' => 'border-purple-200'
            ],
        ];
    @endphp

    <div class="bg-white rounded-2xl overflow-hidden border border-slate-200 shadow-sm mb-6">
        {{-- KPI Cards Grid --}}
        <div class="grid grid-cols-1 border-b border-slate-200 sm:grid-cols-2 lg:grid-cols-4">
            @foreach($logStats as $stat)
                <div class="group relative overflow-hidden bg-white p-4 border-r border-b border-slate-100 transition-all hover:bg-slate-50 hover:shadow-inner lg:[&:nth-child(4n)]:border-r-0 sm:[&:nth-child(2n)]:border-r-0">
                    
                    {{-- Header (Label + Icon) --}}
                    <div class="flex items-center justify-between mb-3">
                        <p class="font-semibold text-xs text-slate-500 uppercase tracking-wider">{{ $stat['label'] }}</p>
                        <div class="flex h-7 w-7 items-center justify-center rounded-lg {{ $stat['bgColor'] }} {{ $stat['trend_color'] }} transition-colors group-hover:shadow-md">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $stat['icon'] }}" />
                            </svg>
                        </div>
                    </div>

                    {{-- Body (Value + Sparkline Graph) --}}
                    <div class="flex items-end justify-between">
                        <div class="z-10">
                            <p class="text-2xl font-black tracking-tight text-slate-800">{{ $stat['value'] }}</p>
                            <p class="mt-0.5 text-[10px] font-medium text-slate-400">{{ $stat['sub'] }}</p>
                        </div>
                        
                        {{-- Mini Line Graph (Sparkline) --}}
                        <div class="w-16 h-8 opacity-60 group-hover:opacity-100 transition-opacity duration-300">
                            <svg viewBox="0 0 100 30" class="w-full h-full stroke-current {{ $stat['trend_color'] }}">
                                <path d="{{ $stat['sparkline'] }}" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" vector-effect="non-scaling-stroke"/>
                            </svg>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Detailed Category Cards --}}
        <div class="grid grid-cols-1 border-slate-200 sm:grid-cols-2 lg:grid-cols-4">
            @foreach($categoryCards as $card)
                <div class="group relative overflow-hidden bg-white p-4 border-r border-b border-slate-100 transition-all hover:bg-slate-50 hover:shadow-inner lg:[&:nth-child(4n)]:border-r-0 sm:[&:nth-child(2n)]:border-r-0">
                    
                    {{-- Header (Label + Icon) --}}
                    <div class="flex items-center justify-between mb-3">
                        <p class="font-semibold text-xs text-slate-500 uppercase tracking-wider">{{ $card['label'] }}</p>
                        <div class="flex h-7 w-7 items-center justify-center rounded-lg {{ $card['bgColor'] }} {{ $card['trend_color'] }} transition-colors group-hover:shadow-md">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $card['icon'] }}" />
                            </svg>
                        </div>
                    </div>

                    {{-- Body (Value + Subtitle) --}}
                    <div class="z-10">
                        <p class="text-2xl font-black tracking-tight text-slate-800">{{ $card['value'] }}</p>
                        <p class="mt-0.5 text-[10px] font-medium text-slate-400">{{ $card['sub'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Charts Top Row --}}
    <div class="mb-8">
        {{-- 7-Day Transaction Trend Line Chart --}}
        <div class="bg-white rounded-2xl overflow-hidden border border-sky-100 shadow-sm">
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-sky-400 to-sky-600"></div>
            <div class="p-5 border-b border-sky-100 flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-semibold text-sky-600 uppercase tracking-widest mb-0.5">Timeline</p>
                    <h3 class="text-sm font-bold text-slate-800">7-Day Transaction Trend</h3>
                </div>
            </div>
            <div class="p-5">
                <div class="h-[240px]">
                    <canvas id="transactionTrendChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Bar Chart: Categories --}}
    <div class="mb-8">
        <div class="bg-white rounded-2xl overflow-hidden border border-sky-100 shadow-sm">
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-indigo-400 to-indigo-600"></div>
            <div class="p-5 border-b border-sky-100">
                <div>
                    <p class="text-[10px] font-semibold text-indigo-600 uppercase tracking-widest mb-0.5">Chart.01</p>
                    <h3 class="text-sm font-bold text-slate-800">Records Breakdown By Category</h3>
                </div>
            </div>
            <div class="p-5">
                <div class="h-[240px]">
                    <canvas id="categoryBarChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Tab Navigation --}}
    <div class="flex flex-wrap gap-1.5 mb-3">
        @foreach($tabs as $tab)
        <button @click="activeTab = '{{ $tab['id'] }}'"
            :class="activeTab === '{{ $tab['id'] }}' ? '{{ $tab['active'] }}' : 'border-slate-200 bg-white text-slate-500 hover:border-slate-300 hover:text-slate-700'"
            class="inline-flex items-center gap-2 border px-4 py-2 text-[10px] font-mono font-bold uppercase tracking-widest transition-colors">
            {{ $tab['label'] }}
            <span class="font-black">{{ $tab['count'] }}</span>
        </button>
        @endforeach
    </div>

    {{-- Search Bar --}}
    <div class="flex items-center gap-2 mb-5">
        <div class="relative flex-1 max-w-sm">
            <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/></svg>
            </span>
            <input
                id="admin-records-search"
                type="text"
                x-model="search"
                placeholder="Search by item name…"
                class="w-full border border-sky-100 bg-white pl-9 pr-4 py-2 text-xs font-mono text-slate-700 placeholder-slate-400 focus:outline-none focus:border-slate-400 transition-colors"
            />
        </div>
        <button @click="search = ''" x-show="search.length > 0" class="border border-sky-100 bg-white px-3 py-2 text-[10px] font-mono font-bold text-slate-500 hover:bg-sky-50 transition-colors">Clear</button>
    </div>

    {{-- ══ STOCK ENTRIES TAB ══ --}}
    <div x-show="activeTab === 'stock-entries'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" style="display:none;">
        <div class="bg-white rounded-2xl overflow-hidden border border-sky-100 shadow-sm">
            <div class="h-1 bg-gradient-to-r from-emerald-400 to-emerald-600"></div>
            <div class="p-5 border-b border-sky-100">
                    <div class="flex items-center gap-2 mb-1"><span class="h-2 w-2 bg-emerald-500 rounded-full inline-block"></span>
                        <p class="text-[10px] font-semibold text-emerald-600 uppercase tracking-widest">Stock Entries</p>
                    </div>
                    <p class="text-xs text-slate-500">All incoming stock records. Edit quantities, lot numbers, dates.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead><tr class="bg-sky-50/80 border-b border-sky-100">
                            <th class="px-6 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 text-left">Item</th>
                            <th class="px-6 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 text-left">Qty</th>
                            <th class="px-6 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 text-left">Lot / SN #</th>
                            <th class="px-6 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 text-left">Expiry</th>
                            <th class="px-6 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 text-left">Received</th>
                            <th class="px-6 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 text-right">Actions</th>
                        </tr></thead>
                        <tbody class="divide-y divide-sky-50">
                        @forelse($stockEntries as $entry)
                        <tr class="hover:bg-sky-50 transition-colors"
                            x-show="search === '' || '{{ strtolower($entry->item->name ?? '') }}'.includes(search.toLowerCase())">
                            <td class="px-6 py-4 font-bold text-[#0f172a]">{{ $entry->item->name ?? '—' }}</td>
                            <td class="whitespace-nowrap px-6 py-4"><span class="font-mono font-black text-slate-700">{{ $entry->quantity }}</span></td>
                            <td class="whitespace-nowrap px-6 py-4 font-mono text-xs text-slate-500">
                                @if(($entry->item->item_type ?? '') === 'device')
                                    <span class="text-[10px] text-slate-400">SN:</span> {{ $entry->serial_number ?? '—' }}
                                @else
                                    {{ $entry->lot_number ?? '—' }}
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-xs text-slate-500">{{ $entry->expiry_date?->format('M d, Y') ?? '—' }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-xs text-slate-500">{{ $entry->received_date?->format('M d, Y') ?? '—' }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.stock-entries.edit', $entry) }}" class="inline-flex items-center border border-sky-100 bg-white px-2.5 py-1.5 text-[10px] font-mono font-bold text-slate-700 hover:bg-sky-50 transition-colors">Edit</a>
                                    <form action="{{ route('admin.stock-entries.destroy', $entry) }}" method="POST" class="m-0 inline" onsubmit="return confirm('Delete this record?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="inline-flex items-center border border-rose-200 bg-rose-50 px-2.5 py-1.5 text-[10px] font-mono font-bold text-rose-600 hover:bg-rose-500 hover:text-white hover:border-rose-500 transition-colors">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="px-6 py-16 text-center">
                            <p class="font-mono text-[10px] text-slate-400 uppercase tracking-widest">// No stock entries found</p>
                        </td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    {{-- ══ USAGE LOGS TAB ══ --}}
    <div x-show="activeTab === 'usage-logs'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" style="display:none;">
        <div class="bg-white rounded-2xl overflow-hidden border border-sky-100 shadow-sm">
            <div class="h-1 bg-gradient-to-r from-rose-400 to-rose-600"></div>
            <div class="p-5 border-b border-sky-100">
                    <div class="flex items-center gap-2 mb-1"><span class="h-2 w-2 bg-rose-500 rounded-full inline-block"></span>
                        <p class="text-[10px] font-semibold text-rose-600 uppercase tracking-widest">Usage Logs</p>
                    </div>
                    <p class="text-xs text-slate-500">All item usage records. Fix quantities and dates.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead><tr class="bg-sky-50/80 border-b border-sky-100">
                            <th class="px-6 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 text-left">Item</th>
                            <th class="px-6 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 text-left">Qty Used</th>
                            <th class="px-6 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 text-left">Used By</th>
                            <th class="px-6 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 text-left">Date</th>
                            <th class="px-6 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 text-right">Actions</th>
                        </tr></thead>
                        <tbody class="divide-y divide-sky-50">
                        @forelse($usageLogs as $log)
                        <tr class="hover:bg-sky-50 transition-colors"
                            x-show="search === '' || '{{ strtolower($log->item->name ?? '') }}'.includes(search.toLowerCase())">
                            <td class="px-6 py-4 font-bold text-[#0f172a]">{{ $log->item->name ?? '—' }}</td>
                            <td class="whitespace-nowrap px-6 py-4"><span class="font-mono font-black text-rose-600">-{{ $log->quantity_used }}</span></td>
                            <td class="whitespace-nowrap px-6 py-4 text-xs text-slate-600">{{ $log->used_by ?? '—' }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-xs text-slate-500">{{ $log->used_at?->format('M d, Y') ?? '—' }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.usage-logs.edit', $log) }}" class="inline-flex items-center border border-sky-100 bg-white px-2.5 py-1.5 text-[10px] font-mono font-bold text-slate-700 hover:bg-sky-50 transition-colors">Edit</a>
                                    <form action="{{ route('admin.usage-logs.destroy', $log) }}" method="POST" class="m-0 inline" onsubmit="return confirm('Delete this record?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="inline-flex items-center border border-rose-200 bg-rose-50 px-2.5 py-1.5 text-[10px] font-mono font-bold text-rose-600 hover:bg-rose-500 hover:text-white hover:border-rose-500 transition-colors">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-6 py-16 text-center">
                            <p class="font-mono text-[10px] text-slate-400 uppercase tracking-widest">// No usage logs found</p>
                        </td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    {{-- ══ BORROWS TAB ══ --}}
    <div x-show="activeTab === 'borrows'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" style="display:none;">
        <div class="bg-white rounded-2xl overflow-hidden border border-sky-100 shadow-sm">
            <div class="h-1 bg-gradient-to-r from-sky-400 to-sky-600"></div>
            <div class="p-5 border-b border-sky-100">
                    <div class="flex items-center gap-2 mb-1"><span class="h-2 w-2 bg-blue-500 rounded-full inline-block"></span>
                        <p class="text-[10px] font-semibold text-blue-600 uppercase tracking-widest">Borrow Records</p>
                    </div>
                    <p class="text-xs text-slate-500">All borrow transactions. Adjust quantities, status, and dates.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead><tr class="bg-sky-50/80 border-b border-sky-100">
                            <th class="px-6 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 text-left">Item</th>
                            <th class="px-6 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 text-left">Borrower</th>
                            <th class="px-6 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 text-left">Borrowed</th>
                            <th class="px-6 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 text-left">Returned</th>
                            <th class="px-6 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 text-left">Used</th>
                            <th class="px-6 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 text-left">Status</th>
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Date</th>
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-right">Actions</th>
                        </tr></thead>
                        <tbody class="divide-y divide-sky-50">
                        @forelse($borrows as $borrow)
                        <tr class="hover:bg-sky-50 transition-colors"
                            x-show="search === '' || '{{ strtolower($borrow->item->name ?? '') }}'.includes(search.toLowerCase())">
                            <td class="px-6 py-4 font-bold text-[#0f172a]">{{ $borrow->item->name ?? '—' }}</td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-[#0f172a] text-xs">{{ $borrow->borrower_name ?? $borrow->staff?->display_name ?? 'Unknown' }}</div>
                                @if($borrow->department)<div class="font-mono text-[10px] text-slate-400">{{ $borrow->department }}</div>@endif
                                @if($borrow->bio_id)<div class="font-mono text-[10px] text-slate-400">Bio: {{ $borrow->bio_id }}</div>@endif
                            </td>
                            <td class="whitespace-nowrap px-6 py-4"><span class="font-mono font-black text-blue-600">{{ $borrow->quantity_borrowed }}</span></td>
                            <td class="whitespace-nowrap px-6 py-4"><span class="font-mono font-black text-teal-600">{{ $borrow->quantity_returned }}</span></td>
                            <td class="whitespace-nowrap px-6 py-4"><span class="font-mono font-black text-rose-500">{{ $borrow->quantity_used }}</span></td>
                            <td class="whitespace-nowrap px-6 py-4">
                                @if($borrow->status === 'active')
                                <span class="inline-flex items-center gap-1.5 border border-amber-200 bg-amber-50 px-2 py-0.5 text-[9px] font-mono font-bold tracking-widest uppercase text-amber-700"><span class="h-1.5 w-1.5 bg-amber-500"></span>Active</span>
                                @elseif($borrow->status === 'partial')
                                <span class="inline-flex items-center gap-1.5 border border-blue-200 bg-blue-50 px-2 py-0.5 text-[9px] font-mono font-bold tracking-widest uppercase text-blue-700"><span class="h-1.5 w-1.5 bg-blue-500"></span>Partial</span>
                                @else
                                <span class="inline-flex items-center gap-1.5 border border-emerald-200 bg-emerald-50 px-2 py-0.5 text-[9px] font-mono font-bold tracking-widest uppercase text-emerald-700"><span class="h-1.5 w-1.5 bg-emerald-500"></span>Returned</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 font-mono text-xs text-slate-500">{{ $borrow->borrowed_at?->format('M d, Y') ?? '—' }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.borrows.edit', $borrow) }}" class="inline-flex items-center border border-sky-100 bg-white px-2.5 py-1.5 text-[10px] font-mono font-bold text-slate-700 hover:bg-sky-50 transition-colors">Edit</a>
                                    <form action="{{ route('admin.borrows.destroy', $borrow) }}" method="POST" class="m-0 inline" onsubmit="return confirm('Delete this record?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="inline-flex items-center border border-rose-200 bg-rose-50 px-2.5 py-1.5 text-[10px] font-mono font-bold text-rose-600 hover:bg-rose-500 hover:text-white hover:border-rose-500 transition-colors">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="px-6 py-16 text-center">
                            <p class="font-mono text-[10px] text-slate-400 uppercase tracking-widest">// No borrow records found</p>
                        </td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    {{-- ══ RETURNS TAB ══ --}}
    <div x-show="activeTab === 'returns'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" style="display:none;">
        <div class="bg-white rounded-2xl overflow-hidden border border-sky-100 shadow-sm">
            <div class="h-1 bg-gradient-to-r from-teal-400 to-teal-600"></div>
            <div class="p-5 border-b border-sky-100">
                    <div class="flex items-center gap-2 mb-1"><span class="h-2 w-2 bg-teal-500 rounded-full inline-block"></span>
                        <p class="text-[10px] font-semibold text-teal-600 uppercase tracking-widest">Return Records</p>
                    </div>
                    <p class="text-xs text-slate-500">History of returned items. Edit quantities or dates.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead><tr class="bg-sky-50/80 border-b border-sky-100">
                            <th class="px-6 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 text-left">Returned On</th>
                            <th class="px-6 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 text-left">Staff</th>
                            <th class="px-6 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 text-left">Item</th>
                            <th class="px-6 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 text-left">Borrowed</th>
                            <th class="px-6 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 text-left">Returned / Used</th>
                            <th class="px-6 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 text-right">Actions</th>
                        </tr></thead>
                        <tbody class="divide-y divide-sky-50">
                        @forelse($returns as $returnRecord)
                        <tr class="hover:bg-sky-50 transition-colors"
                            x-show="search === '' || '{{ strtolower($returnRecord->item->name ?? '') }}'.includes(search.toLowerCase())">
                            <td class="whitespace-nowrap px-6 py-4 font-mono text-xs text-slate-500">{{ $returnRecord->returned_at?->format('M d, Y') ?? '—' }}</td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-[#0f172a] text-xs">{{ $returnRecord->borrower_name ?? $returnRecord->staff?->display_name ?? 'Unknown' }}</div>
                                @if($returnRecord->department)<div class="font-mono text-[10px] text-slate-400">{{ $returnRecord->department }}</div>@endif
                            </td>
                            <td class="px-6 py-4 font-bold text-[#0f172a]">{{ $returnRecord->item->name ?? '—' }}</td>
                            <td class="whitespace-nowrap px-6 py-4"><span class="font-mono font-black text-slate-600">{{ $returnRecord->quantity_borrowed }}</span></td>
                            <td class="whitespace-nowrap px-6 py-4 text-xs font-semibold">
                                <span class="text-teal-600 font-mono font-black">{{ $returnRecord->quantity_returned }}</span> ret /
                                <span class="text-rose-500 font-mono font-black">{{ $returnRecord->quantity_used }}</span> used
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-right">
                                <a href="{{ route('admin.borrows.edit', $returnRecord) }}" class="inline-flex items-center border border-sky-100 bg-white px-2.5 py-1.5 text-[10px] font-mono font-bold text-slate-700 hover:bg-sky-50 transition-colors">Edit</a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="px-6 py-16 text-center">
                            <p class="font-mono text-[10px] text-slate-400 uppercase tracking-widest">// No return records found</p>
                        </td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    {{-- ══ TRANSFERS TAB ══ --}}
    <div x-show="activeTab === 'transfers'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" style="display:none;">
        <div class="bg-white rounded-2xl overflow-hidden border border-sky-100 shadow-sm">
            <div class="h-1 bg-gradient-to-r from-amber-400 to-amber-600"></div>
            <div class="p-5 border-b border-sky-100">
                    <div class="flex items-center gap-2 mb-1"><span class="h-2 w-2 bg-amber-500 rounded-full inline-block"></span>
                        <p class="text-[10px] font-semibold text-amber-600 uppercase tracking-widest">Transfer Records</p>
                    </div>
                    <p class="text-xs text-slate-500">All item transfers. Fix destinations, quantities, and dates.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead><tr class="bg-sky-50/80 border-b border-sky-100">
                            <th class="px-6 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 text-left">Date</th>
                            <th class="px-6 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 text-left">Dir</th>
                            <th class="px-6 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 text-left">Item</th>
                            <th class="px-6 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 text-left">Qty</th>
                            <th class="px-6 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 text-left">Destination</th>
                            <th class="px-6 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 text-left">Party</th>
                            <th class="px-6 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 text-right">Actions</th>
                        </tr></thead>
                        <tbody class="divide-y divide-sky-50">
                        @forelse($transfers as $transfer)
                        <tr class="hover:bg-sky-50 transition-colors"
                            x-show="search === '' || '{{ strtolower($transfer->item->name ?? '') }}'.includes(search.toLowerCase())">
                            <td class="whitespace-nowrap px-6 py-4 font-mono text-xs text-slate-500">{{ $transfer->transferred_at?->format('M d, Y') ?? '—' }}</td>
                            <td class="whitespace-nowrap px-6 py-4">
                                @if($transfer->type === 'in')
                                <span class="inline-flex items-center border border-emerald-200 bg-emerald-50 px-1.5 py-0.5 text-[9px] font-mono font-bold tracking-widest uppercase text-emerald-700">↓ IN</span>
                                @else
                                <span class="inline-flex items-center border border-amber-200 bg-amber-50 px-1.5 py-0.5 text-[9px] font-mono font-bold tracking-widest uppercase text-amber-700">↑ OUT</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-bold text-[#0f172a]">{{ $transfer->item->name ?? '—' }}</td>
                            <td class="whitespace-nowrap px-6 py-4">
                                @php
                                    $prefix = $transfer->type === 'in' ? '+' : '-';
                                    $colorNew  = $transfer->type === 'in' ? 'text-emerald-600' : 'text-rose-600';
                                    $colorUsed = $transfer->type === 'in' ? 'text-emerald-500' : 'text-rose-500';
                                    $colorAll  = $transfer->type === 'in' ? 'text-emerald-600' : 'text-rose-600';
                                @endphp

                                @if(($transfer->new_quantity ?? 0) > 0)
                                <div class="font-mono text-xs {{ $colorNew }} font-black">{{ $prefix }}{{ $transfer->new_quantity }} new</div>
                                @endif
                                @if(($transfer->used_quantity ?? 0) > 0)
                                <div class="font-mono text-xs {{ $colorUsed }} font-black">{{ $prefix }}{{ $transfer->used_quantity }} used</div>
                                @endif
                                @if(($transfer->new_quantity ?? 0) == 0 && ($transfer->used_quantity ?? 0) == 0)
                                <span class="font-mono font-black {{ $colorAll }}">{{ $prefix }}{{ $transfer->quantity }}</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-xs text-slate-600">{{ $transfer->destination ?? '—' }}</td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-[#0f172a] text-xs">{{ $transfer->transferred_to ?? 'Unknown' }}</div>
                                @if($transfer->department)<div class="font-mono text-[10px] text-slate-400">{{ $transfer->department }}</div>@endif
                                @if($transfer->bio_id)<div class="font-mono text-[10px] text-slate-400">Bio: {{ $transfer->bio_id }}</div>@endif
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.transfers.edit', $transfer) }}" class="inline-flex items-center border border-sky-100 bg-white px-2.5 py-1.5 text-[10px] font-mono font-bold text-slate-700 hover:bg-sky-50 transition-colors">Edit</a>
                                    <form action="{{ route('admin.transfers.destroy', $transfer) }}" method="POST" class="m-0 inline" onsubmit="return confirm('Delete this record?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="inline-flex items-center border border-rose-200 bg-rose-50 px-2.5 py-1.5 text-[10px] font-mono font-bold text-rose-600 hover:bg-rose-500 hover:text-white hover:border-rose-500 transition-colors">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="px-6 py-16 text-center">
                            <p class="font-mono text-[10px] text-slate-400 uppercase tracking-widest">// No transfer records found</p>
                        </td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    {{-- ══ DISPOSALS TAB ══ --}}
    <div x-show="activeTab === 'disposals'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" style="display:none;">
        <div class="bg-white rounded-2xl overflow-hidden border border-sky-100 shadow-sm">
            <div class="h-1 bg-gradient-to-r from-sky-400 to-sky-600"></div>
            <div class="p-5 border-b border-sky-100">
                    <div class="flex items-center gap-2 mb-1"><span class="h-2 w-2 bg-slate-600 rounded-full inline-block"></span>
                        <p class="text-[10px] font-semibold text-slate-600 uppercase tracking-widest">Disposal Records</p>
                    </div>
                    <p class="text-xs text-slate-500">All disposed item records. Fix reasons, quantities, and dates.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead><tr class="bg-sky-50/80 border-b border-sky-100">
                            <th class="px-6 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 text-left">Item</th>
                            <th class="px-6 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 text-left">Qty</th>
                            <th class="px-6 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 text-left">Reason</th>
                            <th class="px-6 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 text-left">Disposed By</th>
                            <th class="px-6 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 text-left">Date</th>
                            <th class="px-6 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 text-right">Actions</th>
                        </tr></thead>
                        <tbody class="divide-y divide-sky-50">
                        @forelse($disposals as $disposal)
                        <tr class="hover:bg-sky-50 transition-colors"
                            x-show="search === '' || '{{ strtolower($disposal->item->name ?? '') }}'.includes(search.toLowerCase())">
                            <td class="px-6 py-4 font-bold text-[#0f172a]">{{ $disposal->item->name ?? '—' }}</td>
                            <td class="whitespace-nowrap px-6 py-4"><span class="font-mono font-black text-slate-600">{{ $disposal->quantity }}</span></td>
                            <td class="px-6 py-4 text-xs text-slate-600 max-w-xs truncate">{{ $disposal->reason ?? '—' }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-xs text-slate-600">{{ $disposal->disposed_by ?? '—' }}</td>
                            <td class="whitespace-nowrap px-6 py-4 font-mono text-xs text-slate-500">{{ $disposal->disposed_at?->format('M d, Y') ?? '—' }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.disposals.edit', $disposal) }}" class="inline-flex items-center border border-sky-100 bg-white px-2.5 py-1.5 text-[10px] font-mono font-bold text-slate-700 hover:bg-sky-50 transition-colors">Edit</a>
                                    <form action="{{ route('admin.disposals.destroy', $disposal) }}" method="POST" class="m-0 inline" onsubmit="return confirm('Delete this record?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="inline-flex items-center border border-rose-200 bg-rose-50 px-2.5 py-1.5 text-[10px] font-mono font-bold text-rose-600 hover:bg-rose-500 hover:text-white hover:border-rose-500 transition-colors">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="px-6 py-16 text-center">
                            <p class="font-mono text-[10px] text-slate-400 uppercase tracking-widest">// No disposal records found</p>
                        </td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    {{-- ══ ITEMS TAB ══ --}}
    <div x-show="activeTab === 'items'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" style="display:none;">
        <div class="bg-white rounded-2xl overflow-hidden border border-sky-100 shadow-sm">
            <div class="h-1 bg-gradient-to-r from-violet-400 to-violet-600"></div>
            <div class="p-5 border-b border-sky-100">
                    <div class="flex items-center gap-2 mb-1"><span class="h-2 w-2 bg-violet-500 rounded-full inline-block"></span>
                        <p class="text-[10px] font-semibold text-violet-600 uppercase tracking-widest">Master Item List</p>
                    </div>
                    <p class="text-xs text-slate-500">Manage or delete items directly from the database.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead><tr class="bg-sky-50/80 border-b border-sky-100">
                            <th class="px-6 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 text-left">Name</th>
                            <th class="px-6 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 text-left">Category</th>
                            <th class="px-6 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 text-left">Location</th>
                            <th class="px-6 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 text-left">Stock</th>
                            <th class="px-6 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 text-right">Actions</th>
                        </tr></thead>
                        <tbody class="divide-y divide-sky-50">
                        @forelse($items as $item)
                        <tr class="hover:bg-sky-50 transition-colors"
                            x-show="search === '' || '{{ strtolower($item->name) }}'.includes(search.toLowerCase())">
                            <td class="px-6 py-4 font-bold text-[#0f172a]">{{ $item->name }}</td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <span class="inline-flex items-center border border-sky-100 bg-slate-50 px-2 py-0.5 text-[9px] font-mono font-bold tracking-widest uppercase text-slate-600">
                                    {{ $item->category->name ?? 'Uncategorized' }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                @if($item->storage_location)
                                <span class="inline-flex items-center border border-sky-100 bg-slate-50 px-2 py-0.5 text-[9px] font-mono font-bold tracking-widest uppercase text-slate-600">
                                    {{ $item->storage_location }}{{ $item->storage_section ? ' / ' . $item->storage_section : '' }}
                                </span>
                                @else
                                <span class="text-slate-400 text-[9px] font-mono">—</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <span class="font-mono font-black text-slate-700">{{ $item->stock_quantity }}</span>
                                <span class="font-mono text-[10px] text-slate-400 ml-0.5">{{ $item->unit }}</span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('items.edit', $item) }}" class="inline-flex items-center border border-sky-100 bg-white px-2.5 py-1.5 text-[10px] font-mono font-bold text-slate-700 hover:bg-sky-50 transition-colors">Edit</a>
                                    <form action="{{ route('items.destroy', $item) }}" method="POST" class="m-0 inline"
                                        onsubmit="return confirm('Delete \'{{ addslashes($item->name) }}\' and ALL its records (stock entries, usage logs, borrows, transfers, disposals)? This cannot be undone.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="inline-flex items-center border border-rose-200 bg-rose-50 px-2.5 py-1.5 text-[10px] font-mono font-bold text-rose-600 hover:bg-rose-500 hover:text-white hover:border-rose-500 transition-colors">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-6 py-16 text-center">
                            <p class="font-mono text-[10px] text-slate-400 uppercase tracking-widest">// No items found</p>
                        </td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

</div>

{{-- Charts Script --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const createGradient = (ctx, startColor, endColor) => {
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, startColor);
        gradient.addColorStop(1, endColor);
        return gradient;
    };

    // 7-Day Transaction Trend Line Chart
    const ctxTrend = document.getElementById('transactionTrendChart');
    if (ctxTrend) {
        const trendCtx = ctxTrend.getContext('2d');
        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: [
                    @foreach($sevenDayTrend as $data)
                        '{{ $data['date'] }}',
                    @endforeach
                ],
                datasets: [
                    {
                        label: 'Units In',
                        data: [
                            @foreach($sevenDayTrend as $data)
                                {{ $data['in'] }},
                            @endforeach
                        ],
                        borderColor: '#14b8a6',
                        backgroundColor: createGradient(trendCtx, 'rgba(20, 184, 166, 0.2)', 'rgba(20, 184, 166, 0.01)'),
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#14b8a6',
                        pointBorderColor: '#fff',
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        borderWidth: 2
                    },
                    {
                        label: 'Units Out',
                        data: [
                            @foreach($sevenDayTrend as $data)
                                {{ $data['out'] }},
                            @endforeach
                        ],
                        borderColor: '#f43f5e',
                        backgroundColor: createGradient(trendCtx, 'rgba(244, 63, 94, 0.2)', 'rgba(244, 63, 94, 0.01)'),
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#f43f5e',
                        pointBorderColor: '#fff',
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        borderWidth: 2
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 15,
                            color: '#64748b',
                            font: { family: "'Plus Jakarta Sans', sans-serif", size: 11, weight: '600' }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.95)',
                        padding: 10,
                        borderRadius: 6,
                        titleColor: '#fff',
                        bodyColor: '#e2e8f0',
                        borderColor: '#475569',
                        borderWidth: 1
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        border: { display: false },
                        ticks: { color: '#64748b', font: { family: "'Plus Jakarta Sans', sans-serif", size: 11, weight: '600' } }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0, 0, 0, 0.04)' },
                        border: { display: false },
                        ticks: { color: '#94a3b8', font: { family: "'Fira Code', monospace", size: 10 } }
                    }
                }
            }
        });
    }

    // Bar Chart (Category Breakdown)
    const ctxCat = document.getElementById('categoryBarChart');
    if (ctxCat) {
        const catCtx = ctxCat.getContext('2d');
        new Chart(catCtx, {
            type: 'bar',
            data: {
                labels: ['Stock In', 'Usage', 'Borrows', 'Returns', 'Transfers', 'Disposals', 'Items'],
                datasets: [{
                    label: 'Records',
                    data: [
                        {{ $stockEntries->count() }}, 
                        {{ $usageLogs->count() }}, 
                        {{ $borrows->count() }}, 
                        {{ $returns->count() }}, 
                        {{ $transfers->count() }}, 
                        {{ $disposals->count() }},
                        {{ $items->count() }}
                    ],
                    backgroundColor: [
                        createGradient(catCtx, 'rgba(16, 185, 129, 0.8)', 'rgba(16, 185, 129, 0.1)'),
                        createGradient(catCtx, 'rgba(244, 63, 94, 0.8)', 'rgba(244, 63, 94, 0.1)'),
                        createGradient(catCtx, 'rgba(59, 130, 246, 0.8)', 'rgba(59, 130, 246, 0.1)'),
                        createGradient(catCtx, 'rgba(20, 184, 166, 0.8)', 'rgba(20, 184, 166, 0.1)'),
                        createGradient(catCtx, 'rgba(245, 158, 11, 0.8)', 'rgba(245, 158, 11, 0.1)'),
                        createGradient(catCtx, 'rgba(71, 85, 105, 0.8)', 'rgba(71, 85, 105, 0.1)'),
                        createGradient(catCtx, 'rgba(139, 92, 246, 0.8)', 'rgba(139, 92, 246, 0.1)')
                    ],
                    borderColor: ['#10b981', '#f43f5e', '#3b82f6', '#14b8a6', '#f59e0b', '#475569', '#8b5cf6'],
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