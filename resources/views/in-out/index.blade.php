@extends('layouts.app')

@section('title', 'In and Out')

@section('content')
<div x-data="{ activeTab: '{{ request('tab', 'transfer') }}' }">
    {{-- Tab Navigation --}}
    <div class="mb-6 flex flex-wrap gap-2 rounded-2xl border border-slate-200/80 bg-white p-2 shadow-sm">
        <button @click="activeTab = 'transfer'" :class="activeTab === 'transfer' ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/25' : 'text-slate-500 hover:ring-1 hover:ring-slate-300 hover:text-slate-800'"
                class="flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold transition-all duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
            </svg>
            Transfer
        </button>
        <button @click="activeTab = 'borrow'" :class="activeTab === 'borrow' ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/25' : 'text-slate-500 hover:ring-1 hover:ring-slate-300 hover:text-slate-800'"
                class="flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold transition-all duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
            </svg>
            Borrow
        </button>
        <button @click="activeTab = 'return'" :class="activeTab === 'return' ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/25' : 'text-slate-500 hover:ring-1 hover:ring-slate-300 hover:text-slate-800'"
                class="flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold transition-all duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
            </svg>
            Return
        </button>
    </div>

    {{-- ══════════════════════════════════════════════ --}}
    {{-- Transfer Tab --}}
    {{-- ══════════════════════════════════════════════ --}}
    <div x-show="activeTab === 'transfer'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
        <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-[0_4px_24px_-8px_rgba(0,0,0,0.06)]">
            <div class="flex items-center justify-between border-b border-slate-100 bg-slate-50/50 px-8 py-5">
                <div>
                    <h3 class="text-sm font-semibold text-slate-800">Transfer History</h3>
                    <p class="mt-0.5 text-xs text-slate-500">Record of items permanently moved to other departments.</p>
                </div>
                <a href="{{ route('transfers.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm transition-all hover:bg-slate-800 hover:shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                        <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                    </svg>
                    New Transfer
                </a>
            </div>

            @if($transfers->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm border-separate border-spacing-y-1">
                        <thead>
                            <tr>
                                <th class="px-3 py-2.5 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Date</th>
                                <th class="px-3 py-2.5 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Type</th>
                                <th class="px-3 py-2.5 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Item</th>
                                <th class="px-3 py-2.5 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Qty</th>
                                <th class="px-3 py-2.5 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Destination/Source</th>
                                <th class="px-3 py-2.5 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Party</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transfers as $transfer)
                                <tr class="group transition-all duration-200 hover:bg-white rounded-xl shadow-sm hover:shadow-md hover:ring-1 hover:ring-slate-200/50">
                                    <td class="whitespace-nowrap px-3 py-2.5 font-medium text-slate-600">
                                        {{ $transfer->transferred_at->format('M d, Y h:i A') }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-2.5">
                                        @if($transfer->type === 'in')
                                            <span class="inline-flex items-center gap-1 rounded-lg bg-emerald-50 px-2.5 py-1 text-xs font-extrabold text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                                                ↓ IN
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 rounded-lg bg-amber-50 px-2.5 py-1 text-xs font-extrabold text-amber-700 ring-1 ring-inset ring-amber-600/20">
                                                ↑ OUT
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2.5">
                                        <a href="{{ route('items.show', $transfer->item) }}" class="font-bold text-slate-900 hover:text-emerald-600 transition-colors">
                                            {{ $transfer->item->name }}
                                        </a>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-2.5">
                                        <div class="flex flex-col gap-1">
                                            @if(($transfer->new_quantity ?? 0) > 0)
                                            <span class="inline-flex items-center rounded-lg bg-teal-50 px-2.5 py-1 text-xs font-extrabold text-teal-700 ring-1 ring-inset ring-teal-600/20">
                                                {{ $transfer->new_quantity }} {{ $transfer->item->unit }} New
                                            </span>
                                            @endif
                                            @if(($transfer->used_quantity ?? 0) > 0)
                                            <span class="inline-flex items-center rounded-lg bg-amber-50 px-2.5 py-1 text-xs font-extrabold text-amber-700 ring-1 ring-inset ring-amber-600/20">
                                                {{ $transfer->used_quantity }} {{ $transfer->item->unit }} Used
                                            </span>
                                            @endif
                                            @if(($transfer->new_quantity ?? 0) == 0 && ($transfer->used_quantity ?? 0) == 0)
                                            <span class="inline-flex items-center rounded-lg bg-orange-50 px-2.5 py-1 text-xs font-extrabold text-orange-700 ring-1 ring-inset ring-orange-600/20">
                                                {{ $transfer->quantity }} {{ $transfer->item->unit }}
                                            </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-3 py-2.5">
                                        <div class="font-medium text-slate-600">{{ $transfer->destination }}</div>
                                    </td>
                                    <td class="px-3 py-2.5">
                                        <div class="font-medium text-slate-700">{{ $transfer->transferred_to ?? $transfer->transferred_by ?? 'Unknown' }}</div>
                                        @if($transfer->department || $transfer->bio_id)
                                            <div class="text-[10px] text-slate-400 font-normal uppercase tracking-widest mt-0.5">
                                                @if($transfer->department) {{ $transfer->department }} @endif
                                                @if($transfer->department && $transfer->bio_id) &bull; @endif
                                                @if($transfer->bio_id) Bio ID: {{ $transfer->bio_id }} @endif
                                            </div>
                                        @endif
                                        @if($transfer->approved_by)
                                            <div class="text-[10px] text-emerald-600 font-semibold uppercase tracking-widest mt-1">Processed By: {{ $transfer->approved_by }}</div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="flex flex-col items-center justify-center px-6 py-16 text-center">
                    <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-slate-50 ring-1 ring-inset ring-slate-200">
                        <svg class="h-8 w-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-slate-900">No transfers found</h3>
                    <p class="mt-1 text-sm text-slate-500">Get started by recording a new item transfer.</p>
                    <a href="{{ route('transfers.create') }}" class="mt-6 inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-slate-800">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                            <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                        </svg>
                        New Transfer
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- ══════════════════════════════════════════════ --}}
    {{-- Borrow Tab --}}
    {{-- ══════════════════════════════════════════════ --}}
    <div x-show="activeTab === 'borrow'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
        {{-- Active Borrows --}}
        <div class="mb-8 overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-[0_4px_24px_-8px_rgba(0,0,0,0.06)]">
            <div class="flex items-center justify-between border-b border-slate-100 bg-slate-50/50 px-8 py-5">
                <div>
                    <h3 class="text-sm font-semibold text-slate-800">Active & Partial Borrows</h3>
                    <p class="mt-0.5 text-xs text-slate-500">Items currently out with staff.</p>
                </div>
                <a href="{{ route('borrows.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm transition-all hover:bg-slate-800 hover:shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                        <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                    </svg>
                    New Borrow
                </a>
            </div>

            @if($activeBorrows->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm border-separate border-spacing-y-1">
                        <thead>
                            <tr>
                                <th class="px-3 py-2.5 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Date</th>
                                <th class="px-3 py-2.5 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Status</th>
                                <th class="px-3 py-2.5 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Item</th>
                                <th class="px-3 py-2.5 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Borrowed By</th>
                                <th class="px-3 py-2.5 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Return By</th>
                                <th class="px-3 py-2.5 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Qty Borrowed</th>
                                <th class="px-3 py-2.5 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Returned/Used</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($activeBorrows as $borrow)
                                @php 
                                    $isOverdue = $borrow->return_date && now()->startOfDay()->gt($borrow->return_date); 
                                    $isDueSoon = !$isOverdue && $borrow->return_date && now()->startOfDay()->diffInDays($borrow->return_date, false) <= 1;
                                @endphp
                                <tr class="group transition-all duration-200 hover:bg-white dark:hover:bg-white/5 rounded-xl shadow-sm hover:shadow-md hover:ring-1 hover:ring-slate-200/50 {{ $isOverdue ? 'bg-rose-500/5 dark:bg-rose-500/10' : ($isDueSoon ? 'bg-orange-500/5 dark:bg-orange-500/10' : '') }}">
                                    <td class="whitespace-nowrap px-3 py-2.5 font-medium text-slate-600">
                                        {{ $borrow->borrowed_at->format('M d, Y h:i A') }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-2.5">
                                        @if($isOverdue)
                                            <span class="inline-flex items-center gap-1 rounded-lg bg-rose-100 px-2.5 py-1 text-xs font-extrabold text-rose-700 dark:text-rose-400 ring-1 ring-inset ring-rose-600/20">
                                                <svg class="h-3 w-3 text-rose-600 dark:text-rose-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
                                                Overdue
                                            </span>
                                        @elseif($isDueSoon)
                                            <span class="inline-flex items-center gap-1 rounded-lg bg-orange-100 px-2.5 py-1 text-xs font-extrabold text-orange-700 dark:text-orange-400 ring-1 ring-inset ring-orange-600/20">
                                                <svg class="h-3 w-3 text-orange-600 dark:text-orange-400" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-13a.75.75 0 00-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 000-1.5h-3.25V5z" clip-rule="evenodd" />
                                                </svg>
                                                Due Soon
                                            </span>
                                        @elseif($borrow->status === 'active')
                                            <span class="inline-flex items-center rounded-lg bg-blue-50 px-2.5 py-1 text-xs font-extrabold text-blue-700 ring-1 ring-inset ring-blue-600/20">Active</span>
                                        @else
                                            <span class="inline-flex items-center rounded-lg bg-amber-50 px-2.5 py-1 text-xs font-extrabold text-amber-700 ring-1 ring-inset ring-amber-600/20">Partial</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2.5">
                                        <a href="{{ $borrow->item ? route('items.show', $borrow->item) : '#' }}" class="font-bold text-slate-900 hover:text-emerald-600 transition-colors">
                                            {{ $borrow->item?->name ?? 'Unknown Item' }}
                                        </a>
                                    </td>
                                    <td class="px-3 py-2.5 font-medium text-slate-700">
                                        {{ $borrow->borrower_name ?? $borrow->staff?->display_name ?? 'Unknown Staff' }}
                                        <div class="text-[10px] text-slate-400 font-normal uppercase tracking-widest mt-0.5">
                                            @if($borrow->staff?->specialization) {{ $borrow->staff->specialization }} @endif
                                            @if($borrow->staff?->specialization && $borrow->department) &bull; @endif
                                            @if($borrow->department) {{ $borrow->department }} @endif
                                        </div>
                                        @if($borrow->bio_id)
                                            <div class="text-[10px] text-slate-400 font-normal uppercase tracking-widest mt-0.5">Bio ID: {{ $borrow->bio_id }}</div>
                                        @endif
                                        @if($borrow->approved_by)
                                            <div class="text-[10px] text-indigo-600 font-semibold uppercase tracking-widest mt-1">Processed By: {{ $borrow->approved_by }}</div>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-2.5">
                                        @if($borrow->return_date)
                                            <div class="font-semibold {{ $isOverdue ? 'text-rose-600 dark:text-rose-400' : ($isDueSoon ? 'text-orange-600 dark:text-orange-400' : 'text-slate-700 dark:text-slate-300') }}">
                                                {{ $borrow->return_date->format('M d, Y') }}
                                            </div>
                                            @if($isOverdue)
                                                <div class="text-[10px] font-bold text-rose-500 dark:text-rose-400 uppercase tracking-widest mt-0.5">
                                                    {{ now()->startOfDay()->diffInDays($borrow->return_date) }} day(s) overdue
                                                </div>
                                            @elseif($isDueSoon)
                                                <div class="text-[10px] font-bold text-orange-600 dark:text-orange-400 uppercase tracking-widest mt-0.5">
                                                    {{ now()->startOfDay()->eq($borrow->return_date) ? 'Due Today' : 'Due Tomorrow' }}
                                                </div>
                                            @endif
                                        @else
                                            <span class="text-slate-400">—</span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-2.5">
                                        <span class="font-bold text-slate-800">{{ $borrow->quantity_borrowed }}</span> <span class="text-xs text-slate-500">{{ $borrow->item?->unit }}</span>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-2.5">
                                        <span class="font-medium text-emerald-600">{{ $borrow->quantity_returned }}</span> Ret /
                                        <span class="font-medium text-rose-600">{{ $borrow->quantity_used }}</span> Used
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="px-6 py-12 text-center">
                    <p class="text-sm text-slate-500">No active borrows.</p>
                </div>
            @endif
        </div>

        {{-- Borrow History --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-[0_4px_24px_-8px_rgba(0,0,0,0.06)]">
            <div class="border-b border-slate-100 bg-slate-50/50 px-8 py-5">
                <h3 class="text-sm font-semibold text-slate-800">Borrow History</h3>
                <p class="mt-0.5 text-xs text-slate-500">Past borrows that have been fully returned or consumed.</p>
            </div>

            @if($historyBorrows->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm border-separate border-spacing-y-1">
                        <thead>
                            <tr>
                                <th class="px-3 py-2.5 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Date Borrowed</th>
                                <th class="px-3 py-2.5 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Status</th>
                                <th class="px-3 py-2.5 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Item</th>
                                <th class="px-3 py-2.5 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Borrowed By</th>
                                <th class="px-3 py-2.5 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Qty Borrowed</th>
                                <th class="px-3 py-2.5 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Returned/Used</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($historyBorrows as $borrow)
                                <tr class="group transition-all duration-200 hover:bg-white rounded-xl shadow-sm hover:shadow-md hover:ring-1 hover:ring-slate-200/50">
                                    <td class="whitespace-nowrap px-3 py-2.5 font-medium text-slate-600">
                                        {{ $borrow->borrowed_at->format('M d, Y h:i A') }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-2.5">
                                        <span class="inline-flex items-center rounded-lg bg-emerald-50 px-2.5 py-1 text-xs font-extrabold text-emerald-700 ring-1 ring-inset ring-emerald-600/20">Returned</span>
                                        @if($borrow->return_date && $borrow->returned_at && \Carbon\Carbon::parse($borrow->returned_at)->startOfDay()->gt($borrow->return_date))
                                            <div class="mt-1">
                                                <span class="inline-flex items-center gap-1 rounded-lg bg-amber-100 px-2 py-0.5 text-[10px] font-extrabold text-amber-700 ring-1 ring-inset ring-amber-600/20">
                                                    <svg class="h-2.5 w-2.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-13a.75.75 0 00-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 000-1.5h-3.25V5z" clip-rule="evenodd"/></svg>
                                                    Late Return
                                                </span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2.5">
                                        <div class="font-bold text-slate-900">{{ $borrow->item?->name ?? 'Unknown Item' }}</div>
                                    </td>
                                    <td class="px-3 py-2.5 font-medium text-slate-700">
                                        {{ $borrow->borrower_name ?? $borrow->staff?->display_name ?? 'Unknown Staff' }}
                                        <div class="text-[10px] text-slate-400 font-normal uppercase tracking-widest mt-0.5">
                                            @if($borrow->staff?->specialization) {{ $borrow->staff->specialization }} @endif
                                            @if($borrow->staff?->specialization && $borrow->department) &bull; @endif
                                            @if($borrow->department) {{ $borrow->department }} @endif
                                        </div>
                                        @if($borrow->bio_id)
                                            <div class="text-[10px] text-slate-400 font-normal uppercase tracking-widest mt-0.5">Bio ID: {{ $borrow->bio_id }}</div>
                                        @endif
                                        @if($borrow->approved_by)
                                            <div class="text-[10px] text-indigo-600 font-semibold uppercase tracking-widest mt-1">Processed By: {{ $borrow->approved_by }}</div>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-2.5">
                                        <span class="font-bold text-slate-800">{{ $borrow->quantity_borrowed }}</span> <span class="text-xs text-slate-500">{{ $borrow->item?->unit }}</span>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-2.5">
                                        <span class="font-medium text-emerald-600">{{ $borrow->quantity_returned }}</span> Ret /
                                        <span class="font-medium text-rose-600">{{ $borrow->quantity_used }}</span> Used
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="px-6 py-12 text-center">
                    <p class="text-sm text-slate-500">No borrow history available yet.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- ══════════════════════════════════════════════ --}}
    {{-- Return Tab --}}
    {{-- ══════════════════════════════════════════════ --}}
    <div x-show="activeTab === 'return'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
        {{-- Pending Returns --}}
        <div class="mb-8 overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-[0_4px_24px_-8px_rgba(0,0,0,0.06)]">
            <div class="border-b border-slate-100 bg-slate-50/50 px-8 py-5">
                <h3 class="text-sm font-semibold text-slate-800">Pending Returns</h3>
                <p class="mt-0.5 text-xs text-slate-500">Items currently borrowed and waiting to be returned or marked as used.</p>
            </div>

            @if($pendingReturns->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm border-separate border-spacing-y-1">
                        <thead>
                            <tr>
                                <th class="px-3 py-2.5 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Date Borrowed</th>
                                <th class="px-3 py-2.5 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Return By</th>
                                <th class="px-3 py-2.5 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Status</th>
                                <th class="px-3 py-2.5 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Borrowed By</th>
                                <th class="px-3 py-2.5 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Item</th>
                                <th class="px-3 py-2.5 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Pending Return</th>
                                <th class="px-3 py-2.5 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingReturns as $borrow)
                                @php
                                    $pending = $borrow->quantity_borrowed - $borrow->quantity_returned - $borrow->quantity_used;
                                    $isOverdue = $borrow->return_date && now()->startOfDay()->gt($borrow->return_date);
                                    $isDueSoon = !$isOverdue && $borrow->return_date && now()->startOfDay()->diffInDays($borrow->return_date, false) <= 1;
                                @endphp
                                <tr class="group transition-all duration-200 hover:bg-white dark:hover:bg-white/5 rounded-xl shadow-sm hover:shadow-md hover:ring-1 hover:ring-slate-200/50 {{ $isOverdue ? 'bg-rose-500/5 dark:bg-rose-500/10' : ($isDueSoon ? 'bg-orange-500/5 dark:bg-orange-500/10' : '') }}">
                                    <td class="whitespace-nowrap px-3 py-2.5 font-medium text-slate-600">
                                        {{ $borrow->borrowed_at->format('M d, Y') }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-2.5">
                                        @if($borrow->return_date)
                                            <div class="font-semibold {{ $isOverdue ? 'text-rose-600 dark:text-rose-400' : ($isDueSoon ? 'text-orange-600 dark:text-orange-400' : 'text-slate-700 dark:text-slate-300') }}">
                                                {{ $borrow->return_date->format('M d, Y') }}
                                            </div>
                                            @if($isOverdue)
                                                <div class="text-[10px] font-bold text-rose-500 dark:text-rose-400 uppercase tracking-widest mt-0.5">
                                                    {{ now()->startOfDay()->diffInDays($borrow->return_date) }} day(s) overdue
                                                </div>
                                            @elseif($isDueSoon)
                                                <div class="text-[10px] font-bold text-orange-600 dark:text-orange-400 uppercase tracking-widest mt-0.5">
                                                    {{ now()->startOfDay()->eq($borrow->return_date) ? 'Due Today' : 'Due Tomorrow' }}
                                                </div>
                                            @endif
                                        @else
                                            <span class="text-slate-400">—</span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-2.5">
                                        @if($isOverdue)
                                            <span class="inline-flex items-center gap-1 rounded-lg bg-rose-100 px-2.5 py-1 text-xs font-extrabold text-rose-700 dark:text-rose-400 ring-1 ring-inset ring-rose-600/20">
                                                <svg class="h-3 w-3 text-rose-600 dark:text-rose-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
                                                Overdue
                                            </span>
                                        @elseif($isDueSoon)
                                            <span class="inline-flex items-center gap-1 rounded-lg bg-orange-100 px-2.5 py-1 text-xs font-extrabold text-orange-700 dark:text-orange-400 ring-1 ring-inset ring-orange-600/20">
                                                <svg class="h-3 w-3 text-orange-600 dark:text-orange-400" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-13a.75.75 0 00-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 000-1.5h-3.25V5z" clip-rule="evenodd" />
                                                </svg>
                                                Due Soon
                                            </span>
                                        @elseif($borrow->status === 'active')
                                            <span class="inline-flex items-center rounded-lg bg-blue-50 px-2.5 py-1 text-xs font-extrabold text-blue-700 ring-1 ring-inset ring-blue-600/20">Active</span>
                                        @else
                                            <span class="inline-flex items-center rounded-lg bg-amber-50 px-2.5 py-1 text-xs font-extrabold text-amber-700 ring-1 ring-inset ring-amber-600/20">Partial</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2.5 font-medium text-slate-700">
                                        {{ $borrow->borrower_name ?? $borrow->staff?->display_name ?? 'Unknown Staff' }}
                                        <div class="text-[10px] text-slate-400 font-normal uppercase tracking-widest mt-0.5">
                                            @if($borrow->staff?->specialization) {{ $borrow->staff->specialization }} @endif
                                            @if($borrow->staff?->specialization && $borrow->department) &bull; @endif
                                            @if($borrow->department) {{ $borrow->department }} @endif
                                        </div>
                                        @if($borrow->bio_id)
                                            <div class="text-[10px] text-slate-400 font-normal uppercase tracking-widest mt-0.5">Bio ID: {{ $borrow->bio_id }}</div>
                                        @endif
                                        @if($borrow->approved_by)
                                            <div class="text-[10px] text-indigo-600 font-semibold uppercase tracking-widest mt-1">Processed By: {{ $borrow->approved_by }}</div>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2.5">
                                        <div class="font-bold text-slate-900">{{ $borrow->item->name }}</div>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-2.5">
                                        <span class="font-bold text-slate-900">{{ $pending }}</span> <span class="text-xs text-slate-500">{{ $borrow->item->unit }}</span>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-2.5">
                                        <a href="{{ route('returns.edit', $borrow) }}" class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-50 px-3 py-1.5 text-xs font-bold text-emerald-700 transition-colors hover:bg-emerald-100 hover:text-emerald-800 ring-1 ring-inset ring-emerald-600/20">
                                            Process Return
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-3.5 w-3.5">
                                                <path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd" />
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="flex flex-col items-center justify-center px-6 py-16 text-center">
                    <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-slate-50 ring-1 ring-inset ring-slate-200">
                        <svg class="h-8 w-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-slate-900">No active borrows</h3>
                    <p class="mt-1 text-sm text-slate-500">All borrowed items have been successfully returned or used.</p>
                </div>
            @endif
        </div>

        {{-- Return History --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-[0_4px_24px_-8px_rgba(0,0,0,0.06)]">
            <div class="border-b border-slate-100 bg-slate-50/50 px-8 py-5">
                <h3 class="text-sm font-semibold text-slate-800">Return History</h3>
                <p class="mt-0.5 text-xs text-slate-500">Log of fully returned or consumed borrowed items.</p>
            </div>

            @if($returnHistory->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm border-separate border-spacing-y-1">
                        <thead>
                            <tr>
                                <th class="px-3 py-2.5 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Returned On</th>
                                <th class="px-3 py-2.5 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Borrowed By</th>
                                <th class="px-3 py-2.5 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Item</th>
                                <th class="px-3 py-2.5 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Qty Borrowed</th>
                                <th class="px-3 py-2.5 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Disposition</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($returnHistory as $borrow)
                                @php
                                    $wasLate = $borrow->return_date && $borrow->returned_at && \Carbon\Carbon::parse($borrow->returned_at)->startOfDay()->gt($borrow->return_date);
                                @endphp
                                <tr class="group transition-all duration-200 hover:bg-white rounded-xl shadow-sm hover:shadow-md hover:ring-1 hover:ring-slate-200/50">
                                    <td class="whitespace-nowrap px-3 py-2.5 font-medium text-slate-600">
                                        {{ $borrow->returned_at ? $borrow->returned_at->format('M d, Y h:i A') : '—' }}
                                        @if($wasLate)
                                            <div class="mt-1">
                                                <span class="inline-flex items-center gap-1 rounded-lg bg-amber-100 px-2 py-0.5 text-[10px] font-extrabold text-amber-700 ring-1 ring-inset ring-amber-600/20">
                                                    <svg class="h-2.5 w-2.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-13a.75.75 0 00-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 000-1.5h-3.25V5z" clip-rule="evenodd"/></svg>
                                                    Late Return
                                                </span>
                                            </div>
                                        @endif
                                        @if($borrow->return_date)
                                            <div class="text-[10px] text-slate-400 mt-0.5">Due: {{ $borrow->return_date->format('M d, Y') }}</div>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2.5 font-medium text-slate-700">
                                        {{ $borrow->borrower_name ?? $borrow->staff?->display_name ?? 'Unknown Staff' }}
                                        <div class="text-[10px] text-slate-400 font-normal uppercase tracking-widest mt-0.5">
                                            @if($borrow->staff?->specialization) {{ $borrow->staff->specialization }} @endif
                                            @if($borrow->staff?->specialization && $borrow->department) &bull; @endif
                                            @if($borrow->department) {{ $borrow->department }} @endif
                                        </div>
                                        @if($borrow->bio_id)
                                            <div class="text-[10px] text-slate-400 font-normal uppercase tracking-widest mt-0.5">Bio ID: {{ $borrow->bio_id }}</div>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2.5">
                                        <div class="font-bold text-slate-900">{{ $borrow->item->name }}</div>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-2.5">
                                        <span class="font-bold text-slate-900">{{ $borrow->quantity_borrowed }}</span>
                                        <span class="text-xs text-slate-500">{{ $borrow->item->unit }}</span>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-2.5">
                                        <span class="font-medium text-emerald-600">{{ $borrow->quantity_returned }}</span> Ret /
                                        <span class="font-medium text-rose-600">{{ $borrow->quantity_used }}</span> Used
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="px-6 py-12 text-center">
                    <p class="text-sm text-slate-500">No return history available yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
