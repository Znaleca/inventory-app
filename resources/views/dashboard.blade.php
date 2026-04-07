@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    @php
        $getMetricSize = function ($value) {
            $len = mb_strlen((string) $value);
            if ($len >= 16)
                return 'text-xs lg:text-sm xl:text-base tracking-tighter';
            if ($len >= 12)
                return 'text-sm lg:text-base xl:text-lg tracking-tight';
            if ($len >= 8)
                return 'text-lg lg:text-xl xl:text-2xl tracking-tight';
            return 'text-2xl lg:text-3xl xl:text-4xl tracking-tighter';
        };
    @endphp

    {{-- Stats Grid 1 --}}
<div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">

    {{-- Total Items --}}
    <div class="relative overflow-hidden rounded-[1.5rem] bg-white dark:bg-white p-5 ring-1 ring-slate-200 shadow-lg transition-all duration-300 group hover:-translate-y-1">
        <div class="flex items-center gap-2 relative z-20">
            <div class="flex h-6 w-6 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 ring-1 ring-emerald-500/20">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-3.5 w-3.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                </svg>
            </div>
            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-tighter">Total Items</span>
        </div>

        <div class="mt-4 mb-2 relative z-20 flex flex-col">
            <p class="font-black text-slate-900 leading-none whitespace-nowrap {{ $getMetricSize(number_format($totalItems)) }}">
                {{ number_format($totalItems) }}
            </p>
            <span class="mt-2 inline-flex w-fit items-center gap-1 rounded-full bg-emerald-50 px-2 py-0.5 text-[9px] font-bold text-emerald-600 ring-1 ring-emerald-500/20">
                <div class="h-1 w-1 rounded-full bg-emerald-500"></div> Active
            </span>
        </div>

        {{-- Sparkline Graph SVG --}}
        <div class="absolute bottom-0 left-0 right-0 h-12 w-full opacity-40 group-hover:opacity-60 transition-opacity">
            <svg viewBox="0 0 100 30" preserveAspectRatio="none" class="h-full w-full">
                <path d="M0 25 C 20 25, 30 15, 50 18 S 80 5, 100 10 L 100 30 L 0 30 Z" fill="url(#grad-emerald)" />
                <path d="M0 25 C 20 25, 30 15, 50 18 S 80 5, 100 10" stroke="#10b981" stroke-width="1.5" fill="none" />
                <defs>
                    <linearGradient id="grad-emerald" x1="0%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%" style="stop-color:#10b981;stop-opacity:0.2" />
                        <stop offset="100%" style="stop-color:#10b981;stop-opacity:0" />
                    </linearGradient>
                </defs>
            </svg>
        </div>
    </div>

    {{-- Low Stock --}}
    <div class="relative overflow-hidden rounded-[1.5rem] bg-white dark:bg-white p-5 ring-1 ring-slate-200 shadow-lg transition-all duration-300 group hover:-translate-y-1">
        <div class="flex items-center gap-2 relative z-20">
            <div class="flex h-6 w-6 items-center justify-center rounded-lg bg-orange-50 text-orange-600 ring-1 ring-orange-500/20">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-3.5 w-3.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                </svg>
            </div>
            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-tighter">Low Stock</span>
        </div>
        <div class="mt-4 mb-2 relative z-20 flex flex-col">
            <p class="font-black text-slate-900 leading-none whitespace-nowrap {{ $getMetricSize(number_format($lowStockCount)) }}">
                {{ number_format($lowStockCount) }}
            </p>
            <span class="mt-2 inline-flex w-fit items-center gap-1 rounded-full bg-orange-50 px-2 py-0.5 text-[9px] font-bold text-orange-600 ring-1 ring-orange-500/20">
                <div class="h-1 w-1 rounded-full bg-orange-500 animate-pulse"></div> Critical
            </span>
        </div>

        <div class="absolute bottom-0 left-0 right-0 h-12 w-full opacity-40 group-hover:opacity-60 transition-opacity">
            <svg viewBox="0 0 100 30" preserveAspectRatio="none" class="h-full w-full">
                <path d="M0 10 C 20 12, 40 25, 60 20 S 80 25, 100 22 L 100 30 L 0 30 Z" fill="url(#grad-orange)" />
                <path d="M0 10 C 20 12, 40 25, 60 20 S 80 25, 100 22" stroke="#f97316" stroke-width="1.5" fill="none" />
                <defs>
                    <linearGradient id="grad-orange" x1="0%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%" style="stop-color:#f97316;stop-opacity:0.2" />
                        <stop offset="100%" style="stop-color:#f97316;stop-opacity:0" />
                    </linearGradient>
                </defs>
            </svg>
        </div>
    </div>

    {{-- Expiring --}}
    <div class="relative overflow-hidden rounded-[1.5rem] bg-white dark:bg-white p-5 ring-1 ring-slate-200 shadow-lg transition-all duration-300 group hover:-translate-y-1">
        <div class="flex items-center gap-2 relative z-20">
            <div class="flex h-6 w-6 items-center justify-center rounded-lg bg-amber-50 text-amber-600 ring-1 ring-amber-500/20">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-3.5 w-3.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-tighter">Expiring</span>
        </div>
        <div class="mt-4 mb-2 relative z-20 flex flex-col">
            <p class="font-black text-slate-900 leading-none whitespace-nowrap {{ $getMetricSize(number_format($expiringItems->count())) }}">
                {{ number_format($expiringItems->count()) }}
            </p>
            <span class="mt-2 inline-flex w-fit items-center gap-1 rounded-full bg-amber-50 px-2 py-0.5 text-[9px] font-bold text-amber-600 ring-1 ring-amber-500/20">
                <div class="h-1 w-1 rounded-full bg-amber-500"></div> 30 Days
            </span>
        </div>

        <div class="absolute bottom-0 left-0 right-0 h-12 w-full opacity-40 group-hover:opacity-60 transition-opacity">
            <svg viewBox="0 0 100 30" preserveAspectRatio="none" class="h-full w-full">
                <path d="M0 20 C 30 18, 40 5, 70 12 S 90 15, 100 25 L 100 30 L 0 30 Z" fill="url(#grad-amber)" />
                <path d="M0 20 C 30 18, 40 5, 70 12 S 90 15, 100 25" stroke="#f59e0b" stroke-width="1.5" fill="none" />
                <defs>
                    <linearGradient id="grad-amber" x1="0%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%" style="stop-color:#f59e0b;stop-opacity:0.2" />
                        <stop offset="100%" style="stop-color:#f59e0b;stop-opacity:0" />
                    </linearGradient>
                </defs>
            </svg>
        </div>
    </div>

    {{-- Expired --}}
    <div class="relative overflow-hidden rounded-[1.5rem] bg-white dark:bg-white p-5 ring-1 ring-slate-200 shadow-lg transition-all duration-300 group hover:-translate-y-1">
        <div class="flex items-center gap-2 relative z-20">
            <div class="flex h-6 w-6 items-center justify-center rounded-lg bg-rose-50 text-rose-600 ring-1 ring-rose-500/20">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-3.5 w-3.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-tighter">Expired</span>
        </div>
        <div class="mt-4 mb-2 relative z-20 flex flex-col">
            <p class="font-black text-slate-900 leading-none whitespace-nowrap {{ $getMetricSize(number_format($expiredCount)) }}">
                {{ number_format($expiredCount) }}
            </p>
            <span class="mt-2 inline-flex w-fit items-center gap-1 rounded-full bg-rose-50 px-2 py-0.5 text-[9px] font-bold text-rose-600 ring-1 ring-rose-500/20">
                <div class="h-1 w-1 rounded-full bg-rose-500"></div> Disposal
            </span>
        </div>

        <div class="absolute bottom-0 left-0 right-0 h-12 w-full opacity-40 group-hover:opacity-60 transition-opacity">
            <svg viewBox="0 0 100 30" preserveAspectRatio="none" class="h-full w-full">
                <path d="M0 25 L 20 15 L 40 22 L 60 10 L 80 18 L 100 5 L 100 30 L 0 30 Z" fill="url(#grad-rose)" />
                <path d="M0 25 L 20 15 L 40 22 L 60 10 L 80 18 L 100 5" stroke="#f43f5e" stroke-width="1.5" fill="none" />
                <defs>
                    <linearGradient id="grad-rose" x1="0%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%" style="stop-color:#f43f5e;stop-opacity:0.2" />
                        <stop offset="100%" style="stop-color:#f43f5e;stop-opacity:0" />
                    </linearGradient>
                </defs>
            </svg>
        </div>
    </div>

    {{-- Value --}}
    <div class="relative overflow-hidden rounded-[1.5rem] bg-white dark:bg-white p-5 ring-1 ring-slate-200 shadow-lg transition-all duration-300 group hover:-translate-y-1">
        <div class="flex items-center gap-2 relative z-20">
            <div class="flex h-6 w-6 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 ring-1 ring-indigo-500/20">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-3.5 w-3.5">
                    <rect width="20" height="12" x="2" y="6" rx="2" />
                    <circle cx="12" cy="12" r="2" />
                </svg>
            </div>
            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-tighter">Total Value</span>
        </div>
        <div class="mt-4 mb-2 relative z-20 flex flex-col">
            <p class="font-black text-slate-900 leading-none whitespace-nowrap {{ $getMetricSize('₱' . number_format($totalStockValue, 2)) }}">
                ₱{{ number_format($totalStockValue, 2) }}
            </p>
            <span class="mt-2 inline-flex w-fit items-center gap-1 rounded-full bg-indigo-50 px-2 py-0.5 text-[9px] font-bold text-indigo-600 ring-1 ring-indigo-500/20">
                <div class="h-1 w-1 rounded-full bg-indigo-500"></div> Est. Total
            </span>
        </div>

        <div class="absolute bottom-0 left-0 right-0 h-12 w-full opacity-40 group-hover:opacity-60 transition-opacity">
            <svg viewBox="0 0 100 30" preserveAspectRatio="none" class="h-full w-full">
                <path d="M0 28 C 10 28, 20 5, 40 10 S 70 25, 100 5 L 100 30 L 0 30 Z" fill="url(#grad-indigo)" />
                <path d="M0 28 C 10 28, 20 5, 40 10 S 70 25, 100 5" stroke="#6366f1" stroke-width="1.5" fill="none" />
                <defs>
                    <linearGradient id="grad-indigo" x1="0%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%" style="stop-color:#6366f1;stop-opacity:0.2" />
                        <stop offset="100%" style="stop-color:#6366f1;stop-opacity:0" />
                    </linearGradient>
                </defs>
            </svg>
        </div>
    </div>
