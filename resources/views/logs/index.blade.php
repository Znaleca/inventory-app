@extends('layouts.app')

@section('title', 'Transaction History')

@section('content')

@php
    $totalIn  = $transactions->where('type', 'in')->sum('quantity');
    $totalOut = $transactions->where('type', 'out')->sum('quantity');
    $count    = $transactions->count();

    // Helper: detect specific transaction category from id prefix
    function txCategory(array $tx): string {
        $id = $tx['id'] ?? '';
        if (str_starts_with($id, 'stock-'))    return 'Stock In';
        if (str_starts_with($id, 'usage-'))    return 'Usage Out';
        if (str_starts_with($id, 'transfer-')) return 'Transfer';
        if (str_starts_with($id, 'disposal-')) return 'Disposal';
        if (str_starts_with($id, 'borrow-'))   return 'Borrow Out';
        if (str_starts_with($id, 'return-'))   return 'Return';
        return $tx['type'] === 'in' ? 'Stock In' : 'Usage Out';
    }
@endphp

{{-- Stats Row --}}
<div class="grid grid-cols-3 gap-3 mb-5">
    <div class="bg-white border border-slate-200 relative">
        <div class="absolute top-0 left-0 w-1 h-full bg-slate-500"></div>
        <div class="p-4 pl-5">
            <p class="text-[10px] font-mono font-bold text-slate-400 uppercase tracking-widest mb-1">Total Logs</p>
            <p class="text-2xl font-black font-mono text-slate-800">{{ $count }}</p>
        </div>
    </div>
    <div class="bg-white border border-slate-200 relative">
        <div class="absolute top-0 left-0 w-1 h-full bg-emerald-500"></div>
        <div class="p-4 pl-5">
            <p class="text-[10px] font-mono font-bold text-slate-400 uppercase tracking-widest mb-1">Units In</p>
            <p class="text-2xl font-black font-mono text-emerald-600">+{{ $totalIn }}</p>
        </div>
    </div>
    <div class="bg-white border border-slate-200 relative">
        <div class="absolute top-0 left-0 w-1 h-full bg-rose-500"></div>
        <div class="p-4 pl-5">
            <p class="text-[10px] font-mono font-bold text-slate-400 uppercase tracking-widest mb-1">Units Out</p>
            <p class="text-2xl font-black font-mono text-rose-600">-{{ $totalOut }}</p>
        </div>
    </div>
</div>

{{-- Filter Bar --}}
<div class="bg-white border border-slate-200 relative mb-5">
    <div class="absolute top-0 left-0 w-1 h-full bg-blue-500"></div>
    <div class="ml-1 px-5 py-4">
        <form method="GET" action="{{ route('logs.index') }}" class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-[180px]">
                <label class="block text-[10px] font-mono font-bold text-slate-400 uppercase tracking-widest mb-1.5">Item</label>
                <select name="item"
                    class="block w-full border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:outline-none py-2.5 px-3 text-sm text-slate-800 font-mono transition-colors"
                    onchange="this.form.submit()">
                    <option value="">All Items</option>
                    @foreach($items as $item)
                    <option value="{{ $item->id }}" {{ request('item') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-44">
                <label class="block text-[10px] font-mono font-bold text-slate-400 uppercase tracking-widest mb-1.5">Type</label>
                <select name="type"
                    class="block w-full border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:outline-none py-2.5 px-3 text-sm text-slate-800 font-mono transition-colors"
                    onchange="this.form.submit()">
                    <option value="">All Types</option>
                    <option value="in"  {{ request('type') === 'in'  ? 'selected' : '' }}>Stock In ↑</option>
                    <option value="out" {{ request('type') === 'out' ? 'selected' : '' }}>Movements Out ↓</option>
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-mono font-bold text-slate-400 uppercase tracking-widest mb-1.5">From</label>
                <input type="date" name="from" value="{{ request('from') }}"
                    class="block border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:outline-none py-2.5 px-3 text-sm text-slate-800 transition-colors">
            </div>
            <div>
                <label class="block text-[10px] font-mono font-bold text-slate-400 uppercase tracking-widest mb-1.5">To</label>
                <input type="date" name="to" value="{{ request('to') }}"
                    class="block border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:outline-none py-2.5 px-3 text-sm text-slate-800 transition-colors">
            </div>
            <div class="flex gap-2">
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-slate-900 px-5 py-2.5 text-[11px] font-mono font-bold text-white uppercase tracking-widest hover:bg-slate-800 transition-colors border border-slate-900">
                    Apply
                </button>
                @if(request('item') || request('type') || request('from') || request('to'))
                <a href="{{ route('logs.index') }}"
                    class="inline-flex items-center gap-2 border border-slate-200 bg-slate-50 px-5 py-2.5 text-[11px] font-mono font-bold text-slate-600 uppercase tracking-widest hover:bg-slate-100 transition-colors">
                    Clear
                </a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- Transaction Table --}}
