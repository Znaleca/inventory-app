@extends('layouts.app')

@section('title', 'Items')

@section('actions')
<a href="{{ route('items.create') }}"
    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 text-[11px] font-mono font-bold uppercase tracking-[0.15em] transition-colors border border-blue-700">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-3.5 w-3.5">
        <path d="M8.75 3.75a.75.75 0 00-1.5 0v3.5h-3.5a.75.75 0 000 1.5h3.5v3.5a.75.75 0 001.5 0v-3.5h3.5a.75.75 0 000-1.5h-3.5v-3.5z" />
    </svg>
    New_Item
</a>
@endsection

@section('content')

{{-- Page Header --}}
<div class="mb-5 flex items-end justify-between">
    <div>
        <p class="text-[10px] font-mono font-semibold text-blue-600 uppercase tracking-[0.25em] mb-1">Inventory://Items</p>
        <h1 class="text-xl font-bold text-slate-800 tracking-tight">Item Registry</h1>
    </div>
    <span class="text-[10px] font-mono text-slate-400">{{ $items->count() }} records</span>
</div>

{{-- Search & Filter Bar --}}
<div class="bg-white border border-slate-200 mb-5 relative">
    <div class="absolute top-0 left-0 right-0 h-[2px] bg-gradient-to-r from-blue-500 to-indigo-400"></div>
    <div class="absolute top-0 left-0 w-1 h-full bg-blue-500"></div>
    <form method="GET" action="{{ route('items.index') }}" class="flex flex-wrap items-center gap-3 p-3 pl-4">

        {{-- Search Input --}}
        <div class="relative flex-1 sm:min-w-[280px]">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4 text-slate-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
            </div>
            <input type="text" name="search" value="{{ request('search') }}"
                class="block w-full border border-slate-200 bg-slate-50 py-2 pl-9 pr-4 text-sm font-mono text-slate-800 placeholder:text-slate-400 focus:bg-white focus:border-blue-500 focus:outline-none transition-colors"
                placeholder="Search items...">
        </div>

        {{-- Category Filter --}}
        <select name="category"
            class="block w-full sm:w-44 border border-slate-200 bg-slate-50 px-3 py-2 text-sm font-mono text-slate-700 focus:bg-white focus:border-blue-500 focus:outline-none transition-colors">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>

        {{-- Actions --}}
        <div class="flex gap-2">
            <button type="submit"
                class="flex items-center gap-2 bg-slate-800 hover:bg-blue-600 text-white px-4 py-2 text-[11px] font-mono font-bold uppercase tracking-widest transition-colors border border-slate-700 hover:border-blue-700">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-3.5 w-3.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
                Search
            </button>

            @if(request('search') || request('category'))
            <a href="{{ route('items.index') }}"
                class="flex items-center gap-2 border border-slate-200 bg-white text-slate-500 hover:text-slate-800 hover:border-slate-300 px-4 py-2 text-[11px] font-mono font-bold uppercase tracking-widest transition-colors">
                Clear
            </a>
            @endif
        </div>
    </form>
</div>

