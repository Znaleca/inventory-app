@extends('layouts.app')

@section('title', 'Staff Directory')

@section('content')
<div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-[0_4px_24px_-8px_rgba(0,0,0,0.06)]">

    {{-- Section Header --}}
    <div class="flex items-center justify-between border-b border-slate-100 bg-slate-50/50 px-8 py-5">
        <div>
            <h3 class="text-sm font-semibold text-slate-800">Staff Directory</h3>
            <p class="mt-0.5 text-xs text-slate-500">Doctors, nurses, and technicians available when logging item usage.</p>
        </div>
        <a href="{{ route('staff.create') }}"
            class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm transition-all hover:bg-slate-800 hover:shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
            </svg>
            Add Staff
        </a>
    </div>

    @if($staff->count() > 0)
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm border-separate border-spacing-y-1">
            <thead>
                <tr>
                    <th class="px-3 py-2.5 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Name</th>
                    <th class="px-3 py-2.5 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Type</th>
                    <th class="px-3 py-2.5 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Specialization</th>
                    <th class="px-3 py-2.5 text-right text-xs font-bold uppercase tracking-widest text-slate-500">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($staff as $member)
                @php
                $typeColors = [
                    'doctor'     => ['badge' => 'bg-blue-50 text-blue-700 ring-blue-500/20',       'avatar' => 'bg-blue-100 text-blue-700 ring-1 ring-inset ring-blue-500/20'],
                    'nurse'      => ['badge' => 'bg-pink-50 text-pink-700 ring-pink-500/20',       'avatar' => 'bg-pink-100 text-pink-700 ring-1 ring-inset ring-pink-500/20'],
                    'technician' => ['badge' => 'bg-violet-50 text-violet-700 ring-violet-500/20', 'avatar' => 'bg-violet-100 text-violet-700 ring-1 ring-inset ring-violet-500/20'],
                    'other'      => ['badge' => 'bg-slate-50 text-slate-600 ring-slate-500/20',    'avatar' => 'bg-slate-100 text-slate-600 ring-1 ring-inset ring-slate-500/20'],
                ];
                $colors = $typeColors[$member->type] ?? $typeColors['other'];
                @endphp
                <tr class="group transition-all duration-200 hover:bg-white rounded-xl shadow-sm hover:shadow-md hover:ring-1 hover:ring-slate-200/50">
                    <td class="px-3 py-2.5 rounded-l-xl">
                        <div class="flex items-center gap-3">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl {{ $colors['avatar'] }} text-sm font-black">
                                {{ strtoupper(substr($member->name, 0, 1)) }}
                            </div>
                            <span class="font-bold text-slate-900">{{ $member->display_name }}</span>
                        </div>
                    </td>
                    <td class="whitespace-nowrap px-3 py-2.5">
                        <span class="inline-flex items-center rounded-lg px-2.5 py-1 text-xs font-extrabold ring-1 ring-inset {{ $colors['badge'] }}">
                            {{ ucfirst($member->type) }}
                        </span>
                    </td>
                    <td class="px-3 py-2.5 font-medium text-slate-500">
                        {{ $member->specialization ?: '—' }}
                    </td>
                    <td class="whitespace-nowrap px-3 py-2.5 text-right rounded-r-xl">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('staff.edit', $member) }}"
                                class="inline-flex items-center rounded-lg bg-slate-50 px-3 py-1.5 text-xs font-bold text-slate-600 ring-1 ring-inset ring-slate-500/20 transition-all hover:bg-slate-800 hover:text-white">
                                Edit
                            </a>
                            <form action="{{ route('staff.destroy', $member) }}" method="POST" class="m-0 inline"
                                onsubmit="return confirm('Remove {{ $member->display_name }} from the directory?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center rounded-lg bg-rose-50 px-3 py-1.5 text-xs font-bold text-rose-600 ring-1 ring-inset ring-rose-500/20 transition-all hover:bg-rose-500 hover:text-white">
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
    <div class="flex flex-col items-center justify-center px-6 py-16 text-center">
        <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-slate-50 ring-1 ring-inset ring-slate-200">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-8 w-8 text-slate-400">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
            </svg>
        </div>
        <h3 class="text-sm font-semibold text-slate-900">No staff members yet</h3>
        <p class="mt-1 text-sm text-slate-500">Add doctors, nurses, and technicians to make them available when logging item usage.</p>
        <a href="{{ route('staff.create') }}" class="mt-6 inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-slate-800">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
            </svg>
            Add First Staff Member
        </a>
    </div>
    @endif
</div>
@endsection