@extends('layouts.app')

@section('title', 'Inventory Items')

@section('actions')
    <a href="{{ route('items.create') }}"
        class="group relative inline-flex items-center gap-2 overflow-hidden rounded-2xl bg-gradient-to-br from-slate-800 to-slate-900 px-5 py-2.5 text-sm font-bold text-white shadow-[0_8px_16px_-6px_rgba(15,23,42,0.5)] transition-all duration-300 hover:-translate-y-0.5 hover:shadow-[0_12px_20px_-6px_rgba(15,23,42,0.6)] focus:outline-none focus:ring-2 focus:ring-slate-900 focus:ring-offset-2">
        <div
            class="absolute inset-0 bg-gradient-to-b from-white/10 to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100">
        </div>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
            class="relative h-4 w-4 transition-transform duration-300 group-hover:rotate-90">
            <path
                d="M8.75 3.75a.75.75 0 00-1.5 0v3.5h-3.5a.75.75 0 000 1.5h3.5v3.5a.75.75 0 001.5 0v-3.5h3.5a.75.75 0 000-1.5h-3.5v-3.5z" />
        </svg>
        <span class="relative">New Item</span>
    </a>
@endsection

@section('content')
    {{-- Search & Filter Island --}}
    <div
        class="mb-6 rounded-[1.5rem] bg-white/80 p-4 ring-1 ring-slate-200/50 shadow-[0_8px_30px_-12px_rgba(0,0,0,0.04)] backdrop-blur-xl">
        <form method="GET" action="{{ route('items.index') }}" class="flex flex-wrap items-center gap-3">

            {{-- Search Input --}}
            <div class="relative flex-1 sm:min-w-[320px]">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="h-4 w-4 text-slate-400">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                    class="block w-full rounded-xl border-0 bg-slate-50/50 py-2.5 pl-10 pr-4 text-sm text-slate-800 ring-1 ring-inset ring-slate-200/80 dark:ring-0 placeholder:text-slate-400 transition-all hover:bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500/50"
                    placeholder="Search items by name, SKU...">
            </div>

            {{-- Category Filter --}}
            <select name="category"
                class="block w-full sm:w-48 rounded-xl border-0 bg-slate-50/50 px-4 py-2.5 text-sm text-slate-800 ring-1 ring-inset ring-slate-200/80 dark:ring-0 transition-all hover:bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500/50">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>

            {{-- Buttons --}}
            <div class="flex w-full gap-2 sm:w-auto">

                {{-- SEARCH BUTTON --}}
                <button type="submit"
                    class="flex flex-1 items-center justify-center gap-2 rounded-xl bg-emerald-500 px-4 py-2.5 text-sm font-bold text-white shadow-md transition-all hover:bg-slate-800 sm:flex-none">

                    {{-- Search Icon --}}
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="h-4 w-4">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>

                    <span>Search</span>
                </button>

                {{-- CLEAR BUTTON --}}
                @if(request('search') || request('category'))
                    <a href="{{ route('items.index') }}"
                        class="flex flex-1 items-center justify-center rounded-xl bg-slate-100 px-4 py-2.5 text-sm font-medium text-slate-600 transition-colors hover:bg-slate-200 sm:flex-none">
                        Clear
                    </a>
                @endif

            </div>
        </form>
    </div>

    {{-- Main Table Card --}}
    <div
        class="overflow-hidden rounded-[1.5rem] bg-white/80 ring-1 ring-slate-200/50 shadow-[0_8px_30px_-12px_rgba(0,0,0,0.06)] backdrop-blur-xl">
        @if($items->count() > 0)
            <div class="overflow-x-auto p-2">
                <table class="min-w-full text-sm border-separate border-spacing-y-1">
                    <thead>
                        <tr>
                            <th scope="col"
                                class="whitespace-nowrap px-3 py-2 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">
                                Item</th>
                            <th scope="col"
                                class="whitespace-nowrap px-3 py-2 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">
                                SKU</th>
                            <th scope="col"
                                class="whitespace-nowrap px-3 py-2 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">
                                Category</th>
                            <th scope="col"
                                class="whitespace-nowrap px-3 py-2 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">
                                Stock & Expiry</th>
                            <th scope="col"
                                class="whitespace-nowrap px-3 py-2 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">
                                Price</th>
                            <th scope="col"
                                class="whitespace-nowrap px-3 py-2 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">
                                Status</th>
                            <th scope="col"
                                class="whitespace-nowrap px-3 py-2 text-right text-[10px] font-bold uppercase tracking-widest text-slate-400">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            @php
                                $stock = $item->total_stock;
                                $ratio = $item->reorder_level > 0 ? min($stock / ($item->reorder_level * 3), 1) : 1;
                                $level = $stock <= $item->reorder_level ? 'low' : ($ratio < 0.5 ? 'medium' : 'high');
                                $barColor = match ($level) { 'high' => 'bg-emerald-400',
                                    'medium' => 'bg-amber-400',
                                    'low' => 'bg-rose-400'
                                };
                            @endphp
                            <tr onclick="window.location='{{ route('items.show', $item) }}'"
                                class="group cursor-pointer transition-all duration-200 hover:bg-white rounded-xl shadow-sm hover:shadow-md hover:ring-1 hover:ring-slate-200/50">

                                <td class="px-3 py-2.5 rounded-l-xl">
                                    <div class="flex flex-col gap-1">
                                        <span class="block text-sm font-bold text-slate-800">{{ $item->name }}</span>
                                        <div class="flex items-center gap-2">
                                            @if($item->is_one_time_use)
                                                <span
                                                    class="text-[9px] font-bold uppercase tracking-wider text-rose-500 bg-rose-50 px-1.5 py-0.5 rounded border border-rose-100">Disposable</span>
                                            @else
                                                <span
                                                    class="text-[9px] font-bold uppercase tracking-wider text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-100">Reusable</span>
                                            @endif
                                            @if($item->description)
                                                                <span class="block text-xs font-medium text-slate-400 line-clamp-1 max-w-[150px]">{{
                                                $item->description }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td class="px-3 py-2.5 text-xs font-semibold text-slate-500 font-mono">{{ $item->sku }}</td>
                                <td class="whitespace-nowrap px-3 py-2.5">
                                    <span
                                        class="inline-flex items-center justify-center rounded-lg bg-slate-100/80 px-2.5 py-1.5 text-[11px] font-bold text-slate-600 ring-1 ring-inset ring-slate-500/10">
                                        {{ $item->category->name }}
                                    </span>
                                </td>

                                <td class="px-3 py-2.5">
                                    @php
                                        $today = now()->startOfDay();
                                        $expiredCount = 0;
                                        $nearExpiryCount = 0;
                                        foreach ($item->stockEntries as $entry) {
                                            if (!$entry->expiry_date)
                                                continue;
                                            $exp = \Carbon\Carbon::parse($entry->expiry_date)->startOfDay();
                                            if ($exp->isBefore($today)) {
                                                $expiredCount++;
                                            } elseif ($today->diffInDays($exp) <= 30) {
                                                $nearExpiryCount++;
                                            }
                                    } @endphp <div class="flex flex-col gap-1.5">
                                        {{-- New Stock --}}
                                        <div
                                            class="flex items-center gap-2 rounded-md bg-teal-50/50 px-2 py-1 ring-1 ring-inset ring-teal-500/20 w-fit">
                                            <span class="text-xs font-black text-teal-700">{{ $item->total_stock }}</span>
                                            <div class="h-3 w-px bg-teal-200/50"></div>
                                            <span class="text-[10px] font-bold uppercase tracking-wider text-teal-600">New
                                                Stock</span>
                                        </div>

                                        {{-- Used Stock --}}
                                        @if($item->stock_used > 0)
                                            <div
                                                class="flex items-center gap-2 rounded-md bg-amber-50/50 px-2 py-1 ring-1 ring-inset ring-amber-500/20 w-fit">
                                                <span class="text-xs font-black text-amber-600">{{ $item->stock_used }}</span>
                                                <div class="h-3 w-px bg-amber-200/50"></div>
                                                <span class="text-[10px] font-bold uppercase tracking-wider text-amber-600">Used
                                                    Stock</span>
                                            </div>
                                        @endif

                                        {{-- Expired batch count --}}
                                        @if($expiredCount > 0)
                                            <div
                                                class="flex items-center gap-2 rounded-md bg-rose-50/50 px-2 py-1 ring-1 ring-inset ring-rose-500/20 w-fit">
                                                <span class="text-xs font-black text-rose-700">{{ $expiredCount }}</span>
                                                <div class="h-3 w-px bg-rose-200/50"></div>
                                                <span class="text-[10px] font-bold uppercase tracking-wider text-rose-600">Expired
                                                    Batch{{ $expiredCount > 1 ? 'es' : '' }}</span>
                                            </div>
                                        @endif

                                        {{-- Near-expiry batch count --}}
                                        @if($nearExpiryCount > 0)
                                            <div
                                                class="flex items-center gap-2 rounded-md bg-orange-50/50 px-2 py-1 ring-1 ring-inset ring-orange-500/20 w-fit">
                                                <span class="text-xs font-black text-orange-700">{{ $nearExpiryCount }}</span>
                                                <div class="h-3 w-px bg-orange-200/50"></div>
                                                <span class="text-[10px] font-bold uppercase tracking-wider text-orange-600">Expiring
                                                    Soon</span>
                                            </div>
                                        @endif

                                        {{-- Stock Bar --}}
                                        <div
                                            class="group/bar relative h-1.5 w-32 overflow-hidden rounded-full bg-slate-100 mt-1 ring-1 ring-inset ring-slate-200/50">
                                            <div class="h-full rounded-full transition-all duration-500 {{ $barColor }}"
                                                style="width: {{ $ratio * 100 }}%"></div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-3 py-2.5 text-sm font-bold text-slate-700">₱{{ number_format($item->unit_price, 2) }}
                                </td>

                                <td class="px-3 py-2.5">
                                    @if($stock <= 0) <span
                                        class="inline-flex items-center rounded-lg bg-rose-50 px-2.5 py-1.5 text-[11px] font-bold text-rose-600 ring-1 ring-inset ring-rose-500/20">
                                        Out of Stock</span>
                                    @elseif($item->is_low_stock)
                                        <span
                                            class="inline-flex items-center rounded-lg bg-amber-50 px-2.5 py-1.5 text-[11px] font-bold text-amber-600 ring-1 ring-inset ring-amber-500/20">Low
                                            Stock</span>
                                    @else
                                        <span
                                            class="inline-flex items-center rounded-lg bg-emerald-50 px-2.5 py-1.5 text-[11px] font-bold text-emerald-600 ring-1 ring-inset ring-emerald-500/20">In
                                            Stock</span>
                                    @endif
                                </td>

                                <td class="whitespace-nowrap px-3 py-2.5 text-right rounded-r-xl">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('usage.create', ['item_id' => $item->id]) }}"
                                            onclick="event.stopPropagation()"
                                            class="inline-flex items-center gap-1.5 rounded-lg bg-rose-50 px-3 py-2 text-xs font-bold text-rose-600 ring-1 ring-inset ring-rose-500/20 transition-all hover:bg-rose-500 hover:text-white hover:shadow-md hover:shadow-rose-500/20">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                                                class="h-3.5 w-3.5">
                                                <path fill-rule="evenodd"
                                                    d="M8 2a.75.75 0 01.75.75v8.69l3.22-3.22a.75.75 0 111.06 1.06l-4.5 4.5a.75.75 0 01-1.06 0l-4.5-4.5a.75.75 0 111.06-1.06L7.25 11.44V2.75A.75.75 0 018 2z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            Use
                                        </a>
                                        <a href="{{ route('stock.create', $item) }}" onclick="event.stopPropagation()"
                                            class="inline-flex items-center gap-1.5 rounded-lg bg-indigo-50 px-3 py-2 text-xs font-bold text-indigo-600 ring-1 ring-inset ring-indigo-500/20 transition-all hover:bg-indigo-500 hover:text-white hover:shadow-md hover:shadow-indigo-500/20">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                                                class="h-3.5 w-3.5">
                                                <path
                                                    d="M8.75 3.75a.75.75 0 00-1.5 0v3.5h-3.5a.75.75 0 000 1.5h3.5v3.5a.75.75 0 001.5 0v-3.5h3.5a.75.75 0 000-1.5h-3.5v-3.5z" />
                                            </svg>
                                            Stock
                                        </a>

                                        <a href="{{ route('items.edit', $item) }}" onclick="event.stopPropagation()"
                                            class="inline-flex items-center rounded-lg bg-slate-50 px-3 py-2 text-xs font-bold text-slate-600 ring-1 ring-inset ring-slate-500/20 transition-all hover:bg-slate-800 hover:text-white hover:shadow-md hover:shadow-slate-800/20">
                                            Edit
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            {{-- Glowing Empty State --}}
            <div class="relative flex flex-col items-center justify-center px-3 py-22 text-center group">
                <div class="absolute inset-0 flex items-center justify-center opacity-40">
                    <div
                        class="h-48 w-48 rounded-full bg-gradient-to-br from-indigo-200 to-purple-200 blur-3xl transition-transform duration-700 group-hover:scale-110">
                    </div>
                </div>

                <div
                    class="relative z-10 mb-6 flex h-20 w-20 items-center justify-center rounded-[1.5rem] bg-white ring-1 ring-slate-200/80 shadow-xl shadow-slate-200/40">
                    <div class="absolute inset-0 rounded-[1.5rem] bg-gradient-to-br from-indigo-50/50 to-transparent"></div>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="relative h-8 w-8 text-indigo-400">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                    </svg>
                </div>
                <h3 class="relative z-10 text-lg font-black text-slate-800">No items found</h3>
                <p class="relative z-10 mt-2 max-w-sm text-sm font-medium leading-relaxed text-slate-500">
                    {{ request('search') || request('category') ? 'Try adjusting your search criteria.' : 'Begin populating your
                        inventory by adding your first item.' }}
                </p>
                @unless(request('search') || request('category'))
                    <a href="{{ route('items.create') }}"
                        class="relative z-10 mt-8 group inline-flex items-center gap-2 overflow-hidden rounded-2xl bg-gradient-to-br from-slate-800 to-slate-900 px-3 py-2 text-sm font-bold text-white shadow-[0_8px_16px_-6px_rgba(15,23,42,0.5)] transition-all duration-300 hover:-translate-y-0.5 hover:shadow-[0_12px_20px_-6px_rgba(15,23,42,0.6)]">
                        <div
                            class="absolute inset-0 bg-gradient-to-b from-white/10 to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                        </div>
                        <span class="relative">Create First Item</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                            stroke="currentColor"
                            class="relative h-4 w-4 transition-transform duration-300 group-hover:translate-x-1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </a>
                @endunless
            </div>
        @endif
    </div>
@endsection