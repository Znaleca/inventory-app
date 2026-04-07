@extends('layouts.app')

@section('title', 'Returns')

@section('content')
    <div class="mb-8 overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-[0_4px_24px_-8px_rgba(0,0,0,0.06)]">
        <div class="border-b border-slate-100 bg-slate-50/50 px-8 py-5">
            <h3 class="text-sm font-semibold text-slate-800">Pending Returns</h3>
            <p class="mt-0.5 text-xs text-slate-500">Items currently borrowed and waiting to be returned or marked as used.</p>
        </div>

        @if($activeBorrows->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm border-separate border-spacing-y-1">
                    <thead>
                        <tr>
                            <th class="px-3 py-2.5 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Date Borrowed</th>
                            <th class="px-3 py-2.5 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Status</th>
                            <th class="px-3 py-2.5 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Staff</th>
                            <th class="px-3 py-2.5 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Item</th>
                            <th class="px-3 py-2.5 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Pending Return</th>
                            <th class="px-3 py-2.5 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activeBorrows as $borrow)
                            @php
                                $pending = $borrow->quantity_borrowed - $borrow->quantity_returned - $borrow->quantity_used;
                            @endphp
                            <tr class="group transition-all duration-200 hover:bg-white rounded-xl shadow-sm hover:shadow-md hover:ring-1 hover:ring-slate-200/50">
                                <td class="whitespace-nowrap px-3 py-2.5 font-medium text-slate-600">
                                    {{ $borrow->borrowed_at->format('M d, Y') }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-2.5">
                                    @if($borrow->status === 'active')
                                        <span class="inline-flex items-center rounded-lg bg-blue-50 px-2.5 py-1 text-xs font-extrabold text-blue-700 ring-1 ring-inset ring-blue-600/20">Active</span>
                                    @else
                                        <span class="inline-flex items-center rounded-lg bg-amber-50 px-2.5 py-1 text-xs font-extrabold text-amber-700 ring-1 ring-inset ring-amber-600/20">Partial</span>
                                    @endif
                                </td>
                                <td class="px-3 py-2.5 font-medium text-slate-700">
                                    {{ $borrow->staff->name }}
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

    <!-- Return History -->
    <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-[0_4px_24px_-8px_rgba(0,0,0,0.06)]">
        <div class="border-b border-slate-100 bg-slate-50/50 px-8 py-5">
            <h3 class="text-sm font-semibold text-slate-800">Return History</h3>
            <p class="mt-0.5 text-xs text-slate-500">Log of fully returned or consumed borrowed items.</p>
        </div>

        @if($historyBorrows->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm border-separate border-spacing-y-1">
                    <thead>
                        <tr>
                            <th class="px-3 py-2.5 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Returned On</th>
                            <th class="px-3 py-2.5 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Staff</th>
                            <th class="px-3 py-2.5 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Item</th>
                            <th class="px-3 py-2.5 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Qty Borrowed</th>
                            <th class="px-3 py-2.5 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Disposition</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($historyBorrows as $borrow)
                            <tr class="group transition-all duration-200 hover:bg-white rounded-xl shadow-sm hover:shadow-md hover:ring-1 hover:ring-slate-200/50">
                                <td class="whitespace-nowrap px-3 py-2.5 font-medium text-slate-600">
                                    {{ $borrow->returned_at ? $borrow->returned_at->format('M d, Y h:i A') : '—' }}
                                </td>
                                <td class="px-3 py-2.5 font-medium text-slate-700">
                                    {{ $borrow->staff->name }}
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
@endsection

