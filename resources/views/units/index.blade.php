@extends('layouts.app')

@section('title', 'Units')

@section('actions')
    <a href="{{ route('units.create') }}"
        class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 text-[11px] font-mono font-bold uppercase tracking-[0.15em] transition-colors border border-blue-700">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-3.5 w-3.5">
            <path d="M8.75 3.75a.75.75 0 00-1.5 0v3.5h-3.5a.75.75 0 000 1.5h3.5v3.5a.75.75 0 001.5 0v-3.5h3.5a.75.75 0 000-1.5h-3.5v-3.5z" />
        </svg>
        New_Unit
    </a>
@endsection

@section('content')

{{-- Page Header --}}
<div class="mb-5 flex items-end justify-between">
    <div>
        <p class="text-[10px] font-mono font-semibold text-blue-600 uppercase tracking-[0.25em] mb-1">Inventory://Units</p>
        <h1 class="text-xl font-bold text-slate-800 tracking-tight">Measurement Units</h1>
    </div>
    <span class="text-[10px] font-mono text-slate-400">{{ $units->count() }} records</span>
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
@if($errors->any())
    <div class="mb-4 bg-rose-50 border border-rose-200 relative px-5 py-4">
        <div class="absolute top-0 left-0 w-1 h-full bg-rose-500"></div>
        <ul class="ml-1 space-y-1">
            @foreach($errors->all() as $error)
                <li class="text-sm text-rose-700 font-mono">— {{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- Main Table --}}
<div class="bg-white border border-slate-200 relative overflow-hidden">
    <div class="absolute top-0 left-0 w-1 h-full bg-teal-500"></div>

    @if($units->count() > 0)
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="border-b border-slate-100 bg-slate-50/80">
                    <th scope="col" class="whitespace-nowrap pl-5 pr-3 py-3 text-left text-[10px] font-mono font-bold uppercase tracking-[0.2em] text-slate-400">Unit Name</th>
                    <th scope="col" class="whitespace-nowrap px-3 py-3 text-center text-[10px] font-mono font-bold uppercase tracking-[0.2em] text-slate-400">Related Items</th>
                    <th scope="col" class="whitespace-nowrap px-3 pr-5 py-3 text-right text-[10px] font-mono font-bold uppercase tracking-[0.2em] text-slate-400">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($units as $unit)
                @php $itemCount = \App\Models\Item::where('unit', $unit->name)->count(); @endphp
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="pl-5 pr-3 py-3">
                        <span class="text-base font-black font-mono text-slate-800 tracking-tight">{{ $unit->name }}</span>
                    </td>
                    <td class="whitespace-nowrap px-3 py-3 text-center">
                        <span class="font-mono text-[11px] font-bold text-slate-600 bg-slate-50 border border-slate-200 px-2.5 py-1.5">
                            {{ number_format($itemCount) }}
                        </span>
                    </td>
                    <td class="whitespace-nowrap px-3 pr-5 py-3 text-right">
                        <div class="flex items-center justify-end gap-1.5">
                            <a href="{{ route('units.edit', $unit) }}"
                                class="inline-flex items-center border border-slate-200 bg-slate-50 px-2.5 py-1.5 text-[10px] font-mono font-bold text-slate-600 hover:bg-slate-800 hover:text-white hover:border-slate-800 transition-colors">
                                Edit
                            </a>
                            @if($itemCount === 0)
                                <form action="{{ route('units.destroy', $unit) }}" method="POST" class="m-0"
                                    onsubmit="return confirm('Delete this unit?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center border border-rose-200 bg-rose-50 px-2.5 py-1.5 text-[10px] font-mono font-bold text-rose-600 hover:bg-rose-500 hover:text-white hover:border-rose-500 transition-colors">
                                        Delete
                                    </button>
                                </form>
                            @else
                                <button disabled
                                    class="cursor-not-allowed opacity-40 inline-flex items-center border border-slate-200 bg-slate-50 px-2.5 py-1.5 text-[10px] font-mono font-bold text-slate-400"
                                    title="Cannot delete while items are using it">
                                    Delete
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="flex flex-col items-center justify-center py-20 text-center ml-1">
        <div class="h-14 w-14 border border-slate-200 bg-slate-50 flex items-center justify-center text-slate-400 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-7 w-7">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0 1 18 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375" />
            </svg>
        </div>
        <p class="font-mono text-[10px] text-slate-400 uppercase tracking-widest mb-1">// No records found</p>
        <p class="text-sm font-semibold text-slate-500 mt-1">No units defined yet.</p>
        <a href="{{ route('units.create') }}"
            class="mt-6 inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 text-[11px] font-mono font-bold uppercase tracking-widest transition-colors border border-blue-700">
            + New Unit
        </a>
    </div>
    @endif
</div>



@endsection
