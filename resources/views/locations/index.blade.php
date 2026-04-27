@extends('layouts.app')

@section('title', 'Storage Locations Dashboard')

@section('actions')
<a href="{{ route('locations.create') }}"
    class="inline-flex items-center gap-2 bg-sky-500 hover:bg-sky-600 text-white px-4 py-2 text-[11px] font-mono font-bold uppercase tracking-[0.15em] transition-colors border border-blue-700">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-3.5 w-3.5">
        <path d="M8.75 3.75a.75.75 0 00-1.5 0v3.5h-3.5a.75.75 0 000 1.5h3.5v3.5a.75.75 0 001.5 0v-3.5h3.5a.75.75 0 000-1.5h-3.5v-3.5z" />
    </svg>
    New_Location
</a>
@endsection

@section('content')
@php
    $totalItems = (int) \App\Models\Item::whereNotNull('storage_location')->count();
    
    $storageItems = $storages->map(function($s) {
        return [
            'name' => $s->name,
            'items_count' => \App\Models\Item::where('storage_location', $s->name)->count()
        ];
    })->filter(function($s) { return $s['items_count'] > 0; });
    
    $sectionItems = $sections->map(function($s) {
        return [
            'name' => $s->name,
            'items_count' => \App\Models\Item::where('storage_section', $s->name)->count()
        ];
    })->filter(function($s) { return $s['items_count'] > 0; });
    
    $topStorages = $storages->map(function($s) {
        return [
            'name' => $s->name,
            'items_count' => \App\Models\Item::where('storage_location', $s->name)->count()
        ];
    })->sortByDesc('items_count')->take(6)->values();
    
    $storageItemCounts = $storages->map(function($s) {
        return \App\Models\Item::where('storage_location', $s->name)->count();
    })->values()->toArray();
    
    $sectionItemCounts = $sections->map(function($s) {
        return \App\Models\Item::where('storage_section', $s->name)->count();
    })->values()->toArray();
    
    $allLocationNames = array_merge($storages->pluck('name')->values()->toArray(), $sections->pluck('name')->values()->toArray());
    $allItemCounts = array_merge($storageItemCounts, $sectionItemCounts);
@endphp

