@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

{{-- Page Header --}}
<div class="mb-6 flex items-end justify-between">
    <div>
        <p class="text-[10px] font-mono font-semibold text-blue-600 uppercase tracking-[0.25em] mb-1">System://Overview</p>
        <h1 class="text-xl font-bold text-slate-800 tracking-tight">Inventory Dashboard</h1>
    </div>
    <span class="text-[10px] font-mono text-slate-400">{{ now()->format('Y-m-d • H:i') }}</span>
</div>

{{-- ==================== --}}
{{-- INLINE STAT STRIP     --}}
{{-- ==================== --}}
<div class="bg-white border border-slate-200 mb-6 relative">
    {{-- Top accent line --}}
    <div class="absolute top-0 left-0 right-0 h-[2px] bg-gradient-to-r from-blue-500 to-indigo-400"></div>
    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 divide-x divide-slate-100">

        @php
        $stats = [
            ['label' => 'Total Items',   'value' => number_format($totalItems),              'color' => 'text-slate-800', 'alert' => false],
            ['label' => 'Low Stock',     'value' => number_format($lowStockCount),            'color' => $lowStockCount > 0 ? 'text-orange-500' : 'text-slate-800', 'alert' => $lowStockCount > 0],
            ['label' => 'Expiring 30d',  'value' => number_format($expiringItems->count()),   'color' => $expiringItems->count() > 0 ? 'text-amber-500' : 'text-slate-800', 'alert' => $expiringItems->count() > 0],
            ['label' => 'Need Disposal', 'value' => number_format($expiredCount),             'color' => $expiredCount > 0 ? 'text-rose-500' : 'text-slate-800', 'alert' => $expiredCount > 0],
            ['label' => 'New Stock',     'value' => $totalNewStock,                           'color' => 'text-indigo-600', 'alert' => false],
            ['label' => 'Used',          'value' => $totalUsedStock,                          'color' => 'text-sky-600', 'alert' => false],
            ['label' => 'Borrowed',      'value' => $totalBorrowedCount,                      'color' => 'text-teal-600', 'alert' => false],
            ['label' => 'To Return',     'value' => $pendingReturnsCount,                     'color' => 'text-pink-600', 'alert' => false],
        ];
        @endphp

        @foreach($stats as $stat)
        <div class="px-4 py-4 relative group hover:bg-slate-50 transition-colors">
            @if($stat['alert'])
            <div class="absolute top-2 right-2 h-1.5 w-1.5 bg-current rounded-full {{ $stat['color'] }} opacity-70 animate-pulse"></div>
            @endif
            <p class="text-[10px] font-mono text-slate-400 mb-1.5 uppercase tracking-widest">{{ $stat['label'] }}</p>
            <p class="text-xl font-bold {{ $stat['color'] }}">{{ $stat['value'] }}</p>
        </div>
        @endforeach

    </div>
</div>

