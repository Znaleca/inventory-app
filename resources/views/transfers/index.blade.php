@extends('layouts.app')

@section('title', 'Transfers')

@section('actions')
    <a href="{{ route('transfers.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm transition-all hover:bg-slate-800 hover:shadow-md">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5">
            <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
        </svg>
        New Transfer
    </a>
@endsection

@section('content')
    <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-[0_4px_24px_-8px_rgba(0,0,0,0.06)]">
        <div class="border-b border-slate-100 bg-slate-50/50 px-8 py-5">
            <h3 class="text-sm font-semibold text-slate-800">Transfer History</h3>
            <p class="mt-0.5 text-xs text-slate-500">Record of items moved in or out to other departments.</p>
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
                            <th class="px-3 py-2.5 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Department</th>
                            <th class="px-3 py-2.5 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Processed By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transfers as $transfer)
                            <tr class="group transition-all duration-200 hover:bg-white rounded-xl shadow-sm hover:shadow-md hover:ring-1 hover:ring-slate-200/50">
                                <td class="whitespace-nowrap px-3 py-2.5 font-medium text-slate-600">
                                    {{ $transfer->transferred_at->format('M d, Y h:i A') }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-2.5">
                                    @if(($transfer->type ?? 'out') === 'in')
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
                                <td class="px-3 py-2.5 text-slate-600 font-medium">{{ $transfer->destination }}</td>
                                <td class="px-3 py-2.5 text-slate-500">{{ $transfer->approved_by ?? $transfer->transferred_by ?? '—' }}</td>
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
@endsection
