@extends('layouts.app')

@section('title', 'Add Staff Member')

@section('actions')
    <a href="{{ route('staff.index') }}"
        class="inline-flex items-center gap-2 border border-slate-200 bg-white px-4 py-2 text-[11px] font-mono font-bold text-slate-600 uppercase tracking-widest hover:bg-slate-50 transition-colors">
        ← Back to Directory
    </a>
@endsection

@section('content')
    <div>

        {{-- Page Header --}}
        <div class="mb-5">
            <p class="text-[10px] font-mono font-semibold text-violet-600 uppercase tracking-[0.25em] mb-1">Staff://New</p>
            <h1 class="text-xl font-bold text-slate-800 tracking-tight">Add Staff Member</h1>
            <p class="text-xs text-slate-400 font-mono mt-0.5">Add a Programmer or tech support, or staff to the directory.
            </p>
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

        <form action="{{ route('staff.store') }}" method="POST">
            @csrf

            {{-- Personal Information --}}
            <div class="bg-white border border-slate-200 relative mb-4">
                <div class="absolute top-0 left-0 w-1 h-full bg-violet-500"></div>
                <div class="ml-1">
                    <div class="px-5 py-4 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <span class="h-2 w-2 bg-violet-500 inline-block"></span>
                            <p class="text-[10px] font-mono font-bold text-violet-600 uppercase tracking-widest">01 //
                                Personal Information</p>
                        </div>
                    </div>
                    <div class="px-5 py-5 grid grid-cols-1 gap-4 sm:grid-cols-3">
                        <div class="sm:col-span-1">
                            <label for="title" class="block text-sm font-bold text-slate-700 mb-1.5">Title <span
                                    class="font-normal text-slate-400">(Optional)</span></label>
                            <input type="text" id="title" name="title" value="{{ old('title') }}"
                                placeholder="Engr., Sir, Ma'am"
                                class="block w-full border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:outline-none py-2.5 px-3 text-sm text-slate-800 transition-colors">
                            @error('title') <p class="mt-1 text-xs font-mono font-bold text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label for="name" class="block text-sm font-bold text-slate-700 mb-1.5">Full Name <span
                                    class="text-rose-500">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                placeholder="e.g. John Doe"
                                class="block w-full border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:outline-none py-2.5 px-3 text-sm text-slate-800 transition-colors">
                            @error('name') <p class="mt-1 text-xs font-mono font-bold text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Role Details --}}
            <div class="bg-white border border-slate-200 relative mb-4">
                <div class="absolute top-0 left-0 w-1 h-full bg-slate-400"></div>
                <div class="ml-1">
                    <div class="px-5 py-4 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <span class="h-2 w-2 bg-slate-400 inline-block"></span>
                            <p class="text-[10px] font-mono font-bold text-slate-500 uppercase tracking-widest">02 // Role
                                Details</p>
                        </div>
                    </div>
                    <div class="px-5 py-5 grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="type" class="block text-sm font-bold text-slate-700 mb-1.5">Type <span
                                    class="text-rose-500">*</span></label>
                            <select id="type" name="type" required
                                class="block w-full border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:outline-none py-2.5 px-3 text-sm text-slate-800 transition-colors">
                                <option value="programmer" {{ old('type') === 'programmer' ? 'selected' : '' }}>Programmer</option>
                                <option value="tech support" {{ old('type') === 'tech support' ? 'selected' : '' }}>Tech Support</option>
                                <option value="supervisor" {{ old('type') === 'supervisor' ? 'selected' : '' }}>Supervisor</option>
                                <option value="head" {{ old('type') === 'head' ? 'selected' : '' }}>Head</option>
                            </select>
                            @error('type') <p class="mt-1 text-xs font-mono font-bold text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('staff.index') }}"
                    class="px-5 py-2.5 text-sm font-mono font-bold text-slate-500 hover:text-slate-800 transition-colors border border-slate-200 hover:border-slate-300">
                    Cancel
                </a>
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-slate-900 px-6 py-2.5 text-[11px] font-mono font-bold text-white uppercase tracking-[0.15em] hover:bg-slate-700 transition-colors border border-slate-900">
                    Add to Directory
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                        <path fill-rule="evenodd"
                            d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </form>
    </div>
@endsection