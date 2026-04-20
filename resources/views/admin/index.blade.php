@extends('layouts.app')

@section('title', 'Record Management')

@section('content')
<div x-data="{ activeTab: '{{ $tab }}', search: '' }">

    {{-- Page Header --}}
    <div class="mb-5">
        <p class="text-[10px] font-mono font-semibold text-rose-600 uppercase tracking-[0.25em] mb-1">Admin://Records</p>
        <h1 class="text-xl font-bold text-slate-800 tracking-tight">Record Management</h1>
        <p class="text-xs text-slate-400 font-mono mt-0.5">Edit or delete raw transaction and item records.</p>
    </div>

    {{-- Tab Navigation --}}
    @php
        $tabs = [
            ['id' => 'stock-entries', 'label' => 'Stock Entries',  'count' => $stockEntries->count(), 'bar' => 'bg-emerald-500', 'active' => 'border-emerald-500 text-emerald-700 bg-emerald-50'],
            ['id' => 'usage-logs',   'label' => 'Usage Logs',     'count' => $usageLogs->count(),    'bar' => 'bg-rose-500',    'active' => 'border-rose-500 text-rose-700 bg-rose-50'],
            ['id' => 'borrows',      'label' => 'Borrows',        'count' => $borrows->count(),      'bar' => 'bg-blue-500',    'active' => 'border-blue-500 text-blue-700 bg-blue-50'],
            ['id' => 'returns',      'label' => 'Returns',        'count' => $returns->count(),      'bar' => 'bg-teal-500',    'active' => 'border-teal-500 text-teal-700 bg-teal-50'],
            ['id' => 'transfers',    'label' => 'Transfers',      'count' => $transfers->count(),    'bar' => 'bg-amber-500',   'active' => 'border-amber-500 text-amber-700 bg-amber-50'],
            ['id' => 'disposals',    'label' => 'Disposals',      'count' => $disposals->count(),    'bar' => 'bg-slate-600',   'active' => 'border-slate-600 text-slate-700 bg-slate-100'],
            ['id' => 'items',        'label' => 'Items',          'count' => $items->count(),        'bar' => 'bg-violet-500',  'active' => 'border-violet-500 text-violet-700 bg-violet-50'],
        ];
    @endphp
    <div class="flex flex-wrap gap-1.5 mb-3">
        @foreach($tabs as $tab)
        <button @click="activeTab = '{{ $tab['id'] }}'"
            :class="activeTab === '{{ $tab['id'] }}' ? '{{ $tab['active'] }}' : 'border-slate-200 bg-white text-slate-500 hover:border-slate-300 hover:text-slate-700'"
            class="inline-flex items-center gap-2 border px-4 py-2 text-[10px] font-mono font-bold uppercase tracking-widest transition-colors">
            {{ $tab['label'] }}
            <span class="font-black">{{ $tab['count'] }}</span>
        </button>
        @endforeach
    </div>

    {{-- Search Bar --}}
    <div class="flex items-center gap-2 mb-5">
        <div class="relative flex-1 max-w-sm">
            <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/></svg>
            </span>
            <input
                id="admin-records-search"
                type="text"
                x-model="search"
                placeholder="Search by item name…"
                class="w-full border border-slate-200 bg-white pl-9 pr-4 py-2 text-xs font-mono text-slate-700 placeholder-slate-400 focus:outline-none focus:border-slate-400 transition-colors"
            />
        </div>
        <button @click="search = ''" x-show="search.length > 0" class="border border-slate-200 bg-white px-3 py-2 text-[10px] font-mono font-bold text-slate-500 hover:bg-slate-100 transition-colors">Clear</button>
    </div>

    {{-- ══ STOCK ENTRIES TAB ══ --}}
    <div x-show="activeTab === 'stock-entries'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" style="display:none;">
        <div class="bg-white border border-slate-200 relative">
            <div class="absolute top-0 left-0 w-1 h-full bg-emerald-500"></div>
            <div class="ml-1">
                <div class="px-6 py-5 border-b border-slate-100">
                    <div class="flex items-center gap-2 mb-1"><span class="h-2 w-2 bg-emerald-500 inline-block"></span>
                        <p class="text-[10px] font-mono font-bold text-emerald-600 uppercase tracking-widest">// Stock Entries</p>
                    </div>
                    <p class="text-xs text-slate-500 font-mono">All incoming stock records. Edit quantities, lot numbers, dates.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead><tr class="bg-slate-50/50 border-b border-slate-200">
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Item</th>
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Qty</th>
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Lot / SN #</th>
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Expiry</th>
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Received</th>
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-right">Actions</th>
                        </tr></thead>
                        <tbody class="divide-y divide-slate-100">
                        @forelse($stockEntries as $entry)
                        <tr class="hover:bg-slate-50 transition-colors"
                            x-show="search === '' || '{{ strtolower($entry->item->name ?? '') }}'.includes(search.toLowerCase())">
                            <td class="px-6 py-4 font-bold text-slate-800">{{ $entry->item->name ?? '—' }}</td>
                            <td class="whitespace-nowrap px-6 py-4"><span class="font-mono font-black text-slate-700">{{ $entry->quantity }}</span></td>
                            <td class="whitespace-nowrap px-6 py-4 font-mono text-xs text-slate-500">
                                @if(($entry->item->item_type ?? '') === 'device')
                                    <span class="text-[10px] text-slate-400">SN:</span> {{ $entry->serial_number ?? '—' }}
                                @else
                                    {{ $entry->lot_number ?? '—' }}
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-xs text-slate-500">{{ $entry->expiry_date?->format('M d, Y') ?? '—' }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-xs text-slate-500">{{ $entry->received_date?->format('M d, Y') ?? '—' }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.stock-entries.edit', $entry) }}" class="inline-flex items-center border border-slate-200 bg-white px-2.5 py-1.5 text-[10px] font-mono font-bold text-slate-700 hover:bg-slate-100 transition-colors">Edit</a>
                                    <form action="{{ route('admin.stock-entries.destroy', $entry) }}" method="POST" class="m-0 inline" onsubmit="return confirm('Delete this record?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="inline-flex items-center border border-rose-200 bg-rose-50 px-2.5 py-1.5 text-[10px] font-mono font-bold text-rose-600 hover:bg-rose-500 hover:text-white hover:border-rose-500 transition-colors">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="px-6 py-16 text-center">
                            <p class="font-mono text-[10px] text-slate-400 uppercase tracking-widest">// No stock entries found</p>
                        </td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- ══ USAGE LOGS TAB ══ --}}
    <div x-show="activeTab === 'usage-logs'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" style="display:none;">
        <div class="bg-white border border-slate-200 relative">
            <div class="absolute top-0 left-0 w-1 h-full bg-rose-500"></div>
            <div class="ml-1">
                <div class="px-6 py-5 border-b border-slate-100">
                    <div class="flex items-center gap-2 mb-1"><span class="h-2 w-2 bg-rose-500 inline-block"></span>
                        <p class="text-[10px] font-mono font-bold text-rose-600 uppercase tracking-widest">// Usage Logs</p>
                    </div>
                    <p class="text-xs text-slate-500 font-mono">All item usage records. Fix quantities and dates.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead><tr class="bg-slate-50/50 border-b border-slate-200">
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Item</th>
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Qty Used</th>
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Used By</th>
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Date</th>
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-right">Actions</th>
                        </tr></thead>
                        <tbody class="divide-y divide-slate-100">
                        @forelse($usageLogs as $log)
                        <tr class="hover:bg-slate-50 transition-colors"
                            x-show="search === '' || '{{ strtolower($log->item->name ?? '') }}'.includes(search.toLowerCase())">
                            <td class="px-6 py-4 font-bold text-slate-800">{{ $log->item->name ?? '—' }}</td>
                            <td class="whitespace-nowrap px-6 py-4"><span class="font-mono font-black text-rose-600">-{{ $log->quantity_used }}</span></td>
                            <td class="whitespace-nowrap px-6 py-4 text-xs text-slate-600">{{ $log->used_by ?? '—' }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-xs text-slate-500">{{ $log->used_at?->format('M d, Y') ?? '—' }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.usage-logs.edit', $log) }}" class="inline-flex items-center border border-slate-200 bg-white px-2.5 py-1.5 text-[10px] font-mono font-bold text-slate-700 hover:bg-slate-100 transition-colors">Edit</a>
                                    <form action="{{ route('admin.usage-logs.destroy', $log) }}" method="POST" class="m-0 inline" onsubmit="return confirm('Delete this record?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="inline-flex items-center border border-rose-200 bg-rose-50 px-2.5 py-1.5 text-[10px] font-mono font-bold text-rose-600 hover:bg-rose-500 hover:text-white hover:border-rose-500 transition-colors">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-6 py-16 text-center">
                            <p class="font-mono text-[10px] text-slate-400 uppercase tracking-widest">// No usage logs found</p>
                        </td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- ══ BORROWS TAB ══ --}}
    <div x-show="activeTab === 'borrows'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" style="display:none;">
        <div class="bg-white border border-slate-200 relative">
            <div class="absolute top-0 left-0 w-1 h-full bg-blue-500"></div>
            <div class="ml-1">
                <div class="px-6 py-5 border-b border-slate-100">
                    <div class="flex items-center gap-2 mb-1"><span class="h-2 w-2 bg-blue-500 inline-block"></span>
                        <p class="text-[10px] font-mono font-bold text-blue-600 uppercase tracking-widest">// Borrow Records</p>
                    </div>
                    <p class="text-xs text-slate-500 font-mono">All borrow transactions. Adjust quantities, status, and dates.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead><tr class="bg-slate-50/50 border-b border-slate-200">
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Item</th>
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Borrower</th>
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Borrowed</th>
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Returned</th>
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Used</th>
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Status</th>
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Date</th>
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-right">Actions</th>
                        </tr></thead>
                        <tbody class="divide-y divide-slate-100">
                        @forelse($borrows as $borrow)
                        <tr class="hover:bg-slate-50 transition-colors"
                            x-show="search === '' || '{{ strtolower($borrow->item->name ?? '') }}'.includes(search.toLowerCase())">
                            <td class="px-6 py-4 font-bold text-slate-800">{{ $borrow->item->name ?? '—' }}</td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800 text-xs">{{ $borrow->borrower_name ?? $borrow->staff?->display_name ?? 'Unknown' }}</div>
                                @if($borrow->department)<div class="font-mono text-[10px] text-slate-400">{{ $borrow->department }}</div>@endif
                                @if($borrow->bio_id)<div class="font-mono text-[10px] text-slate-400">Bio: {{ $borrow->bio_id }}</div>@endif
                            </td>
                            <td class="whitespace-nowrap px-6 py-4"><span class="font-mono font-black text-blue-600">{{ $borrow->quantity_borrowed }}</span></td>
                            <td class="whitespace-nowrap px-6 py-4"><span class="font-mono font-black text-teal-600">{{ $borrow->quantity_returned }}</span></td>
                            <td class="whitespace-nowrap px-6 py-4"><span class="font-mono font-black text-rose-500">{{ $borrow->quantity_used }}</span></td>
                            <td class="whitespace-nowrap px-6 py-4">
                                @if($borrow->status === 'active')
                                <span class="inline-flex items-center gap-1.5 border border-amber-200 bg-amber-50 px-2 py-0.5 text-[9px] font-mono font-bold tracking-widest uppercase text-amber-700"><span class="h-1.5 w-1.5 bg-amber-500"></span>Active</span>
                                @elseif($borrow->status === 'partial')
                                <span class="inline-flex items-center gap-1.5 border border-blue-200 bg-blue-50 px-2 py-0.5 text-[9px] font-mono font-bold tracking-widest uppercase text-blue-700"><span class="h-1.5 w-1.5 bg-blue-500"></span>Partial</span>
                                @else
                                <span class="inline-flex items-center gap-1.5 border border-emerald-200 bg-emerald-50 px-2 py-0.5 text-[9px] font-mono font-bold tracking-widest uppercase text-emerald-700"><span class="h-1.5 w-1.5 bg-emerald-500"></span>Returned</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 font-mono text-xs text-slate-500">{{ $borrow->borrowed_at?->format('M d, Y') ?? '—' }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.borrows.edit', $borrow) }}" class="inline-flex items-center border border-slate-200 bg-white px-2.5 py-1.5 text-[10px] font-mono font-bold text-slate-700 hover:bg-slate-100 transition-colors">Edit</a>
                                    <form action="{{ route('admin.borrows.destroy', $borrow) }}" method="POST" class="m-0 inline" onsubmit="return confirm('Delete this record?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="inline-flex items-center border border-rose-200 bg-rose-50 px-2.5 py-1.5 text-[10px] font-mono font-bold text-rose-600 hover:bg-rose-500 hover:text-white hover:border-rose-500 transition-colors">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="px-6 py-16 text-center">
                            <p class="font-mono text-[10px] text-slate-400 uppercase tracking-widest">// No borrow records found</p>
                        </td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- ══ RETURNS TAB ══ --}}
    <div x-show="activeTab === 'returns'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" style="display:none;">
        <div class="bg-white border border-slate-200 relative">
            <div class="absolute top-0 left-0 w-1 h-full bg-teal-500"></div>
            <div class="ml-1">
                <div class="px-6 py-5 border-b border-slate-100">
                    <div class="flex items-center gap-2 mb-1"><span class="h-2 w-2 bg-teal-500 inline-block"></span>
                        <p class="text-[10px] font-mono font-bold text-teal-600 uppercase tracking-widest">// Return Records</p>
                    </div>
                    <p class="text-xs text-slate-500 font-mono">History of returned items. Edit quantities or dates.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead><tr class="bg-slate-50/50 border-b border-slate-200">
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Returned On</th>
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Staff</th>
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Item</th>
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Borrowed</th>
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Returned / Used</th>
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-right">Actions</th>
                        </tr></thead>
                        <tbody class="divide-y divide-slate-100">
                        @forelse($returns as $returnRecord)
                        <tr class="hover:bg-slate-50 transition-colors"
                            x-show="search === '' || '{{ strtolower($returnRecord->item->name ?? '') }}'.includes(search.toLowerCase())">
                            <td class="whitespace-nowrap px-6 py-4 font-mono text-xs text-slate-500">{{ $returnRecord->returned_at?->format('M d, Y') ?? '—' }}</td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800 text-xs">{{ $returnRecord->borrower_name ?? $returnRecord->staff?->display_name ?? 'Unknown' }}</div>
                                @if($returnRecord->department)<div class="font-mono text-[10px] text-slate-400">{{ $returnRecord->department }}</div>@endif
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-800">{{ $returnRecord->item->name ?? '—' }}</td>
                            <td class="whitespace-nowrap px-6 py-4"><span class="font-mono font-black text-slate-600">{{ $returnRecord->quantity_borrowed }}</span></td>
                            <td class="whitespace-nowrap px-6 py-4 text-xs font-semibold">
                                <span class="text-teal-600 font-mono font-black">{{ $returnRecord->quantity_returned }}</span> ret /
                                <span class="text-rose-500 font-mono font-black">{{ $returnRecord->quantity_used }}</span> used
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-right">
                                <a href="{{ route('admin.borrows.edit', $returnRecord) }}" class="inline-flex items-center border border-slate-200 bg-white px-2.5 py-1.5 text-[10px] font-mono font-bold text-slate-700 hover:bg-slate-100 transition-colors">Edit</a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="px-6 py-16 text-center">
                            <p class="font-mono text-[10px] text-slate-400 uppercase tracking-widest">// No return records found</p>
                        </td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- ══ TRANSFERS TAB ══ --}}
    <div x-show="activeTab === 'transfers'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" style="display:none;">
        <div class="bg-white border border-slate-200 relative">
            <div class="absolute top-0 left-0 w-1 h-full bg-amber-500"></div>
            <div class="ml-1">
                <div class="px-6 py-5 border-b border-slate-100">
                    <div class="flex items-center gap-2 mb-1"><span class="h-2 w-2 bg-amber-500 inline-block"></span>
                        <p class="text-[10px] font-mono font-bold text-amber-600 uppercase tracking-widest">// Transfer Records</p>
                    </div>
                    <p class="text-xs text-slate-500 font-mono">All item transfers. Fix destinations, quantities, and dates.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead><tr class="bg-slate-50/50 border-b border-slate-200">
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Date</th>
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Dir</th>
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Item</th>
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Qty</th>
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Destination</th>
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Party</th>
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-right">Actions</th>
                        </tr></thead>
                        <tbody class="divide-y divide-slate-100">
                        @forelse($transfers as $transfer)
                        <tr class="hover:bg-slate-50 transition-colors"
                            x-show="search === '' || '{{ strtolower($transfer->item->name ?? '') }}'.includes(search.toLowerCase())">
                            <td class="whitespace-nowrap px-6 py-4 font-mono text-xs text-slate-500">{{ $transfer->transferred_at?->format('M d, Y') ?? '—' }}</td>
                            <td class="whitespace-nowrap px-6 py-4">
                                @if($transfer->type === 'in')
                                <span class="inline-flex items-center border border-emerald-200 bg-emerald-50 px-1.5 py-0.5 text-[9px] font-mono font-bold tracking-widest uppercase text-emerald-700">↓ IN</span>
                                @else
                                <span class="inline-flex items-center border border-amber-200 bg-amber-50 px-1.5 py-0.5 text-[9px] font-mono font-bold tracking-widest uppercase text-amber-700">↑ OUT</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-800">{{ $transfer->item->name ?? '—' }}</td>
                            <td class="whitespace-nowrap px-6 py-4">
                                @if(($transfer->new_quantity ?? 0) > 0)
                                <div class="font-mono text-xs text-teal-600 font-black">{{ $transfer->new_quantity }} new</div>
                                @endif
                                @if(($transfer->used_quantity ?? 0) > 0)
                                <div class="font-mono text-xs text-amber-600 font-black">{{ $transfer->used_quantity }} used</div>
                                @endif
                                @if(($transfer->new_quantity ?? 0) == 0 && ($transfer->used_quantity ?? 0) == 0)
                                <span class="font-mono font-black text-slate-600">{{ $transfer->quantity }}</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-xs text-slate-600">{{ $transfer->destination ?? '—' }}</td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800 text-xs">{{ $transfer->transferred_to ?? 'Unknown' }}</div>
                                @if($transfer->department)<div class="font-mono text-[10px] text-slate-400">{{ $transfer->department }}</div>@endif
                                @if($transfer->bio_id)<div class="font-mono text-[10px] text-slate-400">Bio: {{ $transfer->bio_id }}</div>@endif
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.transfers.edit', $transfer) }}" class="inline-flex items-center border border-slate-200 bg-white px-2.5 py-1.5 text-[10px] font-mono font-bold text-slate-700 hover:bg-slate-100 transition-colors">Edit</a>
                                    <form action="{{ route('admin.transfers.destroy', $transfer) }}" method="POST" class="m-0 inline" onsubmit="return confirm('Delete this record?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="inline-flex items-center border border-rose-200 bg-rose-50 px-2.5 py-1.5 text-[10px] font-mono font-bold text-rose-600 hover:bg-rose-500 hover:text-white hover:border-rose-500 transition-colors">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="px-6 py-16 text-center">
                            <p class="font-mono text-[10px] text-slate-400 uppercase tracking-widest">// No transfer records found</p>
                        </td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- ══ DISPOSALS TAB ══ --}}
    <div x-show="activeTab === 'disposals'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" style="display:none;">
        <div class="bg-white border border-slate-200 relative">
            <div class="absolute top-0 left-0 w-1 h-full bg-slate-600"></div>
            <div class="ml-1">
                <div class="px-6 py-5 border-b border-slate-100">
                    <div class="flex items-center gap-2 mb-1"><span class="h-2 w-2 bg-slate-600 inline-block"></span>
                        <p class="text-[10px] font-mono font-bold text-slate-600 uppercase tracking-widest">// Disposal Records</p>
                    </div>
                    <p class="text-xs text-slate-500 font-mono">All disposed item records. Fix reasons, quantities, and dates.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead><tr class="bg-slate-50/50 border-b border-slate-200">
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Item</th>
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Qty</th>
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Reason</th>
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Disposed By</th>
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Date</th>
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-right">Actions</th>
                        </tr></thead>
                        <tbody class="divide-y divide-slate-100">
                        @forelse($disposals as $disposal)
                        <tr class="hover:bg-slate-50 transition-colors"
                            x-show="search === '' || '{{ strtolower($disposal->item->name ?? '') }}'.includes(search.toLowerCase())">
                            <td class="px-6 py-4 font-bold text-slate-800">{{ $disposal->item->name ?? '—' }}</td>
                            <td class="whitespace-nowrap px-6 py-4"><span class="font-mono font-black text-slate-600">{{ $disposal->quantity }}</span></td>
                            <td class="px-6 py-4 text-xs text-slate-600 max-w-xs truncate">{{ $disposal->reason ?? '—' }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-xs text-slate-600">{{ $disposal->disposed_by ?? '—' }}</td>
                            <td class="whitespace-nowrap px-6 py-4 font-mono text-xs text-slate-500">{{ $disposal->disposed_at?->format('M d, Y') ?? '—' }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.disposals.edit', $disposal) }}" class="inline-flex items-center border border-slate-200 bg-white px-2.5 py-1.5 text-[10px] font-mono font-bold text-slate-700 hover:bg-slate-100 transition-colors">Edit</a>
                                    <form action="{{ route('admin.disposals.destroy', $disposal) }}" method="POST" class="m-0 inline" onsubmit="return confirm('Delete this record?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="inline-flex items-center border border-rose-200 bg-rose-50 px-2.5 py-1.5 text-[10px] font-mono font-bold text-rose-600 hover:bg-rose-500 hover:text-white hover:border-rose-500 transition-colors">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="px-6 py-16 text-center">
                            <p class="font-mono text-[10px] text-slate-400 uppercase tracking-widest">// No disposal records found</p>
                        </td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- ══ ITEMS TAB ══ --}}
    <div x-show="activeTab === 'items'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" style="display:none;">
        <div class="bg-white border border-slate-200 relative">
            <div class="absolute top-0 left-0 w-1 h-full bg-violet-500"></div>
            <div class="ml-1">
                <div class="px-6 py-5 border-b border-slate-100">
                    <div class="flex items-center gap-2 mb-1"><span class="h-2 w-2 bg-violet-500 inline-block"></span>
                        <p class="text-[10px] font-mono font-bold text-violet-600 uppercase tracking-widest">// Master Item List</p>
                    </div>
                    <p class="text-xs text-slate-500 font-mono">Manage or delete items directly from the database.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead><tr class="bg-slate-50/50 border-b border-slate-200">
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Name</th>
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Category</th>
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Stock</th>
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-right">Actions</th>
                        </tr></thead>
                        <tbody class="divide-y divide-slate-100">
                        @forelse($items as $item)
                        <tr class="hover:bg-slate-50 transition-colors"
                            x-show="search === '' || '{{ strtolower($item->name) }}'.includes(search.toLowerCase())">
                            <td class="px-6 py-4 font-bold text-slate-800">{{ $item->name }}</td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <span class="inline-flex items-center border border-slate-200 bg-slate-50 px-2 py-0.5 text-[9px] font-mono font-bold tracking-widest uppercase text-slate-600">
                                    {{ $item->category->name ?? 'Uncategorized' }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <span class="font-mono font-black text-slate-700">{{ $item->stock_quantity }}</span>
                                <span class="font-mono text-[10px] text-slate-400 ml-0.5">{{ $item->unit }}</span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('items.edit', $item) }}" class="inline-flex items-center border border-slate-200 bg-white px-2.5 py-1.5 text-[10px] font-mono font-bold text-slate-700 hover:bg-slate-100 transition-colors">Edit</a>
                                    <form action="{{ route('items.destroy', $item) }}" method="POST" class="m-0 inline"
                                        onsubmit="return confirm('Delete \'{{ addslashes($item->name) }}\' and ALL its records (stock entries, usage logs, borrows, transfers, disposals)? This cannot be undone.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="inline-flex items-center border border-rose-200 bg-rose-50 px-2.5 py-1.5 text-[10px] font-mono font-bold text-rose-600 hover:bg-rose-500 hover:text-white hover:border-rose-500 transition-colors">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-6 py-16 text-center">
                            <p class="font-mono text-[10px] text-slate-400 uppercase tracking-widest">// No items found</p>
                        </td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection