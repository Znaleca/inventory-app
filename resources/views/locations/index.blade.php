@extends('layouts.app')

@section('title', 'Locations')

@section('actions')
    <a href="{{ route('locations.create') }}"
        class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 text-[11px] font-mono font-bold uppercase tracking-[0.15em] transition-colors border border-blue-700">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-3.5 w-3.5">
            <path d="M8.75 3.75a.75.75 0 00-1.5 0v3.5h-3.5a.75.75 0 000 1.5h3.5v3.5a.75.75 0 001.5 0v-3.5h3.5a.75.75 0 000-1.5h-3.5v-3.5z" />
        </svg>
        New_Location
    </a>
@endsection

@section('content')

{{-- Page Header --}}
<div class="mb-5 flex items-end justify-between">
    <div>
        <p class="text-[10px] font-mono font-semibold text-blue-600 uppercase tracking-[0.25em] mb-1">Inventory://Locations</p>
        <h1 class="text-xl font-bold text-slate-800 tracking-tight">Location Manager</h1>
    </div>
    <span class="text-[10px] font-mono text-slate-400">
        {{ $storages->count() }} storages, {{ $sections->count() }} sections
    </span>
</div>

@if(session('success'))
    <div class="mb-4 bg-emerald-50 border border-emerald-200 relative px-5 py-3">
        <div class="absolute top-0 left-0 w-1 h-full bg-emerald-500"></div>
        <p class="text-sm font-mono font-bold text-emerald-700 ml-1">{{ session('success') }}</p>
    </div>
@endif
@if(session('error'))
    <div class="mb-4 bg-rose-50 border border-rose-200 relative px-5 py-3">
        <div class="absolute top-0 left-0 w-1 h-full bg-rose-500"></div>
        <p class="text-sm font-mono font-bold text-rose-700 ml-1">{{ session('error') }}</p>
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Storages Column --}}
    <div class="bg-white border border-slate-200 relative overflow-hidden flex flex-col h-full">
        <div class="absolute top-0 left-0 w-1 h-full bg-emerald-500"></div>
        
        <div class="px-5 py-4 border-b border-dashed border-slate-100 bg-slate-50/50 ml-1">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="h-2 w-2 bg-emerald-500 inline-block"></span>
                    <h3 class="text-[10px] font-mono font-bold text-emerald-600 uppercase tracking-widest">Storage Locations</h3>
                </div>
                <span class="font-mono text-[10px] font-bold text-slate-400 bg-slate-100 border border-slate-200 px-2 py-0.5">
                    {{ $storages->count() }}
                </span>
            </div>
        </div>

        @if($storages->count() > 0)
        <table class="w-full text-sm ml-1">
            <tbody class="divide-y divide-slate-50">
                @foreach($storages as $loc)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-5 py-3.5">
                        <span class="text-sm font-bold font-mono text-slate-800 tracking-tight">{{ $loc->name }}</span>
                    </td>
                    <td class="pr-5 py-3.5 text-right whitespace-nowrap">
                        <div class="flex items-center justify-end gap-1.5">
                            <a href="{{ route('locations.edit', $loc) }}"
                                class="inline-flex items-center border border-slate-200 bg-slate-50 px-2.5 py-1.5 text-[10px] font-mono font-bold text-slate-600 hover:bg-slate-800 hover:text-white hover:border-slate-800 transition-colors">
                                Edit
                            </a>
                            <form action="{{ route('locations.destroy', $loc) }}" method="POST" class="m-0"
                                onsubmit="return confirm('Delete this storage location?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center border border-rose-200 bg-rose-50 px-2.5 py-1.5 text-[10px] font-mono font-bold text-rose-600 hover:bg-rose-500 hover:text-white hover:border-rose-500 transition-colors">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="flex-1 flex flex-col items-center justify-center py-12 text-center opacity-70 ml-1">
            <p class="font-mono text-[10px] text-slate-400 uppercase tracking-widest mb-1">// Empty</p>
            <p class="text-xs font-semibold text-slate-500">No storage locations</p>
        </div>
        @endif
    </div>

    {{-- Sections Column --}}
    <div class="bg-white border border-slate-200 relative overflow-hidden flex flex-col h-full">
        <div class="absolute top-0 left-0 w-1 h-full bg-indigo-500"></div>
        
        <div class="px-5 py-4 border-b border-dashed border-slate-100 bg-slate-50/50 ml-1">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="h-2 w-2 bg-indigo-500 inline-block"></span>
                    <h3 class="text-[10px] font-mono font-bold text-indigo-600 uppercase tracking-widest">Sections / Bins</h3>
                </div>
                <span class="font-mono text-[10px] font-bold text-slate-400 bg-slate-100 border border-slate-200 px-2 py-0.5">
                    {{ $sections->count() }}
                </span>
            </div>
        </div>

        @if($sections->count() > 0)
        <table class="w-full text-sm ml-1">
            <tbody class="divide-y divide-slate-50">
                @foreach($sections as $loc)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-5 py-3.5">
                        <span class="text-sm font-bold font-mono text-slate-800 tracking-tight">{{ $loc->name }}</span>
                    </td>
                    <td class="pr-5 py-3.5 text-right whitespace-nowrap">
                        <div class="flex items-center justify-end gap-1.5">
                            <a href="{{ route('locations.edit', $loc) }}"
                                class="inline-flex items-center border border-slate-200 bg-slate-50 px-2.5 py-1.5 text-[10px] font-mono font-bold text-slate-600 hover:bg-slate-800 hover:text-white hover:border-slate-800 transition-colors">
                                Edit
                            </a>
                            <form action="{{ route('locations.destroy', $loc) }}" method="POST" class="m-0"
                                onsubmit="return confirm('Delete this section?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center border border-rose-200 bg-rose-50 px-2.5 py-1.5 text-[10px] font-mono font-bold text-rose-600 hover:bg-rose-500 hover:text-white hover:border-rose-500 transition-colors">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="flex-1 flex flex-col items-center justify-center py-12 text-center opacity-70 ml-1">
            <p class="font-mono text-[10px] text-slate-400 uppercase tracking-widest mb-1">// Empty</p>
            <p class="text-xs font-semibold text-slate-500">No sections defined</p>
        </div>
        @endif
    </div>

</div>

@endsection
