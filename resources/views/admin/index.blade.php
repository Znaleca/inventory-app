@extends('layouts.app')

@section('title', 'Record Management')

@section('content')
    <div x-data="{ activeTab: '{{ $tab }}' }">

        {{-- Tab Navigation --}}
        <div class="mb-6 flex flex-wrap gap-2 rounded-2xl border border-slate-200/80 bg-white p-2 shadow-sm">
            <button @click="activeTab = 'stock-entries'"
                    :class="activeTab === 'stock-entries' ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/25' : 'text-slate-500 hover:ring-1 hover:ring-slate-300 hover:text-slate-800'"
                    class="flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold transition-all duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" /></svg>
                Stock Entries
                <span class="rounded-md px-1.5 py-0.5 text-[10px] font-black tracking-widest transition-colors" :class="activeTab === 'stock-entries' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500'">{{ $stockEntries->count() }}</span>
            </button>

            <button @click="activeTab = 'usage-logs'"
                    :class="activeTab === 'usage-logs' ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/25' : 'text-slate-500 hover:ring-1 hover:ring-slate-300 hover:text-slate-800'"
                    class="flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold transition-all duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                Usage Logs
                <span class="rounded-md px-1.5 py-0.5 text-[10px] font-black tracking-widest transition-colors" :class="activeTab === 'usage-logs' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500'">{{ $usageLogs->count() }}</span>
            </button>

            <button @click="activeTab = 'borrows'"
                    :class="activeTab === 'borrows' ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/25' : 'text-slate-500 hover:ring-1 hover:ring-slate-300 hover:text-slate-800'"
                    class="flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold transition-all duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" /></svg>
                Borrows
                <span class="rounded-md px-1.5 py-0.5 text-[10px] font-black tracking-widest transition-colors" :class="activeTab === 'borrows' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500'">{{ $borrows->count() }}</span>
            </button>

            <button @click="activeTab = 'transfers'"
                    :class="activeTab === 'transfers' ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/25' : 'text-slate-500 hover:ring-1 hover:ring-slate-300 hover:text-slate-800'"
                    class="flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold transition-all duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" /></svg>
                Transfers
                <span class="rounded-md px-1.5 py-0.5 text-[10px] font-black tracking-widest transition-colors" :class="activeTab === 'transfers' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500'">{{ $transfers->count() }}</span>
            </button>

            <button @click="activeTab = 'returns'"
                    :class="activeTab === 'returns' ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/25' : 'text-slate-500 hover:ring-1 hover:ring-slate-300 hover:text-slate-800'"
                    class="flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold transition-all duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" /></svg>
                Returns
                <span class="rounded-md px-1.5 py-0.5 text-[10px] font-black tracking-widest transition-colors" :class="activeTab === 'returns' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500'">{{ $returns->count() }}</span>
            </button>

            <button @click="activeTab = 'disposals'"
                    :class="activeTab === 'disposals' ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/25' : 'text-slate-500 hover:ring-1 hover:ring-slate-300 hover:text-slate-800'"
                    class="flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold transition-all duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                Disposals
                <span class="rounded-md px-1.5 py-0.5 text-[10px] font-black tracking-widest transition-colors" :class="activeTab === 'disposals' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500'">{{ $disposals->count() }}</span>
            </button>

            <button @click="activeTab = 'items'"
                    :class="activeTab === 'items' ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/25' : 'text-slate-500 hover:ring-1 hover:ring-slate-300 hover:text-slate-800'"
                    class="flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold transition-all duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" /></svg>
                Items
                <span class="rounded-md px-1.5 py-0.5 text-[10px] font-black tracking-widest transition-colors" :class="activeTab === 'items' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500'">{{ $items->count() }}</span>
            </button>
        </div>

        {{-- ══════════════════════════════════════════════ --}}
        {{-- Stock Entries Tab --}}
        {{-- ══════════════════════════════════════════════ --}}
        <div x-show="activeTab === 'stock-entries'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
            <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-[0_8px_30px_-12px_rgba(0,0,0,0.06)]">
                <div class="border-b border-slate-100 bg-slate-50/50 px-8 py-5 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-slate-800">Stock Entries</h3>
                        <p class="mt-0.5 text-xs font-medium text-slate-500">All incoming stock records. Edit quantities, lot numbers, dates.</p>
                    </div>
                </div>
                <div class="overflow-x-auto p-2">
                    <table class="min-w-full text-sm border-separate border-spacing-y-1">
                        <thead>
                            <tr>
                                <th class="whitespace-nowrap px-3 py-2 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">Item</th>
                                <th class="whitespace-nowrap px-3 py-2 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">Qty</th>
                                <th class="whitespace-nowrap px-3 py-2 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">Lot #</th>
                                <th class="whitespace-nowrap px-3 py-2 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">Expiry</th>
                                <th class="whitespace-nowrap px-3 py-2 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">Received</th>
                                <th class="whitespace-nowrap px-3 py-2 text-right text-[10px] font-bold uppercase tracking-widest text-slate-400">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stockEntries as $entry)
                                <tr class="group transition-all duration-200 hover:bg-white rounded-xl shadow-sm hover:shadow-md hover:ring-1 hover:ring-slate-200/50">
                                    <td class="whitespace-nowrap px-3 py-2.5 rounded-l-xl font-bold text-slate-800">{{ $entry->item->name ?? '—' }}</td>
                                    <td class="whitespace-nowrap px-3 py-2.5">
                                        <span class="inline-flex items-center rounded-lg bg-slate-100 px-2 py-1 text-xs font-black text-slate-600">{{ $entry->quantity }}</span>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-2.5 font-mono text-xs font-bold text-slate-500">{{ $entry->lot_number ?? '—' }}</td>
                                    <td class="whitespace-nowrap px-3 py-2.5 font-medium text-slate-500">{{ $entry->expiry_date?->format('M d, Y') ?? '—' }}</td>
                                    <td class="whitespace-nowrap px-3 py-2.5 font-medium text-slate-500">{{ $entry->received_date?->format('M d, Y') ?? '—' }}</td>
                                    <td class="whitespace-nowrap px-3 py-2.5 text-right rounded-r-xl">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.stock-entries.edit', $entry) }}" class="inline-flex items-center rounded-lg bg-slate-50 px-3 py-1.5 text-xs font-bold text-slate-600 ring-1 ring-inset ring-slate-500/20 transition-all hover:bg-slate-800 hover:text-white">Edit</a>
                                            <form action="{{ route('admin.stock-entries.destroy', $entry) }}" method="POST" class="m-0 inline" onsubmit="return confirm('Are you sure you want to delete this record?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="inline-flex items-center rounded-lg bg-rose-50 px-3 py-1.5 text-xs font-bold text-rose-600 ring-1 ring-inset ring-rose-500/20 transition-all hover:bg-rose-500 hover:text-white">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-16 text-center rounded-xl">
                                        <div class="inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-white ring-1 ring-slate-200/80 shadow-xl shadow-slate-200/40 mb-4">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-8 w-8 text-emerald-400"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" /></svg>
                                        </div>
                                        <h3 class="text-sm font-black text-slate-800">No stock entries found</h3>
                                        <p class="mt-1 text-xs font-medium text-slate-500">There are no incoming stock records to display yet.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════ --}}
        {{-- Usage Logs Tab --}}
        {{-- ══════════════════════════════════════════════ --}}
        <div x-show="activeTab === 'usage-logs'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
            <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-[0_8px_30px_-12px_rgba(0,0,0,0.06)]">
                <div class="border-b border-slate-100 bg-slate-50/50 px-8 py-5 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-slate-800">Usage Logs</h3>
                        <p class="mt-0.5 text-xs font-medium text-slate-500">All item usage records. Fix quantities, patient info, procedure types.</p>
                    </div>
                </div>
                <div class="overflow-x-auto p-2">
                    <table class="min-w-full text-sm border-separate border-spacing-y-1">
                        <thead>
                            <tr>
                                <th class="whitespace-nowrap px-3 py-2 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">Item</th>
                                <th class="whitespace-nowrap px-3 py-2 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">Qty Used</th>
                                <th class="whitespace-nowrap px-3 py-2 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">Patient</th>
                                <th class="whitespace-nowrap px-3 py-2 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">Procedure</th>
                                <th class="whitespace-nowrap px-3 py-2 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">Used By</th>
                                <th class="whitespace-nowrap px-3 py-2 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">Date</th>
                                <th class="whitespace-nowrap px-3 py-2 text-right text-[10px] font-bold uppercase tracking-widest text-slate-400">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($usageLogs as $log)
                                <tr class="group transition-all duration-200 hover:bg-white rounded-xl shadow-sm hover:shadow-md hover:ring-1 hover:ring-slate-200/50">
                                    <td class="whitespace-nowrap px-3 py-2.5 rounded-l-xl font-bold text-slate-800">{{ $log->item->name ?? '—' }}</td>
                                    <td class="whitespace-nowrap px-3 py-2.5">
                                        <span class="inline-flex items-center rounded-lg bg-slate-100 px-2 py-1 text-xs font-black text-slate-600">{{ $log->quantity_used }}</span>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-2.5 font-medium text-slate-600">{{ $log->patient_id ?? '—' }}</td>
                                    <td class="whitespace-nowrap px-3 py-2.5 font-medium text-slate-600">{{ $log->procedure_type ?? '—' }}</td>
                                    <td class="whitespace-nowrap px-3 py-2.5 font-medium text-slate-600">{{ $log->used_by ?? '—' }}</td>
                                    <td class="whitespace-nowrap px-3 py-2.5 font-medium text-slate-500">{{ $log->used_at?->format('M d, Y') ?? '—' }}</td>
                                    <td class="whitespace-nowrap px-3 py-2.5 text-right rounded-r-xl">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.usage-logs.edit', $log) }}" class="inline-flex items-center rounded-lg bg-slate-50 px-3 py-1.5 text-xs font-bold text-slate-600 ring-1 ring-inset ring-slate-500/20 transition-all hover:bg-slate-800 hover:text-white">Edit</a>
                                            <form action="{{ route('admin.usage-logs.destroy', $log) }}" method="POST" class="m-0 inline" onsubmit="return confirm('Are you sure you want to delete this record?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="inline-flex items-center rounded-lg bg-rose-50 px-3 py-1.5 text-xs font-bold text-rose-600 ring-1 ring-inset ring-rose-500/20 transition-all hover:bg-rose-500 hover:text-white">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-16 text-center rounded-xl">
                                        <div class="inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-white ring-1 ring-slate-200/80 shadow-xl shadow-slate-200/40 mb-4">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-8 w-8 text-emerald-400"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                                        </div>
                                        <h3 class="text-sm font-black text-slate-800">No usage logs found</h3>
                                        <p class="mt-1 text-xs font-medium text-slate-500">There are no records of items being used yet.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════ --}}
        {{-- Borrows Tab --}}
        {{-- ══════════════════════════════════════════════ --}}
        <div x-show="activeTab === 'borrows'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
            <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-[0_8px_30px_-12px_rgba(0,0,0,0.06)]">
                <div class="border-b border-slate-100 bg-slate-50/50 px-8 py-5 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-slate-800">Borrow Records</h3>
                        <p class="mt-0.5 text-xs font-medium text-slate-500">All borrow transactions. Adjust quantities, status, and dates.</p>
                    </div>
                </div>
                <div class="overflow-x-auto p-2">
                    <table class="min-w-full text-sm border-separate border-spacing-y-1">
                        <thead>
                            <tr>
                                <th class="whitespace-nowrap px-3 py-2 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">Type</th>
                                <th class="whitespace-nowrap px-3 py-2 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">Item</th>
                                <th class="whitespace-nowrap px-3 py-2 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">Staff</th>
                                <th class="whitespace-nowrap px-3 py-2 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">Borrowed</th>
                                <th class="whitespace-nowrap px-3 py-2 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">Returned</th>
                                <th class="whitespace-nowrap px-3 py-2 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">Used</th>
                                <th class="whitespace-nowrap px-3 py-2 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">Status</th>
                                <th class="whitespace-nowrap px-3 py-2 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">Date</th>
                                <th class="whitespace-nowrap px-3 py-2 text-right text-[10px] font-bold uppercase tracking-widest text-slate-400">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($borrows as $borrow)
                                <tr class="group transition-all duration-200 hover:bg-white rounded-xl shadow-sm hover:shadow-md hover:ring-1 hover:ring-slate-200/50">
                                    <td class="whitespace-nowrap px-3 py-2.5">
                                        @if($borrow->type === 'in')
                                            <span class="inline-flex items-center gap-1 rounded-lg bg-emerald-50 px-2 py-1 text-[10px] font-extrabold text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                                                ↓ IN
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 rounded-lg bg-amber-50 px-2 py-1 text-[10px] font-extrabold text-amber-700 ring-1 ring-inset ring-amber-600/20">
                                                ↑ OUT
                                            </span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-2.5 rounded-l-xl font-bold text-slate-800">{{ $borrow->item->name ?? '—' }}</td>
                                    <td class="whitespace-nowrap px-3 py-2.5">
                                        <div class="font-bold text-slate-800">{{ $borrow->borrower_name ?? $borrow->staff?->display_name ?? 'Unknown Staff' }}</div>
                                        <div class="text-[10px] font-medium text-slate-500 mt-0.5">
                                            @if($borrow->staff?->specialization) {{ $borrow->staff->specialization }} &bull; @endif
                                            @if($borrow->type === 'in' && $borrow->source_department)
                                                <span class="text-emerald-600 font-bold">From: {{ $borrow->source_department }}</span> &rarr; {{ $borrow->department }}
                                            @elseif($borrow->department) 
                                                {{ $borrow->department }} 
                                            @endif
                                        </div>
                                        @if($borrow->bio_id)
                                            <div class="text-[10px] font-medium text-slate-500 mt-0.5">Bio ID: <span class="font-mono">{{ $borrow->bio_id }}</span></div>
                                        @endif
                                        @if($borrow->approved_by)
                                            <div class="text-[9px] font-bold text-emerald-600 uppercase mt-1 tracking-wider">Processed By: {{ $borrow->approved_by }}</div>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-2.5 font-black text-slate-600">{{ $borrow->quantity_borrowed }}</td>
                                    <td class="whitespace-nowrap px-3 py-2.5 font-black text-slate-600">{{ $borrow->quantity_returned }}</td>
                                    <td class="whitespace-nowrap px-3 py-2.5 font-black text-slate-600">{{ $borrow->quantity_used }}</td>
                                    <td class="whitespace-nowrap px-3 py-2.5">
                                        @if($borrow->status === 'active')
                                            <span class="inline-flex items-center gap-2 rounded-lg bg-amber-50/80 px-2.5 py-1.5 text-[11px] font-bold text-amber-700 ring-1 ring-inset ring-amber-500/20">
                                                <div class="h-1.5 w-1.5 rounded-full bg-amber-500 shadow-[0_0_8px_rgba(245,158,11,0.6)]"></div>Active
                                            </span>
                                        @elseif($borrow->status === 'partial')
                                            <span class="inline-flex items-center gap-2 rounded-lg bg-blue-50/80 px-2.5 py-1.5 text-[11px] font-bold text-blue-700 ring-1 ring-inset ring-blue-500/20">
                                                <div class="h-1.5 w-1.5 rounded-full bg-blue-500 shadow-[0_0_8px_rgba(59,130,246,0.6)]"></div>Partial
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-2 rounded-lg bg-emerald-50/80 px-2.5 py-1.5 text-[11px] font-bold text-emerald-700 ring-1 ring-inset ring-emerald-500/20">
                                                <div class="h-1.5 w-1.5 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.6)]"></div>Returned
                                            </span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-2.5 font-medium text-slate-500">{{ $borrow->borrowed_at?->format('M d, Y') ?? '—' }}</td>
                                    <td class="whitespace-nowrap px-3 py-2.5 text-right rounded-r-xl">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.borrows.edit', $borrow) }}" class="inline-flex items-center rounded-lg bg-slate-50 px-3 py-1.5 text-xs font-bold text-slate-600 ring-1 ring-inset ring-slate-500/20 transition-all hover:bg-slate-800 hover:text-white">Edit</a>
                                            <form action="{{ route('admin.borrows.destroy', $borrow) }}" method="POST" class="m-0 inline" onsubmit="return confirm('Are you sure you want to delete this record?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="inline-flex items-center rounded-lg bg-rose-50 px-3 py-1.5 text-xs font-bold text-rose-600 ring-1 ring-inset ring-rose-500/20 transition-all hover:bg-rose-500 hover:text-white">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-16 text-center rounded-xl">
                                        <div class="inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-white ring-1 ring-slate-200/80 shadow-xl shadow-slate-200/40 mb-4">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-8 w-8 text-emerald-400"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" /></svg>
                                        </div>
                                        <h3 class="text-sm font-black text-slate-800">No borrow records found</h3>
                                        <p class="mt-1 text-xs font-medium text-slate-500">There are no records of borrowed items to display.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        {{-- ══════════════════════════════════════════════ --}}
        {{-- Returns Tab --}}
        {{-- ══════════════════════════════════════════════ --}}
        <div x-show="activeTab === 'returns'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
            <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-[0_8px_30px_-12px_rgba(0,0,0,0.06)]">
                <div class="border-b border-slate-100 bg-slate-50/50 px-8 py-5 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-slate-800">Return Records</h3>
                        <p class="mt-0.5 text-xs font-medium text-slate-500">History of returned items. Edit quantities or dates.</p>
                    </div>
                </div>
                <div class="overflow-x-auto p-2">
                    <table class="min-w-full text-sm border-separate border-spacing-y-1">
                        <thead>
                            <tr>
                                <th class="whitespace-nowrap px-3 py-2 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">Returned On</th>
                                <th class="whitespace-nowrap px-3 py-2 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">Staff</th>
                                <th class="whitespace-nowrap px-3 py-2 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">Item</th>
                                <th class="whitespace-nowrap px-3 py-2 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">Borrowed</th>
                                <th class="whitespace-nowrap px-3 py-2 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">Returned/Used</th>
                                <th class="whitespace-nowrap px-3 py-2 text-right text-[10px] font-bold uppercase tracking-widest text-slate-400">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($returns as $returnRecord)
                                <tr class="group transition-all duration-200 hover:bg-white rounded-xl shadow-sm hover:shadow-md hover:ring-1 hover:ring-slate-200/50">
                                    <td class="whitespace-nowrap px-3 py-2.5 rounded-l-xl font-medium text-slate-500">
                                        {{ $returnRecord->returned_at?->format('M d, Y h:i A') ?? '—' }}
                                        @if($returnRecord->return_date)
                                            <div class="text-[10px] text-slate-400 mt-0.5">Due: {{ $returnRecord->return_date->format('M d, Y') }}</div>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-2.5">
                                        <div class="font-bold text-slate-800">{{ $returnRecord->borrower_name ?? $returnRecord->staff?->display_name ?? 'Unknown Staff' }}</div>
                                        <div class="text-[10px] font-medium text-slate-500 mt-0.5">
                                            @if($returnRecord->staff?->specialization) {{ $returnRecord->staff->specialization }} @endif
                                            @if($returnRecord->department) {{ $returnRecord->department }} @endif
                                        </div>
                                        @if($returnRecord->bio_id)
                                            <div class="text-[10px] font-medium text-slate-500 mt-0.5">Bio ID: <span class="font-mono">{{ $returnRecord->bio_id }}</span></div>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-2.5 font-bold text-slate-800">{{ $returnRecord->item->name ?? '—' }}</td>
                                    <td class="whitespace-nowrap px-3 py-2.5 font-black text-slate-600">{{ $returnRecord->quantity_borrowed }}</td>
                                    <td class="whitespace-nowrap px-3 py-2.5 text-xs font-semibold">
                                        <span class="text-emerald-600">{{ $returnRecord->quantity_returned }} Ret</span> / 
                                        <span class="text-rose-600">{{ $returnRecord->quantity_used }} Used</span>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-2.5 text-right rounded-r-xl">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.borrows.edit', $returnRecord) }}" class="inline-flex items-center rounded-lg bg-slate-50 px-3 py-1.5 text-xs font-bold text-slate-600 ring-1 ring-inset ring-slate-500/20 transition-all hover:bg-slate-800 hover:text-white">Edit</a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-16 text-center rounded-xl">
                                        <div class="inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-white ring-1 ring-slate-200/80 shadow-xl shadow-slate-200/40 mb-4">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-8 w-8 text-emerald-400"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" /></svg>
                                        </div>
                                        <h3 class="text-sm font-black text-slate-800">No return records found</h3>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        {{-- ══════════════════════════════════════════════ --}}
        {{-- Transfers Tab --}}
        {{-- ══════════════════════════════════════════════ --}}
        <div x-show="activeTab === 'transfers'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
            <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-[0_8px_30px_-12px_rgba(0,0,0,0.06)]">
                <div class="border-b border-slate-100 bg-slate-50/50 px-8 py-5 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-slate-800">Transfer Records</h3>
                        <p class="mt-0.5 text-xs font-medium text-slate-500">All item transfers. Fix destinations, quantities, and dates.</p>
                    </div>
                </div>
                <div class="overflow-x-auto p-2">
                    <table class="min-w-full text-sm border-separate border-spacing-y-1">
                        <thead>
                            <tr>
                                <th class="whitespace-nowrap px-3 py-2 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">Date</th>
                                <th class="whitespace-nowrap px-3 py-2 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">Type</th>
                                <th class="whitespace-nowrap px-3 py-2 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">Item</th>
                                <th class="whitespace-nowrap px-3 py-2 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">Qty</th>
                                <th class="whitespace-nowrap px-3 py-2 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">Destination/Source</th>
                                <th class="whitespace-nowrap px-3 py-2 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">Party</th>
                                <th class="whitespace-nowrap px-3 py-2 text-right text-[10px] font-bold uppercase tracking-widest text-slate-400">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transfers as $transfer)
                                <tr class="group transition-all duration-200 hover:bg-white rounded-xl shadow-sm hover:shadow-md hover:ring-1 hover:ring-slate-200/50">
                                    <td class="whitespace-nowrap px-3 py-2.5 font-medium text-slate-500 rounded-l-xl">{{ $transfer->transferred_at?->format('M d, Y h:i A') ?? '—' }}</td>
                                    <td class="whitespace-nowrap px-3 py-2.5">
                                        @if($transfer->type === 'in')
                                            <span class="inline-flex items-center gap-1 rounded-lg bg-emerald-50 px-2 py-1 text-[10px] font-extrabold text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                                                ↓ IN
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 rounded-lg bg-amber-50 px-2 py-1 text-[10px] font-extrabold text-amber-700 ring-1 ring-inset ring-amber-600/20">
                                                ↑ OUT
                                            </span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-2.5 font-bold text-slate-800">{{ $transfer->item->name ?? '—' }}</td>
                                    <td class="whitespace-nowrap px-3 py-2.5">
                                        <div class="flex flex-col gap-1">
                                            @if(($transfer->new_quantity ?? 0) > 0)
                                                <span class="inline-flex items-center rounded-md bg-teal-50 px-2 py-0.5 text-[10px] font-black text-teal-700 ring-1 ring-inset ring-teal-600/20">
                                                    {{ $transfer->new_quantity }} New
                                                </span>
                                            @endif
                                            @if(($transfer->used_quantity ?? 0) > 0)
                                                <span class="inline-flex items-center rounded-md bg-amber-50 px-2 py-0.5 text-[10px] font-black text-amber-700 ring-1 ring-inset ring-amber-600/20">
                                                    {{ $transfer->used_quantity }} Used
                                                </span>
                                            @endif
                                            @if(($transfer->new_quantity ?? 0) == 0 && ($transfer->used_quantity ?? 0) == 0)
                                                <span class="inline-flex items-center rounded-lg bg-slate-100 px-2 py-1 text-xs font-black text-slate-600">
                                                    {{ $transfer->quantity }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-2.5 font-medium text-slate-600">{{ $transfer->destination ?? '—' }}</td>
                                    <td class="whitespace-nowrap px-3 py-2.5">
                                        <div class="font-bold text-slate-800">{{ $transfer->transferred_to ?? $transfer->transferred_by ?? 'Unknown' }}</div>
                                        @if($transfer->department || $transfer->bio_id)
                                            <div class="text-[10px] font-medium text-slate-500 mt-0.5">
                                                @if($transfer->department) {{ $transfer->department }} @endif
                                                @if($transfer->department && $transfer->bio_id) &bull; @endif
                                                @if($transfer->bio_id) Bio ID: <span class="font-mono">{{ $transfer->bio_id }}</span> @endif
                                            </div>
                                        @endif
                                        @if($transfer->approved_by)
                                            <div class="text-[9px] font-bold text-emerald-600 uppercase tracking-widest mt-1">Processed By: {{ $transfer->approved_by }}</div>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-2.5 text-right rounded-r-xl">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.transfers.edit', $transfer) }}" class="inline-flex items-center rounded-lg bg-slate-50 px-3 py-1.5 text-xs font-bold text-slate-600 ring-1 ring-inset ring-slate-500/20 transition-all hover:bg-slate-800 hover:text-white">Edit</a>
                                            <form action="{{ route('admin.transfers.destroy', $transfer) }}" method="POST" class="m-0 inline" onsubmit="return confirm('Are you sure you want to delete this record?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="inline-flex items-center rounded-lg bg-rose-50 px-3 py-1.5 text-xs font-bold text-rose-600 ring-1 ring-inset ring-rose-500/20 transition-all hover:bg-rose-500 hover:text-white">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-16 text-center rounded-xl">
                                        <div class="inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-white ring-1 ring-slate-200/80 shadow-xl shadow-slate-200/40 mb-4">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-8 w-8 text-emerald-400"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" /></svg>
                                        </div>
                                        <h3 class="text-sm font-black text-slate-800">No transfer records found</h3>
                                        <p class="mt-1 text-xs font-medium text-slate-500">There are no records of transferred items to display.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════ --}}
        {{-- Disposals Tab --}}
        {{-- ══════════════════════════════════════════════ --}}
        <div x-show="activeTab === 'disposals'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
            <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-[0_8px_30px_-12px_rgba(0,0,0,0.06)]">
                <div class="border-b border-slate-100 bg-slate-50/50 px-8 py-5 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-slate-800">Disposal Records</h3>
                        <p class="mt-0.5 text-xs font-medium text-slate-500">All disposed item records. Fix reasons, quantities, and dates.</p>
                    </div>
                </div>
                <div class="overflow-x-auto p-2">
                    <table class="min-w-full text-sm border-separate border-spacing-y-1">
                        <thead>
                            <tr>
                                <th class="whitespace-nowrap px-3 py-2 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">Item</th>
                                <th class="whitespace-nowrap px-3 py-2 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">Qty</th>
                                <th class="whitespace-nowrap px-3 py-2 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">Reason</th>
                                <th class="whitespace-nowrap px-3 py-2 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">Disposed By</th>
                                <th class="whitespace-nowrap px-3 py-2 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">Date</th>
                                <th class="whitespace-nowrap px-3 py-2 text-right text-[10px] font-bold uppercase tracking-widest text-slate-400">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($disposals as $disposal)
                                <tr class="group transition-all duration-200 hover:bg-white rounded-xl shadow-sm hover:shadow-md hover:ring-1 hover:ring-slate-200/50">
                                    <td class="whitespace-nowrap px-3 py-2.5 rounded-l-xl font-bold text-slate-800">{{ $disposal->item->name ?? '—' }}</td>
                                    <td class="whitespace-nowrap px-3 py-2.5">
                                        <span class="inline-flex items-center rounded-lg bg-slate-100 px-2 py-1 text-xs font-black text-slate-600">{{ $disposal->quantity }}</span>
                                    </td>
                                    <td class="px-3 py-2.5 font-medium text-slate-600 max-w-xs truncate">{{ $disposal->reason ?? '—' }}</td>
                                    <td class="whitespace-nowrap px-3 py-2.5 font-medium text-slate-600">{{ $disposal->disposed_by ?? '—' }}</td>
                                    <td class="whitespace-nowrap px-3 py-2.5 font-medium text-slate-500">{{ $disposal->disposed_at?->format('M d, Y') ?? '—' }}</td>
                                    <td class="whitespace-nowrap px-3 py-2.5 text-right rounded-r-xl">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.disposals.edit', $disposal) }}" class="inline-flex items-center rounded-lg bg-slate-50 px-3 py-1.5 text-xs font-bold text-slate-600 ring-1 ring-inset ring-slate-500/20 transition-all hover:bg-slate-800 hover:text-white">Edit</a>
                                            <form action="{{ route('admin.disposals.destroy', $disposal) }}" method="POST" class="m-0 inline" onsubmit="return confirm('Are you sure you want to delete this record?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="inline-flex items-center rounded-lg bg-rose-50 px-3 py-1.5 text-xs font-bold text-rose-600 ring-1 ring-inset ring-rose-500/20 transition-all hover:bg-rose-500 hover:text-white">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-16 text-center rounded-xl">
                                        <div class="inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-white ring-1 ring-slate-200/80 shadow-xl shadow-slate-200/40 mb-4">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-8 w-8 text-emerald-400"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                        </div>
                                        <h3 class="text-sm font-black text-slate-800">No disposal records found</h3>
                                        <p class="mt-1 text-xs font-medium text-slate-500">There are no records of disposed items to display.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════ --}}
        {{-- Items Tab --}}
        {{-- ══════════════════════════════════════════════ --}}
        <div x-show="activeTab === 'items'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
            <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-[0_8px_30px_-12px_rgba(0,0,0,0.06)]">
                <div class="border-b border-slate-100 bg-slate-50/50 px-8 py-5 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-slate-800">Master Item List</h3>
                        <p class="mt-0.5 text-xs font-medium text-slate-500">Manage or delete items directly from the database.</p>
                    </div>
                </div>
                <div class="overflow-x-auto p-2">
                    <table class="min-w-full text-sm border-separate border-spacing-y-1">
                        <thead>
                            <tr>
                                <th class="whitespace-nowrap px-3 py-2 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">Name / SKU</th>
                                <th class="whitespace-nowrap px-3 py-2 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">Category</th>
                                <th class="whitespace-nowrap px-3 py-2 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">Stock Status</th>
                                <th class="whitespace-nowrap px-3 py-2 text-right text-[10px] font-bold uppercase tracking-widest text-slate-400">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($items as $item)
                                <tr class="group transition-all duration-200 hover:bg-white rounded-xl shadow-sm hover:shadow-md hover:ring-1 hover:ring-slate-200/50">
                                    <td class="whitespace-nowrap px-3 py-2.5 rounded-l-xl">
                                        <div class="font-bold text-slate-800">{{ $item->name }}</div>
                                        <div class="text-[10px] font-mono text-slate-500 mt-0.5">SKU: {{ $item->sku }}</div>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-2.5">
                                        <span class="inline-flex items-center rounded-lg bg-slate-100 px-2.5 py-1 text-[11px] font-bold text-slate-600 ring-1 ring-inset ring-slate-500/10">
                                            {{ $item->category->name ?? 'Uncategorized' }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-2.5">
                                        <div class="font-black text-slate-700">{{ $item->stock_quantity }} {{ $item->unit }}s</div>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-2.5 text-right rounded-r-xl">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('items.edit', $item) }}" class="inline-flex items-center rounded-lg bg-slate-50 px-3 py-2 text-xs font-bold text-slate-600 ring-1 ring-inset ring-slate-500/20 transition-all hover:bg-slate-800 hover:text-white hover:shadow-md hover:shadow-slate-800/20">Edit</a>

                                            @if($item->can_be_deleted)
                                                <form action="{{ route('items.destroy', $item) }}" method="POST" class="m-0 inline" onsubmit="return confirm('Are you sure you want to delete this item?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center rounded-lg bg-rose-50 px-3 py-2 text-xs font-bold text-rose-600 ring-1 ring-inset ring-rose-500/20 transition-all hover:bg-rose-500 hover:text-white hover:shadow-md hover:shadow-rose-500/20">Delete</button>
                                                </form>
                                            @else
                                                <button type="button" disabled title="Cannot delete items with transaction history (stock, borrows, logs)." class="inline-flex items-center rounded-lg bg-slate-100 px-3 py-2 text-xs font-bold text-slate-400 ring-1 ring-inset ring-slate-200 cursor-not-allowed">Delete</button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-16 text-center rounded-xl">
                                        <div class="inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-white ring-1 ring-slate-200/80 shadow-xl shadow-slate-200/40 mb-4">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-8 w-8 text-emerald-400"><path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" /></svg>
                                        </div>
                                        <h3 class="text-sm font-black text-slate-800">No items found</h3>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection