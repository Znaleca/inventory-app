@extends('layouts.app')

@section('title', 'Borrows')

@section('actions')
    <a href="{{ route('borrows.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm transition-all hover:bg-slate-800 hover:shadow-md">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5">
            <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
        </svg>
        New Borrow
    </a>
@endsection

@section('content')
    <div class="mb-8 overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-[0_4px_24px_-8px_rgba(0,0,0,0.06)]">
        <div class="border-b border-slate-100 bg-slate-50/50 px-8 py-5">
            <h3 class="text-sm font-semibold text-slate-800">Active & Partial Borrows</h3>
            <p class="mt-0.5 text-xs text-slate-500">Items currently out with staff.</p>
        </div>

        @if($activeBorrows->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm border-separate border-spacing-y-1">
                    <thead>
                        <tr>
                            <th class="px-3 py-2.5 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Date</th>
                            <th class="px-3 py-2.5 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Status</th>
                            <th class="px-3 py-2.5 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Item</th>
                            <th class="px-3 py-2.5 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Staff</th>
                            <th class="px-3 py-2.5 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Qty Borrowed</th>
                            <th class="px-3 py-2.5 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Returned/Used</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activeBorrows as $borrow)
                            <tr class="group transition-all duration-200 hover:bg-white rounded-xl shadow-sm hover:shadow-md hover:ring-1 hover:ring-slate-200/50">
                                <td class="whitespace-nowrap px-3 py-2.5 font-medium text-slate-600">
                                    {{ $borrow->borrowed_at->format('M d, Y h:i A') }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-2.5">
                                    @if($borrow->status === 'active')
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
                                    {{ $borrow->staff?->name ?? 'Unknown Staff' }}
                                    <div class="text-[10px] text-slate-400 font-normal uppercase tracking-widest mt-0.5">{{ $borrow->staff?->specialization }}</div>
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

    <!-- Borrow History -->
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
                            <th class="px-3 py-2.5 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Staff</th>
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
                                </td>
                                <td class="px-3 py-2.5">
                                    <div class="font-bold text-slate-900">{{ $borrow->item?->name ?? 'Unknown Item' }}</div>
                                </td>
                                <td class="px-3 py-2.5 font-medium text-slate-700">
                                    {{ $borrow->staff?->name ?? 'Unknown Staff' }}
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
@endsection