<div class="bg-white border border-slate-200 relative">
    <div class="absolute top-0 left-0 w-1 h-full bg-slate-400"></div>
    <div class="ml-1">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="h-2 w-2 bg-slate-400 inline-block"></span>
                    <p class="text-[10px] font-mono font-bold text-slate-500 uppercase tracking-widest">// Transaction Log</p>
                </div>
                <h2 class="text-xl font-bold text-slate-800 tracking-tight">All Stock Movements</h2>
                <p class="text-xs text-slate-500 font-mono mt-1">Receipts, usage, borrows, transfers, returns, and disposals.</p>
            </div>
            <span class="inline-flex items-center border border-slate-200 bg-slate-50 px-3 py-1 text-[10px] font-mono font-bold text-slate-600 tracking-widest uppercase shrink-0">
                {{ $count }} records
            </span>
        </div>

        @if($transactions->isEmpty())
        <div class="flex flex-col items-center justify-center py-20 text-center">
            <div class="h-14 w-14 border border-slate-200 bg-slate-50 flex items-center justify-center text-slate-400 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-7 w-7">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
            </div>
            <p class="font-mono text-[10px] text-slate-400 uppercase tracking-widest mb-1">// No records found</p>
            <p class="text-sm font-semibold text-slate-500 mt-1">
                {{ request('item') || request('type') || request('from') || request('to') ? 'Try adjusting your filters.' : 'No transactions logged yet.' }}
            </p>
            @if(request('item') || request('type') || request('from') || request('to'))
            <a href="{{ route('logs.index') }}" class="mt-4 inline-flex items-center border border-slate-200 bg-white px-4 py-2 text-[10px] font-mono font-bold text-slate-600 tracking-widest uppercase hover:bg-slate-50 transition-colors">
                Clear Filters
            </a>
            @endif
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-200">
                        <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Date</th>
                        <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Category</th>
                        <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Item</th>
                        <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Qty</th>
                        <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Party / Details</th>
                        <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Notes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($transactions as $tx)
                    @php
                        $cat = txCategory($tx);
                        $badges = [
                            'Stock In'   => 'border-emerald-200 bg-emerald-50 text-emerald-700',
                            'Usage Out'  => 'border-rose-200 bg-rose-50 text-rose-700',
                            'Transfer'   => 'border-amber-200 bg-amber-50 text-amber-700',
                            'Disposal'   => 'border-slate-300 bg-slate-100 text-slate-600',
                            'Borrow Out' => 'border-blue-200 bg-blue-50 text-blue-700',
                            'Return'     => 'border-teal-200 bg-teal-50 text-teal-700',
                        ];
                        $badge = $badges[$cat] ?? 'border-slate-200 bg-slate-50 text-slate-600';
                        $prefix = in_array($cat, ['Stock In', 'Return']) ? '+' : '-';
                        $numColor = in_array($cat, ['Stock In', 'Return']) ? 'text-emerald-600' : 'text-rose-500';
                    @endphp
                    <tr class="hover:bg-slate-50 transition-colors">

                        {{-- Date --}}
                        <td class="whitespace-nowrap px-6 py-4">
                            <span class="block font-mono text-xs font-bold text-slate-700">{{ $tx['date']->format('Y-m-d') }}</span>
                            <span class="block font-mono text-[10px] text-slate-400">{{ $tx['date']->format('H:i') }}</span>
                        </td>

                        {{-- Category Badge --}}
                        <td class="whitespace-nowrap px-6 py-4">
                            <span class="inline-flex items-center border px-2 py-0.5 text-[9px] font-mono font-bold tracking-widest uppercase {{ $badge }}">
                                {{ $cat }}
                            </span>
                        </td>

                        {{-- Item --}}
                        <td class="px-6 py-4">
                            <a href="{{ route('items.show', $tx['item']) }}"
                                class="font-bold text-slate-900 hover:text-blue-600 transition-colors">
                                {{ $tx['item']->name }}
                            </a>
                            @if($tx['lot_number'])
                            <div class="font-mono text-[10px] text-slate-400 mt-0.5">Lot: {{ $tx['lot_number'] }}</div>
                            @endif
                            @if($tx['expiry_date'])
                            <div class="font-mono text-[10px] text-slate-400">Exp: {{ \Carbon\Carbon::parse($tx['expiry_date'])->format('M Y') }}</div>
                            @endif
                        </td>

                        {{-- Quantity --}}
                        <td class="whitespace-nowrap px-6 py-4">
                            <span class="text-sm font-black font-mono {{ $numColor }}">
                                {{ $prefix }}{{ $tx['quantity'] }}
                            </span>
                            <span class="text-[10px] font-mono text-slate-400 ml-0.5">{{ $tx['item']->unit }}</span>
                        </td>

                        {{-- Party / Details --}}
                        <td class="px-6 py-4 font-mono text-xs text-slate-500 max-w-[180px]">
                            {{ $tx['used_by'] ?: '—' }}
                        </td>

                        {{-- Notes --}}
                        <td class="px-6 py-4 text-xs text-slate-500 max-w-[200px]">
                            <span class="truncate block" title="{{ $tx['notes'] }}">{{ $tx['notes'] ?: '—' }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>

@endsection