{{-- Main Table --}}
<div class="bg-white border border-slate-200 relative overflow-hidden">
    <div class="absolute top-0 left-0 w-1 h-full bg-indigo-500"></div>

    @if($items->count() > 0)
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="border-b border-slate-100 bg-slate-50/80">
                    <th scope="col" class="whitespace-nowrap pl-5 pr-3 py-3 text-left text-[10px] font-mono font-bold uppercase tracking-[0.2em] text-slate-400">Item</th>
                    <th scope="col" class="whitespace-nowrap px-3 py-3 text-left text-[10px] font-mono font-bold uppercase tracking-[0.2em] text-slate-400">Category</th>
                    <th scope="col" class="whitespace-nowrap px-3 py-3 text-left text-[10px] font-mono font-bold uppercase tracking-[0.2em] text-slate-400">Stock / Expiry</th>
                    <th scope="col" class="whitespace-nowrap px-3 py-3 text-left text-[10px] font-mono font-bold uppercase tracking-[0.2em] text-slate-400">Status</th>
                    <th scope="col" class="whitespace-nowrap px-3 py-3 text-right pr-5 text-[10px] font-mono font-bold uppercase tracking-[0.2em] text-slate-400">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($items as $item)
                @php
                    $newStock  = max(0, $item->total_stock);
                    $usedStock = max(0, $item->effective_stock_used);
                    $totalQty  = $newStock + $usedStock;
                    
                    $level = $totalQty <= 0 ? 'low' : ($totalQty <= 5 ? 'medium' : 'high');
                    $barWidth = $totalQty <= 0 ? 0 : min(100, ($totalQty / 20) * 100);
                    $barColor = match ($level) {
                        'high'   => 'bg-emerald-400',
                        'medium' => 'bg-amber-400',
                        'low'    => 'bg-rose-400',
                    };
                    $today = now()->startOfDay();
                    $expiredCount = 0;
                    $nearExpiryCount = 0;
                    foreach ($item->stockEntries as $entry) {
                        if (!$entry->expiry_date) continue;
                        $exp = \Carbon\Carbon::parse($entry->expiry_date)->startOfDay();
                        if ($exp->isBefore($today)) $expiredCount++;
                        elseif ($today->diffInDays($exp) <= 30) $nearExpiryCount++;
                    }
                @endphp
                <tr class="group hover:bg-slate-50 transition-colors">

                    {{-- Item Name --}}
                    <td class="pl-5 pr-3 py-3">
                        <div class="flex flex-col gap-0.5">
                            <span class="text-sm font-bold text-slate-800">{{ $item->name }}</span>
                            <div class="flex items-center gap-1.5 flex-wrap mt-0.5">
                                @if($item->item_type === 'device')
                                <span class="text-[9px] font-mono font-bold uppercase tracking-wider text-violet-600 bg-violet-50 px-1.5 py-0.5 border border-violet-200">Device</span>
                                @else
                                <span class="text-[9px] font-mono font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-1.5 py-0.5 border border-indigo-200">Consumable</span>
                                @endif

                                @if($item->brand)
                                <span class="text-[10px] font-mono text-slate-400">{{ $item->brand }}{{ $item->model ? ' · '.$item->model : '' }}</span>
                                @elseif($item->description)
                                <span class="text-[10px] font-mono text-slate-400 line-clamp-1 max-w-[150px]">{{ $item->description }}</span>
                                @endif
                            </div>
                        </div>
                    </td>

                    {{-- Category --}}
                    <td class="whitespace-nowrap px-3 py-3">
                        <span class="font-mono text-[11px] font-bold text-slate-600 bg-slate-50 border border-slate-200 px-2.5 py-1.5">
                            {{ $item->category->name }}
                        </span>
                    </td>

                    {{-- Stock & Expiry --}}
                    <td class="px-3 py-3">
                        <div class="flex flex-wrap gap-1.5">
                            <span class="flex items-center gap-1.5 bg-teal-50 border border-teal-200 px-2 py-1 text-xs font-bold font-mono text-teal-700">
                                <span class="h-1.5 w-1.5 bg-teal-400 inline-block"></span>
                                {{ $item->total_stock }} New
                            </span>
                            @if($item->effective_stock_used > 0)
                            <span class="flex items-center gap-1.5 bg-amber-50 border border-amber-200 px-2 py-1 text-xs font-bold font-mono text-amber-700">
                                {{ $item->effective_stock_used }} Used
                            </span>
                            @endif
                            @if($item->active_lent_out > 0)
                            <span class="flex items-center gap-1.5 bg-indigo-50 border border-indigo-200 px-2 py-1 text-xs font-bold font-mono text-indigo-700" title="Actively Lent Out">
                                ↑ {{ $item->active_lent_out }} Lent Out
                            </span>
                            @endif

                            @if($expiredCount > 0)
                            <span class="flex items-center gap-1.5 bg-rose-50 border border-rose-200 px-2 py-1 text-xs font-bold font-mono text-rose-700">
                                <span class="h-1.5 w-1.5 bg-rose-500 animate-pulse inline-block"></span>
                                {{ $expiredCount }} Exp.
                            </span>
                            @endif
                            @if($nearExpiryCount > 0)
                            <span class="flex items-center gap-1.5 bg-orange-50 border border-orange-200 px-2 py-1 text-xs font-bold font-mono text-orange-700">
                                {{ $nearExpiryCount }} Soon
                            </span>
                            @endif
                        </div>
                        {{-- Stock bar --}}
                        <div class="mt-2 h-1 w-28 bg-slate-100 border border-slate-200">
                            <div class="h-full {{ $barColor }} transition-all duration-500" style="width: {{ $barWidth }}%"></div>
                        </div>
                    </td>

                    {{-- Status --}}
                    <td class="px-3 py-3">
                        @if($totalQty <= 0)
                        <span class="inline-flex items-center gap-1.5 font-mono text-[11px] font-bold text-rose-600 bg-rose-50 border border-rose-200 px-2.5 py-1.5">
                            <span class="h-1.5 w-1.5 bg-rose-500 animate-pulse inline-block"></span>
                            Out_of_Stock
                        </span>
                        @elseif($item->is_low_stock && $totalQty <= $item->reorder_level)
                        <span class="inline-flex items-center gap-1.5 font-mono text-[11px] font-bold text-amber-600 bg-amber-50 border border-amber-200 px-2.5 py-1.5">
                            <span class="h-1.5 w-1.5 bg-amber-400 inline-block"></span>
                            Low_Stock
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1.5 font-mono text-[11px] font-bold text-emerald-600 bg-emerald-50 border border-emerald-200 px-2.5 py-1.5">
                            <span class="h-1.5 w-1.5 bg-emerald-400 inline-block"></span>
                            In_Stock
                        </span>
                        @endif
                    </td>

                    {{-- Actions --}}
                    <td class="whitespace-nowrap px-3 pr-5 py-3 text-right">
                        <div class="flex items-center justify-end gap-1.5">
                            <a href="{{ route('items.show', $item) }}"
                                class="inline-flex items-center gap-1 border border-slate-200 bg-white px-2.5 py-1.5 text-[10px] font-mono font-bold text-slate-700 hover:bg-slate-100 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-3 w-3">
                                    <path d="M10 12.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z" />
                                    <path fill-rule="evenodd" d="M.664 10.59a1.651 1.651 0 010-1.186A10.004 10.004 0 0110 3c4.257 0 7.893 2.66 9.336 6.41.147.381.146.804 0 1.186A10.004 10.004 0 0110 17c-4.257 0-7.893-2.66-9.336-6.41zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                </svg>
                                View
                            </a>
                            <a href="{{ route('usage.create', ['item_id' => $item->id]) }}"
                                class="inline-flex items-center gap-1 border border-rose-200 bg-rose-50 px-2.5 py-1.5 text-[10px] font-mono font-bold text-rose-600 hover:bg-rose-500 hover:text-white hover:border-rose-500 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-3 w-3">
                                    <path fill-rule="evenodd" d="M8 2a.75.75 0 01.75.75v8.69l3.22-3.22a.75.75 0 111.06 1.06l-4.5 4.5a.75.75 0 01-1.06 0l-4.5-4.5a.75.75 0 111.06-1.06L7.25 11.44V2.75A.75.75 0 018 2z" clip-rule="evenodd" />
                                </svg>
                                Use
                            </a>
                            <a href="{{ route('stock.create', $item) }}"
                                class="inline-flex items-center gap-1 border border-indigo-200 bg-indigo-50 px-2.5 py-1.5 text-[10px] font-mono font-bold text-indigo-600 hover:bg-indigo-600 hover:text-white hover:border-indigo-600 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-3 w-3">
                                    <path d="M8.75 3.75a.75.75 0 00-1.5 0v3.5h-3.5a.75.75 0 000 1.5h3.5v3.5a.75.75 0 001.5 0v-3.5h3.5a.75.75 0 000-1.5h-3.5v-3.5z" />
                                </svg>
                                Stock
                            </a>
                            <a href="{{ route('items.edit', $item) }}"
                                class="inline-flex items-center border border-slate-200 bg-slate-50 px-2.5 py-1.5 text-[10px] font-mono font-bold text-slate-600 hover:bg-slate-800 hover:text-white hover:border-slate-800 transition-colors">
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
    {{-- Empty State --}}
    <div class="flex flex-col items-center justify-center py-20 text-center ml-1">
        <div class="h-14 w-14 border border-slate-200 bg-slate-50 flex items-center justify-center text-slate-400 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-7 w-7">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
            </svg>
        </div>
        <p class="font-mono text-[10px] text-slate-400 uppercase tracking-widest mb-1">// No records found</p>
        <p class="text-sm font-semibold text-slate-500 mt-1">
            {{ request('search') || request('category') ? 'Try adjusting your filters.' : 'No items in inventory yet.' }}
        </p>
        @unless(request('search') || request('category'))
        <a href="{{ route('items.create') }}"
            class="mt-6 inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 text-[11px] font-mono font-bold uppercase tracking-widest transition-colors border border-blue-700">
            + Add First Item
        </a>
        @endunless
    </div>
    @endif
</div>

@endsection