{{-- ============================== --}}
{{-- MAIN AREA: Charts + Alert Feed --}}
{{-- ============================== --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

    {{-- LEFT: Charts (2/3) --}}
    <div class="xl:col-span-2 space-y-5">

        {{-- Doughnut Chart --}}
        <div class="bg-white border border-slate-200 relative">
            <div class="absolute top-0 left-0 w-1 h-full bg-blue-500"></div>
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between ml-1">
                <div>
                    <p class="text-[10px] font-mono text-blue-600 uppercase tracking-widest mb-0.5">Chart.01</p>
                    <p class="text-sm font-bold text-slate-800">Stock Distribution</p>
                </div>
                <span class="flex items-center gap-1.5 text-[10px] font-mono text-emerald-600 bg-emerald-50 border border-emerald-200 px-2.5 py-1">
                    <span class="h-1.5 w-1.5 bg-emerald-500 inline-block animate-pulse"></span>
                    LIVE
                </span>
            </div>
            <div class="p-5 ml-1">
                <div class="h-[260px]">
                    <canvas id="inventoryStatusChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Bar Chart --}}
        <div class="bg-white border border-slate-200 relative">
            <div class="absolute top-0 left-0 w-1 h-full bg-indigo-500"></div>
            <div class="px-5 py-4 border-b border-slate-100 ml-1">
                <p class="text-[10px] font-mono text-indigo-600 uppercase tracking-widest mb-0.5">Chart.02</p>
                <p class="text-sm font-bold text-slate-800">Stock Health Breakdown</p>
            </div>
            <div class="p-5 ml-1">
                <div class="h-[240px]">
                    <canvas id="inventoryHealthChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Line Chart --}}
        <div class="bg-white border border-slate-200 relative">
            <div class="absolute top-0 left-0 w-1 h-full bg-cyan-500"></div>
            <div class="px-5 py-4 border-b border-slate-100 ml-1">
                <p class="text-[10px] font-mono text-cyan-600 uppercase tracking-widest mb-0.5">Chart.03</p>
                <p class="text-sm font-bold text-slate-800">7-Day Movement Trend</p>
            </div>
            <div class="p-5 ml-1">
                <div class="h-[240px]">
                    <canvas id="inventoryTrendChart"></canvas>
                </div>
            </div>
        </div>

    </div>

    {{-- RIGHT: Alert Feed (1/3) --}}
    <div class="xl:col-span-1 bg-white border border-slate-200 flex flex-col relative">
        <div class="absolute top-0 left-0 right-0 h-[2px] bg-gradient-to-r from-orange-400 to-rose-400"></div>

        {{-- Low Stock --}}
        <div class="px-4 pt-5 pb-4 border-b border-slate-100">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <span class="h-2 w-2 bg-orange-400 inline-block"></span>
                    <span class="text-[10px] font-mono font-bold text-slate-600 uppercase tracking-widest">Low_Stock</span>
                </div>
                <a href="{{ route('items.index') }}" class="text-[10px] font-mono text-blue-500 hover:text-blue-700 transition-colors">View →</a>
            </div>
            @if($lowStockItems->count() > 0)
            <div class="space-y-2">
                @foreach($lowStockItems->take(4) as $item)
                <div class="flex items-center justify-between py-1.5 border-b border-dashed border-slate-100 last:border-0">
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-slate-800 truncate">{{ $item->name }}</p>
                        <p class="text-[10px] font-mono text-slate-400">{{ $item->category->name }}</p>
                    </div>
                    <div class="flex items-center gap-2 shrink-0 ml-2">
                        <span class="text-sm font-bold {{ $item->total_stock <= 0 ? 'text-rose-500' : 'text-amber-500' }}">{{ $item->total_stock }}</span>
                        <a href="{{ route('stock.create', $item) }}" class="text-[10px] font-mono font-bold px-2 py-1 border border-slate-200 text-slate-500 hover:border-blue-500 hover:text-blue-600 transition-all">+Stock</a>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-[11px] font-mono text-slate-400 py-2">// No alerts — all stocked</p>
            @endif
        </div>

        {{-- Expiring Soon --}}
        <div class="px-4 py-4 border-b border-slate-100">
            <div class="flex items-center gap-2 mb-3">
                <span class="h-2 w-2 bg-amber-400 inline-block"></span>
                <span class="text-[10px] font-mono font-bold text-slate-600 uppercase tracking-widest">Expiring_Soon</span>
            </div>
            @if($expiringItems->count() > 0)
            <div class="space-y-2">
                @foreach($expiringItems->take(4) as $item)
                @php $breakdownLookup = collect($item->batches_breakdown)->keyBy('id'); @endphp
                @foreach($item->stockEntries as $entry)
                @php
                    $batchData = $breakdownLookup->get($entry->id);
                    if (!$batchData) continue;
                    $daysLeft = now()->startOfDay()->diffInDays($entry->expiry_date->startOfDay(), false);
                    $isCritical = $daysLeft <= 7;
                @endphp
                <div class="flex items-center justify-between py-1.5 border-b border-dashed border-slate-100 last:border-0">
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-slate-800 truncate">{{ $item->name }}</p>
                        <p class="text-[10px] font-mono text-slate-400">{{ $entry->expiry_date->format('Y-m-d') }}</p>
                    </div>
                    <span class="text-[10px] font-mono font-bold px-2 py-1 border shrink-0 ml-2 {{ $isCritical ? 'border-rose-300 text-rose-600 bg-rose-50' : 'border-amber-200 text-amber-600 bg-amber-50' }}">
                        {{ $daysLeft <= 0 ? 'TODAY' : $daysLeft.'D' }}
                    </span>
                </div>
                @endforeach
                @endforeach
            </div>
            @else
            <p class="text-[11px] font-mono text-slate-400 py-2">// No expiring items</p>
            @endif
        </div>

        {{-- Needs Disposal --}}
        <div class="px-4 py-4 border-b border-slate-100">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <span class="h-2 w-2 bg-rose-500 inline-block animate-pulse"></span>
                    <span class="text-[10px] font-mono font-bold text-slate-600 uppercase tracking-widest">Need_Disposal</span>
                </div>
                <a href="{{ route('items.index') }}" class="text-[10px] font-mono text-blue-500 hover:text-blue-700 transition-colors">View →</a>
            </div>
            @if($expiredItems->count() > 0)
            <div class="space-y-2">
                @foreach($expiredItems->take(3) as $item)
                @php $breakdownLookup = collect($item->batches_breakdown)->keyBy('id'); @endphp
                @foreach($item->stockEntries as $entry)
                @php
                    $batchData = $breakdownLookup->get($entry->id);
                    if (!$batchData) continue;
                    $daysPast = now()->startOfDay()->diffInDays($entry->expiry_date->startOfDay());
                @endphp
                <div class="flex items-center justify-between py-1.5 border-b border-dashed border-slate-100 last:border-0">
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-slate-800 truncate">{{ $item->name }}</p>
                    </div>
                    <span class="text-[10px] font-mono font-bold px-2 py-1 border border-rose-300 text-rose-600 bg-rose-50 shrink-0 ml-2">+{{ $daysPast }}D</span>
                </div>
                @endforeach
                @endforeach
            </div>
            @else
            <p class="text-[11px] font-mono text-slate-400 py-2">// No disposal needed</p>
            @endif
        </div>

        {{-- Pending Returns --}}
        <div class="px-4 py-4 border-b border-slate-100">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <span class="h-2 w-2 bg-cyan-500 inline-block"></span>
                    <span class="text-[10px] font-mono font-bold text-slate-600 uppercase tracking-widest">Pending_Returns</span>
                </div>
                <a href="{{ route('in-out.index', ['tab' => 'borrow']) }}" class="text-[10px] font-mono text-blue-500 hover:text-blue-700 transition-colors">View →</a>
            </div>
            @if($pendingReturnsList->count() > 0)
            <div class="space-y-2">
                @foreach($pendingReturnsList as $borrow)
                @php 
                    $isOverdue = $borrow->return_date && $borrow->return_date < now()->startOfDay(); 
                    $qty = $borrow->quantity_borrowed - $borrow->quantity_returned - $borrow->quantity_used;
                @endphp
                <div class="flex items-center justify-between py-1.5 border-b border-dashed border-slate-100 last:border-0">
                    <div class="min-w-0">
                        <p class="text-[13px] font-semibold {{ $isOverdue ? 'text-rose-600' : 'text-slate-800' }} truncate">{{ $borrow->item->name }} <span class="text-[10px] text-slate-400 font-normal">x{{ $qty }}</span></p>
                        <p class="text-[10px] font-mono text-slate-400 truncate w-32">by {{ $borrow->staff->name ?? $borrow->borrower_name }}</p>
                    </div>
                    @if($borrow->return_date)
                    <span class="text-[9px] font-mono font-bold px-1.5 py-0.5 border shrink-0 ml-2 {{ $isOverdue ? 'border-rose-300 text-rose-600 bg-rose-50' : 'border-slate-200 text-slate-500 bg-slate-50' }}">
                        {{ $borrow->return_date->format('M d') }}
                    </span>
                    @else
                    <span class="text-[9px] font-mono text-slate-300 bg-slate-50 px-1.5 shrink-0 ml-2">No Date</span>
                    @endif
                </div>
                @endforeach
            </div>
            @else
            <p class="text-[11px] font-mono text-slate-400 py-2">// No pending returns</p>
            @endif
        </div>

        {{-- Recent Transfers --}}
        <div class="px-4 py-4 flex-1">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <span class="h-2 w-2 bg-purple-500 inline-block"></span>
                    <span class="text-[10px] font-mono font-bold text-slate-600 uppercase tracking-widest">Recent_Transfers</span>
                </div>
                <a href="{{ route('admin.records.index', ['tab' => 'transfers']) }}" class="text-[10px] font-mono text-blue-500 hover:text-blue-700 transition-colors">View →</a>
            </div>
            @if($recentTransfersFeed->count() > 0)
            <div class="space-y-2">
                @foreach($recentTransfersFeed as $transfer)
                <div class="flex items-center justify-between py-1.5 border-b border-dashed border-slate-100 last:border-0">
                    <div class="min-w-0">
                        <p class="text-[13px] font-semibold text-slate-800 truncate">{{ $transfer->item->name ?? '—' }} <span class="text-[10px] text-slate-400 font-normal">x{{ $transfer->quantity }}</span></p>
                        <p class="text-[10px] font-mono text-slate-400 truncate w-32">to {{ $transfer->destination }}</p>
                    </div>
                    <span class="text-[9px] font-mono font-bold px-1.5 py-0.5 border {{ $transfer->type == 'out' ? 'border-amber-200 text-amber-600 bg-amber-50' : 'border-emerald-200 text-emerald-600 bg-emerald-50' }} shrink-0 ml-2">
                        {{ strtoupper($transfer->type) }}
                    </span>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-[11px] font-mono text-slate-400 py-2">// No recent transfers</p>
            @endif
        </div>

    </div>
</div>

{{-- ================================ --}}
{{-- BOTTOM ROW: Recently Used + Added --}}
{{-- ================================ --}}
<div class="mt-5 grid grid-cols-1 xl:grid-cols-2 gap-5">

    {{-- Recently Used --}}
    <div class="bg-white border border-slate-200 relative">
        <div class="absolute top-0 left-0 w-1 h-full bg-indigo-400"></div>
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between ml-1">
            <div>
                <p class="text-[10px] font-mono text-indigo-600 uppercase tracking-widest mb-0.5">Log.Recent</p>
                <p class="text-sm font-bold text-slate-800">Recently Used</p>
            </div>
            <a href="{{ route('logs.index') }}" class="text-[10px] font-mono text-slate-400 hover:text-blue-500 transition-colors">View Log →</a>
        </div>
        <div class="ml-1">
            @if($recentUsage->count() > 0)
            <div class="divide-y divide-slate-50">
                @foreach($recentUsage->take(6) as $log)
                <div class="flex items-center justify-between px-5 py-3 hover:bg-slate-50 transition-colors">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="h-7 w-7 shrink-0 border border-indigo-100 bg-indigo-50 flex items-center justify-center text-indigo-500">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-3.5 w-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-slate-800 truncate">{{ $log->item->name ?? '—' }}</p>
                            <p class="text-[11px] font-mono text-slate-400">{{ $log->used_by ?? 'Unknown' }}{{ $log->procedure_type ? ' · '.$log->procedure_type : '' }}</p>
                        </div>
                    </div>
                    <div class="text-right shrink-0 ml-3">
                        <span class="text-sm font-bold text-rose-500 font-mono">−{{ $log->quantity_used }}</span>
                        <p class="text-[10px] font-mono text-slate-400 mt-0.5">{{ $log->used_at->diffForHumans() }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="px-5 py-10 text-center">
                <p class="text-[11px] font-mono text-slate-400">// No usage recorded yet</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Recently Added --}}
    <div class="bg-white border border-slate-200 relative">
        <div class="absolute top-0 left-0 w-1 h-full bg-emerald-400"></div>
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between ml-1">
            <div>
                <p class="text-[10px] font-mono text-emerald-600 uppercase tracking-widest mb-0.5">Inventory.New</p>
                <p class="text-sm font-bold text-slate-800">Recently Added</p>
            </div>
            <a href="{{ route('items.index') }}" class="text-[10px] font-mono text-slate-400 hover:text-blue-500 transition-colors">View All →</a>
        </div>
        <div class="ml-1">
            @if($recentlyAdded->count() > 0)
            <div class="divide-y divide-slate-50">
                @foreach($recentlyAdded as $item)
                <div class="flex items-center justify-between px-5 py-3 hover:bg-slate-50 transition-colors">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="h-7 w-7 shrink-0 border border-emerald-100 bg-emerald-50 flex items-center justify-center text-emerald-500">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-3.5 w-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-slate-800 truncate">{{ $item->name }}</p>
                            <p class="text-[11px] font-mono text-slate-400">{{ $item->category->name ?? '—' }}</p>
                        </div>
                    </div>
                    <div class="text-right shrink-0 ml-3">
                        <span class="text-sm font-bold text-emerald-600 font-mono">{{ $item->total_stock }} <span class="text-xs text-slate-400 font-normal">{{ $item->unit }}</span></span>
                        <p class="text-[10px] font-mono text-slate-400 mt-0.5">{{ $item->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="px-5 py-10 text-center">
                <p class="text-[11px] font-mono text-slate-400">// No items added yet</p>
            </div>
            @endif
        </div>
    </div>

</div>

{{-- Charts --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Helper to create glassmorphic gradients
    const createGradient = (ctx, startColor, endColor) => {
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, startColor);
        gradient.addColorStop(1, endColor);
        return gradient;
    };

    const ctx1 = document.getElementById('inventoryStatusChart');
    if (ctx1) {
        const ctx = ctx1.getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['New Stock', 'Used', 'Borrowed', 'Expired'],
                datasets: [{
                    data: [{{ $totalNewStock }}, {{ $totalUsedStock }}, {{ $totalBorrowedCount }}, {{ $expiredCount }}],
                    backgroundColor: [
                        createGradient(ctx, 'rgba(99, 102, 241, 0.9)', 'rgba(99, 102, 241, 0.4)'),   // Indigo
                        createGradient(ctx, 'rgba(14, 165, 233, 0.9)', 'rgba(14, 165, 233, 0.4)'),   // Sky
                        createGradient(ctx, 'rgba(20, 184, 166, 0.9)', 'rgba(20, 184, 166, 0.4)'),   // Teal
                        createGradient(ctx, 'rgba(244, 63, 94, 0.9)', 'rgba(244, 63, 94, 0.4)')      // Rose
                    ],
                    borderColor: [
                        '#6366f1', '#0ea5e9', '#14b8a6', '#f43f5e'
                    ],
                    borderWidth: 2,
                    hoverOffset: 0,
                    hoverBorderWidth: 2,
                    hoverBackgroundColor: [
                        createGradient(ctx, 'rgba(99, 102, 241, 1)', 'rgba(99, 102, 241, 0.6)'),
                        createGradient(ctx, 'rgba(14, 165, 233, 1)', 'rgba(14, 165, 233, 0.6)'),
                        createGradient(ctx, 'rgba(20, 184, 166, 1)', 'rgba(20, 184, 166, 0.6)'),
                        createGradient(ctx, 'rgba(244, 63, 94, 1)', 'rgba(244, 63, 94, 0.6)')
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            color: '#64748b',
                            font: { family: "'Plus Jakarta Sans', sans-serif", size: 11, weight: '600' }
                        }
                    }
                }
            }
        });
    }

    const ctx2Element = document.getElementById('inventoryHealthChart');
    if (ctx2Element) {
        const ctx2 = ctx2Element.getContext('2d');
        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: ['Total', 'Low_Stock', 'Expiring', 'Expired', 'Borrowed', 'Transferred'],
                datasets: [{
                    label: 'Count',
                    data: [{{ $totalItems }}, {{ $lowStockCount }}, {{ $expiringItems->count() }}, {{ $expiredCount }}, {{ $totalBorrowedCount }}, {{ $totalTransfersCount }}],
                    backgroundColor: [
                        createGradient(ctx2, 'rgba(99, 102, 241, 0.8)', 'rgba(99, 102, 241, 0.1)'), // Indigo
                        createGradient(ctx2, 'rgba(249, 115, 22, 0.8)', 'rgba(249, 115, 22, 0.1)'), // Orange
                        createGradient(ctx2, 'rgba(245, 158, 11, 0.8)', 'rgba(245, 158, 11, 0.1)'), // Amber
                        createGradient(ctx2, 'rgba(244, 63, 94, 0.8)', 'rgba(244, 63, 94, 0.1)'),   // Rose
                        createGradient(ctx2, 'rgba(6, 182, 212, 0.8)', 'rgba(6, 182, 212, 0.1)'),   // Cyan
                        createGradient(ctx2, 'rgba(168, 85, 247, 0.8)', 'rgba(168, 85, 247, 0.1)')  // Purple
                    ],
                    borderColor: [
                        '#6366f1', '#f97316', '#f59e0b', '#f43f5e', '#06b6d4', '#a855f7'
                    ],
                    borderWidth: { top: 2, right: 2, bottom: 0, left: 2 },
                    borderRadius: 4,
                    borderSkipped: false,
                    barThickness: 32,
                    hoverBackgroundColor: [
                        createGradient(ctx2, 'rgba(99, 102, 241, 1)', 'rgba(99, 102, 241, 0.4)'),
                        createGradient(ctx2, 'rgba(249, 115, 22, 1)', 'rgba(249, 115, 22, 0.4)'),
                        createGradient(ctx2, 'rgba(245, 158, 11, 1)', 'rgba(245, 158, 11, 0.4)'),
                        createGradient(ctx2, 'rgba(244, 63, 94, 1)', 'rgba(244, 63, 94, 0.4)'),
                        createGradient(ctx2, 'rgba(6, 182, 212, 1)', 'rgba(6, 182, 212, 0.4)'),
                        createGradient(ctx2, 'rgba(168, 85, 247, 1)', 'rgba(168, 85, 247, 0.4)')
                    ]
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
                        ticks: { color: '#64748b', font: { family: "'Fira Code', monospace", size: 10 } }
                    }
                }
            }
        });
    }

    const ctx3Element = document.getElementById('inventoryTrendChart');
    if (ctx3Element) {
        const ctx3 = ctx3Element.getContext('2d');
        new Chart(ctx3, {
            type: 'line',
            data: {
                labels: @json($trendLabels),
                datasets: [
                    {
                        label: 'In',
                        data: @json($trendStockIn),
                        borderColor: '#14b8a6',
                        backgroundColor: createGradient(ctx3, 'rgba(20, 184, 166, 0.30)', 'rgba(20, 184, 166, 0.02)'),
                        fill: true,
                        tension: 0.35,
                        borderWidth: 2,
                        pointRadius: 3,
                        pointHoverRadius: 4,
                        pointBackgroundColor: '#14b8a6'
                    },
                    {
                        label: 'Out',
                        data: @json($trendStockOut),
                        borderColor: '#f43f5e',
                        backgroundColor: createGradient(ctx3, 'rgba(244, 63, 94, 0.30)', 'rgba(244, 63, 94, 0.02)'),
                        fill: true,
                        tension: 0.35,
                        borderWidth: 2,
                        pointRadius: 3,
                        pointHoverRadius: 4,
                        pointBackgroundColor: '#f43f5e'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle',
                            color: '#64748b',
                            font: { family: "'Plus Jakarta Sans', sans-serif", size: 11, weight: '600' }
                        }
                    }
                },
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
                        ticks: { color: '#64748b', font: { family: "'Fira Code', monospace", size: 10 } }
                    }
                }
            }
        });
    }
});
</script>

@endsection