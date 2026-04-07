@extends('layouts.app')

@section('title', $item->name)

@section('actions')
<a href="{{ route('items.index') }}"
    class="group inline-flex items-center gap-2 rounded-xl bg-slate-50 px-4 py-2 text-sm font-medium text-slate-600 ring-1 ring-inset ring-slate-500/10 transition-all hover:bg-slate-100 hover:text-slate-900">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
        class="h-4 w-4 transition-transform duration-300 group-hover:-translate-x-0.5">
        <path fill-rule="evenodd"
            d="M17 10a.75.75 0 01-.75.75H5.66l4.22 4.22a.75.75 0 11-1.06 1.06l-5.5-5.5a.75.75 0 010-1.06l5.5-5.5a.75.75 0 111.06 1.06l-4.22 4.22h10.59a.75.75 0 01.75.75z"
            clip-rule="evenodd" />
    </svg>
    Back
</a>

<a href="{{ route('items.edit', $item) }}"
    class="inline-flex items-center gap-1.5 rounded-xl bg-slate-50 px-4 py-2 text-sm font-medium text-slate-600 ring-1 ring-inset ring-slate-500/10 transition-colors hover:bg-slate-100 hover:text-slate-900">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
        class="h-4 w-4">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
    </svg>
    Edit
</a>
<a href="{{ route('usage.create', ['item_id' => $item->id]) }}"
    class="group relative inline-flex items-center gap-1.5 overflow-hidden rounded-xl bg-rose-600 px-5 py-2 text-sm font-medium text-white shadow-lg shadow-rose-600/20 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-rose-600/30">
    <div
        class="absolute inset-0 bg-gradient-to-b from-white/10 to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100">
    </div>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="relative h-4 w-4">
        <path fill-rule="evenodd"
            d="M8 2a.75.75 0 01.75.75v8.69l3.22-3.22a.75.75 0 111.06 1.06l-4.5 4.5a.75.75 0 01-1.06 0l-4.5-4.5a.75.75 0 111.06-1.06L7.25 11.44V2.75A.75.75 0 018 2z"
            clip-rule="evenodd" />
    </svg>
    <span class="relative">Log Usage</span>
