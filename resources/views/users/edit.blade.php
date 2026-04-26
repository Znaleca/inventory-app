@extends('layouts.app')

@section('title', 'Edit User Account')

@section('actions')
    <a href="{{ route('users.index') }}"
        class="inline-flex items-center gap-2 border border-sky-100 bg-white px-4 py-2 text-[11px] font-mono font-bold text-slate-600 uppercase tracking-widest hover:bg-sky-50 transition-colors">
        ← Back to Users
    </a>
@endsection

@section('content')
<div class="bg-white rounded-2xl overflow-hidden border border-sky-100">

    {{-- Page Header --}}
    <div class="p-6 border-b border-sky-100 flex items-center justify-between shrink-0 mb-6">
        <div>
        <p class="font-mono text-[10px] font-bold uppercase tracking-widest text-sky-500 mb-1">System://Users//{{ $user->id }}//Edit</p>
        <h3 class="text-xl font-black text-[#0f172a] tracking-tight">{{ $user->name }}</h3>
        <div class="flex items-center gap-2 mt-1">
            @if($user->role === 'admin')
            <span class="inline-flex items-center gap-1.5 border border-indigo-200 bg-indigo-50 px-2 py-0.5 text-[9px] font-mono font-bold tracking-widest uppercase text-indigo-700">
                <span class="h-1.5 w-1.5 bg-indigo-500"></span>Administrator
            </span>
            @else
            <span class="inline-flex items-center gap-1.5 border border-sky-100 bg-sky-50 px-2 py-0.5 text-[9px] font-mono font-bold tracking-widest uppercase text-slate-600">
                <span class="h-1.5 w-1.5 bg-slate-400"></span>Standard_User
            </span>
            @endif
            <span class="text-xs font-mono text-slate-400">· Edit Mode</span>
        </div>
        </div>
    </div>

    <div class="p-6 pt-0">

    @if ($errors->any())
        <div class="mb-5 bg-rose-50 border border-rose-200 relative px-5 py-4">
            <div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-rose-400 to-rose-600"></div>
            <p class="font-mono text-[10px] text-rose-600 uppercase tracking-widest font-bold mb-2">// Errors</p>
            <ul class="space-y-1">
                @foreach ($errors->all() as $error)
                    <li class="text-sm text-rose-700">— {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Profile Information --}}
        <div class="bg-white rounded-2xl overflow-hidden border border-sky-100 relative mb-4">
            <div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-slate-600 to-slate-800"></div>
            <div class="px-5 py-4 border-b border-sky-100">
                    <div class="flex items-center gap-2">
                        <span class="h-2 w-2 bg-slate-800 inline-block"></span>
                        <p class="text-[10px] font-mono font-bold text-slate-600 uppercase tracking-widest">01 // Profile Information</p>
                    </div>
                </div>
                <div class="px-5 py-5 space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-bold text-slate-700 mb-1.5">Full Name <span class="text-rose-500">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                            class="block w-full border border-sky-100 bg-sky-50 focus:bg-white focus:border-sky-500 focus:outline-none py-2.5 px-3 text-sm text-[#0f172a] transition-colors">
                        @error('name') <p class="mt-1 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror
                    </div>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="bio_id" class="block text-sm font-bold text-slate-700 mb-1.5">Bio ID / Username <span class="text-rose-500">*</span></label>
                            <input type="text" id="bio_id" name="bio_id" value="{{ old('bio_id', $user->bio_id) }}" required
                                class="block w-full border border-sky-100 bg-sky-50 focus:bg-white focus:border-sky-500 focus:outline-none py-2.5 px-3 text-sm text-[#0f172a] font-mono transition-colors">
                            @error('bio_id') <p class="mt-1 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="role" class="block text-sm font-bold text-slate-700 mb-1.5">Role <span class="text-rose-500">*</span></label>
                            <select id="role" name="role" required
                                class="block w-full border border-sky-100 bg-sky-50 focus:bg-white focus:border-sky-500 focus:outline-none py-2.5 px-3 text-sm text-[#0f172a] transition-colors">
                                <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>Standard User</option>
                                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Administrator</option>
                            </select>
                            @error('role') <p class="mt-1 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Reset Password --}}
        <div class="bg-white rounded-2xl overflow-hidden border border-sky-100 relative mb-4">
            <div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-sky-400 to-sky-600"></div>
            <div class="px-5 py-4 border-b border-sky-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="h-2 w-2 bg-indigo-500 inline-block"></span>
                        <p class="text-[10px] font-mono font-bold text-indigo-600 uppercase tracking-widest">02 // Reset Password</p>
                    </div>
                    <span class="text-[10px] font-mono text-slate-400">Leave blank to keep current</span>
                </div>
                <div class="px-5 py-5 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="password" class="block text-sm font-bold text-slate-700 mb-1.5">New Password</label>
                        <input type="password" id="password" name="password" placeholder="••••••••"
                            class="block w-full border border-sky-100 bg-sky-50 focus:bg-white focus:border-sky-500 focus:outline-none py-2.5 px-3 text-sm text-[#0f172a] transition-colors">
                        @error('password') <p class="mt-1 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-bold text-slate-700 mb-1.5">Confirm New Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="••••••••"
                            class="block w-full border border-sky-100 bg-sky-50 focus:bg-white focus:border-sky-500 focus:outline-none py-2.5 px-3 text-sm text-[#0f172a] transition-colors">
                    </div>
                </div>
            </div>
        </div>

        {{-- Submit --}}
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('users.index') }}"
                class="px-5 py-2.5 text-sm font-mono font-bold text-slate-500 hover:text-[#0f172a] transition-colors border border-sky-100 hover:border-slate-300">
                Cancel
            </a>
            <button type="submit"
                class="inline-flex items-center gap-2 bg-[#0f172a] px-6 py-2.5 text-[11px] font-mono font-bold text-white uppercase tracking-[0.15em] hover:bg-slate-700 transition-colors border border-[#0f172a]">
                Save Changes
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                    <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </form>
    </div>
</div>
@endsection