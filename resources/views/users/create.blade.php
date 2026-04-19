@extends('layouts.app')

@section('title', 'Create User Account')

@section('actions')
    <a href="{{ route('users.index') }}"
        class="inline-flex items-center gap-2 border border-slate-200 bg-white px-4 py-2 text-[11px] font-mono font-bold text-slate-600 uppercase tracking-widest hover:bg-slate-50 transition-colors">
        ← Back to Users
    </a>
@endsection

@section('content')
<div class="mx-auto max-w-2xl">

    {{-- Page Header --}}
    <div class="mb-5">
        <p class="text-[10px] font-mono font-semibold text-slate-600 uppercase tracking-[0.25em] mb-1">Users://New</p>
        <h1 class="text-xl font-bold text-slate-800 tracking-tight">New User Account</h1>
        <p class="text-xs text-slate-400 font-mono mt-0.5">Grant system access to a new staff member.</p>
    </div>

    @if ($errors->any())
        <div class="mb-5 bg-rose-50 border border-rose-200 relative px-5 py-4">
            <div class="absolute top-0 left-0 w-1 h-full bg-rose-500"></div>
            <p class="font-mono text-[10px] text-rose-600 uppercase tracking-widest font-bold mb-2 ml-1">// Errors</p>
            <ul class="ml-1 space-y-1">
                @foreach ($errors->all() as $error)
                    <li class="text-sm text-rose-700">— {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('users.store') }}" method="POST">
        @csrf

        {{-- Profile Information --}}
        <div class="bg-white border border-slate-200 relative mb-4">
            <div class="absolute top-0 left-0 w-1 h-full bg-slate-800"></div>
            <div class="ml-1">
                <div class="px-5 py-4 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <span class="h-2 w-2 bg-slate-800 inline-block"></span>
                        <p class="text-[10px] font-mono font-bold text-slate-600 uppercase tracking-widest">01 // Profile Information</p>
                    </div>
                </div>
                <div class="px-5 py-5 space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-bold text-slate-700 mb-1.5">Full Name <span class="text-rose-500">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus placeholder="e.g. Dr. John Doe"
                            class="block w-full border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:outline-none py-2.5 px-3 text-sm text-slate-800 transition-colors">
                        @error('name') <p class="mt-1 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror
                    </div>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="bio_id" class="block text-sm font-bold text-slate-700 mb-1.5">Bio ID / Username <span class="text-rose-500">*</span></label>
                            <input type="text" id="bio_id" name="bio_id" value="{{ old('bio_id') }}" required placeholder="e.g. 0123"
                                class="block w-full border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:outline-none py-2.5 px-3 text-sm text-slate-800 font-mono transition-colors">
                            @error('bio_id') <p class="mt-1 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="role" class="block text-sm font-bold text-slate-700 mb-1.5">Role <span class="text-rose-500">*</span></label>
                            <select id="role" name="role" required
                                class="block w-full border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:outline-none py-2.5 px-3 text-sm text-slate-800 transition-colors">
                                <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>Standard User</option>
                                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrator</option>
                            </select>
                            @error('role') <p class="mt-1 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Security Settings --}}
        <div class="bg-white border border-slate-200 relative mb-4">
            <div class="absolute top-0 left-0 w-1 h-full bg-indigo-500"></div>
            <div class="ml-1">
                <div class="px-5 py-4 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <span class="h-2 w-2 bg-indigo-500 inline-block"></span>
                        <p class="text-[10px] font-mono font-bold text-indigo-600 uppercase tracking-widest">02 // Security Settings</p>
                    </div>
                </div>
                <div class="px-5 py-5 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="password" class="block text-sm font-bold text-slate-700 mb-1.5">Password <span class="text-rose-500">*</span></label>
                        <input type="password" id="password" name="password" required placeholder="••••••••"
                            class="block w-full border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:outline-none py-2.5 px-3 text-sm text-slate-800 transition-colors">
                        @error('password') <p class="mt-1 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-bold text-slate-700 mb-1.5">Confirm Password <span class="text-rose-500">*</span></label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="••••••••"
                            class="block w-full border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:outline-none py-2.5 px-3 text-sm text-slate-800 transition-colors">
                    </div>
                </div>
            </div>
        </div>

        {{-- Submit --}}
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('users.index') }}"
                class="px-5 py-2.5 text-sm font-mono font-bold text-slate-500 hover:text-slate-800 transition-colors border border-slate-200 hover:border-slate-300">
                Cancel
            </a>
            <button type="submit"
                class="inline-flex items-center gap-2 bg-slate-900 px-6 py-2.5 text-[11px] font-mono font-bold text-white uppercase tracking-[0.15em] hover:bg-slate-700 transition-colors border border-slate-900">
                Create Account
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                    <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </form>
</div>
@endsection