</a>
<div x-data="{ open: false }" class="relative inline-block text-left z-20">
    <button @click="open = !open" @click.away="open = false" type="button"
        class="group relative inline-flex items-center gap-2 overflow-hidden rounded-xl bg-gradient-to-br from-yellow-400 to-yellow-500 px-5 py-2.5 text-sm font-bold text-white shadow-md shadow-yellow-500/20 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-yellow-500/40 active:translate-y-0 active:shadow-md">
        <div class="absolute inset-0 bg-white/20 opacity-0 transition-opacity duration-300 group-hover:opacity-100">
        </div>

        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
            class="relative h-4 w-4 transition-transform duration-300 group-hover:scale-110">
            <path fill-rule="evenodd"
                d="M5 3.25V4H2.75a.75.75 0 000 1.5h.3l.815 8.15A1.5 1.5 0 005.357 15h5.285a1.5 1.5 0 001.493-1.35l.815-8.15h.3a.75.75 0 000-1.5H11v-.75A1.25 1.25 0 009.75 2h-3.5A1.25 1.25 0 005 3.25zm1.5 0V4h3v-.75a.25.25 0 00-.25-.25h-2.5a.25.25 0 00-.25.25zM7.5 7.75a.75.75 0 011.5 0v4.5a.75.75 0 01-1.5 0v-4.5z"
                clip-rule="evenodd" />
        </svg>

        <span class="relative tracking-wide">Dispose</span>

        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
            class="relative -mr-1 h-5 w-5 transition-transform duration-300 ease-out" :class="{ 'rotate-180': open }">
            <path fill-rule="evenodd"
                d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                clip-rule="evenodd" />
        </svg>
    </button>

    <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
        class="absolute left-0 mt-3 w-52 origin-top-left rounded-xl bg-white/95 dark:bg-[#13141B]/95 backdrop-blur-md p-2 shadow-xl shadow-slate-900/10 ring-1 ring-slate-200 dark:ring-white/10 dark:shadow-[0_20px_40px_-10px_rgba(0,0,0,0.7)] focus:outline-none">
        
        @if($item->stock_used > 0)
        <a href="{{ route('disposals.create', ['item_id' => $item->id, 'disposal_type' => 'used']) }}"
            class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-200 transition-all duration-200 hover:bg-yellow-50 dark:hover:bg-yellow-500/10 hover:text-yellow-700 dark:hover:text-yellow-400">
            <span class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-md bg-slate-100 dark:bg-white/5 text-slate-500 dark:text-slate-400 transition-colors duration-200 group-hover:bg-yellow-100 dark:group-hover:bg-yellow-500/20 group-hover:text-yellow-600 dark:group-hover:text-yellow-400">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-4 w-4">
                    <path fill-rule="evenodd" d="M5 3.25V4H2.75a.75.75 0 000 1.5h.3l.815 8.15A1.5 1.5 0 005.357 15h5.285a1.5 1.5 0 001.493-1.35l.815-8.15h.3a.75.75 0 000-1.5H11v-.75A1.25 1.25 0 009.75 2h-3.5A1.25 1.25 0 005 3.25zm1.5 0V4h3v-.75a.25.25 0 00-.25-.25h-2.5a.25.25 0 00-.25.25zM7.5 7.75a.75.75 0 011.5 0v4.5a.75.75 0 01-1.5 0v-4.5z" clip-rule="evenodd" />
                </svg>
            </span>
            Dispose Used
        </a>
        @endif

        <a href="{{ route('disposals.create', ['item_id' => $item->id, 'disposal_type' => 'new']) }}"
            class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-200 transition-all duration-200 hover:bg-rose-50 dark:hover:bg-rose-500/10 hover:text-rose-700 dark:hover:text-rose-400">
            <span class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-md bg-slate-100 dark:bg-white/5 text-slate-500 dark:text-slate-400 transition-colors duration-200 group-hover:bg-rose-100 dark:group-hover:bg-rose-500/20 group-hover:text-rose-600 dark:group-hover:text-rose-400">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-4 w-4">
                    <path fill-rule="evenodd" d="M5.28 4.22a.75.75 0 00-1.06 1.06L6.94 8l-2.72 2.72a.75.75 0 101.06 1.06L8 9.06l2.72 2.72a.75.75 0 101.06-1.06L9.06 8l2.72-2.72a.75.75 0 00-1.06-1.06L8 6.94 5.28 4.22z" clip-rule="evenodd" />
                </svg>
            </span>
            Dispose Expired
        </a>
    </div>
</div>
<a href="{{ route('stock.create', $item) }}"
    class="group relative inline-flex items-center gap-1.5 overflow-hidden rounded-xl bg-slate-900 px-5 py-2 text-sm font-medium text-white shadow-lg shadow-slate-900/20 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-slate-900/30">
    <div
        class="absolute inset-0 bg-gradient-to-b from-white/10 to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100">
    </div>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="relative h-4 w-4">
        <path
            d="M8.75 3.75a.75.75 0 00-1.5 0v3.5h-3.5a.75.75 0 000 1.5h3.5v3.5a.75.75 0 001.5 0v-3.5h3.5a.75.75 0 000-1.5h-3.5v-3.5z" />
    </svg>
    <span class="relative">Receive Stock</span>
</a>
@endsection

