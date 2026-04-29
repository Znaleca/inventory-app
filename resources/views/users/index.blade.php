@extends('layouts.app')

@section('title', 'User Management')

@section('actions')
<a href="{{ route('users.create') }}"
    class="inline-flex items-center gap-2 bg-sky-500 hover:bg-sky-600 text-white px-4 py-2 text-[11px] font-mono font-bold uppercase tracking-[0.15em] transition-colors border border-blue-700">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-3.5 w-3.5">
        <path d="M8.75 3.75a.75.75 0 00-1.5 0v3.5h-3.5a.75.75 0 000 1.5h3.5v3.5a.75.75 0 001.5 0v-3.5h3.5a.75.75 0 000-1.5h-3.5v-3.5z" />
    </svg>
    New_User_Account
</a>
@endsection

@section('content')
@php
    $totalUsers = $users->count();
    $adminCount = $users->where('role', 'admin')->count();
    $activeCount = $users->where('is_active', true)->count();
    $inactiveCount = $users->where('is_active', false)->count();
@endphp

{{-- KPI Cards --}}
<div class="bg-white rounded-2xl overflow-hidden border border-slate-200 shadow-sm mb-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">
        <div class="group relative overflow-hidden bg-white p-4 border-r border-b border-slate-100 transition-all hover:bg-slate-50 hover:shadow-inner sm:[&:nth-child(2n)]:border-r-0 lg:[&:nth-child(4n)]:border-r-0 lg:border-b-0 sm:border-b">
            <div class="flex items-center justify-between mb-3">
                <p class="font-semibold text-xs text-slate-500 uppercase tracking-wider">Total Users</p>
                <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-slate-50 text-slate-400 transition-colors group-hover:bg-slate-200 group-hover:text-slate-600">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                </div>
            </div>
            <div class="z-10">
                <p class="text-2xl font-black tracking-tight text-slate-800">{{ $totalUsers }}</p>
                <p class="mt-0.5 text-[10px] font-medium text-slate-400">System accounts</p>
            </div>
        </div>

        <div class="group relative overflow-hidden bg-white p-4 border-r border-b border-slate-100 transition-all hover:bg-slate-50 hover:shadow-inner sm:[&:nth-child(2n)]:border-r-0 lg:[&:nth-child(4n)]:border-r-0 lg:border-b-0 sm:border-b">
            <div class="flex items-center justify-between mb-3">
                <p class="font-semibold text-xs text-slate-500 uppercase tracking-wider">Administrators</p>
                <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 transition-colors group-hover:bg-indigo-100">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="z-10">
                <p class="text-2xl font-black tracking-tight text-slate-800">{{ $adminCount }}</p>
                <p class="mt-0.5 text-[10px] font-medium text-slate-400">High-level access</p>
            </div>
        </div>

        <div class="group relative overflow-hidden bg-white p-4 border-r border-b border-slate-100 transition-all hover:bg-slate-50 hover:shadow-inner sm:[&:nth-child(2n)]:border-r-0 lg:[&:nth-child(4n)]:border-r-0 lg:border-b-0">
            <div class="flex items-center justify-between mb-3">
                <p class="font-semibold text-xs text-slate-500 uppercase tracking-wider">Active</p>
                <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 transition-colors group-hover:bg-emerald-100">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.348a1.125 1.125 0 010 1.971l-11.54 6.347a1.125 1.125 0 01-1.667-.985V5.653z" />
                    </svg>
                </div>
            </div>
            <div class="z-10">
                <p class="text-2xl font-black tracking-tight text-slate-800">{{ $activeCount }}</p>
                <p class="mt-0.5 text-[10px] font-medium text-slate-400">Currently enabled</p>
            </div>
        </div>

        <div class="group relative overflow-hidden bg-white p-4 border-r border-b border-slate-100 transition-all hover:bg-slate-50 hover:shadow-inner sm:[&:nth-child(2n)]:border-r-0 lg:[&:nth-child(4n)]:border-r-0 lg:border-b-0">
            <div class="flex items-center justify-between mb-3">
                <p class="font-semibold text-xs text-slate-500 uppercase tracking-wider">Disabled</p>
                <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-rose-50 text-rose-600 transition-colors group-hover:bg-rose-100">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                    </svg>
                </div>
            </div>
            <div class="z-10">
                <p class="text-2xl font-black tracking-tight text-slate-800">{{ $inactiveCount }}</p>
                <p class="mt-0.5 text-[10px] font-medium text-slate-400">Deactivated accounts</p>
            </div>
        </div>
    </div>
