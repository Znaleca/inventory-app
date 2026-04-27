@extends('layouts.app')

@section('title', 'Categories Dashboard')

@section('actions')
    <a href="{{ route('categories.create') }}"
        class="inline-flex items-center gap-2 bg-sky-500 hover:bg-sky-600 text-white px-4 py-2 text-[11px] font-mono font-bold uppercase tracking-[0.15em] transition-colors border border-blue-700">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-3.5 w-3.5">
            <path
                d="M8.75 3.75a.75.75 0 00-1.5 0v3.5h-3.5a.75.75 0 000 1.5h3.5v3.5a.75.75 0 001.5 0v-3.5h3.5a.75.75 0 000-1.5h-3.5v-3.5z" />
        </svg>
        New_Category
    </a>
@endsection

@section('content')
    @php
        $deviceCats = $categories->where('item_type', 'device');
        $consumableCats = $categories->where('item_type', 'consumable');
        $totalItemsInCategories = (int) $categories->sum('items_count');
        $avgItemsPerCategory = $categories->count() > 0 ? round($totalItemsInCategories / $categories->count(), 1) : 0;
        $topCategories = $categories->sortByDesc('items_count')->take(6)->values();
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
                <div
                    class="group relative overflow-hidden bg-white p-4 border-r border-b border-slate-100 transition-all hover:bg-slate-50 hover:shadow-inner lg:[&:nth-child(4n)]:border-r-0 sm:[&:nth-child(2n)]:border-r-0 lg:border-b-0 sm:border-b">
                    <div class="flex items-center justify-between mb-3">
                        <p class="font-semibold text-xs text-slate-500 uppercase tracking-wider">Total Categories</p>
                        <div
                            class="flex h-7 w-7 items-center justify-center rounded-lg bg-slate-50 text-slate-400 transition-colors group-hover:bg-slate-200 group-hover:text-slate-600">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                            </svg>
                        </div>
                    </div>
                    <div class="z-10">
                        <p class="text-2xl font-black tracking-tight text-slate-800">{{ $categories->count() }}</p>
                        <p class="mt-0.5 text-[10px] font-medium text-slate-400">All registered categories</p>
                    </div>
                </div>

                <div
                    class="group relative overflow-hidden bg-white p-4 border-r border-b border-slate-100 transition-all hover:bg-slate-50 hover:shadow-inner lg:[&:nth-child(4n)]:border-r-0 sm:[&:nth-child(2n)]:border-r-0 lg:border-b-0 sm:border-b">
                    <div class="flex items-center justify-between mb-3">
                        <p class="font-semibold text-xs text-slate-500 uppercase tracking-wider">Devices</p>
                        <div
                            class="flex h-7 w-7 items-center justify-center rounded-lg bg-violet-50 text-violet-600 transition-colors group-hover:bg-violet-100">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25" />
                            </svg>
                        </div>
                    </div>
                    <div class="z-10">
                        <p class="text-2xl font-black tracking-tight text-slate-800">{{ $deviceCats->count() }}</p>
                        <p class="mt-0.5 text-[10px] font-medium text-slate-400">Hardware & electronics</p>
                    </div>
                </div>

                <div
                    class="group relative overflow-hidden bg-white p-4 border-r border-b border-slate-100 transition-all hover:bg-slate-50 hover:shadow-inner lg:[&:nth-child(4n)]:border-r-0 sm:[&:nth-child(2n)]:border-r-0 lg:border-b-0">
                    <div class="flex items-center justify-between mb-3">
                        <p class="font-semibold text-xs text-slate-500 uppercase tracking-wider">Consumables</p>
                        <div
                            class="flex h-7 w-7 items-center justify-center rounded-lg bg-sky-50 text-sky-600 transition-colors group-hover:bg-sky-100">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375M3.75 16.125v4.125C3.75 22.653 7.444 24.75 12 24.75s8.25-2.097 8.25-4.625v-4.125" />
                            </svg>
                        </div>
                    </div>
                    <div class="z-10">
                        <p class="text-2xl font-black tracking-tight text-slate-800">{{ $consumableCats->count() }}</p>
                        <p class="mt-0.5 text-[10px] font-medium text-slate-400">Single-use materials</p>
                    </div>
                </div>

                <div
                    class="group relative overflow-hidden bg-white p-4 border-r border-b border-slate-100 transition-all hover:bg-slate-50 hover:shadow-inner lg:[&:nth-child(4n)]:border-r-0 sm:[&:nth-child(2n)]:border-r-0 lg:border-b-0">
                    <div class="flex items-center justify-between mb-3">
                        <p class="font-semibold text-xs text-slate-500 uppercase tracking-wider">Avg Items/Cat</p>
                        <div
                            class="flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 transition-colors group-hover:bg-emerald-100">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                            </svg>
                        </div>
                    </div>
                    <div class="z-10">
                        <p class="text-2xl font-black tracking-tight text-slate-800">{{ $avgItemsPerCategory }}</p>
                        <p class="mt-0.5 text-[10px] font-medium text-slate-400">Distribution density</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-2 gap-5 mb-6">
            <div class="bg-white rounded-2xl overflow-hidden border border-sky-100 shadow-sm">
                <div class="p-5 border-b border-sky-100">
                    <p class="text-[10px] font-semibold text-indigo-600 uppercase tracking-widest mb-0.5">Chart.01</p>
                    <h3 class="text-sm font-bold text-slate-800">Category Type Distribution</h3>
                </div>
                <div class="p-5">
                    <div class="h-[240px]"><canvas id="categoryTypeChart"></canvas></div>
                </div>
            </div>
            <div class="bg-white rounded-2xl overflow-hidden border border-sky-100 shadow-sm">
                <div class="p-5 border-b border-sky-100">
                    <p class="text-[10px] font-semibold text-teal-600 uppercase tracking-widest mb-0.5">Chart.02</p>
                    <h3 class="text-sm font-bold text-slate-800">Top Categories by Item Count</h3>
                </div>
                <div class="p-5">
                    <div class="h-[240px]"><canvas id="topCategoriesChart"></canvas></div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl overflow-hidden border border-sky-100 shadow-sm">
            <div class="p-5 border-b border-sky-100 flex items-center justify-between gap-4">
                <div>
                    <p class="text-[10px] font-semibold text-sky-600 uppercase tracking-widest mb-0.5">Registry</p>
                    <h3 class="text-sm font-bold text-slate-800">Category Records</h3>
                </div>
                <div class="relative w-full max-w-sm">
                    <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z" />
                        </svg>
                    </span>
                    <input type="text" x-model="search" placeholder="Search category..."
                        class="w-full border border-sky-100 bg-white pl-9 pr-4 py-2 text-xs font-mono text-slate-700 placeholder-slate-400 focus:outline-none focus:border-slate-400 transition-colors" />
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="bg-sky-50/80 border-b border-sky-100">
                            <th
                                class="px-6 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 text-left">
                                Category Name</th>
                            <th
                                class="px-6 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 text-left">
                                Type</th>
                            <th
                                class="px-6 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 text-left">
                                Description</th>
                            <th
                                class="px-6 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 text-center">
                                Items</th>
                            <th
                                class="px-6 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 text-right">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-sky-50">
                        @forelse($categories as $category)
                            <tr class="hover:bg-sky-50 transition-colors"
                                x-show="search === '' || '{{ strtolower($category->name) }}'.includes(search.toLowerCase())">
                                <td class="px-6 py-4 font-bold text-[#0f172a]">{{ $category->name }}</td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <span
                                        class="inline-flex items-center border px-2 py-0.5 text-[9px] font-mono font-bold uppercase tracking-widest {{ $category->item_type === 'device' ? 'border-violet-200 bg-violet-50 text-violet-700' : 'border-sky-200 bg-sky-50 text-sky-700' }}">
                                        {{ $category->item_type }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-600 max-w-xs truncate">
                                    {{ $category->description ?: '—' }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-center">
                                    <span
                                        class="font-mono text-[11px] font-bold text-slate-700">{{ number_format($category->items_count) }}</span>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('categories.edit', $category) }}"
                                            class="inline-flex items-center border border-sky-100 bg-white px-2.5 py-1.5 text-[10px] font-mono font-bold text-slate-700 hover:bg-sky-50 transition-colors">Edit</a>
                                        <form action="{{ route('categories.destroy', $category) }}" method="POST"
                                            class="m-0 inline" onsubmit="return confirm('Delete this category?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center border border-rose-200 bg-rose-50 px-2.5 py-1.5 text-[10px] font-mono font-bold text-rose-600 hover:bg-rose-500 hover:text-white hover:border-rose-500 transition-colors">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <p class="font-mono text-[10px] text-slate-400 uppercase tracking-widest">// No categories
                                        found</p>
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

            const typeCanvas = document.getElementById('categoryTypeChart');
            if (typeCanvas) {
                const tctx = typeCanvas.getContext('2d');
                new Chart(tctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Device', 'Consumable'],
                        datasets: [{
                            data: [{{ $deviceCats->count() }}, {{ $consumableCats->count() }}],
                            backgroundColor: [
                                createHorizontalGradient(tctx, '#4c1d95', '#7c3aed', '#c4b5fd'), // Violet
                                createHorizontalGradient(tctx, '#0369a1', '#0ea5e9', '#bae6fd')  // Sky Blue
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
                                position: 'right',
                                labels: { usePointStyle: true, pointStyle: 'circle', color: '#64748b', font: { family: "ui-sans-serif, system-ui, sans-serif", size: 12, weight: '600' } }
                            },
                            tooltip: tooltipStyle
                        }
                    }
                });
            }

            const topCanvas = document.getElementById('topCategoriesChart');
            if (topCanvas) {
                const bctx = topCanvas.getContext('2d');
                new Chart(bctx, {
                    type: 'bar',
                    data: {
                        labels: @json($topCategories->pluck('name')->values()),
                        datasets: [{
                            label: 'Items',
                            data: @json($topCategories->pluck('items_count')->values()),
                            backgroundColor: createVerticalGradient(bctx, 'rgba(14, 165, 233, 0.9)', 'rgba(14, 165, 233, 0.1)'),
                            borderColor: '#0ea5e9',
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
                        plugins: { legend: { display: false }, tooltip: tooltipStyle },
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