@section('content')
{{-- Item Stats - Updated Grid to hold exactly 6 items neatly --}}
<div class="mb-8 grid grid-cols-2 gap-4 lg:grid-cols-3 xl:grid-cols-6">

    {{-- Current Stock --}}
    <div
        class="flex flex-col items-center justify-center rounded-2xl border border-slate-200/80 bg-white p-6 shadow-[0_4px_24px_-8px_rgba(0,0,0,0.06)] text-center transition-all hover:shadow-lg hover:-translate-y-0.5">
        <div
            class="mb-3 flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 ring-1 ring-inset ring-emerald-500/20">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="h-6 w-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
            </svg>
        </div>
        @php
        $stockText = $item->total_stock . ' ' . $item->unit;
        $len = mb_strlen($stockText);
        $sizeClass = $len > 16 ? 'text-xs' : ($len > 12 ? 'text-sm' : ($len > 8 ? 'text-lg' : 'text-xl'));
        @endphp
        <p class="font-bold text-emerald-600 leading-tight break-words {{ $sizeClass }}">
            {{ $stockText }}
        </p>
        <p class="mt-1 text-[11px] font-medium uppercase tracking-[0.05em] text-slate-400">Current Stock</p>
    </div>

    {{-- Reorder Level --}}
    <div
        class="flex flex-col items-center justify-center rounded-2xl border border-slate-200/80 bg-white p-6 shadow-[0_4px_24px_-8px_rgba(0,0,0,0.06)] text-center transition-all hover:shadow-lg hover:-translate-y-0.5">
        <div
            class="mb-3 flex h-12 w-12 items-center justify-center rounded-xl bg-rose-50 text-rose-600 ring-1 ring-inset ring-rose-500/20">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="h-6 w-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
            </svg>
        </div>
        @php
        $reorderText = (string)$item->reorder_level;
        $len = mb_strlen($reorderText);
        $sizeClass = $len > 16 ? 'text-xs' : ($len > 12 ? 'text-sm' : ($len > 8 ? 'text-lg' : 'text-xl'));
        @endphp
        <p class="font-bold text-rose-600 leading-tight break-words {{ $sizeClass }}">
            {{ $reorderText }}
        </p>
        <p class="mt-1 text-[11px] font-medium uppercase tracking-[0.05em] text-slate-400">Reorder Level</p>
    </div>

    {{-- Unit Price --}}
    <div
        class="flex flex-col items-center justify-center rounded-2xl border border-slate-200/80 bg-white p-6 shadow-[0_4px_24px_-8px_rgba(0,0,0,0.06)] text-center transition-all hover:shadow-lg hover:-translate-y-0.5">
        <div
            class="mb-3 flex h-12 w-12 items-center justify-center rounded-xl bg-sky-50 text-sky-600 ring-1 ring-inset ring-sky-500/20">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="h-6 w-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
            </svg>
        </div>
        @php
        $priceText = '₱' . number_format($item->unit_price, 2);
        $len = mb_strlen($priceText);
        $sizeClass = $len > 16 ? 'text-xs' : ($len > 12 ? 'text-sm' : ($len > 8 ? 'text-lg' : 'text-xl'));
        @endphp
        <p class="font-bold text-sky-600 leading-tight break-words {{ $sizeClass }}">
            {{ $priceText }}
        </p>
        <p class="mt-1 text-[11px] font-medium uppercase tracking-[0.05em] text-slate-400">Unit Price</p>
    </div>

    {{-- Total Stock Value --}}
    <div
        class="flex flex-col items-center justify-center rounded-2xl border border-slate-200/80 bg-white p-6 shadow-[0_4px_24px_-8px_rgba(0,0,0,0.06)] text-center transition-all hover:shadow-lg hover:-translate-y-0.5">
        <div
            class="mb-3 flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 ring-1 ring-inset ring-indigo-500/20">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="h-6 w-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M20.25 6.375c0 8.485-7.5 11.9-7.5 11.9s-7.5-3.415-7.5-11.9a7.5 7.5 0 1115 0z" />
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 6.75a2.25 2.25 0 110 4.5 2.25 2.25 0 010-4.5z" />
            </svg>
        </div>
        @php
        $totalValue = $item->unit_price * $item->total_stock;
        $valueText = '₱' . number_format($totalValue, 2);
        $len = mb_strlen($valueText);
        $sizeClass = $len > 16 ? 'text-xs' : ($len > 12 ? 'text-sm' : ($len > 8 ? 'text-lg' : 'text-xl'));
        @endphp
        <p class="font-bold text-indigo-600 leading-tight break-words {{ $sizeClass }}">
            {{ $valueText }}
        </p>
        <p class="mt-1 text-[11px] font-medium uppercase tracking-[0.05em] text-slate-400">Total Value</p>
    </div>

    {{-- Used Stock --}}
    <div
        class="flex flex-col items-center justify-center rounded-2xl border border-slate-200/80 bg-white p-6 shadow-[0_4px_24px_-8px_rgba(0,0,0,0.06)] text-center transition-all hover:shadow-lg hover:-translate-y-0.5">
        <div
            class="mb-3 flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50 text-amber-600 ring-1 ring-inset ring-amber-500/20">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="h-6 w-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h.75a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.25 2.25 0 005.25 4.5c0 .414.336.75.75.75h.75a.75.75 0 00.75-.75c0-.231-.035-.454-.1-.664m5.8 0A2.25 2.25 0 0115.75 4.5V5.25A2.25 2.25 0 0113.5 7.5h-9A2.25 2.25 0 012.25 5.25V4.5A2.25 2.25 0 014.5 2.25h9a2.25 2.25 0 012.25 2.25zM7.5 10.5h.008v.008H7.5V10.5zm3 0h.008v.008H10.5V10.5zm-6 0h.008v.008H4.5V10.5zm9 0h.008v.008H13.5V10.5zm-9 3h.008v.008H4.5v-.008zm3 0h.008v.008H7.5v-.008zm3 0h.008v.008H10.5v-.008zm3 0h.008v.008H13.5v-.008zm-9 3h.008v.008H4.5v-.008zm3 0h.008v.008H7.5v-.008zm3 0h.008v.008H10.5v-.008zm3 0h.008v.008H13.5v-.008z" />
            </svg>
        </div>
        @php
        $usedStockText = $item->stock_used . ' ' . $item->unit;
        $len = mb_strlen($usedStockText);
        $sizeClass = $len > 16 ? 'text-xs' : ($len > 12 ? 'text-sm' : ($len > 8 ? 'text-lg' : 'text-xl'));
        @endphp
        <p class="font-bold text-amber-600 leading-tight break-words {{ $sizeClass }}">
            {{ $usedStockText }}
        </p>
        <p class="mt-1 text-[11px] font-medium uppercase tracking-[0.05em] text-slate-400">Used Stock</p>
    </div>

    {{-- Category --}}
    <div
        class="flex flex-col items-center justify-center rounded-2xl border border-slate-200/80 bg-white p-6 shadow-[0_4px_24px_-8px_rgba(0,0,0,0.06)] text-center transition-all hover:shadow-lg hover:-translate-y-0.5">
        <div
            class="mb-3 flex h-12 w-12 items-center justify-center rounded-xl bg-fuchsia-50 text-fuchsia-600 ring-1 ring-inset ring-fuchsia-500/20">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="h-6 w-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
            </svg>
        </div>
        @php
        $categoryText = $item->category?->name ?? 'Uncategorized';
        $len = mb_strlen($categoryText);
        $sizeClass = $len > 16 ? 'text-xs' : ($len > 12 ? 'text-sm' : ($len > 8 ? 'text-lg' : 'text-xl'));
        @endphp
        <p class="font-bold text-fuchsia-600 leading-none w-full truncate {{ $sizeClass }}">
            {{ $categoryText }}
        </p>
        <p class="mt-2 text-[11px] font-medium uppercase tracking-[0.05em] text-slate-400 w-full truncate">Category</p>
    </div>
