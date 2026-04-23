@extends('layouts.app')

@section('title', 'Edit Location')

@section('actions')
    <a href="{{ route('locations.index') }}"
        class="inline-flex items-center gap-2 border border-slate-200 bg-white px-4 py-2 text-[11px] font-mono font-bold text-slate-600 uppercase tracking-widest hover:bg-slate-50 transition-colors">
        ← Back to Locations
    </a>
@endsection

@section('content')
    <div>

        {{-- Page Header --}}
        <div class="mb-5">
            <p class="text-[10px] font-mono font-semibold text-blue-600 uppercase tracking-[0.25em] mb-1">Locations://Edit</p>
            <h1 class="text-xl font-bold text-slate-800 tracking-tight">Edit Location</h1>
            <p class="text-xs text-slate-400 font-mono mt-0.5">Modifying <strong class="text-slate-600">{{ $location->name }}</strong></p>
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

        <form action="{{ route('locations.update', $location) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Form Card --}}
            <div class="bg-white border border-slate-200 mb-4 relative">
                <div class="absolute top-0 left-0 w-1 h-full bg-teal-500"></div>
                <div class="px-5 py-4 ml-1">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="h-2 w-2 bg-teal-500 inline-block"></span>
                        <p class="text-[10px] font-mono font-bold text-teal-600 uppercase tracking-widest">01 // Location Details</p>
                    </div>

                    <div class="space-y-4 mt-1">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1.5">Location Type</label>
                            <input type="text" value="{{ ucfirst($location->type) }} (Read Only)" disabled
                                class="block w-full border border-slate-200 bg-slate-100 py-2.5 px-3 text-sm font-mono text-slate-500 cursor-not-allowed">
                            <p class="mt-1.5 text-[10px] font-mono text-slate-400">Location types cannot be changed after creation.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1.5">Location Name <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $location->name) }}"
                                class="block w-full border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:outline-none py-2.5 px-3 text-sm font-mono text-slate-800 transition-colors"
                                required>
                            @error('name') <p class="mt-1.5 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('locations.index') }}"
                    class="px-5 py-2.5 text-sm font-mono font-bold text-slate-500 hover:text-slate-800 transition-colors border border-slate-200 hover:border-slate-300">
                    Cancel
                </a>
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 text-[11px] font-mono font-bold uppercase tracking-[0.15em] transition-colors border border-blue-700">
                    <span>Save Changes</span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                        <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </form>
    </div>
@endsection
