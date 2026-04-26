@extends('layouts.app')

@section('title', 'New Location')

@section('actions')
    <a href="{{ route('locations.index') }}"
        class="inline-flex items-center gap-2 border border-sky-100 bg-white px-4 py-2 text-[11px] font-mono font-bold text-slate-600 uppercase tracking-widest hover:bg-sky-50 transition-colors">
        ← Back to Locations
    </a>
@endsection

@section('content')
    <div class="bg-white rounded-2xl overflow-hidden border border-sky-100">

        {{-- Page Header --}}
        <div class="p-6 border-b border-sky-100 bg-white flex items-center justify-between shrink-0 mb-6">
            <div>
                <p class="font-mono text-[10px] font-bold uppercase tracking-widest text-sky-500 mb-1">Locations://Create</p>
                <h3 class="text-xl font-black text-[#0f172a] tracking-tight">New Tracking Location</h3>
                <p class="text-xs text-slate-400 font-mono mt-1">Define where inventory items can be physically stored.</p>
            </div>
        </div>

        <div class="p-6 pt-0">

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

        <form action="{{ route('locations.store') }}" method="POST">
            @csrf

            {{-- Form Card --}}
            <div class="bg-white border border-sky-100 mb-4 relative">
                <div class="absolute top-0 left-0 w-1 h-full bg-teal-500"></div>
                <div class="px-5 py-4 ml-1">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="h-2 w-2 bg-teal-500 inline-block"></span>
                        <p class="text-[10px] font-mono font-bold text-teal-600 uppercase tracking-widest">01 // Location Details</p>
                    </div>

                    <div class="space-y-4 mt-1">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1.5">Location Type <span class="text-rose-500">*</span></label>
                            <select name="type" required
                                class="block w-full border border-sky-100 bg-sky-50 focus:bg-white focus:border-sky-500 focus:outline-none py-2.5 px-3 text-sm font-mono text-[#0f172a] transition-colors">
                                <option value="storage">Storage Location (e.g. Main Warehouse, Stock Room)</option>
                                <option value="section">Section / Bin (e.g. Shelf B2, Cabinet 3)</option>
                            </select>
                            <p class="mt-1.5 text-[10px] font-mono text-slate-400">Classify the scale of this physical space.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1.5">Location Name <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                class="block w-full border border-sky-100 bg-sky-50 focus:bg-white focus:border-sky-500 focus:outline-none py-2.5 px-3 text-sm font-mono text-[#0f172a] transition-colors"
                                required placeholder="e.g. Storage Area 1">
                            @error('name') <p class="mt-1.5 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('locations.index') }}"
                    class="px-5 py-2.5 text-sm font-mono font-bold text-sky-500 hover:text-[#0f172a] transition-colors border border-sky-100 hover:border-slate-300">
                    Cancel
                </a>
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-sky-500 hover:bg-sky-600 text-white px-6 py-2.5 text-[11px] font-mono font-bold uppercase tracking-[0.15em] transition-colors border border-blue-700">
                    <span>Save Location</span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                        <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </form>
        </div>
    </div>
@endsection