</div>

{{-- Item Details --}}
<div
    class="mb-6 overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-[0_4px_24px_-8px_rgba(0,0,0,0.06)]">
    <div class="border-b border-slate-100 dark:border-transparent bg-slate-50/50 px-3 py-2.5">
        <h3 class="text-sm font-semibold text-slate-800">Item Specifications</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <tbody class="divide-y divide-slate-50 dark:divide-transparent">
                <tr class="transition-colors hover:bg-slate-50/50">
                    <td
                        class="w-48 px-3 py-2.5 text-xs font-medium uppercase tracking-[0.1em] text-slate-400 whitespace-nowrap">
                        SKU</td>
                    <td class="px-3 py-2.5 font-mono text-sm font-medium text-slate-600 whitespace-nowrap">{{ $item->sku
                        }}</td>
                </tr>

                <tr class="transition-colors hover:bg-slate-50/50">
                    <td
                        class="w-48 px-3 py-2.5 text-xs font-medium uppercase tracking-[0.1em] text-slate-400 whitespace-nowrap">
                        Description</td>
                    <td class="px-3 py-2.5 text-sm text-slate-700 whitespace-nowrap">{{ $item->description ?? '—' }}
                    </td>
                </tr>
                <tr class="transition-colors hover:bg-slate-50/50">
                    <td
                        class="w-48 px-3 py-2.5 text-xs font-medium uppercase tracking-[0.1em] text-slate-400 whitespace-nowrap">
                        Usage Type</td>
                    <td class="px-3 py-2.5 whitespace-nowrap">
                        @if($item->is_one_time_use)
                        <span
                            class="inline-flex items-center rounded-lg bg-rose-50 px-2.5 py-1 text-[11px] font-bold text-rose-600 ring-1 ring-inset ring-rose-500/20">
                            Disposable (One-Time Use)
                        </span>
                        @else
                        <span
                            class="inline-flex items-center rounded-lg bg-emerald-50 px-2.5 py-1 text-[11px] font-bold text-emerald-600 ring-1 ring-inset ring-emerald-500/20">
                            Reusable
                        </span>
                        @endif
                    </td>
                </tr>
                <tr class="transition-colors hover:bg-slate-50/50">
                    <td
                        class="w-48 px-3 py-2.5 text-xs font-medium uppercase tracking-[0.1em] text-slate-400 whitespace-nowrap">
                        Created</td>
                    <td class="px-3 py-2.5 text-sm text-slate-600 whitespace-nowrap">{{ $item->created_at->format('M d,
                        Y') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

{{-- Batch Breakdown --}}
<div
    class="mb-6 overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-[0_4px_24px_-8px_rgba(0,0,0,0.06)]">
    <div class="border-b border-slate-100 dark:border-transparent bg-slate-50/50 px-3 py-2.5 flex items-center justify-between">
        <h3 class="text-sm font-semibold text-slate-800">Current Stock Breakdown (by Batch)</h3>
        <span class="text-[10px] font-medium text-slate-400 uppercase tracking-wider">FIFO Tracked</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-100 dark:border-transparent bg-slate-50/30 dark:bg-transparent">
                    <th
                        class="px-3 py-2 text-left text-[11px] font-medium uppercase tracking-[0.1em] text-slate-400 whitespace-nowrap">
                        Lot #</th>
                    <th
                        class="px-3 py-2 text-left text-[11px] font-medium uppercase tracking-[0.1em] text-slate-400 whitespace-nowrap">
                        Expiry</th>
                    <th
                        class="px-3 py-2 text-left text-[11px] font-medium uppercase tracking-[0.1em] text-slate-400 whitespace-nowrap">
                        Received</th>
                    <th
                        class="px-3 py-2 text-right text-[11px] font-medium uppercase tracking-[0.1em] text-slate-400 whitespace-nowrap">
                        Current Qty</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50 dark:divide-transparent">
                @foreach($item->batches_breakdown as $batch)
                <tr class="transition-colors hover:bg-slate-50/50">
                    <td class="px-3 py-2.5 font-mono text-xs text-slate-600 whitespace-nowrap">{{ $batch['lot_number']
                        ??
                        '—' }}</td>
                    <td class="px-3 py-2.5 whitespace-nowrap">
                        @if($batch['expiry_date'])
                        @php
                        $expiry = \Carbon\Carbon::parse($batch['expiry_date'])->startOfDay();
                        $today = now()->startOfDay();
                        @endphp

                        @if($expiry->isBefore($today))
                        <span
                            class="inline-flex items-center rounded-md bg-rose-50 px-2 py-1 text-[10px] font-bold uppercase tracking-wider text-rose-600 ring-1 ring-inset ring-rose-500/20">
                            EXPIRED: {{ $expiry->format('M d, Y') }}
                        </span>
                        @elseif($today->diffInDays($expiry) <= 30) <span
                            class="inline-flex items-center rounded-md bg-amber-50 px-2 py-1 text-[10px] font-bold uppercase tracking-wider text-amber-600 ring-1 ring-inset ring-amber-500/20">
                            Exp: {{ $expiry->format('M d, Y') }}
                            </span>
                            @else
                            <span
                                class="inline-flex items-center rounded-md bg-slate-50 px-2 py-1 text-[10px] font-bold uppercase tracking-wider text-slate-600 ring-1 ring-inset ring-slate-500/20">
                                Exp: {{ $expiry->format('M d, Y') }}
                            </span>
                            @endif
                            @else
                            <span class="text-slate-400 text-xs">—</span>
                            @endif
                    </td>
                    <td class="px-3 py-2.5 text-xs text-slate-500 whitespace-nowrap">{{
                        \Carbon\Carbon::parse($batch['received_date'])->format('M d, Y') }}</td>
                    <td class="px-3 py-2.5 text-right whitespace-nowrap">
                        <span class="text-sm font-bold text-slate-900">{{ $batch['remaining'] }}</span>
                        <span class="text-[10px] text-slate-400 ml-1">{{ $item->unit }}</span>
                    </td>
                </tr>
                @endforeach
                @if($item->stock_used > 0)
                <tr class="bg-amber-50/30 dark:bg-amber-500/10 border-t-2 border-amber-100/50 dark:border-amber-500/20">
                    <td colspan="3" class="px-3 py-2.5 text-xs font-semibold text-amber-700 whitespace-nowrap">
                        Accumulated
                        Used Stock</td>
                    <td class="px-3 py-2.5 text-right whitespace-nowrap">
                        <span class="text-sm font-bold text-amber-600">{{ $item->stock_used }}</span>
                        <span class="text-[10px] text-amber-500/70 ml-1">{{ $item->unit }}</span>
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

<div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
    {{-- Usage History --}}
    <div
        class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-[0_4px_24px_-8px_rgba(0,0,0,0.06)]">
        <div class="flex items-center justify-between border-b border-slate-100 dark:border-transparent bg-slate-50/50 px-3 py-2.5">
            <h3 class="flex items-center gap-2 text-sm font-semibold text-slate-800">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="h-4 w-4 text-slate-400">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Usage History
            </h3>
        </div>
        @if($item->usageLogs->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-transparent bg-slate-50/30 dark:bg-transparent">
                        <th
                            class="px-3 py-2 text-left text-[11px] font-medium uppercase tracking-[0.1em] text-slate-400 whitespace-nowrap">
                            Qty</th>
                        <th
                            class="px-3 py-2 text-left text-[11px] font-medium uppercase tracking-[0.1em] text-slate-400 whitespace-nowrap">
                            Patient/Procedure</th>
                        <th
                            class="px-3 py-2 text-left text-[11px] font-medium uppercase tracking-[0.1em] text-slate-400 whitespace-nowrap">
                            Used By</th>
                        <th
                            class="px-3 py-2 text-left text-[11px] font-medium uppercase tracking-[0.1em] text-slate-400 whitespace-nowrap">
                            Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-transparent">
                    @foreach($item->usageLogs as $log)
                    <tr class="transition-colors hover:bg-slate-50/60">
                        <td class="px-3 py-2.5 font-semibold text-rose-600 whitespace-nowrap text-xs">{{
                            $log->quantity_used }}</td>
                        <td class="px-3 py-2.5 min-w-[140px]">
                            <div class="text-[11px] font-medium text-slate-700 break-words line-clamp-2">{{
                                $log->patient_id ?? 'No Patient ID' }}</div>
                            <div class="text-[9px] text-slate-400 truncate">{{ $log->procedure_type ?? 'No Procedure' }}
                            </div>
                        </td>
                        <td class="px-3 py-2.5 text-[10px] text-slate-500 whitespace-nowrap">{{ $log->used_by ?? '—' }}
                        </td>
                        <td class="px-3 py-2.5 text-[10px] font-medium text-slate-400 whitespace-nowrap">{{
                            $log->used_at->format('M d, Y') }}<br><span class="text-[9px]">{{ $log->used_at->format('h:i
                                A') }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="py-10 text-center text-sm text-slate-400">No usage logged yet.</div>
        @endif
    </div>

    {{-- Stock History --}}
    <div
        class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-[0_4px_24px_-8px_rgba(0,0,0,0.06)]">
        <div class="flex items-center justify-between border-b border-slate-100 dark:border-transparent bg-slate-50/50 px-3 py-2.5">
            <h3 class="flex items-center gap-2 text-sm font-semibold text-slate-800">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="h-4 w-4 text-slate-400">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 3.75H6.912a2.25 2.25 0 00-2.15 1.588L2.35 13.177a2.25 2.25 0 00-.1.661V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 00-2.15-1.588H15M2.25 13.5h3.86a2.25 2.25 0 012.012 1.244l.256.512a2.25 2.25 0 002.013 1.244h3.218a2.25 2.25 0 002.013-1.244l.256-.512a2.25 2.25 0 012.013-1.244h3.859M12 3v8.25m0 0l-3-3m3 3l3-3" />
                </svg>
                Stock Received
            </h3>
        </div>
        @if($item->stockEntries->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-transparent bg-slate-50/30 dark:bg-transparent">
                        <th
                            class="px-3 py-2 text-left text-[11px] font-medium uppercase tracking-[0.1em] text-slate-400 whitespace-nowrap">
                            Qty</th>
                        <th
                            class="px-3 py-2 text-left text-[11px] font-medium uppercase tracking-[0.1em] text-slate-400 whitespace-nowrap">
                            Lot #</th>
                        <th
                            class="px-3 py-2 text-left text-[11px] font-medium uppercase tracking-[0.1em] text-slate-400 whitespace-nowrap">
                            Expiry</th>
                        <th
                            class="px-3 py-2 text-left text-[11px] font-medium uppercase tracking-[0.1em] text-slate-400 whitespace-nowrap">
                            Received</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-transparent">
                    @foreach($item->stockEntries as $entry)
                    <tr class="transition-colors hover:bg-slate-50/60">
                        <td class="px-3 py-2.5 font-semibold text-slate-800 text-xs whitespace-nowrap">{{
                            $entry->quantity
                            }}</td>
                        <td class="px-3 py-2.5 font-mono text-[10px] text-slate-500 whitespace-nowrap">{{
                            $entry->lot_number ?? '—' }}</td>
                        <td class="px-3 py-2.5 whitespace-nowrap">
                            @if($entry->expiry_date)
                            @php
                            $entryExpiry = \Carbon\Carbon::parse($entry->expiry_date)->startOfDay();
                            $today = now()->startOfDay();
                            @endphp

                            @if($entryExpiry->isBefore($today))
                            <span
                                class="inline-flex items-center rounded-md bg-rose-50 px-2 py-1 text-[10px] font-bold uppercase tracking-wider text-rose-600 ring-1 ring-inset ring-rose-500/20">
                                EXPIRED: {{ $entryExpiry->format('M d, Y') }}
                            </span>
                            @elseif($today->diffInDays($entryExpiry) <= 30) <span
                                class="inline-flex items-center rounded-md bg-amber-50 px-2 py-1 text-[10px] font-bold uppercase tracking-wider text-amber-600 ring-1 ring-inset ring-amber-500/20">
                                Exp: {{ $entryExpiry->format('M d, Y') }}
                                </span>
                                @else
                                <span
                                    class="inline-flex items-center rounded-md bg-slate-50 px-2 py-1 text-[10px] font-bold uppercase tracking-wider text-slate-600 ring-1 ring-inset ring-slate-500/20">
                                    Exp: {{ $entryExpiry->format('M d, Y') }}
                                </span>
                                @endif
                                @else
                                <span
                                    class="inline-flex items-center rounded-md bg-slate-50 px-1.5 py-0.5 text-[10px] font-medium text-slate-500 ring-1 ring-inset ring-slate-500/10">N/A</span>
                                @endif
                        </td>
                        <td class="px-3 py-2.5 text-[10px] font-medium text-slate-400 whitespace-nowrap">{{
                            $entry->received_date->format('M d, Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="py-10 text-center text-sm text-slate-400">No stock received yet.</div>
        @endif
    </div>

</div>
@endsection