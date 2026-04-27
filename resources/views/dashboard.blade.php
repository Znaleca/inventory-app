@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="bg-white rounded-2xl overflow-hidden border border-slate-200 shadow-sm">
    {{-- KPI Cards Grid --}}
    @php
        // Added 'sparkline' paths and 'trend_color' for the mini line graphs
        $stats = [
            [
                'label' => 'Total Items', 'value' => number_format($totalItems), 'sub' => 'Master records', 
                'icon' => 'M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z',
                'trend_color' => 'text-sky-500', 'sparkline' => 'M0,20 Q10,15 20,20 T40,10 T60,15 T80,5 T100,0'
            ],
            [
                'label' => 'Low Stock', 'value' => number_format($lowStockCount), 'sub' => 'Needs reordering', 
                'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
                'trend_color' => 'text-amber-500', 'sparkline' => 'M0,5 Q10,10 20,5 T40,15 T60,10 T80,25 T100,20'
            ],
            [
                'label' => 'Expiring 30d', 'value' => number_format($expiringItems->count()), 'sub' => 'Due soon', 
                'icon' => 'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z',
                'trend_color' => 'text-rose-500', 'sparkline' => 'M0,15 L20,25 L40,15 L60,20 L80,5 L100,10'
            ],
            [
                'label' => 'Need Disposal', 'value' => number_format($expiredCount), 'sub' => 'Expired batches', 
                'icon' => 'M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0',
                'trend_color' => 'text-slate-400', 'sparkline' => 'M0,25 L20,20 L40,25 L60,15 L80,20 L100,25'
            ],
            [
                'label' => 'New Stock', 'value' => number_format($totalNewStock), 'sub' => 'In stock', 
                'icon' => 'M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3',
                'trend_color' => 'text-emerald-500', 'sparkline' => 'M0,30 L20,20 L40,25 L60,10 L80,15 L100,5'
            ],
            [
                'label' => 'Used', 'value' => number_format($totalUsedStock), 'sub' => 'Out stock', 
                'icon' => 'M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5',
                'trend_color' => 'text-indigo-500', 'sparkline' => 'M0,10 L20,15 L40,5 L60,20 L80,10 L100,25'
            ],
            [
                'label' => 'Borrowed', 'value' => number_format($totalBorrowedCount), 'sub' => 'On hand externally', 
                'icon' => 'M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z',
                'trend_color' => 'text-blue-500', 'sparkline' => 'M0,20 Q15,5 30,15 T60,5 T90,15 L100,10'
            ],
            [
                'label' => 'To Return', 'value' => number_format($pendingReturnsCount), 'sub' => 'Pending return', 
                'icon' => 'M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3',
                'trend_color' => 'text-violet-500', 'sparkline' => 'M0,5 Q20,25 40,15 T80,25 L100,15'
            ],
        ];
    @endphp
    
    <div class="grid grid-cols-1 border-b border-slate-200 sm:grid-cols-2 lg:grid-cols-4">
        @foreach($stats as $stat)
            <div class="group relative overflow-hidden bg-white p-4 border-r border-b border-slate-100 transition-all hover:bg-slate-50 hover:shadow-inner lg:[&:nth-child(4n)]:border-r-0 sm:[&:nth-child(2n)]:border-r-0">
                
                {{-- Header (Label + Icon) --}}
                <div class="flex items-center justify-between mb-3">
                    <p class="font-semibold text-xs text-slate-500 uppercase tracking-wider">{{ $stat['label'] }}</p>
                    <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-slate-50 text-slate-400 transition-colors group-hover:bg-sky-100 group-hover:text-sky-600">
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

    {{-- Main Panels (Charts & Feed) --}}
    <div class="grid grid-cols-1 xl:grid-cols-12 bg-slate-50/30">
        
        {{-- Left Side: Charts --}}
        <div class="border-r border-slate-200 xl:col-span-8 bg-white">
            
            {{-- Top Row Charts Grid (Doughnut & Bar) --}}
            <div class="grid grid-cols-1 lg:grid-cols-2">
                {{-- Doughnut Chart --}}
                <div class="p-5 border-b border-r border-slate-100 flex flex-col">
                    <div class="mb-4 flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-semibold uppercase tracking-widest text-sky-600">Distribution</p>
                            <h3 class="text-sm font-bold text-slate-800">Stock Categories</h3>
                        </div>
                        <span class="relative flex h-2 w-2"><span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-sky-400 opacity-75"></span><span class="relative inline-flex h-2 w-2 rounded-full bg-sky-500"></span></span>
                    </div>
                    <div class="flex-1 min-h-[260px] relative">
                        <canvas id="inventoryStatusChart"></canvas>
                    </div>
                </div>
                
                {{-- Bar Chart --}}
                <div class="p-5 border-b border-slate-100 flex flex-col">
                    <div class="mb-4">
                        <p class="text-[10px] font-semibold uppercase tracking-widest text-sky-600">Metrics</p>
                        <h3 class="text-sm font-bold text-slate-800">Stock Health Breakdown</h3>
                    </div>
                    <div class="flex-1 min-h-[260px] relative">
                        <canvas id="inventoryHealthChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Full Width Timeline Chart --}}
            <div class="p-5">
                <div class="mb-4 flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-semibold uppercase tracking-widest text-sky-600">Timeline</p>
                        <h3 class="text-sm font-bold text-slate-800">7-Day Movement Trend</h3>
                    </div>
                </div>
                <div class="h-[240px] w-full">
                    <canvas id="inventoryTrendChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Right Side: Operational Alerts --}}
        <div class="p-5 xl:col-span-4 flex flex-col bg-white">
            <div class="mb-5 flex items-center justify-between border-b border-slate-100 pb-3 shrink-0">
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-widest text-sky-600">Activity Feed</p>
                    <h3 class="text-sm font-bold text-slate-800">Operational Alerts</h3>
                </div>
            </div>

            <div class="space-y-6 flex-1 pr-1">
                {{-- Low Stock Module --}}
                <div>
                    <div class="mb-2.5 flex items-center justify-between">
                        <h4 class="text-[11px] font-bold text-slate-600 uppercase tracking-wider flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Low Stock
                        </h4>
                        <a href="{{ route('items.index') }}" class="text-[10px] font-bold text-sky-600 hover:text-sky-800">View All</a>
                    </div>
                    @if($lowStockItems->count() > 0)
                        <div class="space-y-2">
                            @foreach($lowStockItems->take(3) as $item)
                                <div class="flex items-center justify-between rounded-lg border border-slate-100 bg-white p-2.5 shadow-sm">
                                    <div class="min-w-0 flex-1">
                                        <p class="truncate text-xs font-bold text-slate-700">{{ $item->name }}</p>
                                        <p class="text-[9px] text-slate-400">{{ $item->category->name }}</p>
                                    </div>
                                    <span class="ml-2 rounded px-2 py-0.5 text-[10px] font-bold {{ $item->total_stock <= 0 ? 'bg-rose-50 text-rose-600' : 'bg-amber-50 text-amber-600' }}">
                                        {{ $item->total_stock }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="rounded-lg border border-dashed border-slate-200 bg-slate-50 p-3 text-center">
                            <p class="text-[10px] font-medium text-slate-400">Stock levels optimal.</p>
                        </div>
                    @endif
                </div>

                {{-- Expiring Soon Module --}}
                <div>
                    <div class="mb-2.5 flex items-center justify-between">
                        <h4 class="text-[11px] font-bold text-slate-600 uppercase tracking-wider flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Expiring Soon
                        </h4>
                    </div>
                    @if($expiringItems->count() > 0)
                        <div class="space-y-2">
                            @foreach($expiringItems->take(3) as $item)
                                @php $breakdownLookup = collect($item->batches_breakdown)->keyBy('id'); @endphp
                                @foreach($item->stockEntries as $entry)
                                    @php
                                        $batchData = $breakdownLookup->get($entry->id);
                                        if (!$batchData) continue;
                                        $daysLeft = now()->startOfDay()->diffInDays($entry->expiry_date->startOfDay(), false);
                                    @endphp
                                    <div class="flex items-center justify-between rounded-lg border border-slate-100 bg-white p-2.5 shadow-sm">
                                        <div class="min-w-0 flex-1">
                                            <p class="truncate text-xs font-bold text-slate-700">{{ $item->name }}</p>
                                            <p class="text-[9px] text-slate-400">Exp: {{ $entry->expiry_date->format('M d, Y') }}</p>
                                        </div>
                                        <span class="ml-2 rounded bg-rose-50 px-1.5 py-0.5 text-[9px] font-bold text-rose-600">
                                            {{ $daysLeft <= 0 ? 'TODAY' : $daysLeft . 'D' }}
                                        </span>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    @else
                        <div class="rounded-lg border border-dashed border-slate-200 bg-slate-50 p-3 text-center">
                            <p class="text-[10px] font-medium text-slate-400">No items expiring soon.</p>
                        </div>
                    @endif
                </div>

                {{-- Pending Returns Module --}}
                <div>
                    <div class="mb-2.5 flex items-center justify-between">
                        <h4 class="text-[11px] font-bold text-slate-600 uppercase tracking-wider flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-sky-500"></span> Pending Returns
                        </h4>
                        <a href="{{ route('in-out.index', ['tab' => 'borrow']) }}" class="text-[10px] font-bold text-sky-600 hover:text-sky-800">Manage</a>
                    </div>
                    @if($pendingReturnsList->count() > 0)
                        <div class="space-y-2">
                            @foreach($pendingReturnsList->take(4) as $borrow)
                                @php
                                    $isOverdue = $borrow->return_date && $borrow->return_date < now()->startOfDay();
                                    $qty = $borrow->quantity_borrowed - $borrow->quantity_returned - $borrow->quantity_used;
                                @endphp
                                <div class="flex items-center justify-between rounded-lg border border-slate-100 bg-white p-2.5 shadow-sm">
                                    <div class="min-w-0 flex-1">
                                        <p class="truncate text-xs font-bold {{ $isOverdue ? 'text-rose-600' : 'text-slate-700' }}">
                                            {{ $borrow->item->name }} <span class="text-sky-500 ml-1">x{{ $qty }}</span>
                                        </p>
                                        <p class="truncate text-[9px] text-slate-400">To: {{ $borrow->staff->name ?? $borrow->borrower_name }}</p>
                                    </div>
                                    <span class="ml-2 rounded px-1.5 py-0.5 text-[9px] font-bold uppercase tracking-wider {{ $isOverdue ? 'bg-rose-50 text-rose-600' : 'bg-slate-100 text-slate-500' }}">
                                        {{ $borrow->return_date ? $borrow->return_date->format('M d') : 'Open' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="rounded-lg border border-dashed border-slate-200 bg-slate-50 p-3 text-center">
                            <p class="text-[10px] font-medium text-slate-400">All returned.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Bottom Tables --}}
    <div class="grid grid-cols-1 border-t border-slate-200 lg:grid-cols-2 bg-white">
        
        {{-- Recent Usage Table --}}
        <div class="overflow-hidden flex flex-col border-r border-slate-200">
            <div class="p-4 border-b border-slate-100 flex items-center justify-between shrink-0">
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-widest text-sky-600">Log Data</p>
                    <h3 class="text-sm font-bold text-slate-800">Recent Usage</h3>
                </div>
                <a href="{{ route('logs.index') }}" class="rounded bg-slate-50 px-2 py-1 text-[10px] font-semibold text-slate-600 hover:bg-slate-100">Full History</a>
            </div>
            
            <div class="overflow-x-auto flex-1">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50/50 border-b border-slate-100">
                        <tr>
                            <th class="px-4 py-2.5 font-semibold text-slate-500">Item</th>
                            <th class="px-4 py-2.5 font-semibold text-slate-500">User / Proc.</th>
                            <th class="px-4 py-2.5 font-semibold text-slate-500 text-right">Qty</th>
                            <th class="px-4 py-2.5 font-semibold text-slate-500 text-right">Time</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($recentUsage->take(6) as $log)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-4 py-3">
                                    <p class="font-semibold text-slate-800">{{ $log->item->name ?? '—' }}</p>
                                </td>
                                <td class="px-4 py-3">
                                    <p class="text-slate-600">{{ $log->used_by ?? 'Unknown' }}</p>
                                    @if($log->procedure_type)
                                        <p class="text-[9px] text-slate-400 mt-0.5">{{ $log->procedure_type }}</p>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <span class="inline-flex items-center gap-1 rounded bg-rose-50 px-1.5 py-0.5 font-mono text-[10px] font-bold text-rose-600">
                                        -{{ $log->quantity_used }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right text-slate-400 text-[10px]">
                                    {{ $log->used_at->diffForHumans() }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-slate-400">No usage recorded yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Recent Additions Table --}}
        <div class="overflow-hidden flex flex-col">
            <div class="p-4 border-b border-slate-100 flex items-center justify-between shrink-0">
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-widest text-sky-600">Registry</p>
                    <h3 class="text-sm font-bold text-slate-800">Recently Added</h3>
                </div>
                <a href="{{ route('items.index') }}" class="rounded bg-slate-50 px-2 py-1 text-[10px] font-semibold text-slate-600 hover:bg-slate-100">Inventory</a>
            </div>

            <div class="overflow-x-auto flex-1">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50/50 border-b border-slate-100">
                        <tr>
                            <th class="px-4 py-2.5 font-semibold text-slate-500">Item Name</th>
                            <th class="px-4 py-2.5 font-semibold text-slate-500">Category</th>
                            <th class="px-4 py-2.5 font-semibold text-slate-500 text-right">Initial Stock</th>
                            <th class="px-4 py-2.5 font-semibold text-slate-500 text-right">Added</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($recentlyAdded->take(6) as $item)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-4 py-3">
                                    <p class="font-semibold text-slate-800">{{ $item->name }}</p>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex rounded-full bg-slate-100 px-2 py-0.5 text-[9px] font-semibold text-slate-500">
                                        {{ $item->category->name ?? '—' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <span class="inline-flex items-center gap-1 rounded bg-emerald-50 px-1.5 py-0.5 font-mono text-[10px] font-bold text-emerald-600">
                                        +{{ $item->total_stock }} <span class="font-normal opacity-70">{{ $item->unit }}</span>
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right text-slate-400 text-[10px]">
                                    {{ $item->created_at->diffForHumans() }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-slate-400">No items added yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom scrollbar for the alerts section */
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 4px; }
    .custom-scrollbar:hover::-webkit-scrollbar-thumb { background: #cbd5e1; }
</style>

{{-- Chart.js Initialization --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const createVerticalGradient = (ctx, startColor, endColor) => {
        const gradient = ctx.createLinearGradient(0, 0, 0, 320);
        gradient.addColorStop(0, startColor);
        gradient.addColorStop(1, endColor);
        return gradient;
    };

    const createHorizontalGradient = (ctx, startColor, midColor, endColor) => {
        const gradient = ctx.createLinearGradient(0, 0, 400, 0);
        gradient.addColorStop(0, startColor);
        gradient.addColorStop(0.5, midColor);
        gradient.addColorStop(1, endColor);
        return gradient;
    };

    const baseTickStyle = { color: '#94a3b8', font: { family: "'Fira Code', monospace", size: 10 } };
    const baseGrid = { color: 'rgba(15, 23, 42, 0.04)', drawBorder: false };

    // 1. Doughnut Chart (Distribution)
    const ctx1 = document.getElementById('inventoryStatusChart');
    if (ctx1) {
        new Chart(ctx1.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['New Stock', 'Used', 'Borrowed', 'Expired'],
                datasets: [{
                    data: [{{ $totalNewStock }}, {{ $totalUsedStock }}, {{ $totalBorrowedCount }}, {{ $expiredCount }}],
                    backgroundColor: [
                        createHorizontalGradient(ctx1.getContext('2d'), '#1e3a8a', '#0ea5e9', '#7dd3fc'),
                        createHorizontalGradient(ctx1.getContext('2d'), '#0284c7', '#0ea5e9', '#bae6fd'),
                        createHorizontalGradient(ctx1.getContext('2d'), '#0f172a', '#1e3a8a', '#38bdf8'),
                        createHorizontalGradient(ctx1.getContext('2d'), '#ef4444', '#fb7185', '#fecaca')
                    ],
                    borderColor: '#ffffff',
                    borderWidth: 3,
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            color: '#334155',
                            font: { family: "ui-sans-serif, system-ui, sans-serif", size: 11, weight: '600' }
                        }
                    }
                }
            }
        });
    }

    // 2. Bar Chart (Health)
    const ctx2Element = document.getElementById('inventoryHealthChart');
    if (ctx2Element) {
        const ctx2 = ctx2Element.getContext('2d');
        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: ['Total', 'Low', 'Expiring', 'Expired', 'Out'],
                datasets: [{
                    data: [{{ $totalItems }}, {{ $lowStockCount }}, {{ $expiringItems->count() }}, {{ $expiredCount }}, {{ $totalBorrowedCount }}],
                    backgroundColor: [
                        createVerticalGradient(ctx2, 'rgba(30, 58, 138, 0.9)', 'rgba(30, 58, 138, 0.1)'),
                        createVerticalGradient(ctx2, 'rgba(245, 158, 11, 0.9)', 'rgba(245, 158, 11, 0.1)'),
                        createVerticalGradient(ctx2, 'rgba(225, 29, 72, 0.9)', 'rgba(225, 29, 72, 0.1)'),
                        createVerticalGradient(ctx2, 'rgba(220, 38, 38, 0.9)', 'rgba(220, 38, 38, 0.1)'),
                        createVerticalGradient(ctx2, 'rgba(14, 165, 233, 0.9)', 'rgba(14, 165, 233, 0.1)')
                    ],
                    borderColor: ['#1e3a8a', '#f59e0b', '#e11d48', '#dc2626', '#0ea5e9'],
                    borderWidth: 2,
                    borderRadius: 6,
                    borderSkipped: false,
                    barThickness: 'flex',
                    maxBarThickness: 40
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: baseGrid, border: { display: false }, ticks: baseTickStyle },
                    x: { grid: { display: false }, border: { display: false }, ticks: baseTickStyle }
                }
            }
        });
    }

    // 3. Line Chart (Trend)
    const ctx3Element = document.getElementById('inventoryTrendChart');
    if (ctx3Element) {
        const ctx3 = ctx3Element.getContext('2d');
        new Chart(ctx3, {
            type: 'line',
            data: {
                labels: @json($trendLabels),
                datasets: [
                    {
                        label: 'Stock In',
                        data: @json($trendStockIn),
                        borderColor: '#0ea5e9',
                        backgroundColor: createVerticalGradient(ctx3, 'rgba(14, 165, 233, 0.2)', 'rgba(14, 165, 233, 0.0)'),
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointBackgroundColor: '#0ea5e9',
                        pointBorderWidth: 2,
                        pointBorderColor: '#ffffff'
                    },
                    {
                        label: 'Stock Out',
                        data: @json($trendStockOut),
                        borderColor: '#0f172a',
                        backgroundColor: createVerticalGradient(ctx3, 'rgba(15, 23, 42, 0.1)', 'rgba(15, 23, 42, 0.0)'),
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointBackgroundColor: '#0f172a',
                        pointBorderWidth: 2,
                        pointBorderColor: '#ffffff'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        position: 'top',
                        align: 'end',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle',
                            color: '#334155',
                            font: { family: "ui-sans-serif, system-ui, sans-serif", size: 12, weight: '600' }
                        }
                    }
                },
                scales: {
                    y: { beginAtZero: true, grid: baseGrid, border: { display: false }, ticks: baseTickStyle },
                    x: { grid: { display: false }, border: { display: false }, ticks: baseTickStyle }
                }
            }
        });
    }
});
</script>

@endsection