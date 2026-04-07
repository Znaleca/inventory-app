@extends('layouts.app')

@section('title', 'User Management')

@section('content')
<div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-[0_4px_24px_-8px_rgba(0,0,0,0.06)]">

    {{-- Section Header --}}
    <div class="flex items-center justify-between border-b border-slate-100 bg-slate-50/50 px-8 py-5">
        <div>
            <h3 class="text-sm font-semibold text-slate-800">System Accounts</h3>
            <p class="mt-0.5 text-xs text-slate-500">Manage personnel access and application roles.</p>
        </div>
        <a href="{{ route('users.create') }}"
            class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm transition-all hover:bg-slate-800 hover:shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
            </svg>
            New User Account
        </a>
    </div>

    @if($users->isEmpty())
    <div class="flex flex-col items-center justify-center px-6 py-16 text-center">
        <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-slate-50 ring-1 ring-inset ring-slate-200">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-8 w-8 text-slate-400">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
            </svg>
        </div>
        <h3 class="text-sm font-semibold text-slate-900">No users found</h3>
        <p class="mt-1 text-sm text-slate-500">Add user accounts to grant access to the application.</p>
        <a href="{{ route('users.create') }}" class="mt-6 inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-slate-800">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
            </svg>
            Add First User
        </a>
    </div>
    @else
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm border-separate border-spacing-y-1">
            <thead>
                <tr>
                    <th class="px-3 py-2.5 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Personnel</th>
                    <th class="px-3 py-2.5 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Bio ID</th>
                    <th class="px-3 py-2.5 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Privilege</th>
                    <th class="px-3 py-2.5 text-right text-xs font-bold uppercase tracking-widest text-slate-500">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr class="group transition-all duration-200 hover:bg-white rounded-xl shadow-sm hover:shadow-md hover:ring-1 hover:ring-slate-200/50">
                    <td class="px-3 py-2.5 rounded-l-xl">
                        <div class="flex items-center gap-3">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl {{ $user->role === 'admin' ? 'bg-indigo-100 text-indigo-700 ring-1 ring-inset ring-indigo-500/20' : 'bg-slate-100 text-slate-600 ring-1 ring-inset ring-slate-500/20' }} text-sm font-black transition-colors">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div class="flex flex-col">
                                <span class="font-bold text-slate-900">{{ $user->name }}</span>
                                @if(!$user->is_active)
                                <span class="text-[10px] font-bold text-rose-500 uppercase tracking-wider">Deactivated</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="whitespace-nowrap px-3 py-2.5">
                        <span class="font-mono text-xs font-bold text-slate-500">{{ $user->bio_id }}</span>
                    </td>
                    <td class="whitespace-nowrap px-3 py-2.5">
                        @if($user->role === 'admin')
                        <span class="inline-flex items-center gap-1.5 rounded-lg bg-indigo-50 px-2.5 py-1 text-xs font-extrabold text-indigo-700 ring-1 ring-inset ring-indigo-500/20">
                            <div class="h-1.5 w-1.5 rounded-full bg-indigo-500 shadow-[0_0_8px_rgba(99,102,241,0.6)]"></div>
                            Administrator
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1.5 rounded-lg bg-slate-50 px-2.5 py-1 text-xs font-extrabold text-slate-600 ring-1 ring-inset ring-slate-500/20">
                            <div class="h-1.5 w-1.5 rounded-full bg-slate-400"></div>
                            Standard User
                        </span>
                        @endif
                    </td>
                    <td class="whitespace-nowrap px-3 py-2.5 text-right rounded-r-xl">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('users.activity', $user) }}"
                                class="inline-flex items-center rounded-lg bg-emerald-50 px-3 py-1.5 text-xs font-bold text-emerald-700 ring-1 ring-inset ring-emerald-500/20 transition-all hover:bg-emerald-600 hover:text-white"
                                title="View activity log">
                                Activity
                            </a>
                            <a href="{{ route('users.edit', $user) }}"
                                class="inline-flex items-center rounded-lg bg-slate-50 px-3 py-1.5 text-xs font-bold text-slate-600 ring-1 ring-inset ring-slate-500/20 transition-all hover:bg-slate-800 hover:text-white">
                                Edit
                            </a>
                            @if($user->id !== auth()->id())
                            <form action="{{ route('users.destroy', $user) }}" method="POST" class="m-0 inline"
                                onsubmit="return confirm('Are you sure you want to {{ $user->is_active ? 'deactivate' : 'activate' }} this user account?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center rounded-lg {{ $user->is_active ? 'bg-amber-50 text-amber-600 ring-amber-500/20 hover:bg-amber-500' : 'bg-emerald-50 text-emerald-600 ring-emerald-500/20 hover:bg-emerald-500' }} px-3 py-1.5 text-xs font-bold ring-1 ring-inset transition-all hover:text-white">
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