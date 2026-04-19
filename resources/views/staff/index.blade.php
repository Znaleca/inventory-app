@extends('layouts.app')

@section('title', 'Staff Directory')

@section('content')
<div class="bg-white border border-slate-200 relative mb-6">
    <div class="absolute top-0 left-0 w-1 h-full bg-violet-500"></div>

    <div class="ml-1">
        {{-- Section Header --}}
        <div class="px-6 py-5 border-b border-slate-100 flex flex-col md:flex-row items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="h-2 w-2 bg-violet-500 inline-block"></span>
                    <p class="text-[10px] font-mono font-bold text-violet-600 uppercase tracking-widest">// System Staff</p>
                </div>
                <h2 class="text-xl font-bold text-slate-800 tracking-tight">Staff Directory</h2>
                <p class="text-xs text-slate-500 font-mono mt-1">Programmers and tech support staff available when logging item usage.</p>
            </div>
            <a href="{{ route('staff.create') }}"
                class="inline-flex items-center gap-2 bg-slate-900 px-5 py-2.5 text-[11px] font-mono font-bold text-white uppercase tracking-widest transition-colors hover:bg-slate-800 border border-slate-900">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                    <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                </svg>
                Add Staff
            </a>
        </div>

        @if($staff->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-200">
                        <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Name</th>
                        <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Type</th>
                        <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($staff as $member)
                    @php
                        $typeColors = [
                            'programmer' => 'text-blue-600 bg-blue-50 border-blue-200',
                            'tech support' => 'text-emerald-600 bg-emerald-50 border-emerald-200',
                            'supervisor' => 'text-amber-600 bg-amber-50 border-amber-200',
                            'head' => 'text-violet-600 bg-violet-50 border-violet-200',
                        ];
                        $colors = $typeColors[$member->type] ?? 'text-slate-600 bg-slate-50 border-slate-200';
                    @endphp
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <span class="font-bold text-slate-900">{{ $member->display_name }}</span>
                            </div>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <span class="inline-flex items-center border px-2 py-0.5 text-[9px] font-mono font-bold tracking-widest uppercase {{ $colors }}">
                                {{ $member->type }}
                            </span>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('staff.edit', $member) }}"
                                    class="inline-flex items-center border border-slate-200 bg-white px-2.5 py-1.5 text-[10px] font-mono font-bold text-slate-700 hover:bg-slate-100 transition-colors">
                                    Edit
                                </a>
                                <form action="{{ route('staff.destroy', $member) }}" method="POST" class="m-0 inline"
                                    onsubmit="return confirm('Remove {{ $member->display_name }} from the directory?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center border border-rose-200 bg-rose-50 px-2.5 py-1.5 text-[10px] font-mono font-bold text-rose-600 hover:bg-rose-500 hover:text-white hover:border-rose-500 transition-colors">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="flex flex-col items-center justify-center py-20 text-center">
            <div class="h-14 w-14 border border-slate-200 bg-slate-50 flex items-center justify-center text-slate-400 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-7 w-7">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                </svg>
            </div>
            <p class="font-mono text-[10px] text-slate-400 uppercase tracking-widest mb-1">// No records found</p>
            <p class="text-sm font-semibold text-slate-500 mt-1">Add programmers or tech support.</p>
        </div>
        @endif
    </div>
</div>
@endsection