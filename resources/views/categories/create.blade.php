@extends('layouts.app')

@section('title', 'Create Category')

@section('actions')
    <a href="{{ route('categories.index') }}"
        class="inline-flex items-center gap-2 border border-slate-200 bg-white px-4 py-2 text-[11px] font-mono font-bold text-slate-600 uppercase tracking-widest hover:bg-slate-50 transition-colors">
        ← Back to Categories
    </a>
@endsection

@section('content')
    <div>

        {{-- Page Header --}}
        <div class="mb-5">
            <p class="text-[10px] font-mono font-semibold text-blue-600 uppercase tracking-[0.25em] mb-1">Categories://Create</p>
            <h1 class="text-xl font-bold text-slate-800 tracking-tight">Create New Category</h1>
            <p class="text-xs text-slate-400 font-mono mt-0.5">Group your inventory items by defining a new classification.</p>
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

        <form action="{{ route('categories.store') }}" method="POST"
            x-data="{ itemType: '{{ old('item_type', 'consumable') }}' }">
            @csrf

            {{-- ======================== --}}
            {{-- SECTION 1: Type         --}}
            {{-- ======================== --}}
            <div class="bg-white border border-slate-200 mb-4 relative">
                <div class="absolute top-0 left-0 w-1 h-full bg-blue-500"></div>
                <div class="px-5 py-4 ml-1">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="h-2 w-2 bg-blue-500 inline-block"></span>
                        <p class="text-[10px] font-mono font-bold text-blue-600 uppercase tracking-widest">01 // Category Type</p>
                    </div>

                    <input type="hidden" name="item_type" :value="itemType">

                    <div class="grid grid-cols-2 gap-3 mt-1">
                        <label @click="itemType = 'consumable'"
                            :class="itemType === 'consumable' ? 'border-indigo-500 bg-indigo-50' : 'border-slate-200 bg-slate-50 hover:bg-slate-100'"
                            class="flex items-center gap-3 border p-3 cursor-pointer transition-colors">
                            <input type="radio" name="_type_radio" value="consumable" class="accent-indigo-600 w-4 h-4" :checked="itemType === 'consumable'">
                            <div>
                                <p class="text-sm font-bold text-slate-800">Consumable</p>
                                <p class="text-[10px] font-mono text-slate-500 mt-0.5">Supplies, medicines, etc.</p>
                            </div>
                        </label>
                        <label @click="itemType = 'device'"
                            :class="itemType === 'device' ? 'border-violet-500 bg-violet-50' : 'border-slate-200 bg-slate-50 hover:bg-slate-100'"
                            class="flex items-center gap-3 border p-3 cursor-pointer transition-colors">
                            <input type="radio" name="_type_radio" value="device" class="accent-violet-600 w-4 h-4" :checked="itemType === 'device'">
                            <div>
                                <p class="text-sm font-bold text-slate-800">Device / Equipment</p>
                                <p class="text-[10px] font-mono text-slate-500 mt-0.5">Tracked with serial numbers.</p>
                            </div>
                        </label>
                    </div>
                    @error('item_type') <p class="mt-2 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- ======================== --}}
            {{-- SECTION 2: Details      --}}
            {{-- ======================== --}}
            <div class="bg-white border border-slate-200 mb-4 relative">
                <div class="absolute top-0 left-0 w-1 h-full bg-sky-500"></div>
                <div class="px-5 py-4 ml-1">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="h-2 w-2 bg-sky-500 inline-block"></span>
                        <p class="text-[10px] font-mono font-bold text-sky-600 uppercase tracking-widest">02 // Category Details</p>
                    </div>

                    <div class="space-y-4 mt-1">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1.5">Category Name <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                class="block w-full border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:outline-none py-2.5 px-3 text-sm font-mono text-slate-800 transition-colors"
                                required placeholder="e.g. Surgical Instruments, Printers">
                            @error('name') <p class="mt-1.5 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1.5">Description <span class="font-normal text-slate-400">(Optional)</span></label>
                            <textarea name="description" rows="3"
                                class="block w-full border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:outline-none py-2.5 px-3 text-sm font-mono text-slate-800 transition-colors placeholder:text-slate-400"
                                placeholder="Briefly describe what items belong in this category...">{{ old('description') }}</textarea>
                            @error('description') <p class="mt-1.5 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('categories.index') }}"
                    class="px-5 py-2.5 text-sm font-mono font-bold text-slate-500 hover:text-slate-800 transition-colors border border-slate-200 hover:border-slate-300">
                    Cancel
                </a>
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 text-[11px] font-mono font-bold uppercase tracking-[0.15em] transition-colors border border-blue-700">
                    <span>Save Category</span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                        <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </form>
    </div>
@endsection