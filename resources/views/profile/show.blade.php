@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="mx-auto max-w-5xl space-y-6">

    {{-- Hero Profile Card --}}
    <div class="relative overflow-hidden rounded-[2rem] bg-white border border-slate-200/60 shadow-[0_8px_30px_-12px_rgba(0,0,0,0.08)] transition-all">

        {{-- Cover Photo / Header Gradient --}}
        <div class="h-48 w-full bg-gradient-to-r from-emerald-400 via-teal-500 to-emerald-500 relative border-b border-emerald-600/20">
            <div class="absolute inset-0 bg-black/5 mix-blend-overlay"></div>
            <svg class="absolute inset-0 h-full w-full opacity-20" xmlns="http://www.w3.org/2000/svg">
                <defs><pattern id="hero-pattern" width="40" height="40" patternUnits="userSpaceOnUse"><circle cx="2" cy="2" r="2" fill="currentColor"/></pattern></defs>
                <rect width="100%" height="100%" fill="url(#hero-pattern)" class="text-white"/>
            </svg>
        </div>

        <div class="px-8 sm:px-12 pb-10 relative">
            {{-- Avatar & Actions --}}
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-6 -mt-20 sm:-mt-16 mb-6">
                <div class="relative flex items-end pt-1">
                    <div class="relative rounded-full p-2 bg-white shadow-xl">
                        <div class="flex h-32 w-32 items-center justify-center rounded-full bg-gradient-to-tr from-emerald-500 to-teal-400 text-4xl font-black text-white bg-cover bg-center ring-1 ring-slate-900/5 shadow-inner"
                            @if(!empty($user->profile_photo_url))
                                style="background-image: url('{{ $user->profile_photo_url }}')"
                            @endif
                        >
                            @if(empty($user->profile_photo_url))
                                {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                            @endif
                        </div>
                        <div class="absolute bottom-4 right-4 h-6 w-6 rounded-full bg-emerald-500 border-4 border-white shadow-sm flex items-center justify-center"></div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('profile.edit') }}"
                        class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-bold text-white shadow-md hover:bg-slate-800 transition-all duration-300 ring-1 ring-transparent">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                        </svg>
                        Edit Profile
                    </a>
                </div>
            </div>

            {{-- User Info --}}
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $user->name ?? 'User' }}</h1>
                <p class="text-slate-500 font-medium mt-1">{{ $user->email ?? '' }}</p>

                <div class="flex flex-wrap items-center gap-3 mt-4">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[11px] font-bold uppercase tracking-widest text-emerald-700 bg-emerald-50 border border-emerald-200/60 rounded-full">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        {{ $user->role ?? 'Staff' }}
                    </span>
                    @if($user->bio_id)
                    <span class="px-3 py-1 text-[11px] font-bold text-slate-600 bg-slate-50 border border-slate-200 rounded-full tracking-widest uppercase">
                        ID: {{ $user->bio_id }}
                    </span>
                    @endif
                    <span class="px-3 py-1 text-[11px] font-bold text-slate-500 bg-slate-50 border border-slate-200 rounded-full tracking-widest uppercase">
                        Joined {{ $user->created_at ? $user->created_at->format('M Y') : '—' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Row --}}
    <div class="grid grid-cols-3 lg:grid-cols-6 gap-3">
        @php
            $statCards = [
                ['label' => 'Total',    'key' => 'total',    'color' => 'slate',   'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />'],
                ['label' => 'Usage',    'key' => 'usage',    'color' => 'orange',  'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z" />'],
                ['label' => 'Borrows',  'key' => 'borrow',   'color' => 'blue',    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />'],
                ['label' => 'Returns',  'key' => 'return',   'color' => 'teal',    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />'],
                ['label' => 'Transfers','key' => 'transfer', 'color' => 'violet',  'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />'],
                ['label' => 'Disposals','key' => 'disposal', 'color' => 'rose',    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />'],
            ];
            $colorMap = [
                'slate'  => ['bg' => 'bg-slate-50',  'text' => 'text-slate-600'],
                'orange' => ['bg' => 'bg-orange-50', 'text' => 'text-orange-600'],
                'blue'   => ['bg' => 'bg-blue-50',   'text' => 'text-blue-600'],
                'teal'   => ['bg' => 'bg-teal-50',   'text' => 'text-teal-600'],
                'violet' => ['bg' => 'bg-violet-50', 'text' => 'text-violet-600'],
                'rose'   => ['bg' => 'bg-rose-50',   'text' => 'text-rose-600'],
            ];
        @endphp
        @foreach($statCards as $card)
        @php $c = $colorMap[$card['color']]; @endphp
        <div class="rounded-2xl border border-slate-200/60 bg-white p-4 shadow-sm">
            <div class="flex items-center gap-2 mb-2">
                <div class="flex h-7 w-7 items-center justify-center rounded-lg {{ $c['bg'] }} {{ $c['text'] }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5">{!! $card['icon'] !!}</svg>
                </div>
            </div>
            <p class="text-2xl font-black text-slate-900">{{ $counts[$card['key']] }}</p>
            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mt-0.5">{{ $card['label'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- Full Activity Timeline with Filter Tabs --}}
    <div x-data="{ activeFilter: 'All' }"
         class="rounded-[2rem] border border-slate-200/60 bg-white shadow-sm overflow-hidden">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 px-8 py-6 border-b border-slate-100 bg-slate-50/50">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-200/50 text-slate-600 border border-slate-200/60 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-900">Full Activity Log</h3>
                    <p class="text-xs font-medium text-slate-500 mt-0.5">Every action performed by this account</p>
                </div>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/60 shrink-0">
                {{ $counts['total'] }} records
            </span>
        </div>

        {{-- Filter Tabs --}}
        <div class="flex items-center gap-1 px-8 py-4 border-b border-slate-100 overflow-x-auto">
            @php
                $filters = ['All', 'Usage', 'Borrow', 'Return', 'Transfer', 'Disposal'];
                $filterColors = [
                    'All'      => "bg-slate-900 text-white shadow-sm",
                    'Usage'    => "bg-orange-500 text-white shadow-sm",
                    'Borrow'   => "bg-blue-500 text-white shadow-sm",
                    'Return'   => "bg-teal-500 text-white shadow-sm",
                    'Transfer' => "bg-violet-500 text-white shadow-sm",
                    'Disposal' => "bg-rose-500 text-white shadow-sm",
                ];
                $filterInactive = "bg-white text-slate-500 border border-slate-200 hover:border-slate-300 hover:text-slate-700";
            @endphp
            @foreach($filters as $filter)
            <button
                @click="activeFilter = '{{ $filter }}'"
                :class="activeFilter === '{{ $filter }}' ? '{{ $filterColors[$filter] }}' : '{{ $filterInactive }}'"
                class="shrink-0 px-4 py-1.5 rounded-xl text-xs font-bold transition-all duration-200">
                {{ $filter }}
                @if($filter !== 'All')
                <span class="ml-1 opacity-70">{{ $counts[strtolower($filter)] ?? 0 }}</span>
                @else
                <span class="ml-1 opacity-70">{{ $counts['total'] }}</span>
                @endif
            </button>
            @endforeach
        </div>

        {{-- Timeline List --}}
        @if($activityLog->isNotEmpty())
        <div class="divide-y divide-slate-100 max-h-[640px] overflow-y-auto">
            @foreach($activityLog as $tx)
            @php
                $type = $tx['type'];
                $iconStyles = [
                    'Usage'    => ['wrap' => 'bg-orange-50 border-orange-100 text-orange-500 group-hover:bg-orange-100', 'svg' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z" />'],
                    'Borrow'   => ['wrap' => 'bg-blue-50 border-blue-100 text-blue-500 group-hover:bg-blue-100',     'svg' => '<path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />'],
                    'Return'   => ['wrap' => 'bg-teal-50 border-teal-100 text-teal-500 group-hover:bg-teal-100',     'svg' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />'],
                    'Transfer' => ['wrap' => 'bg-violet-50 border-violet-100 text-violet-500 group-hover:bg-violet-100', 'svg' => '<path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />'],
                    'Disposal' => ['wrap' => 'bg-rose-50 border-rose-100 text-rose-500 group-hover:bg-rose-100',     'svg' => '<path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />'],
                ];
                $typeBadge = [
                    'Usage'    => 'bg-orange-100 text-orange-700',
                    'Borrow'   => 'bg-blue-100 text-blue-700',
                    'Return'   => 'bg-teal-100 text-teal-700',
                    'Transfer' => 'bg-violet-100 text-violet-700',
                    'Disposal' => 'bg-rose-100 text-rose-700',
                ];
                $icon = $iconStyles[$type] ?? $iconStyles['Usage'];
                $badge = $typeBadge[$type] ?? 'bg-slate-100 text-slate-700';
                $date = $tx['date'] instanceof \Carbon\Carbon ? $tx['date'] : \Carbon\Carbon::parse($tx['date']);
            @endphp
            <div
                x-show="activeFilter === 'All' || activeFilter === '{{ $type }}'"
                x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0 translate-y-1"
                x-transition:enter-end="opacity-100 translate-y-0"
                class="flex items-start gap-4 px-8 py-4 hover:bg-slate-50/50 transition-colors group">

                {{-- Type Icon --}}
                <div class="flex-shrink-0 flex items-center justify-center w-11 h-11 rounded-xl border transition-colors mt-0.5 {{ $icon['wrap'] }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        {!! $icon['svg'] !!}
                    </svg>
                </div>

                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <p class="text-sm font-bold text-slate-800 truncate">{{ $tx['item'] }}</p>
                        <span class="px-2 py-0.5 text-[10px] font-black uppercase tracking-widest rounded-md {{ $badge }}">{{ $tx['label'] ?? $type }}</span>
                    </div>
                    @if(!empty($tx['detail']))
                    <p class="text-xs text-slate-500 font-medium mt-0.5 truncate">{{ $tx['detail'] }}</p>
                    @endif
                    @if(!empty($tx['notes']))
                    <p class="text-xs text-slate-400 mt-0.5 truncate italic">{{ $tx['notes'] }}</p>
                    @endif
                </div>

                {{-- Qty + Date --}}
                <div class="text-right shrink-0">
                    <p class="text-lg font-black text-slate-900">{{ $tx['qty'] }}<span class="text-[10px] text-slate-400 uppercase tracking-widest ml-1 font-bold">qty</span></p>
                    <p class="text-[10px] uppercase font-bold tracking-wide text-slate-400 mt-0.5">
                        {{ $date->diffForHumans() }}
                    </p>
                    <p class="text-[10px] text-slate-300 font-medium mt-0.5">
                        {{ $date->format('M d, Y') }}
                    </p>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Empty-filter state --}}
        <div x-show="
                (activeFilter === 'Usage' && {{ $counts['usage'] ?? 0 }} === 0) ||
                (activeFilter === 'Borrow' && {{ $counts['borrow'] ?? 0 }} === 0) ||
                (activeFilter === 'Return' && {{ $counts['return'] ?? 0 }} === 0) ||
                (activeFilter === 'Transfer' && {{ $counts['transfer'] ?? 0 }} === 0) ||
                (activeFilter === 'Disposal' && {{ $counts['disposal'] ?? 0 }} === 0)
             "
             style="display: none;"
             class="flex flex-col items-center justify-center py-16 px-6 text-center">
            <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-50 text-slate-400 ring-1 ring-slate-100">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-8 w-8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h3 class="text-sm font-bold text-slate-900">No Recent Activity</h3>
            <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">There are no records found for this specific filter.</p>
        </div>

        @else
        <div class="flex flex-col items-center justify-center py-16 px-6 text-center">
            <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-50 text-slate-400 ring-1 ring-slate-100">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-8 w-8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h3 class="text-sm font-bold text-slate-900">No Activity Yet</h3>
            <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">This account has not performed any logged actions yet.</p>
        </div>
        @endif
    </div>

</div>
@endsection
