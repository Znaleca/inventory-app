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

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-sky-100 p-5">
            <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-widest">Total Locations</p>
            <p class="text-2xl font-black text-slate-800 mt-2">{{ $storages->count() + $sections->count() }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-sky-100 p-5">
            <p class="text-[10px] font-semibold text-emerald-500 uppercase tracking-widest">Storage Places</p>
            <p class="text-2xl font-black text-emerald-600 mt-2">{{ $storages->count() }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-sky-100 p-5">
            <p class="text-[10px] font-semibold text-violet-500 uppercase tracking-widest">Sections / Bins</p>
            <p class="text-2xl font-black text-violet-600 mt-2">{{ $sections->count() }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-sky-100 p-5">
            <p class="text-[10px] font-semibold text-sky-500 uppercase tracking-widest">Total Items Stored</p>
            <p class="text-2xl font-black text-sky-600 mt-2">{{ number_format($totalItems) }}</p>
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
    const makeGradient = (ctx, c1, c2) => {
        const g = ctx.createLinearGradient(0, 0, 0, 300);
        g.addColorStop(0, c1);
        g.addColorStop(1, c2);
        return g;
    };

    // COLOR DOTS FOR PIE CHART LEGEND
    const sectionColorDots = document.querySelectorAll('.section-color-dot');
    const storageColors = [
        'rgba(34, 197, 94, 0.9)',      // emerald - dark
        'rgba(59, 130, 246, 0.9)',     // blue - dark
        'rgba(249, 115, 22, 0.9)',     // orange - dark
        'rgba(236, 72, 153, 0.9)',     // pink - dark
    ];
    
    const sectionColors = [
        'rgba(139, 92, 246, 0.7)',     // violet - medium
        'rgba(34, 211, 238, 0.7)',     // cyan - medium
        'rgba(168, 85, 247, 0.7)',     // purple - medium
        'rgba(14, 165, 233, 0.7)',     // sky - medium
        'rgba(244, 63, 94, 0.7)',      // rose - medium
        'rgba(249, 191, 22, 0.7)',     // yellow - medium
        'rgba(236, 217, 100, 0.7)',    // lime - medium
    ];

    // LINE CHART - Storage Locations & Sections Trend
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
                        borderColor: 'rgba(34, 197, 94, 0.9)',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 5,
                        pointBackgroundColor: 'rgba(34, 197, 94, 0.9)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointHoverRadius: 7,
                        segment: {
                            borderDash: (ctx) => ctx.p0DataIndex >= storageLabels.length ? [5, 5] : []
                        }
                    },
                    {
                        label: 'Sections / Bins',
                        data: [...Array(storageCounts.length).fill(null), ...sectionCounts],
                        borderColor: 'rgba(139, 92, 246, 0.9)',
                        backgroundColor: 'rgba(139, 92, 246, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 5,
                        pointBackgroundColor: 'rgba(139, 92, 246, 0.9)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointHoverRadius: 7,
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
                            color: '#64748b',
                            font: { size: 12, weight: 'bold' }
                        }
                    }
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

    // PIE CHART - Nested (Storage outer, Sections inner)
    const pieCanvas = document.getElementById('distributionPieChart');
    if (pieCanvas) {
        const pctx = pieCanvas.getContext('2d');
        
        const storageLabels = @json($storages->pluck('name')->values());
        const sectionLabels = @json($sections->pluck('name')->values());
        const storageCounts = @json($storages->map(function($s) { return \App\Models\Item::where('storage_location', $s->name)->count(); })->values());
        const sectionCounts = @json($sections->map(function($s) { return \App\Models\Item::where('storage_section', $s->name)->count(); })->values());
        
        const storageColors = [
            'rgba(34, 197, 94, 0.9)',      // emerald - dark
            'rgba(59, 130, 246, 0.9)',     // blue - dark
            'rgba(249, 115, 22, 0.9)',     // orange - dark
            'rgba(236, 72, 153, 0.9)',     // pink - dark
        ];
        
        const sectionColors = [
            'rgba(139, 92, 246, 0.7)',     // violet - medium
            'rgba(34, 211, 238, 0.7)',     // cyan - medium
            'rgba(168, 85, 247, 0.7)',     // purple - medium
            'rgba(14, 165, 233, 0.7)',     // sky - medium
            'rgba(244, 63, 94, 0.7)',      // rose - medium
            'rgba(249, 191, 22, 0.7)',     // yellow - medium
            'rgba(236, 217, 100, 0.7)',    // lime - medium
        ];
        
        const storageBackgroundColors = storageLabels.map((_, idx) => storageColors[idx % storageColors.length]);
        const sectionBackgroundColors = sectionLabels.map((_, idx) => sectionColors[idx % sectionColors.length]);
        
        const storageBorderColors = storageBackgroundColors.map(c => c.replace('0.9', '1'));
        const sectionBorderColors = sectionBackgroundColors.map(c => c.replace('0.7', '0.9'));
        
        new Chart(pctx, {
            type: 'doughnut',
            data: {
                labels: [...storageLabels, ...sectionLabels],
                datasets: [
                    {
                        label: 'Storage Locations',
                        data: [...storageCounts, ...Array(sectionCounts.length).fill(0)],
                        backgroundColor: [...storageBackgroundColors, ...Array(sectionCounts.length).fill('transparent')],
                        borderColor: [...storageBorderColors, ...Array(sectionCounts.length).fill('transparent')],
                        borderWidth: 2
                    },
                    {
                        label: 'Sections / Bins',
                        data: [...Array(storageCounts.length).fill(0), ...sectionCounts],
                        backgroundColor: [...Array(storageCounts.length).fill('transparent'), ...sectionBackgroundColors],
                        borderColor: [...Array(storageCounts.length).fill('transparent'), ...sectionBorderColors],
                        borderWidth: 2
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            color: '#64748b',
                            font: { size: 11 },
                            padding: 15,
                            usePointStyle: false,
                            generateLabels: function(chart) {
                                const data = chart.data;
                                const labels = data.labels || [];
                                const allColors = [...storageBackgroundColors, ...sectionBackgroundColors];
                                
                                return labels.map((label, idx) => ({
                                    text: label,
                                    fillStyle: allColors[idx],
                                    hidden: false,
                                    index: idx,
                                    pointStyle: 'circle'
                                }));
                            }
                        },
                        onClick: function(e, legendItem, legend) {
                            // Prevent legend click behavior
                        }
                    }
                }
            }
        });
    }
});
</script>
@endsection
