@extends('layouts.app')

@section('title', 'In and Out Dashboard')

@section('content')
<div x-data="{ activeTab: '{{ request('tab', 'transfer') }}' }" class="mx-auto max-w-7xl">
    
    {{-- Main Header --}}
    <div class="mb-6 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <p class="text-[10px] font-mono font-semibold text-slate-500 uppercase tracking-[0.25em] mb-1">Inventory://Logistics</p>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">In / Out Dashboard</h1>
            <p class="text-xs text-slate-400 font-mono mt-1">Manage transfers, borrows, and returns.</p>
        </div>
        
        {{-- Custom Flat Tabs --}}
        <div class="flex flex-wrap gap-2">
            <button @click="activeTab = 'transfer'" 
                :class="activeTab === 'transfer' ? 'bg-blue-600 border border-blue-600 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-500 hover:bg-slate-50'"
                class="flex items-center gap-2 px-5 py-2.5 text-[11px] font-mono font-bold uppercase tracking-widest transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                </svg>
                Transfers
            </button>
            <button @click="activeTab = 'borrow'" 
                :class="activeTab === 'borrow' ? 'bg-sky-600 border border-sky-600 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-500 hover:bg-slate-50'"
                class="flex items-center gap-2 px-5 py-2.5 text-[11px] font-mono font-bold uppercase tracking-widest transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                </svg>
                Borrows
            </button>
            <button @click="activeTab = 'return'" 
                :class="activeTab === 'return' ? 'bg-teal-600 border border-teal-600 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-500 hover:bg-slate-50'"
                class="flex items-center gap-2 px-5 py-2.5 text-[11px] font-mono font-bold uppercase tracking-widest transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
                </svg>
                Returns
            </button>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════ --}}
    {{-- Transfer Tab --}}
    {{-- ══════════════════════════════════════════════ --}}
    <div x-show="activeTab === 'transfer'" x-cloak>
        <div class="bg-white border border-slate-200 relative">
            <div class="absolute top-0 left-0 w-1 h-full bg-blue-500"></div>
            
            {{-- Module Header --}}
            <div class="px-6 py-5 ml-1 border-b border-slate-100 flex flex-col md:flex-row items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="h-2 w-2 bg-blue-500 inline-block"></span>
                        <p class="text-[10px] font-mono font-bold text-blue-600 uppercase tracking-widest">// Logged Transfers</p>
                    </div>
                    <h2 class="text-xl font-bold text-slate-800 tracking-tight">Transfer History</h2>
                    <p class="text-xs text-slate-500 font-mono mt-1">Record of items moved physically.</p>
                </div>
                <a href="{{ route('transfers.create') }}" class="inline-flex items-center gap-2 bg-blue-600 px-5 py-2.5 text-[11px] font-mono font-bold text-white uppercase tracking-widest transition-colors hover:bg-blue-700 border border-blue-700 hover:border-blue-800">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4"><path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" /></svg>
                    New Transfer
                </a>
            </div>

            @if($transfers->count() > 0)
            <div class="overflow-x-auto ml-1">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-200">
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Date</th>
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Dir</th>
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Item Name</th>
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Serial Record</th>
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Volume</th>
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Location / Party</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($transfers as $transfer)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="whitespace-nowrap px-6 py-4 font-mono text-xs text-slate-600">
                                {{ $transfer->transferred_at->format('Y-m-d H:i') }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                @if($transfer->type === 'in')
                                <span class="bg-emerald-100 text-emerald-700 px-2 py-0.5 text-[10px] font-bold font-mono tracking-widest uppercase border border-emerald-200">↓ IN</span>
                                @else
                                <span class="bg-amber-100 text-amber-700 px-2 py-0.5 text-[10px] font-bold font-mono tracking-widest uppercase border border-amber-200">↑ OUT</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('items.show', $transfer->item) }}" class="font-bold text-slate-900 hover:text-blue-600 transition-colors">
                                    {{ $transfer->item->name }}
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                @if($transfer->serial_number)
                                <div class="font-mono text-xs text-violet-600 font-bold bg-violet-50 max-w-[200px] break-all border border-violet-100 px-2 py-1">{{ $transfer->serial_number }}</div>
                                @else
                                <span class="font-mono text-[10px] text-slate-300">// N/A</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 font-mono">
                                <div class="flex flex-col gap-1 text-xs">
                                    @if(($transfer->new_quantity ?? 0) > 0)
                                        <span class="text-teal-700">{{ $transfer->new_quantity }} NEW</span>
                                    @endif
                                    @if(($transfer->used_quantity ?? 0) > 0)
                                        <span class="text-amber-700">{{ $transfer->used_quantity }} USED</span>
                                    @endif
                                    @if(($transfer->new_quantity ?? 0) == 0 && ($transfer->used_quantity ?? 0) == 0)
                                        <span class="text-slate-700">{{ $transfer->quantity }} {{ strtoupper($transfer->item->unit) }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800 text-sm">{{ $transfer->destination }}</div>
                                <div class="font-mono text-[10px] text-slate-500 mt-1 uppercase tracking-wider">
                                    Entity: {{ $transfer->transferred_to ?? $transfer->transferred_by ?? 'Unknown' }}
                                    @if($transfer->department) <br>Dept: {{ $transfer->department }} @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="px-6 py-16 text-center ml-1 bg-slate-50">
                <p class="font-mono text-xs text-slate-400">// No transfer logs detected</p>
                <a href="{{ route('transfers.create') }}" class="font-mono text-xs font-bold text-blue-600 hover:text-blue-700 mt-2 inline-block">INITIATE TRANSFER →</a>
            </div>
            @endif
        </div>
    </div>

    {{-- ══════════════════════════════════════════════ --}}
    {{-- Borrow Tab --}}
    {{-- ══════════════════════════════════════════════ --}}
    <div x-show="activeTab === 'borrow'" x-cloak>
        
        {{-- Active Borrows --}}
        <div class="bg-white border border-slate-200 relative mb-8">
            <div class="absolute top-0 left-0 w-1 h-full bg-sky-500"></div>
            
            <div class="px-6 py-5 ml-1 border-b border-slate-100 flex flex-col md:flex-row items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="h-2 w-2 bg-sky-500 inline-block"></span>
                        <p class="text-[10px] font-mono font-bold text-sky-600 uppercase tracking-widest">// Active Borrows</p>
                    </div>
                    <h2 class="text-xl font-bold text-slate-800 tracking-tight">Active &amp; Partial Pending</h2>
                </div>
                <a href="{{ route('borrows.create') }}" class="inline-flex items-center gap-2 bg-slate-900 px-5 py-2.5 text-[11px] font-mono font-bold text-white uppercase tracking-widest transition-colors hover:bg-slate-800 border border-slate-900">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4"><path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" /></svg>
                    New Borrow
                </a>
            </div>

            @if($activeBorrows->count() > 0)
            <div class="overflow-x-auto ml-1">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-200">
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Date</th>
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Status</th>
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Item Name</th>
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Party</th>
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Return By</th>
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Qty Tracking</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($activeBorrows as $borrow)
                        @php 
                            $isOverdue = $borrow->return_date && now()->startOfDay()->gt($borrow->return_date); 
                            $isDueSoon = !$isOverdue && $borrow->return_date && now()->startOfDay()->diffInDays($borrow->return_date, false) <= 1;
                        @endphp
                        <tr class="hover:bg-slate-50 transition-colors {{ $isOverdue ? 'bg-rose-50/30' : '' }}">
                            <td class="whitespace-nowrap px-6 py-4 font-mono text-xs text-slate-600">
                                {{ $borrow->borrowed_at->format('Y-m-d H:i') }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                @if($isOverdue)
                                    <span class="bg-rose-100 text-rose-700 px-2 py-0.5 text-[10px] font-bold font-mono tracking-widest uppercase border border-rose-200 text-red-500"><span class="animate-pulse mr-1">●</span>OVERDUE</span>
                                @elseif($isDueSoon)
                                    <span class="bg-orange-100 text-orange-700 px-2 py-0.5 text-[10px] font-bold font-mono tracking-widest uppercase border border-orange-200">DUE SOON</span>
                                @elseif($borrow->status === 'active')
                                    <span class="bg-blue-100 text-blue-700 px-2 py-0.5 text-[10px] font-bold font-mono tracking-widest uppercase border border-blue-200">ACTIVE</span>
                                @else
                                    <span class="bg-amber-100 text-amber-700 px-2 py-0.5 text-[10px] font-bold font-mono tracking-widest uppercase border border-amber-200">PARTIAL</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ $borrow->item ? route('items.show', $borrow->item) : '#' }}" class="font-bold text-slate-900 hover:text-sky-600 transition-colors">
                                    {{ $borrow->item?->name ?? 'Unknown Item' }}
                                </a>
                                @if($borrow->serial_number)
                                    <div class="font-mono text-[10px] text-slate-400 mt-1 max-w-[150px] truncate" title="{{ $borrow->serial_number }}">SN Log: {{ $borrow->serial_number }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800 text-sm">{{ $borrow->borrower_name ?? $borrow->staff?->display_name ?? 'Unknown Staff' }}</div>
                                <div class="font-mono text-[10px] text-slate-500 mt-1 uppercase tracking-wider">
                                    Entity: {{ $borrow->department ?? '-' }}
                                    @if($borrow->bio_id) <br>ID: {{ $borrow->bio_id }} @endif
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                @if($borrow->return_date)
                                    <div class="font-mono text-xs {{ $isOverdue ? 'text-rose-600 font-bold' : ($isDueSoon ? 'text-orange-600 font-bold' : 'text-slate-600') }}">
                                        {{ $borrow->return_date->format('Y-m-d') }}
                                    </div>
                                @else
                                    <span class="font-mono text-[10px] text-slate-300">// N/A</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 font-mono text-xs">
                                @php
                                    $newOut  = $borrow->new_quantity  ?? 0;
                                    $usedOut = $borrow->used_quantity ?? 0;
                                @endphp
                                @if($newOut > 0)
                                <div class="flex items-center gap-1 text-teal-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-2.5 h-2.5 shrink-0"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-11.25a.75.75 0 00-1.5 0v2.5h-2.5a.75.75 0 000 1.5h2.5v2.5a.75.75 0 001.5 0v-2.5h2.5a.75.75 0 000-1.5h-2.5v-2.5z" clip-rule="evenodd" /></svg>
                                    <span class="font-bold">{{ $newOut }}</span> NEW {{ strtoupper($borrow->type) }}
                                </div>
                                @endif
                                @if($usedOut > 0)
                                <div class="flex items-center gap-1 text-amber-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-2.5 h-2.5 shrink-0"><path fill-rule="evenodd" d="M15.312 11.424a5.5 5.5 0 01-9.201 2.466l-.312-.311h2.433a.75.75 0 000-1.5H3.989a.75.75 0 00-.75.75v4.242a.75.75 0 001.5 0v-2.43l.31.31a7 7 0 0011.712-3.138.75.75 0 00-1.449-.39zm1.23-3.723a.75.75 0 00.219-.53V2.929a.75.75 0 00-1.5 0V5.36l-.31-.31A7 7 0 003.239 8.188a.75.75 0 101.448.389A5.5 5.5 0 0113.89 6.11l.311.31h-2.432a.75.75 0 000 1.5h4.243a.75.75 0 00.53-.219z" clip-rule="evenodd" /></svg>
                                    <span class="font-bold">{{ $usedOut }}</span> USED {{ strtoupper($borrow->type) }}
                                </div>
                                @endif
                                @if($newOut == 0 && $usedOut == 0)
                                <span class="font-bold text-slate-800">{{ $borrow->quantity_borrowed }} {{ strtoupper($borrow->type) }}</span>
                                @endif
                                <div class="mt-1 text-[10px] text-slate-400">
                                    <span class="text-teal-600">{{ $borrow->quantity_returned }} RET</span> /
                                    <span class="text-rose-600">{{ $borrow->quantity_used }} USD</span>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="px-6 py-16 text-center ml-1 bg-slate-50">
                <p class="font-mono text-xs text-slate-400">// No active borrows tracked</p>
            </div>
            @endif
        </div>

        {{-- Borrow History --}}
        <div class="bg-white border border-slate-200 relative mb-8">
            <div class="absolute top-0 left-0 w-1 h-full bg-slate-300"></div>
            <div class="px-6 py-5 ml-1 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-slate-800 tracking-tight font-mono uppercase tracking-widest">Borrow History Log</h3>
                </div>
            </div>

            @if($historyBorrows->count() > 0)
            <div class="overflow-x-auto ml-1 opacity-80 hover:opacity-100 transition-opacity grayscale hover:grayscale-0">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Date Borrowed</th>
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Status</th>
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Item Name</th>
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Party</th>
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Qty Log</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($historyBorrows as $borrow)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="whitespace-nowrap px-6 py-3 font-mono text-xs text-slate-600">
                                {{ $borrow->borrowed_at->format('Y-m-d') }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-3">
                                <span class="bg-slate-100 text-slate-600 px-2 py-0.5 text-[10px] font-bold font-mono tracking-widest uppercase border border-slate-200">CLOSED</span>
                            </td>
                            <td class="px-6 py-3 font-bold text-slate-700">
                                {{ $borrow->item?->name ?? 'Unknown Item' }}
                            </td>
                            <td class="px-6 py-3 font-mono text-[10px] text-slate-500">
                                {{ $borrow->borrower_name ?? 'Unknown' }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-3 font-mono text-xs text-slate-500">
                                {{ $borrow->quantity_borrowed }} {{ strtoupper($borrow->type) }} / {{ $borrow->quantity_returned }} RET
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="px-6 py-10 text-center ml-1 bg-slate-50">
                <p class="font-mono text-[11px] text-slate-400">// No history architecture found</p>
            </div>
            @endif
        </div>
    </div>

    {{-- ══════════════════════════════════════════════ --}}
    {{-- Return Tab --}}
    {{-- ══════════════════════════════════════════════ --}}
    <div x-show="activeTab === 'return'" x-cloak>
        {{-- Pending Returns --}}
        <div class="bg-white border border-slate-200 relative mb-8">
            <div class="absolute top-0 left-0 w-1 h-full bg-teal-500"></div>
            
            <div class="px-6 py-5 ml-1 border-b border-slate-100 flex flex-col md:flex-row items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="h-2 w-2 bg-teal-500 inline-block"></span>
                        <p class="text-[10px] font-mono font-bold text-teal-600 uppercase tracking-widest">// Return Actions</p>
                    </div>
                    <h2 class="text-xl font-bold text-slate-800 tracking-tight">Process Imminent Returns</h2>
                </div>
            </div>

            @if($pendingReturns->count() > 0)
            <div class="overflow-x-auto ml-1">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-200">
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Borrowed</th>
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Status</th>
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Item Name</th>
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Pending Qty</th>
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($pendingReturns as $borrow)
                        @php
                            $pending = $borrow->quantity_borrowed - $borrow->quantity_returned - $borrow->quantity_used;
                            $isOverdue = $borrow->return_date && now()->startOfDay()->gt($borrow->return_date);
                        @endphp
                        <tr class="hover:bg-slate-50 transition-colors {{ $isOverdue ? 'bg-rose-50/30' : '' }}">
                            <td class="whitespace-nowrap px-6 py-4 font-mono text-xs text-slate-600">
                                {{ $borrow->borrowed_at->format('Y-m-d') }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                @if($isOverdue)
                                    <span class="bg-rose-100 text-rose-700 px-2 py-0.5 text-[10px] font-bold font-mono tracking-widest uppercase border border-rose-200">OVERDUE</span>
                                @else
                                    <span class="bg-slate-100 text-slate-700 px-2 py-0.5 text-[10px] font-bold font-mono tracking-widest uppercase border border-slate-200">PENDING</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-900">
                                {{ $borrow->item->name }}
                                <div class="font-mono text-[10px] font-normal text-slate-500 uppercase tracking-widest">{{ $borrow->borrower_name }}</div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 font-mono text-xs">
                                @php
                                    $newOut   = $borrow->new_quantity ?? 0;
                                    $usedOut  = $borrow->used_quantity ?? 0;
                                    $pendingNew  = max(0, $newOut - $borrow->quantity_returned);
                                    $pendingUsed = $usedOut;
                                @endphp
                                @if($newOut > 0)
                                <div class="flex items-center gap-1.5 text-teal-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-2.5 h-2.5 shrink-0"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-11.25a.75.75 0 00-1.5 0v2.5h-2.5a.75.75 0 000 1.5h2.5v2.5a.75.75 0 001.5 0v-2.5h2.5a.75.75 0 000-1.5h-2.5v-2.5z" clip-rule="evenodd" /></svg>
                                    <span class="font-black text-rose-600 text-base">{{ $pendingNew }}</span>
                                    <span class="text-slate-500">NEW {{ strtoupper($borrow->item->unit) }}</span>
                                </div>
                                @endif
                                @if($usedOut > 0)
                                <div class="flex items-center gap-1.5 text-amber-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-2.5 h-2.5 shrink-0"><path fill-rule="evenodd" d="M15.312 11.424a5.5 5.5 0 01-9.201 2.466l-.312-.311h2.433a.75.75 0 000-1.5H3.989a.75.75 0 00-.75.75v4.242a.75.75 0 001.5 0v-2.43l.31.31a7 7 0 0011.712-3.138.75.75 0 00-1.449-.39zm1.23-3.723a.75.75 0 00.219-.53V2.929a.75.75 0 00-1.5 0V5.36l-.31-.31A7 7 0 003.239 8.188a.75.75 0 101.448.389A5.5 5.5 0 0113.89 6.11l.311.31h-2.432a.75.75 0 000 1.5h4.243a.75.75 0 00.53-.219z" clip-rule="evenodd" /></svg>
                                    <span class="font-black text-rose-600 text-base">{{ $pendingUsed }}</span>
                                    <span class="text-slate-500">USED {{ strtoupper($borrow->item->unit) }}</span>
                                </div>
                                @endif
                                @if($newOut == 0 && $usedOut == 0)
                                <span class="font-black text-rose-600 text-lg">{{ $pending }}</span>
                                <span class="text-slate-500">{{ strtoupper($borrow->item->unit) }}</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-right">
                                <a href="{{ route('returns.edit', $borrow) }}" class="inline-flex items-center gap-2 bg-teal-600 px-4 py-2 text-[10px] font-mono font-bold text-white uppercase tracking-widest transition-colors hover:bg-teal-700 border border-teal-700">
                                    Process
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-3 w-3"><path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd" /></svg>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="px-6 py-16 text-center ml-1 bg-slate-50">
                <p class="font-mono text-xs text-slate-400">// No imminent returns requiring execution</p>
            </div>
            @endif
        </div>
        
        <div class="bg-white border border-slate-200 relative mb-8">
            <div class="absolute top-0 left-0 w-1 h-full bg-slate-300"></div>
            <div class="px-6 py-5 ml-1 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-slate-800 tracking-tight font-mono uppercase tracking-widest">Return Architecture Log</h3>
                </div>
            </div>
            @if($returnHistory->count() > 0)
            <div class="overflow-x-auto ml-1 opacity-80 hover:opacity-100 transition-opacity grayscale hover:grayscale-0">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Returned At</th>
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Item Name</th>
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Party</th>
                            <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Ledger Qty</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($returnHistory as $borrow)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="whitespace-nowrap px-6 py-3 font-mono text-xs text-slate-600">
                                {{ $borrow->returned_at ? $borrow->returned_at->format('Y-m-d') : '—' }}
                            </td>
                            <td class="px-6 py-3 font-bold text-slate-700">
                                {{ $borrow->item->name }}
                            </td>
                            <td class="px-6 py-3 font-mono text-[10px] text-slate-500">
                                {{ $borrow->borrower_name ?? 'Unknown' }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-3 font-mono text-xs text-slate-500">
                                {{ $borrow->quantity_borrowed }} OUT / {{ $borrow->quantity_returned }} IN
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="px-6 py-10 text-center ml-1 bg-slate-50">
                <p class="font-mono text-[11px] text-slate-400">// No closed loop returns found</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