<div x-data="{ search: '' }">
    @if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 relative px-5 py-3">
        <div class="absolute top-0 left-0 w-1 h-full bg-emerald-500"></div>
        <p class="text-sm font-mono font-bold text-emerald-700 ml-1">{{ session('success') }}</p>
    </div>
    @endif

    <div class="bg-white rounded-2xl overflow-hidden border border-slate-200 shadow-sm mb-6">
        <div class="grid grid-cols-1 border-slate-200 sm:grid-cols-2 lg:grid-cols-4">
            <div class="group relative overflow-hidden bg-white p-4 border-r border-b border-slate-100 transition-all hover:bg-slate-50 hover:shadow-inner lg:[&:nth-child(4n)]:border-r-0 sm:[&:nth-child(2n)]:border-r-0 lg:border-b-0 sm:border-b">
                <div class="flex items-center justify-between mb-3">
                    <p class="font-semibold text-xs text-slate-500 uppercase tracking-wider">Total Locations</p>
                    <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-slate-50 text-slate-400 transition-colors group-hover:bg-slate-200 group-hover:text-slate-600">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                           <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                        </svg>
                    </div>
                </div>
                <div class="z-10">
                    <p class="text-2xl font-black tracking-tight text-slate-800">{{ $storages->count() + $sections->count() }}</p>
                    <p class="mt-0.5 text-[10px] font-medium text-slate-400">All registered locations</p>
                </div>
            </div>

            <div class="group relative overflow-hidden bg-white p-4 border-r border-b border-slate-100 transition-all hover:bg-slate-50 hover:shadow-inner lg:[&:nth-child(4n)]:border-r-0 sm:[&:nth-child(2n)]:border-r-0 lg:border-b-0 sm:border-b">
                <div class="flex items-center justify-between mb-3">
                    <p class="font-semibold text-xs text-slate-500 uppercase tracking-wider">Storage Places</p>
                    <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 transition-colors group-hover:bg-emerald-100">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6z" />
                        </svg>
                    </div>
                </div>
                <div class="z-10">
                    <p class="text-2xl font-black tracking-tight text-slate-800">{{ $storages->count() }}</p>
                    <p class="mt-0.5 text-[10px] font-medium text-slate-400">Primary locations</p>
                </div>
            </div>

            <div class="group relative overflow-hidden bg-white p-4 border-r border-b border-slate-100 transition-all hover:bg-slate-50 hover:shadow-inner lg:[&:nth-child(4n)]:border-r-0 sm:[&:nth-child(2n)]:border-r-0 lg:border-b-0">
                <div class="flex items-center justify-between mb-3">
                    <p class="font-semibold text-xs text-slate-500 uppercase tracking-wider">Sections / Bins</p>
                    <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-violet-50 text-violet-600 transition-colors group-hover:bg-violet-100">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M12 10.5v6m3-3H9m4.06-7.19l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z" />
                        </svg>
                    </div>
                </div>
                <div class="z-10">
                    <p class="text-2xl font-black tracking-tight text-slate-800">{{ $sections->count() }}</p>
                    <p class="mt-0.5 text-[10px] font-medium text-slate-400">Sub-locations</p>
                </div>
            </div>

            <div class="group relative overflow-hidden bg-white p-4 border-r border-b border-slate-100 transition-all hover:bg-slate-50 hover:shadow-inner lg:[&:nth-child(4n)]:border-r-0 sm:[&:nth-child(2n)]:border-r-0 lg:border-b-0">
                <div class="flex items-center justify-between mb-3">
                    <p class="font-semibold text-xs text-slate-500 uppercase tracking-wider">Total Items Stored</p>
                    <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-sky-50 text-sky-600 transition-colors group-hover:bg-sky-100">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375M3.75 16.125v4.125C3.75 22.653 7.444 24.75 12 24.75s8.25-2.097 8.25-4.625v-4.125" />
                        </svg>
                    </div>
                </div>
                <div class="z-10">
                    <p class="text-2xl font-black tracking-tight text-slate-800">{{ number_format($totalItems) }}</p>
                    <p class="mt-0.5 text-[10px] font-medium text-slate-400">Assigned to locations</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-5 mb-6">
        <div class="bg-white rounded-2xl overflow-hidden border border-sky-100 shadow-sm">
            <div class="p-5 border-b border-sky-100">
                <p class="text-[10px] font-semibold text-indigo-600 uppercase tracking-widest mb-0.5">Chart.01</p>
                <h3 class="text-sm font-bold text-slate-800">Storage Locations & Sections Trend</h3>
            </div>
            <div class="p-5">
                <div class="h-[240px]"><canvas id="storageLineChart"></canvas></div>
            </div>
        </div>
        <div class="bg-white rounded-2xl overflow-hidden border border-sky-100 shadow-sm">
            <div class="p-5 border-b border-sky-100">
                <p class="text-[10px] font-semibold text-teal-600 uppercase tracking-widest mb-0.5">Chart.02</p>
                <h3 class="text-sm font-bold text-slate-800">Items Distribution by Location</h3>
            </div>
            <div class="p-5">
                <div class="h-[240px]"><canvas id="distributionPieChart"></canvas></div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-5 mb-6">
        <div class="bg-white rounded-2xl overflow-hidden border border-sky-100 shadow-sm">
            <div class="p-5 border-b border-sky-100 flex items-center justify-between gap-4">
                <div>
                    <p class="text-[10px] font-semibold text-emerald-600 uppercase tracking-widest mb-0.5">Registry</p>
                    <h3 class="text-sm font-bold text-slate-800">Storage Places</h3>
                </div>
                <span class="font-mono text-xs font-bold text-emerald-600 bg-emerald-50 border border-emerald-100 px-3 py-1.5">{{ $storages->count() }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="bg-sky-50/80 border-b border-sky-100">
                            <th class="px-6 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 text-left">Location Name</th>
                            <th class="px-6 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 text-center">Items</th>
                            <th class="px-6 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-sky-50" id="storagesTableBody">
                        @forelse($storages as $storage)
                        @php $itemCount = \App\Models\Item::where('storage_location', $storage->name)->count(); @endphp
                        <tr class="hover:bg-sky-50 transition-colors">
                            <td class="px-6 py-4 font-bold text-[#0f172a]">{{ $storage->name }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-center">
                                <span class="font-mono text-[11px] font-bold text-slate-700">{{ number_format($itemCount) }}</span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('locations.edit', $storage) }}" class="inline-flex items-center border border-sky-100 bg-white px-2.5 py-1.5 text-[10px] font-mono font-bold text-slate-700 hover:bg-sky-50 transition-colors">Edit</a>
                                    <form action="{{ route('locations.destroy', $storage) }}" method="POST" class="m-0 inline"
                                        onsubmit="return confirm('Delete this storage location?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center border border-rose-200 bg-rose-50 px-2.5 py-1.5 text-[10px] font-mono font-bold text-rose-600 hover:bg-rose-500 hover:text-white hover:border-rose-500 transition-colors">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-16 text-center">
                                <p class="font-mono text-[10px] text-slate-400 uppercase tracking-widest">// No storage locations found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-2xl overflow-hidden border border-sky-100 shadow-sm">
            <div class="p-5 border-b border-sky-100 flex items-center justify-between gap-4">
                <div>
                    <p class="text-[10px] font-semibold text-violet-600 uppercase tracking-widest mb-0.5">Registry</p>
                    <h3 class="text-sm font-bold text-slate-800">Sections / Bins</h3>
                </div>
                <span class="font-mono text-xs font-bold text-violet-600 bg-violet-50 border border-violet-100 px-3 py-1.5">{{ $sections->count() }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="bg-sky-50/80 border-b border-sky-100">
                            <th class="px-6 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 text-left">Section Name</th>
                            <th class="px-6 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 text-center">Items</th>
                            <th class="px-6 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-sky-50" id="sectionsTableBody">
                        @forelse($sections as $section)
                        @php $itemCount = \App\Models\Item::where('storage_section', $section->name)->count(); @endphp
                        <tr class="hover:bg-sky-50 transition-colors">
                            <td class="px-6 py-4 font-bold text-[#0f172a]">{{ $section->name }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-center">
                                <span class="font-mono text-[11px] font-bold text-slate-700">{{ number_format($itemCount) }}</span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('locations.edit', $section) }}" class="inline-flex items-center border border-sky-100 bg-white px-2.5 py-1.5 text-[10px] font-mono font-bold text-slate-700 hover:bg-sky-50 transition-colors">Edit</a>
                                    <form action="{{ route('locations.destroy', $section) }}" method="POST" class="m-0 inline"
                                        onsubmit="return confirm('Delete this section?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center border border-rose-200 bg-rose-50 px-2.5 py-1.5 text-[10px] font-mono font-bold text-rose-600 hover:bg-rose-500 hover:text-white hover:border-rose-500 transition-colors">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-16 text-center">
                                <p class="font-mono text-[10px] text-slate-400 uppercase tracking-widest">// No sections found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const createVerticalGradient = (ctx, startColor, endColor) => {
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
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
    const tooltipStyle = {
        backgroundColor: 'rgba(15, 23, 42, 0.95)',
        titleColor: '#f8fafc',
        bodyColor: '#e2e8f0',
        borderColor: '#334155',
        borderWidth: 1,
        padding: 12,
        boxPadding: 6,
        usePointStyle: true,
        cornerRadius: 8,
        bodyFont: { family: "'Fira Code', monospace", size: 11 }
    };

    // LINE CHART
    const lineCanvas = document.getElementById('storageLineChart');
    if (lineCanvas) {
        const lctx = lineCanvas.getContext('2d');
        
        const storageLabels = @json($storages->pluck('name')->values());
        const sectionLabels = @json($sections->pluck('name')->values());
        const allLabels = [...storageLabels, ...sectionLabels];
        
        const storageCounts = @json($storages->map(function($s) { return \App\Models\Item::where('storage_location', $s->name)->count(); })->values());
        const sectionCounts = @json($sections->map(function($s) { return \App\Models\Item::where('storage_section', $s->name)->count(); })->values());
        
        new Chart(lctx, {
            type: 'line',
            data: {
                labels: allLabels,
                datasets: [
                    {
                        label: 'Storage Locations',
                        data: [...storageCounts, ...Array(sectionCounts.length).fill(null)],
                        borderColor: '#059669', // Emerald
                        backgroundColor: createVerticalGradient(lctx, 'rgba(16, 185, 129, 0.2)', 'rgba(16, 185, 129, 0.0)'),
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointBackgroundColor: '#059669',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointHoverRadius: 6,
                        segment: {
                            borderDash: (ctx) => ctx.p0DataIndex >= storageLabels.length ? [5, 5] : []
                        }
                    },
                    {
                        label: 'Sections / Bins',
                        data: [...Array(storageCounts.length).fill(null), ...sectionCounts],
                        borderColor: '#6366f1', // Indigo
                        backgroundColor: createVerticalGradient(lctx, 'rgba(99, 102, 241, 0.2)', 'rgba(99, 102, 241, 0.0)'),
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointBackgroundColor: '#6366f1',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointHoverRadius: 6,
                        segment: {
                            borderDash: (ctx) => ctx.p0DataIndex < storageLabels.length ? [5, 5] : []
                        }
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
                        display: true,
                        labels: {
                            usePointStyle: true,
                            color: '#334155',
                            font: { family: "ui-sans-serif, system-ui, sans-serif", size: 12, weight: '600' }
                        }
                    },
                    tooltip: tooltipStyle
                },
                scales: {
                    y: { beginAtZero: true, grid: baseGrid, border: { display: false }, ticks: baseTickStyle },
                    x: { grid: { display: false }, border: { display: false }, ticks: baseTickStyle }
                }
            }
        });
    }

    // PIE CHART
    const pieCanvas = document.getElementById('distributionPieChart');
    if (pieCanvas) {
        const pctx = pieCanvas.getContext('2d');
        
        const storageLabels = @json($storages->pluck('name')->values());
        const sectionLabels = @json($sections->pluck('name')->values());
        const storageCounts = @json($storages->map(function($s) { return \App\Models\Item::where('storage_location', $s->name)->count(); })->values());
        const sectionCounts = @json($sections->map(function($s) { return \App\Models\Item::where('storage_section', $s->name)->count(); })->values());
        
        // Premium horizontal gradients for doughnut slices
        const colorPalette = [
            createHorizontalGradient(pctx, '#1e3a8a', '#0ea5e9', '#7dd3fc'),
            createHorizontalGradient(pctx, '#0284c7', '#0ea5e9', '#bae6fd'),
            createHorizontalGradient(pctx, '#0f172a', '#1e3a8a', '#38bdf8'),
            createHorizontalGradient(pctx, '#ef4444', '#fb7185', '#fecaca'),
            createHorizontalGradient(pctx, '#f59e0b', '#fbbf24', '#fde68a'),
            createHorizontalGradient(pctx, '#10b981', '#34d399', '#a7f3d0'),
            createHorizontalGradient(pctx, '#4c1d95', '#7c3aed', '#c4b5fd')
        ];
        
        const storageBackgroundColors = storageLabels.map((_, idx) => colorPalette[idx % colorPalette.length]);
        const sectionBackgroundColors = sectionLabels.map((_, idx) => colorPalette[(idx + 2) % colorPalette.length]); // Offset slightly for variety
        
        new Chart(pctx, {
            type: 'doughnut',
            data: {
                labels: [...storageLabels, ...sectionLabels],
                datasets: [
                    {
                        label: 'Storage Locations',
                        data: [...storageCounts, ...Array(sectionCounts.length).fill(0)],
                        backgroundColor: [...storageBackgroundColors, ...Array(sectionCounts.length).fill('transparent')],
                        borderColor: '#ffffff',
                        borderWidth: 3,
                        hoverOffset: 6
                    },
                    {
                        label: 'Sections / Bins',
                        data: [...Array(storageCounts.length).fill(0), ...sectionCounts],
                        backgroundColor: [...Array(storageCounts.length).fill('transparent'), ...sectionBackgroundColors],
                        borderColor: '#ffffff',
                        borderWidth: 3,
                        hoverOffset: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: tooltipStyle
                }
            }
        });
    }
});
</script>
@endsection