</div>

{{-- Users Table --}}
<div class="bg-white rounded-2xl overflow-hidden border border-sky-100 shadow-sm">
    <div class="p-5 border-b border-sky-100 flex items-center justify-between gap-4">
        <div>
            <p class="text-[10px] font-semibold text-slate-500 uppercase tracking-widest mb-0.5">Registry</p>
            <h3 class="text-sm font-bold text-slate-800">System Users</h3>
        </div>
        <span class="font-mono text-xs font-bold text-slate-600 bg-slate-50 border border-slate-200 px-3 py-1.5">{{ $totalUsers }}</span>
    </div>
    
    @if($users->isEmpty())
    <div class="flex flex-col items-center justify-center py-20 text-center">
        <div class="h-14 w-14 border border-sky-100 bg-sky-50 flex items-center justify-center text-slate-400 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-7 w-7">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
            </svg>
        </div>
        <p class="font-mono text-[10px] text-slate-400 uppercase tracking-widest mb-1">// No accounts found</p>
        <p class="text-sm font-semibold text-sky-500 mt-1">Add user accounts.</p>
    </div>
    @else
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="bg-sky-50/80 border-b border-sky-100">
                    <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Personnel</th>
                    <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Bio ID</th>
                    <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-left">Privilege</th>
                    <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-500 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-sky-50">
                @foreach($users as $user)
                <tr class="hover:bg-sky-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="flex flex-col">
                                <span class="font-bold text-[#0f172a]">{{ $user->name }}</span>
                                @if(!$user->is_active)
                                <span class="inline-flex mt-1 border border-rose-200 bg-rose-50 px-1.5 py-0.5 text-[9px] font-mono font-bold text-rose-500 tracking-wider w-fit">Disabled</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="whitespace-nowrap px-6 py-4">
                        <span class="font-mono text-xs font-bold text-slate-500">{{ $user->bio_id }}</span>
                    </td>
                    <td class="whitespace-nowrap px-6 py-4">
                        @if($user->role === 'admin')
                        <span class="inline-flex items-center gap-1.5 border border-indigo-200 bg-indigo-50 px-2 py-0.5 text-[9px] font-mono font-bold tracking-widest uppercase text-indigo-700">
                            <span class="h-1.5 w-1.5 bg-indigo-500"></span>
                            Administrator
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1.5 border border-slate-200 bg-slate-50 px-2 py-0.5 text-[9px] font-mono font-bold tracking-widest uppercase text-slate-600">
                            <span class="h-1.5 w-1.5 bg-slate-400"></span>
                            Standard_User
                        </span>
                        @endif
                    </td>
                    <td class="whitespace-nowrap px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-1.5">
                            <a href="{{ route('users.activity', $user) }}"
                                class="inline-flex items-center border border-emerald-200 bg-emerald-50 px-2.5 py-1.5 text-[10px] font-mono font-bold text-emerald-700 hover:bg-emerald-600 hover:text-white transition-colors"
                                title="View activity log">
                                Activity
                            </a>
                            <a href="{{ route('users.edit', $user) }}"
                                class="inline-flex items-center border border-sky-100 bg-white px-2.5 py-1.5 text-[10px] font-mono font-bold text-slate-700 hover:bg-sky-50 transition-colors">
                                Edit
                            </a>
                            @if($user->id !== auth()->id())
                            <form action="{{ route('users.destroy', $user) }}" method="POST" class="m-0 inline"
                                onsubmit="return confirm('Are you sure you want to {{ $user->is_active ? 'deactivate' : 'activate' }} this user account?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center border {{ $user->is_active ? 'border-amber-200 bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white hover:border-amber-500' : 'border-teal-200 bg-teal-50 text-teal-600 hover:bg-teal-600 hover:text-white hover:border-teal-600' }} px-2.5 py-1.5 text-[10px] font-mono font-bold transition-colors">
                                    {{ $user->is_active ? 'Disable' : 'Enable' }}
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endsection