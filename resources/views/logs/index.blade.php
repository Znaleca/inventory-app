@extends('layouts.app')

@section('title', 'Transaction History')

@section('content')

{{-- Filter Island --}}
<div
    class="mb-6 rounded-[1.5rem] bg-white/80 p-4 ring-1 ring-slate-200/50 shadow-[0_8px_30px_-12px_rgba(0,0,0,0.04)] backdrop-blur-xl">
    <form method="GET" action="{{ route('logs.index') }}" class="flex flex-wrap items-end gap-3">
        <div class="w-full sm:w-auto flex-1 sm:min-w-[200px]">
            <label class="mb-1.5 block text-[10px] font-bold uppercase tracking-widest text-slate-400">Item</label>
            <select name="item"
                class="block w-full rounded-xl border-0 bg-slate-50/50 px-4 py-2.5 text-sm text-slate-800 ring-1 ring-inset ring-slate-200/80 transition-all hover:bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500/50"
                onchange="this.form.submit()">
                <option value="">All Items</option>
                @foreach($items as $item)
                <option value="{{ $item->id }}" {{ request('item')==$item->id ? 'selected' : '' }}>{{ $item->name }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="w-full sm:w-auto flex-1 sm:max-w-[180px]">
            <label class="mb-1.5 block text-[10px] font-bold uppercase tracking-widest text-slate-400">Type</label>
            <select name="type"
                class="block w-full rounded-xl border-0 bg-slate-50/50 px-4 py-2.5 text-sm text-slate-800 ring-1 ring-inset ring-slate-200/80 transition-all hover:bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500/50"
                onchange="this.form.submit()">
                <option value="">All Types</option>
                <option value="in" {{ request('type')==='in' ? 'selected' : '' }}>Stock In ↑</option>
                <option value="out" {{ request('type')==='out' ? 'selected' : '' }}>Usage Out ↓</option>
            </select>
        </div>

        <div class="w-full sm:w-auto">
            <label class="mb-1.5 block text-[10px] font-bold uppercase tracking-widest text-slate-400">From</label>
            <input type="date" name="from" value="{{ request('from') }}"
                class="block w-full rounded-xl border-0 bg-slate-50/50 px-4 py-2.5 text-sm text-slate-800 ring-1 ring-inset ring-slate-200/80 transition-all hover:bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500/50">
        </div>

        <div class="w-full sm:w-auto">
            <label class="mb-1.5 block text-[10px] font-bold uppercase tracking-widest text-slate-400">To</label>
            <input type="date" name="to" value="{{ request('to') }}"
                class="block w-full rounded-xl border-0 bg-slate-50/50 px-4 py-2.5 text-sm text-slate-800 ring-1 ring-inset ring-slate-200/80 transition-all hover:bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500/50">
        </div>

        <div class="flex w-full gap-2 sm:w-auto pt-2 sm:pt-0">
            <button type="submit"
                class="flex flex-1 items-center justify-center rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:bg-slate-800 sm:flex-none">
                Apply
            </button>
            @if(request('item') || request('type') || request('from') || request('to'))
            <a href="{{ route('logs.index') }}"
                class="flex flex-1 items-center justify-center rounded-xl bg-slate-100 px-5 py-2.5 text-sm font-medium text-slate-600 transition-colors hover:bg-slate-200 sm:flex-none">
                Clear
            </a>
            @endif
        </div>
    </form>
</div>

{{-- Summary Stats Cards --}}
@php
$totalIn = $transactions->where('type', 'in')->sum('quantity');
$totalOut = $transactions->where('type', 'out')->sum('quantity');
$count = $transactions->count();
@endphp
<div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
    {{-- Total Transactions --}}
    <div
        class="overflow-hidden rounded-[1.5rem] bg-white/80 p-5 ring-1 ring-slate-200/50 shadow-[0_8px_30px_-12px_rgba(0,0,0,0.04)] backdrop-blur-xl transition-all duration-300 hover:-translate-y-0.5 hover:shadow-[0_12px_30px_-12px_rgba(0,0,0,0.08)] hover:bg-white">
        <div class="flex items-center gap-4">
            <div
                class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-slate-800 text-white shadow-lg shadow-slate-800/20">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="h-6 w-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z" />
                </svg>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-2xl font-black text-slate-900 leading-none">{{ $count }}</p>
                <p class="mt-1.5 text-[10px] font-bold uppercase tracking-widest text-slate-400">Total Transactions</p>
            </div>
        </div>
    </div>

    {{-- Units Received --}}
    <div
        class="overflow-hidden rounded-[1.5rem] bg-white/80 p-5 ring-1 ring-slate-200/50 shadow-[0_8px_30px_-12px_rgba(0,0,0,0.04)] backdrop-blur-xl transition-all duration-300 hover:-translate-y-0.5 hover:shadow-[0_12px_30px_-12px_rgba(0,0,0,0.08)] hover:bg-white">
        <div class="flex items-center gap-4">
            <div
                class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-500 text-white shadow-lg shadow-emerald-500/20">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-6 w-6">
                    <path fill-rule="evenodd"
                        d="M8 14a.75.75 0 01-.75-.75V4.56L4.03 7.78a.75.75 0 01-1.06-1.06l4.5-4.5a.75.75 0 011.06 0l4.5 4.5a.75.75 0 01-1.06 1.06L8.75 4.56v8.69A.75.75 0 018 14z"
                        clip-rule="evenodd" />
                </svg>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-2xl font-black text-slate-900 leading-none">+{{ $totalIn }}</p>
                <p class="mt-1.5 text-[10px] font-bold uppercase tracking-widest text-slate-400">Units Received</p>
            </div>
        </div>
    </div>

    {{-- Units Used --}}
    <div
        class="overflow-hidden rounded-[1.5rem] bg-white/80 p-5 ring-1 ring-slate-200/50 shadow-[0_8px_30px_-12px_rgba(0,0,0,0.04)] backdrop-blur-xl transition-all duration-300 hover:-translate-y-0.5 hover:shadow-[0_12px_30px_-12px_rgba(0,0,0,0.08)] hover:bg-white">
        <div class="flex items-center gap-4">
            <div
                class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-rose-500 text-white shadow-lg shadow-rose-500/20">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-6 w-6">
                    <path fill-rule="evenodd"
                        d="M8 2a.75.75 0 01.75.75v8.69l3.22-3.22a.75.75 0 111.06 1.06l-4.5 4.5a.75.75 0 01-1.06 0l-4.5-4.5a.75.75 0 111.06-1.06L7.25 11.44V2.75A.75.75 0 018 2z"
                        clip-rule="evenodd" />
                </svg>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-2xl font-black text-slate-900 leading-none">-{{ $totalOut }}</p>
                <p class="mt-1.5 text-[10px] font-bold uppercase tracking-widest text-slate-400">Units Used</p>
            </div>
        </div>
    </div>
</div>

{{-- Main Table Card --}}
<div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-[0_4px_24px_-8px_rgba(0,0,0,0.06)]">
    <div class="border-b border-slate-100 bg-slate-50/50 px-8 py-5">
        <h3 class="text-sm font-semibold text-slate-800">Transaction Log</h3>
        <p class="mt-0.5 text-xs text-slate-500">All stock movements — incoming deliveries and usage records.</p>
    </div>

    @if($transactions->isEmpty())
    {{-- Glowing Empty State --}}
    <div class="relative flex flex-col items-center justify-center px-6 py-24 text-center group">
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
                    d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
            </svg>
        </div>

        <h3 class="relative z-10 text-lg font-black text-slate-800">No transactions found</h3>
        <p class="relative z-10 mt-2 max-w-sm text-sm font-medium leading-relaxed text-slate-500">
            Try adjusting your filters or date range to see results.
        </p>
        @if(request('item') || request('type') || request('from') || request('to'))
        <a href="{{ route('logs.index') }}"
            class="relative z-10 mt-6 inline-flex items-center rounded-xl bg-slate-100 px-4 py-2 text-sm font-bold text-slate-600 transition-colors hover:bg-slate-200">
            Clear Filters
        </a>
        @endif
    </div>
    @else
    <div class="overflow-x-auto p-2">
        <table class="min-w-full text-sm border-separate border-spacing-y-1">
            <thead>
                <tr>
                    <th scope="col"
                        class="whitespace-nowrap px-3 py-2 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">
                        Date</th>
                    <th scope="col"
                        class="whitespace-nowrap px-3 py-2 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">
                        Type</th>
                    <th scope="col"
                        class="whitespace-nowrap px-3 py-2 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">
                        Item</th>
                    <th scope="col"
                        class="whitespace-nowrap px-3 py-2 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">
                        Qty</th>
                    <th scope="col"
                        class="whitespace-nowrap px-3 py-2 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">
                        Details</th>
                    <th scope="col"
                        class="whitespace-nowrap px-3 py-2 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">
                        Notes</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $tx)
                <tr
                    class="group transition-all duration-200 hover:bg-white rounded-xl shadow-sm hover:shadow-md hover:ring-1 hover:ring-slate-200/50">

                    {{-- Date --}}
                    <td class="whitespace-nowrap px-3 py-2.5 rounded-l-xl">
                        <span class="block text-sm font-bold text-slate-800">{{ $tx['date']->format('M d, Y') }}</span>
                        <span class="block mt-0.5 text-xs font-semibold text-slate-400">{{ $tx['date']->format('h:i A')
                            }}</span>
                    </td>

                    {{-- Type Badge --}}
                    <td class="whitespace-nowrap px-3 py-2.5">
                        @if($tx['type'] === 'in')
                        <span
                            class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-50/80 px-2.5 py-1.5 text-[11px] font-bold text-emerald-700 ring-1 ring-inset ring-emerald-500/20">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                                class="h-3.5 w-3.5">
                                <path fill-rule="evenodd"
                                    d="M8 14a.75.75 0 01-.75-.75V4.56L4.03 7.78a.75.75 0 01-1.06-1.06l4.5-4.5a.75.75 0 011.06 0l4.5 4.5a.75.75 0 01-1.06 1.06L8.75 4.56v8.69A.75.75 0 018 14z"
                                    clip-rule="evenodd" />
                            </svg>
                            Stock In
                        </span>
                        @elseif(isset($tx['id']) && str_starts_with($tx['id'], 'borrow-'))
                        <span
                            class="inline-flex items-center gap-1.5 rounded-lg bg-blue-50/80 px-2.5 py-1.5 text-[11px] font-bold text-blue-700 ring-1 ring-inset ring-blue-500/20">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                                class="h-3.5 w-3.5">
                                <path
                                    d="M11 3v2h2V3h-2zm-1 0v2H3V3h7zm3 3v2h-2V6h2zm-3 0v2H3V6h7zm0 3v2H3V9h7zM3 12v2h7v-2H3zm10 0v2h-2v-2h2z">
                                </path>
                            </svg>
                            Borrowed Out
                        </span>
                        @elseif(isset($tx['id']) && str_starts_with($tx['id'], 'return-'))
                        <span
                            class="inline-flex items-center gap-1.5 rounded-lg bg-indigo-50/80 px-2.5 py-1.5 text-[11px] font-bold text-indigo-700 ring-1 ring-inset ring-indigo-500/20">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                                class="h-3.5 w-3.5">
                                <path fill-rule="evenodd"
                                    d="M12.5 9.75a.75.75 0 00-.75.75v1.5a.75.75 0 01-.75.75H5a.75.75 0 01-.75-.75V3.75a.75.75 0 01.75-.75h6a.75.75 0 01.75.75v1.5a.75.75 0 001.5 0v-1.5A2.25 2.25 0 0011 1.25H5A2.25 2.25 0 002.75 3.5v8.25A2.25 2.25 0 005 14h6a2.25 2.25 0 002.25-2.25v-1.25a.75.75 0 00-.75-.75z"
                                    clip-rule="evenodd" />
                                <path fill-rule="evenodd"
                                    d="M10.22 5.22a.75.75 0 011.06 0l2.5 2.5a.75.75 0 010 1.06l-2.5 2.5a.75.75 0 01-1.06-1.06L11.94 8.5H6.75a.75.75 0 010-1.5h5.19l-1.72-1.72a.75.75 0 010-1.06z"
                                    clip-rule="evenodd" />
                            </svg>
                            Returned In
                        </span>
                        @elseif(isset($tx['id']) && str_starts_with($tx['id'], 'disposal-'))
                        <span
                            class="inline-flex items-center gap-1.5 rounded-lg bg-slate-100/80 px-2.5 py-1.5 text-[11px] font-bold text-slate-700 ring-1 ring-inset ring-slate-500/20">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                                class="h-3.5 w-3.5">
                                <path
                                    d="M2.5 4a.5.5 0 01.5-.5h10a.5.5 0 010 1H13v9a2 2 0 01-2 2H5a2 2 0 01-2-2V4.5h-.5a.5.5 0 01-.5-.5zM4.5 4.5V13a1 1 0 001 1h6a1 1 0 001-1V4.5h-8zM6.25 6a.5.5 0 01.5.5v5a.5.5 0 01-1 0v-5a.5.5 0 01.5-.5zm3.5 0a.5.5 0 01.5.5v5a.5.5 0 01-1 0v-5a.5.5 0 01.5-.5zM5.5 2.5a.5.5 0 01.5-.5h4a.5.5 0 010 1H6a.5.5 0 01-.5-.5z">
                                </path>
                            </svg>
                            Disposed
                        </span>
                        @else
                        <span
                            class="inline-flex items-center gap-1.5 rounded-lg bg-rose-50/80 px-2.5 py-1.5 text-[11px] font-bold text-rose-600 ring-1 ring-inset ring-rose-500/20">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                                class="h-3.5 w-3.5">
                                <path fill-rule="evenodd"
                                    d="M8 2a.75.75 0 01.75.75v8.69l3.22-3.22a.75.75 0 111.06 1.06l-4.5 4.5a.75.75 0 01-1.06 0l-4.5-4.5a.75.75 0 111.06-1.06L7.25 11.44V2.75A.75.75 0 018 2z"
                                    clip-rule="evenodd" />
                            </svg>
                            {{ isset($tx['id']) && str_starts_with($tx['id'], 'transfer-') ? 'Transferred Out' : 'Usage
                            Out' }}
                        </span>
                        @endif
                    </td>

                    {{-- Item --}}
                    <td class="px-3 py-2.5">
                        <a href="{{ route('items.show', $tx['item']) }}"
                            class="block text-sm font-bold text-slate-800 hover:text-indigo-600 transition-colors">{{
                            $tx['item']->name }}</a>
                        <span class="block mt-0.5 font-mono text-xs font-semibold text-slate-400">{{ $tx['item']->sku
                            }}</span>
                    </td>

                    {{-- Quantity --}}
                    <td class="whitespace-nowrap px-3 py-2.5">
                        <span
                            class="text-sm font-black {{ $tx['type'] === 'in' ? 'text-emerald-600' : 'text-rose-500' }}">
                            {{ $tx['type'] === 'in' ? '+' : '-' }}{{ $tx['quantity'] }}
                        </span>
                        <span class="text-xs font-bold text-slate-400"> {{ $tx['item']->unit }}</span>
                    </td>

                    {{-- Details --}}
                    <td class="px-3 py-2.5 text-xs font-medium text-slate-500 space-y-1">
                        @if($tx['type'] === 'in')
                        @if($tx['lot_number']) <div class="flex items-center gap-1"><span
                                class="text-slate-400 uppercase tracking-wider text-[9px] font-bold">Lot:</span> <span
                                class="font-mono text-slate-700">{{ $tx['lot_number'] }}</span></div> @endif
                        @if($tx['expiry_date']) <div class="flex items-center gap-1"><span
                                class="text-slate-400 uppercase tracking-wider text-[9px] font-bold">Exp:</span>
                            <span>{{ \Carbon\Carbon::parse($tx['expiry_date'])->format('M Y') }}</span></div> @endif
                        @else
                        @if($tx['used_by']) <div class="flex items-center gap-1"><span
                                class="text-slate-400 uppercase tracking-wider text-[9px] font-bold">By:</span> <span
                                class="text-slate-700">{{ $tx['used_by'] }}</span></div> @endif
                        @if($tx['patient_id']) <div class="flex items-center gap-1"><span
                                class="text-slate-400 uppercase tracking-wider text-[9px] font-bold">Patient:</span>
                            <span class="font-mono">{{ $tx['patient_id'] }}</span></div> @endif
                        @if($tx['procedure_type']) <div>{{ $tx['procedure_type'] }}</div> @endif
                        @endif
                        @if(!$tx['lot_number'] && !$tx['expiry_date'] && !$tx['used_by'] && !$tx['patient_id'] &&
                        !$tx['procedure_type'])
                        <span class="text-slate-300">—</span>
                        @endif
                    </td>

                    {{-- Notes --}}
                    <td class="px-3 py-2.5 text-xs font-medium text-slate-500 max-w-[200px] rounded-r-xl">
                        {{ $tx['notes'] ?: '—' }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endsection