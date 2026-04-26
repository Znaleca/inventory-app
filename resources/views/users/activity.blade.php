@extends('layouts.app')

@section('title', $user->name . ' — Activity Log')

@section('actions')
    <a href="{{ route('users.index') }}"
        class="inline-flex items-center gap-2 border border-sky-100 bg-white px-4 py-2 text-[11px] font-mono font-bold text-slate-600 uppercase tracking-widest hover:bg-sky-50 transition-colors">
        ← Back to Users
    </a>
    <a href="{{ route('users.edit', $user) }}"
        class="inline-flex items-center gap-2 border border-sky-100 bg-white px-4 py-2 text-[11px] font-mono font-bold text-slate-600 uppercase tracking-widest hover:bg-sky-50 transition-colors">
        Edit Account
    </a>
@endsection

@section('content')
<div class="mx-auto max-w-5xl">

    {{-- Page Header --}}
    <div class="bg-white rounded-2xl overflow-hidden border border-sky-100 mb-5">
        <div class="p-6 flex items-center justify-between">
        <div>
            <p class="font-mono text-[10px] font-bold uppercase tracking-widest text-sky-500 mb-1">System://Users//{{ $user->id }}//Activity</p>
            <h3 class="text-xl font-black text-[#0f172a] tracking-tight">{{ $user->name }}</h3>
        <div class="flex items-center gap-2 mt-1">
            @if($user->role === 'admin')
            <span class="inline-flex items-center gap-1.5 border border-indigo-200 bg-indigo-50 px-2 py-0.5 text-[9px] font-mono font-bold tracking-widest uppercase text-indigo-700">
                <span class="h-1.5 w-1.5 bg-indigo-500"></span>Administrator
            </span>
            @else
            <span class="inline-flex items-center gap-1.5 border border-sky-100 bg-sky-50 px-2 py-0.5 text-[9px] font-mono font-bold tracking-widest uppercase text-slate-600">
                <span class="h-1.5 w-1.5 bg-slate-400"></span>Standard_User
            </span>
            @endif
            @if($user->bio_id)
            <span class="font-mono text-[10px] text-slate-400 uppercase tracking-wider">ID: {{ $user->bio_id }}</span>
            @endif
            @if(!$user->is_active)
            <span class="inline-flex border border-rose-200 bg-rose-50 px-2 py-0.5 text-[9px] font-mono font-bold tracking-widest uppercase text-rose-600">Deactivated</span>
            @endif
        </div>
        </div>
    </div>

    {{-- Stats Row --}}
    @php
        $statCards = [
            ['label' => 'Total',    'key' => 'total',     'bar' => 'bg-sky-500'],
            ['label' => 'Usage',    'key' => 'usage',     'bar' => 'bg-orange-500'],
            ['label' => 'Borrows',  'key' => 'borrow',    'bar' => 'bg-blue-500'],
            ['label' => 'Returns',  'key' => 'return',    'bar' => 'bg-teal-500'],
            ['label' => 'Transfers','key' => 'transfer',  'bar' => 'bg-violet-500'],
            ['label' => 'Disposals','key' => 'disposal',  'bar' => 'bg-rose-500'],
        ];
    @endphp
    <div class="grid grid-cols-3 lg:grid-cols-6 gap-3 mb-5">
        @foreach($statCards as $card)
        <div class="bg-white rounded-2xl overflow-hidden border border-sky-100 relative">
            <div class="absolute top-0 left-0 right-0 h-[3px] {{ $card['bar'] }}"></div>
            <div class="p-4 pl-5">
                <p class="text-[10px] font-mono font-bold text-slate-400 uppercase tracking-widest mb-1">{{ $card['label'] }}</p>
                <p class="text-2xl font-black font-mono text-[#0f172a]">{{ $counts[$card['key']] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Activity Timeline --}}
    <div x-data="{ activeFilter: 'All' }" class="bg-white rounded-2xl overflow-hidden border border-sky-100 relative">
        <div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-sky-400 to-sky-600"></div>
        {{-- Header --}}
            <div class="px-6 py-5 border-b border-sky-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="h-2 w-2 bg-sky-500 inline-block"></span>
                        <p class="text-[10px] font-mono font-bold text-sky-500 uppercase tracking-widest">// Activity Log</p>
                    </div>
                    <h2 class="text-base font-bold text-[#0f172a] tracking-tight">All recorded actions for this account</h2>
                </div>
                <span class="inline-flex items-center border border-sky-100 bg-sky-50 px-3 py-1 text-[10px] font-mono font-bold text-slate-600 tracking-widest uppercase shrink-0">
                    {{ $counts['total'] }} records
                </span>
            </div>

            {{-- Filter Tabs --}}
            @php
                $filters = ['All', 'Usage', 'Borrow', 'Return', 'Transfer', 'Disposal'];
                $filterStyles = [
                    'All'      => 'bg-[#0f172a] text-white border-[#0f172a]',
                    'Usage'    => 'bg-orange-500 text-white border-orange-500',
                    'Borrow'   => 'bg-blue-500 text-white border-blue-500',
                    'Return'   => 'bg-teal-500 text-white border-teal-500',
                    'Transfer' => 'bg-violet-500 text-white border-violet-500',
                    'Disposal' => 'bg-rose-500 text-white border-rose-500',
                ];
                $filterInactive = 'border-sky-100 bg-white text-slate-500 hover:border-sky-200 hover:text-slate-700';
            @endphp
            <div class="flex items-center gap-1.5 px-6 py-3 border-b border-sky-100 overflow-x-auto">
                @foreach($filters as $filter)
                <button
                    @click="activeFilter = '{{ $filter }}'"
                    :class="activeFilter === '{{ $filter }}' ? '{{ $filterStyles[$filter] }}' : '{{ $filterInactive }}'"
                    class="shrink-0 px-3 py-1 border text-[10px] font-mono font-bold uppercase tracking-widest transition-colors">
                    {{ $filter }}
                    @if($filter !== 'All')
                    <span class="ml-1 opacity-70">{{ $counts[strtolower($filter)] ?? 0 }}</span>
                    @else
                    <span class="ml-1 opacity-70">{{ $counts['total'] }}</span>
                    @endif
                </button>
                @endforeach
            </div>

        {{-- Activity List --}}
            @if($activityLog->isNotEmpty())
            <div class="divide-y divide-sky-50 max-h-[640px] overflow-y-auto">
                @foreach($activityLog as $tx)
                @php
                    $type = $tx['type'];
                    $badgeMap = [
                        'Usage'    => 'border-orange-200 bg-orange-50 text-orange-700',
                        'Borrow'   => 'border-blue-200 bg-blue-50 text-blue-700',
                        'Return'   => 'border-teal-200 bg-teal-50 text-teal-700',
                        'Transfer' => 'border-violet-200 bg-violet-50 text-violet-700',
                        'Disposal' => 'border-rose-200 bg-rose-50 text-rose-700',
                    ];
                    $badge = $badgeMap[$type] ?? 'border-sky-100 bg-sky-50 text-slate-600';
                    $date = $tx['date'] instanceof \Carbon\Carbon ? $tx['date'] : \Carbon\Carbon::parse($tx['date']);
                @endphp
                <div
                    x-show="activeFilter === 'All' || activeFilter === '{{ $type }}'"
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="flex items-start gap-4 px-6 py-4 hover:bg-sky-50 transition-colors">

                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <p class="text-sm font-bold text-[#0f172a] truncate">{{ $tx['item'] }}</p>
                            <span class="inline-flex items-center border px-2 py-0.5 text-[9px] font-mono font-bold tracking-widest uppercase {{ $badge }}">{{ $type }}</span>
                        </div>
                        @if(!empty($tx['detail']))
                        <p class="text-xs font-mono text-slate-500 mt-0.5 truncate">{{ $tx['detail'] }}</p>
                        @endif
                        @if(!empty($tx['notes']))
                        <p class="text-xs text-slate-400 mt-0.5 truncate italic">{{ $tx['notes'] }}</p>
                        @endif
                    </div>

                    <div class="text-right shrink-0">
                        <p class="text-lg font-black font-mono text-[#0f172a]">{{ $tx['qty'] }}<span class="text-[10px] text-slate-400 uppercase tracking-widest ml-1 font-bold">qty</span></p>
                        <p class="text-[10px] uppercase font-bold tracking-wide text-slate-400 mt-0.5">{{ $date->diffForHumans() }}</p>
                        <p class="text-[10px] font-mono text-slate-300 mt-0.5">{{ $date->format('M d, Y') }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="flex flex-col items-center justify-center py-20 text-center">
                <div class="h-14 w-14 border border-sky-100 bg-sky-50 flex items-center justify-center text-slate-400 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-7 w-7">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="font-mono text-[10px] text-slate-400 uppercase tracking-widest mb-1">// No records found</p>
                <p class="text-sm font-semibold text-slate-500 mt-1">This account has no recorded activity yet.</p>
            </div>
            @endif
    </div>
</div>
@endsection
