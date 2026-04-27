@extends('layouts.app')

@section('title', 'Units Dashboard')

@section('actions')
<a href="{{ route('units.create') }}"
    class="inline-flex items-center gap-2 bg-sky-500 hover:bg-sky-600 text-white px-4 py-2 text-[11px] font-mono font-bold uppercase tracking-[0.15em] transition-colors border border-blue-700">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-3.5 w-3.5">
        <path d="M8.75 3.75a.75.75 0 00-1.5 0v3.5h-3.5a.75.75 0 000 1.5h3.5v3.5a.75.75 0 001.5 0v-3.5h3.5a.75.75 0 000-1.5h-3.5v-3.5z" />
    </svg>
    New_Unit
</a>
@endsection

@section('content')
@php
    $totalItems = (int) $units->sum(fn($u) => \App\Models\Item::where('unit', $u->name)->count());
    $avgItemsPerUnit = $units->count() > 0 ? round($totalItems / $units->count(), 1) : 0;
    $topUnits = $units->map(function($u) {
        return [
            'name' => $u->name,
            'items_count' => \App\Models\Item::where('unit', $u->name)->count()
        ];
    })->sortByDesc('items_count')->take(6)->values();
@endphp

<div x-data="{ search: '' }">
    @if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 relative px-5 py-3">
        <div class="absolute top-0 left-0 w-1 h-full bg-emerald-500"></div>
        <p class="text-sm font-mono font-bold text-emerald-700 ml-1">{{ session('success') }}</p>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-sky-100 p-5">
            <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-widest">Total Units</p>
            <p class="text-2xl font-black text-slate-800 mt-2">{{ $units->count() }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-sky-100 p-5">
            <p class="text-[10px] font-semibold text-violet-500 uppercase tracking-widest">Total Items</p>
            <p class="text-2xl font-black text-violet-600 mt-2">{{ number_format($totalItems) }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-sky-100 p-5">
            <p class="text-[10px] font-semibold text-sky-500 uppercase tracking-widest">Avg Items / Unit</p>
            <p class="text-2xl font-black text-sky-600 mt-2">{{ $avgItemsPerUnit }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-sky-100 p-5">
            <p class="text-[10px] font-semibold text-emerald-500 uppercase tracking-widest">Active Units</p>
            <p class="text-2xl font-black text-emerald-600 mt-2">{{ $units->filter(fn($u) => \App\Models\Item::where('unit', $u->name)->count() > 0)->count() }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-5 mb-6">
        <div class="bg-white rounded-2xl overflow-hidden border border-sky-100 shadow-sm">
            <div class="p-5 border-b border-sky-100">
                <p class="text-[10px] font-semibold text-indigo-600 uppercase tracking-widest mb-0.5">Chart.01</p>
                <h3 class="text-sm font-bold text-slate-800">Unit Usage Trend</h3>
            </div>
            <div class="p-5">
                <div class="h-[240px]"><canvas id="unitTrendChart"></canvas></div>
            </div>
        </div>
        <div class="bg-white rounded-2xl overflow-hidden border border-sky-100 shadow-sm">
            <div class="p-5 border-b border-sky-100">
                <p class="text-[10px] font-semibold text-teal-600 uppercase tracking-widest mb-0.5">Chart.02</p>
                <h3 class="text-sm font-bold text-slate-800">Top Units by Item Count</h3>
            </div>
            <div class="p-5">
                <div class="h-[240px]"><canvas id="topUnitsChart"></canvas></div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl overflow-hidden border border-sky-100 shadow-sm">
        <div class="p-5 border-b border-sky-100 flex items-center justify-between gap-4">
            <div>
                <p class="text-[10px] font-semibold text-sky-600 uppercase tracking-widest mb-0.5">Registry</p>
                <h3 class="text-sm font-bold text-slate-800">Unit Records</h3>
            </div>
            <div class="relative w-full max-w-sm">
                <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                    </svg>
                </span>
                <input type="text" x-model="search" placeholder="Search unit..."
                    class="w-full border border-sky-100 bg-white pl-9 pr-4 py-2 text-xs font-mono text-slate-700 placeholder-slate-400 focus:outline-none focus:border-slate-400 transition-colors" />
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="bg-sky-50/80 border-b border-sky-100">
                        <th class="px-6 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 text-left">Unit Name</th>
                        <th class="px-6 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 text-center">Items</th>
                        <th class="px-6 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-sky-50">
                    @forelse($units as $unit)
                    @php $itemCount = \App\Models\Item::where('unit', $unit->name)->count(); @endphp
                    <tr class="hover:bg-sky-50 transition-colors"
                        x-show="search === '' || '{{ strtolower($unit->name) }}'.includes(search.toLowerCase())">
                        <td class="px-6 py-4 font-bold text-[#0f172a]">{{ $unit->name }}</td>
                        <td class="whitespace-nowrap px-6 py-4 text-center">
                            <span class="font-mono text-[11px] font-bold text-slate-700">{{ number_format($itemCount) }}</span>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('units.edit', $unit) }}" class="inline-flex items-center border border-sky-100 bg-white px-2.5 py-1.5 text-[10px] font-mono font-bold text-slate-700 hover:bg-sky-50 transition-colors">Edit</a>
                                @if($itemCount === 0)
                                    <form action="{{ route('units.destroy', $unit) }}" method="POST" class="m-0 inline"
                                        onsubmit="return confirm('Delete this unit?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center border border-rose-200 bg-rose-50 px-2.5 py-1.5 text-[10px] font-mono font-bold text-rose-600 hover:bg-rose-500 hover:text-white hover:border-rose-500 transition-colors">Delete</button>
                                    </form>
                                @else
                                    <button disabled class="cursor-not-allowed opacity-40 inline-flex items-center border border-sky-100 bg-sky-50 px-2.5 py-1.5 text-[10px] font-mono font-bold text-slate-400" title="Cannot delete while items are using it">Delete</button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-16 text-center">
                            <p class="font-mono text-[10px] text-slate-400 uppercase tracking-widest">// No units found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const makeGradient = (ctx, c1, c2) => {
        const g = ctx.createLinearGradient(0, 0, 0, 300);
        g.addColorStop(0, c1);
        g.addColorStop(1, c2);
        return g;
    };

    const trendCanvas = document.getElementById('unitTrendChart');
    if (trendCanvas) {
        const tctx = trendCanvas.getContext('2d');
        new Chart(tctx, {
            type: 'line',
            data: {
                labels: @json($units->pluck('name')->values()),
                datasets: [{
                    label: 'Items per Unit',
                    data: @json($units->map(fn($u) => \App\Models\Item::where('unit', $u->name)->count())->values()),
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    borderColor: 'rgba(99, 102, 241, 0.8)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: 'rgba(99, 102, 241, 0.9)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: true, labels: { usePointStyle: true, color: '#64748b' } }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.04)' },
                        border: { display: false },
                        ticks: { color: '#94a3b8' }
                    },
                    x: {
                        grid: { display: false },
                        border: { display: false },
                        ticks: { color: '#64748b' }
                    }
                }
            }
        });
    }

    const topCanvas = document.getElementById('topUnitsChart');
    if (topCanvas) {
        const bctx = topCanvas.getContext('2d');
        new Chart(bctx, {
            type: 'doughnut',
            data: {
                labels: @json($topUnits->pluck('name')->values()),
                datasets: [{
                    data: @json($topUnits->pluck('items_count')->values()),
                    backgroundColor: [
                        'rgba(139, 92, 246, 0.9)',
                        'rgba(14, 165, 233, 0.9)',
                        'rgba(20, 184, 166, 0.9)',
                        'rgba(249, 115, 22, 0.9)',
                        'rgba(236, 72, 153, 0.9)',
                        'rgba(34, 197, 94, 0.9)'
                    ],
                    borderColor: ['#8b5cf6', '#0ea5e9', '#14b8a6', '#f97316', '#ec4899', '#22c55e'],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'right',
                        labels: { usePointStyle: true, pointStyle: 'circle', color: '#64748b' }
                    }
                }
            }
        });
    }
});
</script>
@endsection
