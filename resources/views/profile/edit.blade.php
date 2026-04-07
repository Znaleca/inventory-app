@extends('layouts.app')

@section('title', 'Account Settings')

@section('actions')
<a href="{{ route('dashboard') }}"
    class="group inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2 text-sm font-semibold text-slate-600 shadow-sm ring-1 ring-inset ring-slate-300 transition-all hover:bg-slate-50 hover:text-slate-900">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
        class="h-4 w-4 transition-transform duration-300 group-hover:-translate-x-1">
        <path fill-rule="evenodd"
            d="M17 10a.75.75 0 01-.75.75H5.66l4.22 4.22a.75.75 0 11-1.06 1.06l-5.5-5.5a.75.75 0 010-1.06l5.5-5.5a.75.75 0 111.06 1.06l-4.22 4.22h10.59a.75.75 0 01.75.75z"
            clip-rule="evenodd" />
    </svg>
    Back to Dashboard
</a>
@endsection

@section('content')
<div class="mx-auto max-w-3xl">

    @if(session('success'))
    <div
        class="mb-6 flex items-center gap-3 rounded-[1.5rem] bg-emerald-50 px-3 py-2.5 text-sm font-medium text-emerald-800 ring-1 ring-inset ring-emerald-500/20">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
            class="h-5 w-5 text-emerald-500 shrink-0">
            <path fill-rule="evenodd"
                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                clip-rule="evenodd" />
        </svg>
        {{ session('success') }}
    </div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data"
        class="overflow-hidden rounded-[2rem] bg-white ring-1 ring-slate-200 shadow-[0_8px_30px_-12px_rgba(0,0,0,0.1)]">
        @csrf
        @method('PATCH')

        {{-- Header Section --}}
        <div class="border-b border-slate-100 bg-slate-50/50 px-8 py-6">
            <div class="flex items-center gap-4">
                <div
                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-blue-50 text-blue-600 ring-1 ring-inset ring-blue-500/20">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="h-6 w-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Account Settings</h2>
                    <p class="text-sm text-slate-500 mt-0.5">Manage your profile information and account security.</p>
                </div>
            </div>
        </div>

        {{-- Main Form Body --}}
        <div class="px-8 py-8 space-y-8">

            {{-- SECTION: Account Credentials --}}
            <div>
                <h3 class="mb-4 flex items-center gap-2 text-sm font-bold uppercase tracking-widest text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                        <path fill-rule="evenodd"
                            d="M2.5 4A1.5 1.5 0 001 5.5V6h18v-.5A1.5 1.5 0 0017.5 4h-15zM19 8.5H1v6A1.5 1.5 0 002.5 16h15a1.5 1.5 0 001.5-1.5v-6zM3 13.25a.75.75 0 01.75-.75h1.5a.75.75 0 010 1.5h-1.5a.75.75 0 01-.75-.75zm4.75-.75a.75.75 0 000 1.5h3.5a.75.75 0 000-1.5h-3.5z"
                            clip-rule="evenodd" />
                    </svg>
                    Account Credentials
                </h3>

                <div class="space-y-6">
                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Bio ID / Username</label>
                        <input type="text" value="{{ $user->bio_id }}" readonly
                            class="block w-full cursor-not-allowed rounded-xl border-0 bg-slate-50 py-3 px-4 text-slate-500 shadow-sm ring-1 ring-inset ring-slate-200/50 sm:text-sm sm:leading-6">

                        <div
                            class="mt-3 inline-flex items-center gap-2 rounded-xl bg-amber-50 px-3 py-2 text-xs font-semibold text-amber-700 ring-1 ring-inset ring-amber-500/20">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                class="h-4 w-4">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z"
                                    clip-rule="evenodd" />
                            </svg>
                            Only a system administrator can change your Bio ID.
                        </div>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">System Role</label>
                        <span
                            class="inline-flex items-center rounded-lg px-3 py-1.5 text-xs font-bold uppercase tracking-widest {{ $user->role === 'admin' ? 'bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-500/20' : 'bg-slate-50 text-slate-700 ring-1 ring-inset ring-slate-500/20' }}">
                            {{ $user->role }}
                        </span>
                    </div>
                </div>
            </div>

            <hr class="border-slate-100">

            {{-- SECTION: Personal Information --}}
            <div>
                <h3 class="mb-4 flex items-center gap-2 text-sm font-bold uppercase tracking-widest text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-5.5-2.5a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0zM10 12a5.99 5.99 0 00-4.793 2.39A6.483 6.483 0 0010 16.5a6.483 6.483 0 004.793-2.11A5.99 5.99 0 0010 12z"
                            clip-rule="evenodd" />
                    </svg>
                    Personal Information
                </h3>

                <div class="mb-6 flex items-center gap-4">
                    <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-full bg-slate-900 border-2 border-white shadow bg-cover bg-center" 
                         style="background-image: url('{{ $user->profile_photo_url ?? '' }}')">
                        @if(!$user->profile_photo_url)
                            <span class="text-xl font-bold text-white">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        @endif
                    </div>
                    <div>
                        <label for="photo" class="block text-sm font-bold text-slate-700 mb-1">Profile Photo</label>
                        <input type="file" id="photo" name="photo" accept="image/*"
                            class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 transition-all cursor-pointer">
                        @error('photo') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="name" class="mb-2 block text-sm font-bold text-slate-700">Full Name <span
                            class="text-rose-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                        class="block w-full rounded-xl border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all">
                    @error('name') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <hr class="border-slate-100">

            {{-- SECTION: Update Password --}}
            <div>
                <h3 class="mb-1 flex items-center gap-2 text-sm font-bold uppercase tracking-widest text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                        <path fill-rule="evenodd"
                            d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z"
                            clip-rule="evenodd" />
                    </svg>
                    Update Password
                </h3>
                <p class="mb-6 text-sm text-slate-500">Leave these fields blank if you do not wish to change your
                    password.</p>

                <div class="space-y-6">
                    <div>
                        <label for="current_password" class="mb-2 block text-sm font-bold text-slate-700">Current
                            Password</label>
                        <input type="password" id="current_password" name="current_password"
                            placeholder="Enter current password to authorize changes"
                            class="block w-full rounded-xl border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all">
                        @error('current_password') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="password" class="mb-2 block text-sm font-bold text-slate-700">New
                                Password</label>
                            <input type="password" id="password" name="password" placeholder="Enter new password"
                                class="block w-full rounded-xl border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all">
                            @error('password') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="password_confirmation"
                                class="mb-2 block text-sm font-bold text-slate-700">Confirm New Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                placeholder="Re-type new password"
                                class="block w-full rounded-xl border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all">
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Footer / Submit Area --}}
        <div class="bg-slate-50 px-8 py-5 flex items-center justify-end gap-3 border-t border-slate-100">
            <button type="reset"
                class="rounded-xl px-5 py-2.5 text-sm font-bold text-slate-500 transition-colors hover:bg-slate-200 hover:text-slate-900">
                Discard Changes
            </button>
            <button type="submit"
                class="group relative inline-flex items-center justify-center gap-2 overflow-hidden rounded-xl bg-slate-900 px-6 py-2.5 text-sm font-bold text-white shadow-md transition-all duration-300 hover:bg-blue-600 hover:shadow-lg hover:shadow-blue-500/30 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2">
                <span class="relative">Save Settings</span>
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