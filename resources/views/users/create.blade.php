@extends('layouts.app')

@section('title', 'Create User Account')

@section('actions')
<a href="{{ route('users.index') }}"
    class="group inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2 text-sm font-semibold text-slate-600 shadow-sm ring-1 ring-inset ring-slate-300 transition-all hover:bg-slate-50 hover:text-slate-900">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
        class="h-4 w-4 transition-transform duration-300 group-hover:-translate-x-1">
        <path fill-rule="evenodd"
            d="M17 10a.75.75 0 01-.75.75H5.66l4.22 4.22a.75.75 0 11-1.06 1.06l-5.5-5.5a.75.75 0 010-1.06l5.5-5.5a.75.75 0 111.06 1.06l-4.22 4.22h10.59a.75.75 0 01.75.75z"
            clip-rule="evenodd" />
    </svg>
    Back to Users
</a>
@endsection

@section('content')
<div class="mx-auto max-w-3xl">
    <form action="{{ route('users.store') }}" method="POST"
        class="overflow-hidden rounded-[2rem] bg-white ring-1 ring-slate-200 shadow-[0_8px_30px_-12px_rgba(0,0,0,0.1)]">
        @csrf

        {{-- Header Section --}}
        <div class="border-b border-slate-100 bg-slate-50/50 px-8 py-6">
            <div class="flex items-center gap-4">
                <div
                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-blue-50 text-blue-600 ring-1 ring-inset ring-blue-500/20">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="h-6 w-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.662 0v.109A12.318 12.318 0 0110.331 21c-2.331 0-4.512-.645-6.374-1.766z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-900">New User Account</h2>
                    <p class="text-sm text-slate-500 mt-0.5">Grant system access to a new staff member.</p>
                </div>
            </div>
        </div>

        {{-- Main Form Body --}}
        <div class="px-8 py-8 space-y-8">

            {{-- SECTION: Profile Information --}}
            <div>
                <h3 class="mb-4 flex items-center gap-2 text-sm font-bold uppercase tracking-widest text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-5.5-2.5a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0zM10 12a5.99 5.99 0 00-4.793 2.39A6.483 6.483 0 0010 16.5a6.483 6.483 0 004.793-2.11A5.99 5.99 0 0010 12z"
                            clip-rule="evenodd" />
                    </svg>
                    Profile Information
                </h3>

                <div class="space-y-6">
                    <div>
                        <label for="name" class="mb-2 block text-sm font-bold text-slate-700">Full Name <span
                                class="text-rose-500">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                            placeholder="e.g. Dr. John Doe"
                            class="block w-full rounded-xl border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all">
                        @error('name') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="bio_id" class="mb-2 block text-sm font-bold text-slate-700">Bio ID / Username
                                <span class="text-rose-500">*</span></label>
                            <input type="text" id="bio_id" name="bio_id" value="{{ old('bio_id') }}" required
                                placeholder="e.g. 0123"
                                class="block w-full rounded-xl border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all">
                            @error('bio_id') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="role" class="mb-2 block text-sm font-bold text-slate-700">Role <span
                                    class="text-rose-500">*</span></label>
                            <select id="role" name="role" required
                                class="block w-full rounded-xl border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all">
                                <option value="user" {{ old('role')==='user' ? 'selected' : '' }}>Standard User</option>
                                <option value="admin" {{ old('role')==='admin' ? 'selected' : '' }}>Administrator
                                </option>
                            </select>
                            @error('role') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <hr class="border-slate-100">

            {{-- SECTION: Security Settings --}}
            <div>
                <h3 class="mb-4 flex items-center gap-2 text-sm font-bold uppercase tracking-widest text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                        <path fill-rule="evenodd"
                            d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z"
                            clip-rule="evenodd" />
                    </svg>
                    Security Settings
                </h3>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="password" class="mb-2 block text-sm font-bold text-slate-700">Password <span
                                class="text-rose-500">*</span></label>
                        <input type="password" id="password" name="password" required placeholder="••••••••"
                            class="block w-full rounded-xl border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all">
                        @error('password') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="mb-2 block text-sm font-bold text-slate-700">Confirm
                            Password <span class="text-rose-500">*</span></label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                            placeholder="••••••••"
                            class="block w-full rounded-xl border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all">
                    </div>
                </div>
            </div>

        </div>

        {{-- Footer / Submit Area --}}
        <div class="bg-slate-50 px-8 py-5 flex items-center justify-end gap-3 border-t border-slate-100">
            <a href="{{ route('users.index') }}"
                class="rounded-xl px-5 py-2.5 text-sm font-bold text-slate-500 transition-colors hover:bg-slate-200 hover:text-slate-900">
                Cancel
            </a>
            <button type="submit"
                class="group relative inline-flex items-center justify-center gap-2 overflow-hidden rounded-xl bg-slate-900 px-6 py-2.5 text-sm font-bold text-white shadow-md transition-all duration-300 hover:bg-blue-600 hover:shadow-lg hover:shadow-blue-500/30 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2">
                <span class="relative">Create Account</span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                    class="relative h-4 w-4 transition-transform duration-300 group-hover:translate-x-0.5">
                    <path fill-rule="evenodd"
                        d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                        clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </form>
</div>
@endsection