</div>

    {{-- Additional Metrics --}}
    <div class="mb-8 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        {{-- New Items --}}
        <div
            class="relative overflow-hidden rounded-[1.5rem] bg-white dark:bg-white/90 p-5 ring-1 ring-slate-200 dark:ring-slate-200/60 shadow-sm dark:shadow-[0_4px_20px_-8px_rgba(0,0,0,0.06)] transition-all duration-300 hover:-translate-y-0.5 hover:shadow-md group">
            <div class="flex items-center gap-4 relative z-10">
                <div
                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-teal-50 text-teal-600 dark:bg-teal-500/10 dark:text-teal-400 ring-1 ring-teal-500/20 dark:ring-teal-500/20 group-hover:bg-teal-500/20 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-xl font-bold text-slate-800 dark:text-slate-800">{{ $totalNewStock }}</p>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-400">New Items
                    </p>
                </div>
            </div>
        </div>

        {{-- Used Items --}}
        <div
            class="relative overflow-hidden rounded-[1.5rem] bg-white dark:bg-white/90 p-5 ring-1 ring-slate-200 dark:ring-slate-200/60 shadow-sm dark:shadow-[0_4px_20px_-8px_rgba(0,0,0,0.06)] transition-all duration-300 hover:-translate-y-0.5 hover:shadow-md group">
            <div class="flex items-center gap-4 relative z-10">
                <div
                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400 ring-1 ring-amber-500/20 dark:ring-amber-500/20 group-hover:bg-amber-500/20 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-xl font-bold text-slate-800 dark:text-slate-800">{{ $totalUsedStock }}</p>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-400">Used Items
                    </p>
                </div>
            </div>
        </div>

        {{-- Borrowed Items --}}
        <div
            class="relative overflow-hidden rounded-[1.5rem] bg-white dark:bg-white/90 p-5 ring-1 ring-slate-200 dark:ring-slate-200/60 shadow-sm dark:shadow-[0_4px_20px_-8px_rgba(0,0,0,0.06)] transition-all duration-300 hover:-translate-y-0.5 hover:shadow-md group">
            <div class="flex items-center gap-4 relative z-10">
                <div
                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400 ring-1 ring-blue-500/20 dark:ring-blue-500/20 group-hover:bg-blue-500/20 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-xl font-bold text-slate-800 dark:text-slate-800">{{ $totalBorrowedCount }}</p>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-400">Borrowed
                    </p>
                </div>
            </div>
        </div>

        {{-- Pending Returns --}}
        <div
            class="relative overflow-hidden rounded-[1.5rem] bg-white dark:bg-white/90 p-5 ring-1 ring-slate-200 dark:ring-slate-200/60 shadow-sm dark:shadow-[0_4px_20px_-8px_rgba(0,0,0,0.06)] transition-all duration-300 hover:-translate-y-0.5 hover:shadow-md group">
            <div class="flex items-center gap-4 relative z-10">
                <div
                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-orange-50 text-orange-600 dark:bg-orange-500/10 dark:text-orange-400 ring-1 ring-orange-500/20 dark:ring-orange-500/20 group-hover:bg-orange-500/20 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-xl font-bold text-slate-800 dark:text-slate-800">{{ $pendingReturnsCount }}</p>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-400">To Return
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Tables Grid --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 xl:grid-cols-3">

        {{-- Low Stock Alerts Card --}}
        <div
            class="overflow-hidden rounded-[1.5rem] bg-white ring-1 ring-slate-200/60 shadow-[0_8px_30px_-12px_rgba(0,0,0,0.04)] flex flex-col">
            <div class="flex items-center justify-between border-b border-slate-100 bg-slate-50/50 px-3 py-2.5 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-orange-100/50 text-orange-500">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                            <path fill-rule="evenodd"
                                d="M10 2a.75.75 0 01.75.75v.258a33.186 33.186 0 016.668.83.75.75 0 01-.336 1.461 31.28 31.28 0 00-1.103-.232l1.702 7.545a.75.75 0 01-.387.832A4.981 4.981 0 0115 14c-.825 0-1.606-.2-2.294-.556a.75.75 0 01-.387-.832l1.77-7.849a31.743 31.743 0 00-3.339-.254v9.505a20.023 20.023 0 013.78.501.75.75 0 01-.362 1.454A18.458 18.458 0 0010 15.75a18.454 18.454 0 00-4.168.474.75.75 0 01-.363-1.454 20.02 20.02 0 013.781-.501V5.509a31.68 31.68 0 00-3.339.254l1.771 7.849a.75.75 0 01-.387.832A4.979 4.979 0 015 14a4.98 4.98 0 01-2.294-.556.75.75 0 01-.387-.832L4.02 5.067c-.37.07-.738.148-1.103.232A.75.75 0 012.25 3.84a33.17 33.17 0 016.668-.83V2.75A.75.75 0 0110 2zm-5 9.77l-1.372-6.086a30.158 30.158 0 012.744 0L5 11.77zm10 0l-1.372-6.086a30.066 30.066 0 012.744 0L15 11.77z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-800">Low Stock Alerts</h3>
                </div>
                <a href="{{ route('items.index') }}"
                    class="inline-flex items-center rounded-lg bg-white px-3 py-1.5 text-xs font-semibold text-slate-600 ring-1 ring-inset ring-slate-200 transition-all hover:bg-slate-50 hover:text-slate-900 hover:shadow-sm">
                    View All
                </a>
            </div>
            @if($lowStockItems->count() > 0)
                <div class="overflow-x-auto flex-1 p-2">
                    <table class="min-w-full text-sm border-separate border-spacing-y-1">
                        <thead>
                            <tr>
                                <th
                                    class="whitespace-nowrap py-2 pl-4 pr-3 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">
                                    Item</th>
                                <th
                                    class="whitespace-nowrap py-2 px-3 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">
                                    Status</th>
                                <th
                                    class="whitespace-nowrap py-2 pl-3 pr-4 text-right text-[10px] font-bold uppercase tracking-widest text-slate-400">
                                    Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lowStockItems->take(5) as $item)
                                        <tr class="group transition-all duration-200 hover:bg-slate-50 rounded-xl">
                                            <td class="py-3 pl-4 pr-3 rounded-l-xl">
                                                <div class="flex flex-col">
                                                    <span class="text-sm font-bold text-slate-800 line-clamp-1">{{ $item->name }}</span>
                                                    <span class="text-xs font-medium text-slate-400 line-clamp-1">{{ $item->category->name
                                                        }}</span>
                                                </div>
                                            </td>
                                            <td class="whitespace-nowrap py-3 px-3">
                                                <div class="flex flex-col gap-1">
                                                    <span
                                                        class="inline-flex w-fit items-center rounded-md px-2 py-1 text-[11px] font-bold ring-1 ring-inset {{ $item->total_stock <= 0 ? 'bg-rose-50 text-rose-600 ring-rose-500/20' : 'bg-amber-50 text-amber-600 ring-amber-500/20' }}">
                                                        {{ $item->total_stock }} {{ $item->unit }} Left
                                                    </span>
                                                    <span class="text-[10px] font-semibold text-slate-400">Reorder at {{
                                $item->reorder_level }}</span>
                                                </div>
                                            </td>
                                            <td class="whitespace-nowrap py-3 pl-3 pr-4 text-right rounded-r-xl">
                                                <a href="{{ route('stock.create', $item) }}"
                                                    class="inline-flex items-center justify-center rounded-lg bg-slate-900 px-3 py-1.5 text-xs font-semibold text-white shadow-sm transition-all hover:bg-emerald-500 hover:shadow-emerald-500/20">
                                                    Restock
                                                </a>
                                            </td>
                                        </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="flex flex-1 flex-col items-center justify-center py-12 text-center">
                    <div class="mb-3 rounded-full bg-emerald-50 p-3 text-emerald-500 ring-1 ring-emerald-500/20">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="h-6 w-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-slate-600">Inventory is healthy</p>
                    <p class="text-xs text-slate-400 mt-1">No low stock alerts found.</p>
                </div>
            @endif
        </div>

        {{-- Expiring Soon Card --}}
        <div
            class="overflow-hidden rounded-[1.5rem] bg-white ring-1 ring-slate-200/60 shadow-[0_8px_30px_-12px_rgba(0,0,0,0.04)] flex flex-col">
            <div class="flex items-center justify-between border-b border-slate-100 bg-slate-50/50 px-3 py-2.5 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-100/50 text-amber-500">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-13a.75.75 0 00-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 000-1.5h-3.25V5z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-800">Expiring Soon (30 Days)</h3>
                </div>
            </div>
            @if($expiringItems->count() > 0)
                <div class="overflow-x-auto flex-1 p-2">
                    <table class="min-w-full text-sm border-separate border-spacing-y-1">
                        <thead>
                            <tr>
                                <th
                                    class="whitespace-nowrap py-2 pl-4 pr-3 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">
                                    Item Details</th>
                                <th
                                    class="whitespace-nowrap py-2 px-3 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">
                                    Expiration Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($expiringItems->take(5) as $item)
                                @php
                                    $breakdownLookup = collect($item->batches_breakdown)->keyBy('id');
                                @endphp
                                @foreach($item->stockEntries as $entry)
                                        @php
                                            $batchData = $breakdownLookup->get($entry->id);
                                            if (!$batchData)
                                                continue;
                                            $daysLeft = now()->startOfDay()->diffInDays($entry->expiry_date->startOfDay(), false);
                                        $isCritical = $daysLeft <= 7; @endphp <tr
                                            class="group transition-all duration-200 hover:bg-slate-50 rounded-xl">
                                            <td class="py-3 pl-4 pr-3 rounded-l-xl">
                                                <div class="flex flex-col">
                                                    <div class="flex items-center gap-2">
                                                        <span class="text-sm font-bold text-slate-800 line-clamp-1">{{ $item->name }}</span>
                                                        <span
                                                            class="inline-flex items-center rounded-md bg-slate-100 px-1.5 py-0.5 text-[10px] font-semibold text-slate-500">{{
                                    $batchData['remaining'] }} {{ $item->unit }}</span>
                                                    </div>
                                                    <span class="text-[11px] font-medium text-slate-400 font-mono mt-0.5">Lot: {{
                                    $entry->lot_number ?? 'N/A' }}</span>
                                                </div>
                                            </td>
                                            <td class="whitespace-nowrap py-3 pr-4 pl-3 rounded-r-xl">
                                                <div class="flex items-center gap-2">
                                                    <span
                                                        class="inline-flex items-center rounded-md px-2 py-1 text-[11px] font-bold ring-1 ring-inset {{ $isCritical ? 'bg-rose-50 text-rose-600 ring-rose-500/20' : 'bg-amber-50 text-amber-600 ring-amber-500/20' }}">
                                                        {{ $entry->expiry_date->format('M d, Y') }}
                                                    </span>
                                                    <span
                                                        class="text-[10px] font-semibold {{ $isCritical ? 'text-rose-500' : 'text-amber-500' }}">
                                                        {{ $daysLeft < 0 ? 'Expired' : ($daysLeft == 0 ? 'Today' : $daysLeft . 'd left') }}
                                                    </span>
                                                </div>
                                            </td>
                                        </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="flex flex-1 flex-col items-center justify-center py-12 text-center">
                    <div class="mb-3 rounded-full bg-emerald-50 p-3 text-emerald-500 ring-1 ring-emerald-500/20">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="h-6 w-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-slate-600">No expiring items</p>
                    <p class="text-xs text-slate-400 mt-1">All batches are well within safe dates.</p>
                </div>
            @endif
        </div>

        {{-- Expired Items Card --}}
        <div
            class="overflow-hidden rounded-[1.5rem] bg-white ring-1 ring-slate-200/60 shadow-[0_8px_30px_-12px_rgba(0,0,0,0.04)] flex flex-col">
            <div class="flex items-center justify-between border-b border-slate-100 bg-slate-50/50 px-3 py-2.5 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-rose-100/50 text-rose-600">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-13a.75.75 0 00-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 000-1.5h-3.25V5z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-800">Need Disposal (Expired)</h3>
                </div>
                <a href="{{ route('items.index') }}"
                    class="inline-flex items-center rounded-lg bg-white px-3 py-1.5 text-xs font-semibold text-slate-600 ring-1 ring-inset ring-slate-200 transition-all hover:bg-slate-50 hover:text-slate-900 hover:shadow-sm">
                    View All
                </a>
            </div>
            @if($expiredCount > 0)
                <div class="overflow-x-auto flex-1 p-2">
                    <table class="min-w-full text-sm border-separate border-spacing-y-1">
                        <thead>
                            <tr>
                                <th
                                    class="whitespace-nowrap py-2 pl-4 pr-3 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">
                                    Item Details</th>
                                <th
                                    class="whitespace-nowrap py-2 px-3 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">
                                    Expired On</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($expiredItems->take(5) as $item)
                                @php
                                    $breakdownLookup = collect($item->batches_breakdown)->keyBy('id');
                                @endphp
                                @foreach($item->stockEntries as $entry)
                                    @php
                                        $batchData = $breakdownLookup->get($entry->id);
                                        if (!$batchData)
                                            continue;
                                        $daysSince = now()->startOfDay()->diffInDays($entry->expiry_date->startOfDay(), false);
                                        if ($daysSince >= 0)
                                            continue; 
                                    @endphp
                                    <tr class="group transition-all duration-200 hover:bg-slate-50 rounded-xl">
                                        <td class="py-3 pl-4 pr-3 rounded-l-xl">
                                            <div class="flex flex-col">
                                                <div class="flex items-center gap-2">
                                                    <span class="text-sm font-bold text-slate-800 line-clamp-1">{{ $item->name }}</span>
                                                    <span
                                                        class="inline-flex items-center rounded-md bg-rose-50 px-1.5 py-0.5 text-[10px] font-bold text-rose-600 ring-1 ring-inset ring-rose-500/20">{{ $batchData['remaining'] }}
                                                        {{ $item->unit }}</span>
                                                </div>
                                                <span class="text-[11px] font-medium text-slate-400 font-mono mt-0.5">Lot:
                                                    {{ $entry->lot_number ?? 'N/A' }}</span>
                                            </div>
                                        </td>
                                        <td class="whitespace-nowrap py-3 pr-4 pl-3 rounded-r-xl">
                                            <div class="flex flex-col gap-1">
                                                <span
                                                    class="text-[11px] font-bold text-slate-600">{{ $entry->expiry_date->format('M d, Y') }}</span>
                                                <span class="text-[10px] font-semibold text-rose-500">{{ abs($daysSince) }} days
                                                    ago</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="flex flex-1 flex-col items-center justify-center py-12 text-center">
                    <div class="mb-3 rounded-full bg-emerald-50 p-3 text-emerald-500 ring-1 ring-emerald-500/20">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="h-6 w-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-slate-600">Zero expired items</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Recent Usage --}}
    <div
        class="mt-6 overflow-hidden rounded-[1.5rem] bg-white ring-1 ring-slate-200/60 shadow-[0_8px_30px_-12px_rgba(0,0,0,0.04)]">
        <div class="flex items-center justify-between border-b border-slate-100 bg-slate-50/50 px-3 py-2.5">
            <div class="flex items-center gap-3">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100/80 text-slate-500">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                        <path fill-rule="evenodd"
                            d="M6 4.75A.75.75 0 016.75 4h10.5a.75.75 0 010 1.5H6.75A.75.75 0 016 4.75zM6 10a.75.75 0 01.75-.75h10.5a.75.75 0 010 1.5H6.75A.75.75 0 016 10zm0 5.25a.75.75 0 01.75-.75h10.5a.75.75 0 010 1.5H6.75a.75.75 0 01-.75-.75zM1.99 4.75a1 1 0 011-1H3a1 1 0 011 1v.01a1 1 0 01-1 1h-.01a1 1 0 01-1-1v-.01zM1.99 15.25a1 1 0 011-1H3a1 1 0 011 1v.01a1 1 0 01-1 1h-.01a1 1 0 01-1-1v-.01zM1.99 10a1 1 0 011-1H3a1 1 0 011 1v.01a1 1 0 01-1 1h-.01a1 1 0 01-1-1V10z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-slate-800">Recent Usage Activity</h3>
            </div>
            <a href="{{ route('logs.index') }}"
                class="inline-flex items-center rounded-lg bg-white px-3 py-1.5 text-xs font-semibold text-slate-600 ring-1 ring-inset ring-slate-200 transition-all hover:bg-slate-50 hover:text-slate-900 hover:shadow-sm">
                View Full Log
            </a>
        </div>
        @if($recentUsage->count() > 0)
            <div class="overflow-x-auto p-2">
                <table class="min-w-full text-sm border-separate border-spacing-y-1">
                    <thead>
                        <tr>
                            <th
                                class="whitespace-nowrap py-2 pl-4 pr-3 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">
                                Item</th>
                            <th
                                class="whitespace-nowrap py-2 px-3 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">
                                Qty</th>
                            <th
                                class="whitespace-nowrap py-2 px-3 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">
                                Procedure</th>
                            <th
                                class="whitespace-nowrap py-2 px-3 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">
                                Used By</th>
                            <th
                                class="whitespace-nowrap py-2 pl-3 pr-4 text-right text-[10px] font-bold uppercase tracking-widest text-slate-400">
                                Timestamp</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentUsage as $log)
                                <tr class="group transition-all duration-200 hover:bg-slate-50 rounded-xl">
                                    <td class="py-3 pl-4 pr-3 rounded-l-xl">
                                        <span class="text-sm font-bold text-slate-800 line-clamp-1">{{ $log->item->name }}</span>
                                    </td>
                                    <td class="whitespace-nowrap py-3 px-3">
                                        <span
                                            class="inline-flex items-center rounded-md bg-slate-100 px-2 py-1 text-[11px] font-bold text-slate-600 ring-1 ring-inset ring-slate-500/10">
                                            -{{ $log->quantity_used }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap py-3 px-3 text-xs font-semibold text-slate-500">{{ $log->procedure_type
                            ?? '—' }}</td>
                                    <td class="whitespace-nowrap py-3 px-3">
                                        <div class="flex items-center gap-2">
                                            <div
                                                class="flex h-5 w-5 items-center justify-center rounded-full bg-indigo-100 text-[9px] font-bold text-indigo-600">
                                                {{ strtoupper(substr($log->used_by ?? 'U', 0, 1)) }}
                                            </div>
                                            <span class="text-xs font-semibold text-slate-600">{{ $log->used_by ?? '—' }}</span>
                                        </div>
                                    </td>
                                    <td
                                        class="whitespace-nowrap py-3 pl-3 pr-4 text-right rounded-r-xl text-[11px] font-semibold text-slate-400">
                                        {{ $log->used_at->format('M d, Y') }} <span class="text-slate-300 mx-1">•</span> {{
                            $log->used_at->format('h:i A') }}
                                    </td>
                                </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Recent Returns --}}
    <div
        class="overflow-hidden rounded-[1.5rem] bg-white ring-1 ring-slate-200/60 shadow-[0_8px_30px_-12px_rgba(0,0,0,0.04)]">
        <div class="flex items-center justify-between border-b border-slate-100 bg-slate-50/50 px-3 py-2.5">
            <div class="flex items-center gap-3">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100/50 text-emerald-600">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                        <path fill-rule="evenodd" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" clip-rule="evenodd" />
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-slate-800">Recent Returns</h3>
            </div>
            <a href="{{ route('in-out.index') }}"
                class="inline-flex items-center rounded-lg bg-white px-3 py-1.5 text-xs font-semibold text-slate-600 ring-1 ring-inset ring-slate-200 transition-all hover:bg-slate-50 hover:text-slate-900 hover:shadow-sm">
                View All
            </a>
        </div>
        @if($recentReturns->count() > 0)
            <div class="overflow-x-auto p-2">
                <table class="min-w-full text-sm border-separate border-spacing-y-1">
                    <thead>
                        <tr>
                            <th
                                class="whitespace-nowrap py-2 pl-4 pr-3 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">
                                Item</th>
                            <th
                                class="whitespace-nowrap py-2 px-3 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">
                                Qty</th>
                            <th
                                class="whitespace-nowrap py-2 px-3 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">
                                Returned By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentReturns as $return)
                            <tr class="group transition-all duration-200 hover:bg-slate-50 rounded-xl">
                                <td class="py-3 pl-4 pr-3 rounded-l-xl">
                                    <span class="text-sm font-bold text-slate-800 line-clamp-1">{{ $return->item->name }}</span>
                                </td>
                                <td class="whitespace-nowrap py-3 px-3">
                                    <span
                                        class="inline-flex items-center rounded-md bg-emerald-50 px-2 py-1 text-[11px] font-bold text-emerald-600 ring-1 ring-inset ring-emerald-500/20">
                                        +{{ $return->quantity_returned }} Ret
                                    </span>
                                </td>
                                <td class="whitespace-nowrap py-3 pl-3 pr-4 rounded-r-xl">
                                    <div class="flex items-center gap-2">
                                        <span
                                            class="text-xs font-semibold text-slate-600">{{ $return->borrower_name ?? $return->staff?->display_name ?? '—' }}</span>
                                    </div>
                                    <div class="text-[10px] text-slate-400">{{ $return->returned_at->format('M d, Y h:i A') }}</div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="py-12 text-center text-sm font-semibold text-slate-500">No returns recorded yet.</div>
        @endif
    </div>

    {{-- Recent Disposals --}}
    <div
        class="overflow-hidden rounded-[1.5rem] bg-white ring-1 ring-slate-200/60 shadow-[0_8px_30px_-12px_rgba(0,0,0,0.04)]">
        <div class="flex items-center justify-between border-b border-slate-100 bg-slate-50/50 px-3 py-2.5">
            <div class="flex items-center gap-3">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-orange-100/50 text-orange-600">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                        <path fill-rule="evenodd"
                            d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-slate-800">Recent Disposals</h3>
            </div>
            <a href="{{ route('admin.records.index') }}?tab=disposals"
                class="inline-flex items-center rounded-lg bg-white px-3 py-1.5 text-xs font-semibold text-slate-600 ring-1 ring-inset ring-slate-200 transition-all hover:bg-slate-50 hover:text-slate-900 hover:shadow-sm">
                View All
            </a>
        </div>
        @if($recentDisposals->count() > 0)
            <div class="overflow-x-auto p-2">
                <table class="min-w-full text-sm border-separate border-spacing-y-1">
                    <thead>
                        <tr>
                            <th
                                class="whitespace-nowrap py-2 pl-4 pr-3 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">
                                Item</th>
                            <th
                                class="whitespace-nowrap py-2 px-3 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">
                                Qty</th>
                            <th
                                class="whitespace-nowrap py-2 px-3 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">
                                Reason</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentDisposals as $disposal)
                            <tr class="group transition-all duration-200 hover:bg-slate-50 rounded-xl">
                                <td class="py-3 pl-4 pr-3 rounded-l-xl">
                                    <span class="text-sm font-bold text-slate-800 line-clamp-1">{{ $disposal->item->name }}</span>
                                    <div class="text-[10px] text-slate-400">{{ $disposal->disposed_at->format('M d, Y') }}</div>
                                </td>
                                <td class="whitespace-nowrap py-3 px-3">
                                    <span
                                        class="inline-flex items-center rounded-md bg-stone-100 px-2 py-1 text-[11px] font-bold text-stone-600 ring-1 ring-inset ring-stone-500/10">
                                        -{{ $disposal->quantity }}
                                        @if($disposal->type === 'used')
                                            <span class="ml-1 text-[9px] uppercase tracking-wider text-amber-600">(Used)</span>
                                        @elseif($disposal->type === 'new')
                                            <span class="ml-1 text-[9px] uppercase tracking-wider text-rose-600">(New)</span>
                                        @endif
                                    </span>
                                </td>
                                <td class="whitespace-nowrap py-3 pl-3 pr-4 rounded-r-xl">
                                    <span
                                        class="text-xs font-medium text-slate-600">{{ Str::limit($disposal->reason, 25) ?? '—' }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="py-12 text-center text-sm font-semibold text-slate-500">No disposals recorded yet.</div>
        @endif
    </div>
    </div>
@endsection