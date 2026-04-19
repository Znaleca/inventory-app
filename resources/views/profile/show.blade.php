@extends('layouts.app')

@section('title', 'My Profile')

@section('actions')
    <a href="{{ route('profile.edit') }}"
        class="inline-flex items-center gap-2 border border-slate-200 bg-white px-4 py-2 text-[11px] font-mono font-bold text-slate-600 uppercase tracking-widest hover:bg-slate-50 transition-colors">
        Edit Profile
    </a>
@endsection

@section('content')
<div class="mx-auto max-w-5xl">

    {{-- Profile Header --}}
    <div class="bg-white border border-slate-200 relative mb-5">
        <div class="absolute top-0 left-0 w-1 h-full bg-emerald-500"></div>
        <div class="ml-1">
            {{-- Cover Strip --}}
            <div class="h-28 w-full relative overflow-hidden" style="background: linear-gradient(135deg, #1e293b 0%, #334155 50%, #1e293b 100%);">
                <svg class="absolute inset-0 h-full w-full opacity-10" xmlns="http://www.w3.org/2000/svg">
                    <defs><pattern id="profile-dot" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="2" cy="2" r="1.5" fill="currentColor"/></pattern></defs>
                    <rect width="100%" height="100%" fill="url(#profile-dot)" class="text-white"/>
                </svg>
                {{-- Role badge in top-right --}}
                <div class="absolute top-4 right-6">
                    @if($user->role === 'admin')
                    <span class="inline-flex items-center gap-1.5 border border-indigo-500/30 bg-indigo-500/20 px-3 py-1 text-[9px] font-mono font-bold tracking-widest uppercase text-indigo-300">
                        <span class="h-1.5 w-1.5 bg-indigo-400"></span>Administrator
                    </span>
                    @else
                    <span class="inline-flex items-center gap-1.5 border border-white/20 bg-white/10 px-3 py-1 text-[9px] font-mono font-bold tracking-widest uppercase text-white/70">
                        <span class="h-1.5 w-1.5 bg-white/50"></span>Standard_User
                    </span>
                    @endif
                </div>
            </div>

            {{-- Avatar + Info --}}
            <div class="px-8 pb-8 relative">
                <div class="flex flex-col sm:flex-row sm:items-end gap-4 -mt-12 mb-5">
                    {{-- Avatar --}}
                    <div class="relative rounded-full p-1.5 bg-white border border-slate-200 shadow-lg w-fit">
                        <div class="flex h-20 w-20 items-center justify-center rounded-full text-2xl font-black text-white
                            {{ $user->role === 'admin' ? 'bg-gradient-to-tr from-indigo-600 to-violet-500' : 'bg-gradient-to-tr from-slate-700 to-slate-500' }}"
                            @if(!empty($user->profile_photo_url)) style="background-image: url('{{ $user->profile_photo_url }}'); background-size: cover;" @endif>
                            @if(empty($user->profile_photo_url))
                            {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                            @endif
                        </div>
                        <div class="absolute bottom-2 right-2 h-4 w-4 bg-emerald-500 border-2 border-white"></div>
                    </div>
                </div>

                <h1 class="text-2xl font-black text-slate-900 tracking-tight">{{ $user->name ?? 'User' }}</h1>
                <p class="text-sm text-slate-500 font-mono mt-0.5">{{ $user->email ?? '' }}</p>
                <div class="flex flex-wrap items-center gap-2 mt-3">
                    @if($user->bio_id)
                    <span class="inline-flex items-center border border-slate-200 bg-slate-50 px-2 py-0.5 text-[9px] font-mono font-bold tracking-widest uppercase text-slate-600">
                        ID: {{ $user->bio_id }}
                    </span>
                    @endif
                    <span class="inline-flex items-center border border-slate-200 bg-slate-50 px-2 py-0.5 text-[9px] font-mono font-bold tracking-widest uppercase text-slate-500">
                        Joined {{ $user->created_at ? $user->created_at->format('M Y') : '—' }}
                    </span>
                    @if(!($user->is_active ?? true))
                    <span class="inline-flex items-center border border-rose-200 bg-rose-50 px-2 py-0.5 text-[9px] font-mono font-bold tracking-widest uppercase text-rose-600">Deactivated</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Row --}}
    @php
        $statCards = [
            ['label' => 'Total',     'key' => 'total',    'bar' => 'bg-slate-500'],
            ['label' => 'Usage',     'key' => 'usage',    'bar' => 'bg-orange-500'],
            ['label' => 'Borrows',   'key' => 'borrow',   'bar' => 'bg-blue-500'],
            ['label' => 'Returns',   'key' => 'return',   'bar' => 'bg-teal-500'],
            ['label' => 'Transfers', 'key' => 'transfer', 'bar' => 'bg-violet-500'],
            ['label' => 'Disposals', 'key' => 'disposal', 'bar' => 'bg-rose-500'],
        ];
    @endphp
    <div class="grid grid-cols-3 lg:grid-cols-6 gap-3 mb-5">
        @foreach($statCards as $card)
        <div class="bg-white border border-slate-200 relative">
            <div class="absolute top-0 left-0 w-1 h-full {{ $card['bar'] }}"></div>
            <div class="p-4 pl-5">
                <p class="text-[10px] font-mono font-bold text-slate-400 uppercase tracking-widest mb-1">{{ $card['label'] }}</p>
                <p class="text-2xl font-black font-mono text-slate-800">{{ $counts[$card['key']] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Activity Timeline --}}
    <div x-data="{ activeFilter: 'All' }" class="bg-white border border-slate-200 relative">
        <div class="absolute top-0 left-0 w-1 h-full bg-slate-400"></div>
        <div class="ml-1">

            {{-- Header --}}
            <div class="px-6 py-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="h-2 w-2 bg-slate-400 inline-block"></span>
                        <p class="text-[10px] font-mono font-bold text-slate-500 uppercase tracking-widest">// Full Activity Log</p>
                    </div>
                    <h2 class="text-base font-bold text-slate-800 tracking-tight">Every action performed by this account</h2>
                </div>
                <span class="inline-flex items-center border border-emerald-200 bg-emerald-50 px-3 py-1 text-[10px] font-mono font-bold text-emerald-700 tracking-widest uppercase shrink-0">
                    {{ $counts['total'] }} records
                </span>
            </div>

            {{-- Filter Tabs --}}
            @php
                $filters = ['All', 'Usage', 'Borrow', 'Return', 'Transfer', 'Disposal'];
                $filterStyles = [
                    'All'      => 'bg-slate-900 text-white border-slate-900',
                    'Usage'    => 'bg-orange-500 text-white border-orange-500',
                    'Borrow'   => 'bg-blue-500 text-white border-blue-500',
                    'Return'   => 'bg-teal-500 text-white border-teal-500',
                    'Transfer' => 'bg-violet-500 text-white border-violet-500',
                    'Disposal' => 'bg-rose-500 text-white border-rose-500',
                ];
                $filterInactive = 'border-slate-200 bg-white text-slate-500 hover:border-slate-300 hover:text-slate-700';
            @endphp
            <div class="flex items-center gap-1.5 px-6 py-3 border-b border-slate-100 overflow-x-auto">
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
            <div class="divide-y divide-slate-100 max-h-[640px] overflow-y-auto">
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
                    $badge = $badgeMap[$type] ?? 'border-slate-200 bg-slate-50 text-slate-600';
                    $date = $tx['date'] instanceof \Carbon\Carbon ? $tx['date'] : \Carbon\Carbon::parse($tx['date']);
                @endphp
                <div
                    x-show="activeFilter === 'All' || activeFilter === '{{ $type }}'"
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="flex items-start gap-4 px-6 py-4 hover:bg-slate-50 transition-colors">

                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <p class="text-sm font-bold text-slate-800 truncate">{{ $tx['item'] }}</p>
                            <span class="inline-flex items-center border px-2 py-0.5 text-[9px] font-mono font-bold tracking-widest uppercase {{ $badge }}">{{ $tx['label'] ?? $type }}</span>
                        </div>
                        @if(!empty($tx['detail']))
                        <p class="text-xs font-mono text-slate-500 mt-0.5 truncate">{{ $tx['detail'] }}</p>
                        @endif
                        @if(!empty($tx['notes']))
                        <p class="text-xs text-slate-400 mt-0.5 truncate italic">{{ $tx['notes'] }}</p>
                        @endif
                    </div>

                    <div class="text-right shrink-0">
                        <p class="text-lg font-black font-mono text-slate-900">{{ $tx['qty'] }}<span class="text-[10px] text-slate-400 uppercase tracking-widest ml-1 font-bold">qty</span></p>
                        <p class="text-[10px] uppercase font-bold tracking-wide text-slate-400 mt-0.5">{{ $date->diffForHumans() }}</p>
                        <p class="text-[10px] font-mono text-slate-300 mt-0.5">{{ $date->format('M d, Y') }}</p>
                    </div>
                </div>
                @endforeach

                {{-- Empty filter state --}}
                <div x-show="
                    (activeFilter === 'Usage' && {{ $counts['usage'] ?? 0 }} === 0) ||
                    (activeFilter === 'Borrow' && {{ $counts['borrow'] ?? 0 }} === 0) ||
                    (activeFilter === 'Return' && {{ $counts['return'] ?? 0 }} === 0) ||
                    (activeFilter === 'Transfer' && {{ $counts['transfer'] ?? 0 }} === 0) ||
                    (activeFilter === 'Disposal' && {{ $counts['disposal'] ?? 0 }} === 0)"
                    style="display: none;"
                    class="flex flex-col items-center justify-center py-16 text-center">
                    <div class="h-12 w-12 border border-slate-200 bg-slate-50 flex items-center justify-center text-slate-400 mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="font-mono text-[10px] text-slate-400 uppercase tracking-widest">// No records for this filter</p>
                </div>
            </div>
            @else
            <div class="flex flex-col items-center justify-center py-20 text-center">
                <div class="h-14 w-14 border border-slate-200 bg-slate-50 flex items-center justify-center text-slate-400 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-7 w-7">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="font-mono text-[10px] text-slate-400 uppercase tracking-widest mb-1">// No activity yet</p>
                <p class="text-sm font-semibold text-slate-500 mt-1">This account has not performed any logged actions yet.</p>
            </div>
            @endif
        </div>
    </div>

</div>
@endsection
