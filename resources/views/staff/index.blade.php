@extends('layouts.app')

@section('title', 'Staff Directory')

@section('actions')
<a href="{{ route('staff.create') }}"
    class="inline-flex items-center gap-2 bg-sky-500 hover:bg-sky-600 text-white px-4 py-2 text-[11px] font-mono font-bold uppercase tracking-[0.15em] transition-colors border border-blue-700">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-3.5 w-3.5">
        <path d="M8.75 3.75a.75.75 0 00-1.5 0v3.5h-3.5a.75.75 0 000 1.5h3.5v3.5a.75.75 0 001.5 0v-3.5h3.5a.75.75 0 000-1.5h-3.5v-3.5z" />
    </svg>
    Add_Staff
</a>
@endsection

@section('content')
@php
    $typeColors = [
        'programmer'   => ['bg' => 'bg-sky-50',    'text' => 'text-sky-600',    'border' => 'border-sky-200'],
        'tech support' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600','border' => 'border-emerald-200'],
        'supervisor'   => ['bg' => 'bg-amber-50',   'text' => 'text-amber-600',  'border' => 'border-amber-200'],
        'head'         => ['bg' => 'bg-violet-50',  'text' => 'text-violet-600', 'border' => 'border-violet-200'],
    ];

    $typeCounts = $staff->groupBy('type')->map->count();
@endphp

{{-- KPI Cards --}}
<div class="bg-white rounded-2xl overflow-hidden border border-slate-200 shadow-sm mb-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">
        <div class="group relative overflow-hidden bg-white p-4 border-r border-b border-slate-100 transition-all hover:bg-slate-50 hover:shadow-inner sm:[&:nth-child(2n)]:border-r-0 lg:[&:nth-child(4n)]:border-r-0 lg:border-b-0 sm:border-b">
            <div class="flex items-center justify-between mb-3">
                <p class="font-semibold text-xs text-slate-500 uppercase tracking-wider">Total Staff</p>
                <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-slate-50 text-slate-400 transition-colors group-hover:bg-slate-200 group-hover:text-slate-600">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                </div>
            </div>
            <div class="z-10">
                <p class="text-2xl font-black tracking-tight text-slate-800">{{ $staff->count() }}</p>
                <p class="mt-0.5 text-[10px] font-medium text-slate-400">All registered staff</p>
            </div>
        </div>

        <div class="group relative overflow-hidden bg-white p-4 border-r border-b border-slate-100 transition-all hover:bg-slate-50 hover:shadow-inner sm:[&:nth-child(2n)]:border-r-0 lg:[&:nth-child(4n)]:border-r-0 lg:border-b-0 sm:border-b">
            <div class="flex items-center justify-between mb-3">
                <p class="font-semibold text-xs text-slate-500 uppercase tracking-wider">Programmers</p>
                <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-sky-50 text-sky-600 transition-colors group-hover:bg-sky-100">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5" />
                    </svg>
                </div>
            </div>
            <div class="z-10">
                <p class="text-2xl font-black tracking-tight text-slate-800">{{ $typeCounts['programmer'] ?? 0 }}</p>
                <p class="mt-0.5 text-[10px] font-medium text-slate-400">Development staff</p>
            </div>
        </div>

        <div class="group relative overflow-hidden bg-white p-4 border-r border-b border-slate-100 transition-all hover:bg-slate-50 hover:shadow-inner sm:[&:nth-child(2n)]:border-r-0 lg:[&:nth-child(4n)]:border-r-0 lg:border-b-0">
            <div class="flex items-center justify-between mb-3">
                <p class="font-semibold text-xs text-slate-500 uppercase tracking-wider">Tech Support</p>
                <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 transition-colors group-hover:bg-emerald-100">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z" />
                    </svg>
                </div>
            </div>
            <div class="z-10">
                <p class="text-2xl font-black tracking-tight text-slate-800">{{ $typeCounts['tech support'] ?? 0 }}</p>
                <p class="mt-0.5 text-[10px] font-medium text-slate-400">Support personnel</p>
            </div>
        </div>

        <div class="group relative overflow-hidden bg-white p-4 border-r border-b border-slate-100 transition-all hover:bg-slate-50 hover:shadow-inner sm:[&:nth-child(2n)]:border-r-0 lg:[&:nth-child(4n)]:border-r-0 lg:border-b-0">
            <div class="flex items-center justify-between mb-3">
                <p class="font-semibold text-xs text-slate-500 uppercase tracking-wider">Supervisors / Heads</p>
                <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-violet-50 text-violet-600 transition-colors group-hover:bg-violet-100">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="z-10">
                <p class="text-2xl font-black tracking-tight text-slate-800">{{ ($typeCounts['supervisor'] ?? 0) + ($typeCounts['head'] ?? 0) }}</p>
                <p class="mt-0.5 text-[10px] font-medium text-slate-400">Leadership roles</p>
            </div>
        </div>
    </div>
</div>

{{-- Staff Table --}}
<div class="bg-white rounded-2xl overflow-hidden border border-sky-100 shadow-sm">
    <div class="p-5 border-b border-sky-100 flex items-center justify-between gap-4">
        <div>
            <p class="text-[10px] font-semibold text-violet-600 uppercase tracking-widest mb-0.5">Registry</p>
            <h3 class="text-sm font-bold text-slate-800">Staff Directory</h3>
        </div>
        <span class="font-mono text-xs font-bold text-violet-600 bg-violet-50 border border-violet-100 px-3 py-1.5">{{ $staff->count() }}</span>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="bg-sky-50/80 border-b border-sky-100">
                    <th class="px-6 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 text-left">Name</th>
                    <th class="px-6 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 text-left">Type</th>
                    <th class="px-6 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-sky-50">
                @forelse($staff as $member)
                @php
                    $c = $typeColors[$member->type] ?? ['bg' => 'bg-slate-50', 'text' => 'text-slate-600', 'border' => 'border-slate-200'];
                @endphp
                <tr class="hover:bg-sky-50 transition-colors">
                    <td class="px-6 py-4 font-bold text-[#0f172a]">{{ $member->display_name }}</td>
                    <td class="whitespace-nowrap px-6 py-4">
                        <span class="inline-flex items-center border px-2 py-0.5 text-[9px] font-mono font-bold uppercase tracking-widest {{ $c['bg'] }} {{ $c['text'] }} {{ $c['border'] }}">
                            {{ $member->type }}
                        </span>
                    </td>
                    <td class="whitespace-nowrap px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-1.5">
                            <a href="{{ route('staff.edit', $member) }}"
                                class="inline-flex items-center border border-sky-100 bg-white px-2.5 py-1.5 text-[10px] font-mono font-bold text-slate-700 hover:bg-sky-50 transition-colors">Edit</a>
                            <form action="{{ route('staff.destroy', $member) }}" method="POST" class="m-0 inline"
                                onsubmit="return confirm('Remove {{ $member->display_name }} from the directory?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center border border-rose-200 bg-rose-50 px-2.5 py-1.5 text-[10px] font-mono font-bold text-rose-600 hover:bg-rose-500 hover:text-white hover:border-rose-500 transition-colors">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-6 py-16 text-center">
                        <p class="font-mono text-[10px] text-slate-400 uppercase tracking-widest">// No staff records found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection