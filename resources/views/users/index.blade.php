@extends('layouts.app')

@section('title', 'User Management')

@section('content')
<div class="bg-white rounded-2xl overflow-hidden border border-sky-100 relative mb-6">
    <div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-slate-600 to-slate-800"></div>

    {{-- Section Header --}}
    <div class="px-6 py-5 border-b border-sky-100 flex flex-col md:flex-row items-center justify-between gap-4">
        <div>
            <p class="font-mono text-[10px] font-bold uppercase tracking-widest text-sky-500 mb-1">System://Users</p>
            <h2 class="text-xl font-black text-[#0f172a] tracking-tight">System Users</h2>
            <p class="text-xs text-slate-400 font-mono mt-1">Manage personnel access and application roles.</p>
        </div>
        <a href="{{ route('users.create') }}"
            class="inline-flex items-center gap-2 bg-[#0f172a] px-5 py-2.5 text-[11px] font-mono font-bold text-white uppercase tracking-widest transition-colors hover:bg-slate-700 border border-[#0f172a]">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
            </svg>
            New User Account
        </a>
    </div>

        @if($users->isEmpty())
        <div class="flex flex-col items-center justify-center py-20 text-center">
            <div class="h-14 w-14 border border-sky-100 bg-sky-50 flex items-center justify-center text-slate-400 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-7 w-7">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                </svg>
            </div>
            <p class="font-mono text-[10px] text-slate-400 uppercase tracking-widest mb-1">// No accounts found</p>
            <p class="text-sm font-semibold text-slate-500 mt-1">Add user accounts.</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="bg-sky-50/80 border-b border-sky-100">
                        <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-400 text-left">Personnel</th>
                        <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-400 text-left">Bio ID</th>
                        <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-400 text-left">Privilege</th>
                        <th class="px-6 py-3 font-mono text-[10px] font-bold uppercase tracking-widest text-slate-400 text-right">Actions</th>
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
                                    <span class="inline-flex mt-1 border border-rose-200 bg-rose-50 px-1.5 py-0.5 text-[9px] font-mono font-bold text-rose-500 tracking-wider">Deactivated</span>
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
                            <span class="inline-flex items-center gap-1.5 border border-sky-100 bg-sky-50 px-2 py-0.5 text-[9px] font-mono font-bold tracking-widest uppercase text-slate-600">
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
</div>
@endsection