@extends('layouts.app')

@section('title', 'Profile Settings')

@section('actions')
<a href="{{ route('profile.show') }}"
    class="inline-flex items-center gap-2 border border-sky-100 bg-white px-4 py-2 text-[11px] font-mono font-bold text-slate-600 uppercase tracking-widest hover:bg-sky-50 transition-colors">
    ← View Profile
</a>
@endsection

@section('content')
<div class="mx-auto max-w-3xl">

    <div class="bg-white rounded-2xl overflow-hidden border border-sky-100 mb-5">
        {{-- Page Header --}}
        <div class="p-6 flex items-center justify-between shrink-0">
            <div>
                <p class="font-mono text-[10px] font-bold uppercase tracking-widest mb-1 text-sky-500">System://Profile//Settings</p>
                <h3 class="text-xl font-black text-[#0f172a] tracking-tight">Account Settings</h3>
                <p class="text-xs text-slate-400 font-mono mt-1">Manage your name, photo, and password.</p>
            </div>
        </div>
    </div>

    {{-- Success Banner --}}
    @if(session('success'))
    <div class="mb-5 bg-emerald-50 border border-emerald-200 relative px-5 py-3 flex items-center gap-3">
        <div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-emerald-400 to-emerald-600"></div>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4 text-emerald-500 ml-1 shrink-0">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
        </svg>
        <p class="text-sm font-mono text-emerald-700">{{ session('success') }}</p>
    </div>
    @endif

    {{-- Errors --}}
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

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        {{-- ======================== --}}
        {{-- SECTION 1: Identity     --}}
        {{-- ======================== --}}
        <div class="bg-white border border-sky-100 mb-4 relative">
            <div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-sky-400 to-sky-600"></div>
            <div class="px-5 py-4">
                <div class="flex items-center gap-2 mb-4">
                    <span class="h-2 w-2 bg-sky-500 inline-block"></span>
                    <p class="text-[10px] font-mono font-bold text-sky-500 uppercase tracking-widest">01 // Account Identity</p>
                </div>

                {{-- Avatar + Name --}}
                <div class="flex items-start gap-5 mb-5">
                    {{-- Avatar --}}
                    <div x-data="{ preview: null }" class="flex flex-col items-center gap-2 shrink-0">
                        <div class="h-20 w-20 bg-slate-800 border-2 border-sky-100 overflow-hidden flex items-center justify-center relative">
                            <template x-if="preview">
                                <img :src="preview" class="h-full w-full object-cover">
                            </template>
                            <template x-if="!preview">
                                @if($user->profile_photo_url)
                                    <img src="{{ $user->profile_photo_url }}" class="h-full w-full object-cover">
                                @else
                                    <span class="text-2xl font-black font-mono text-white">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                @endif
                            </template>
                        </div>
                        <label for="photo"
                            class="cursor-pointer text-[10px] font-mono font-bold text-sky-500 hover:text-sky-700 uppercase tracking-widest transition-colors">
                            Change Photo
                        </label>
                        <input type="file" id="photo" name="photo" accept="image/*" class="sr-only"
                            x-on:change="preview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null">
                        @error('photo') <p class="text-[10px] font-mono font-bold text-rose-500 mt-0.5">{{ $message }}</p> @enderror
                    </div>

                    {{-- Identity Fields --}}
                    <div class="flex-1 space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-bold text-slate-700 mb-1.5">Full Name <span class="text-rose-500">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                                class="block w-full border border-sky-100 bg-sky-50 focus:bg-white focus:border-sky-500 focus:outline-none py-2.5 px-3 text-sm font-mono text-[#0f172a] transition-colors"
                                placeholder="Your full name">
                            @error('name') <p class="mt-1.5 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1.5">Bio ID / Username</label>
                            <input type="text" value="{{ $user->bio_id }}" readonly
                                class="block w-full border border-slate-100 bg-sky-50 py-2.5 px-3 text-sm font-mono text-slate-400 cursor-not-allowed">
                            <p class="mt-1.5 text-[10px] font-mono text-amber-600">⚠ Only an administrator can change your Bio ID.</p>
                        </div>
                    </div>
                </div>

                {{-- Role Badge --}}
                <div class="border-t border-dashed border-slate-100 pt-4 flex items-center gap-3">
                    <p class="text-[10px] font-mono font-bold text-slate-400 uppercase tracking-widest">System Role</p>
                    <span class="text-[9px] font-mono font-bold uppercase tracking-wider px-2 py-1 border
                        {{ $user->role === 'admin' ? 'text-sky-500 bg-sky-50 border-sky-200' : 'text-slate-500 bg-sky-50 border-sky-100' }}">
                        {{ $user->role }}
                    </span>
                    <span class="text-[10px] font-mono text-slate-400">Member since {{ $user->created_at->format('M Y') }}</span>
                </div>
            </div>
        </div>

        {{-- ======================== --}}
        {{-- SECTION 2: Password     --}}
        {{-- ======================== --}}
        <div class="bg-white border border-sky-100 mb-4 relative">
            <div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-rose-400 to-rose-600"></div>
            <div class="px-5 py-4">
                <div class="flex items-center gap-2 mb-1">
                    <span class="h-2 w-2 bg-rose-500 inline-block"></span>
                    <p class="text-[10px] font-mono font-bold text-rose-600 uppercase tracking-widest">02 // Update Password</p>
                </div>
                <p class="text-[10px] font-mono text-slate-400 mb-4 ml-4">Leave blank if you don't want to change your password.</p>

                <div class="space-y-4">
                    <div>
                        <label for="current_password" class="block text-sm font-bold text-slate-700 mb-1.5">Current Password</label>
                        <input type="password" id="current_password" name="current_password"
                            placeholder="Enter your current password to authorize changes"
                            class="block w-full border border-sky-100 bg-sky-50 focus:bg-white focus:border-rose-400 focus:outline-none py-2.5 px-3 text-sm font-mono text-[#0f172a] transition-colors placeholder:text-slate-400">
                        @error('current_password') <p class="mt-1.5 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="password" class="block text-sm font-bold text-slate-700 mb-1.5">New Password</label>
                            <input type="password" id="password" name="password"
                                placeholder="Enter new password"
                                class="block w-full border border-sky-100 bg-sky-50 focus:bg-white focus:border-rose-400 focus:outline-none py-2.5 px-3 text-sm font-mono text-[#0f172a] transition-colors placeholder:text-slate-400">
                            @error('password') <p class="mt-1.5 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-bold text-slate-700 mb-1.5">Confirm New Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                placeholder="Re-type new password"
                                class="block w-full border border-sky-100 bg-sky-50 focus:bg-white focus:border-rose-400 focus:outline-none py-2.5 px-3 text-sm font-mono text-[#0f172a] transition-colors placeholder:text-slate-400">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Submit --}}
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('profile.show') }}"
                class="px-5 py-2.5 text-sm font-mono font-bold text-slate-500 hover:text-[#0f172a] transition-colors border border-sky-100 hover:border-slate-300">
                Cancel
            </a>
            <button type="submit"
                class="inline-flex items-center gap-2 bg-[#0f172a] hover:bg-slate-700 text-white px-6 py-2.5 text-[11px] font-mono font-bold uppercase tracking-[0.15em] transition-colors border border-[#0f172a]">
                <span>Save Settings</span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                    <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </form>
</div>
@endsection