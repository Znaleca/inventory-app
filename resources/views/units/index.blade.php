@extends('layouts.app')

@section('title', 'Measurement Units')

@section('actions')
{{-- We'll use a modal or simple inline form later, but for now we follow standard CRUD UI --}}
<button type="button" onclick="document.getElementById('new-unit-modal').classList.remove('hidden')"
    class="group relative inline-flex items-center gap-2 overflow-hidden rounded-2xl bg-gradient-to-br from-slate-800 to-slate-900 px-5 py-2.5 text-sm font-bold text-white shadow-[0_8px_16px_-6px_rgba(15,23,42,0.5)] transition-all duration-300 hover:-translate-y-0.5 hover:shadow-[0_12px_20px_-6px_rgba(15,23,42,0.6)] focus:outline-none focus:ring-2 focus:ring-slate-900 focus:ring-offset-2">
    <div class="absolute inset-0 bg-gradient-to-b from-white/10 to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="relative h-4 w-4 transition-transform duration-300 group-hover:rotate-90">
        <path d="M8.75 3.75a.75.75 0 00-1.5 0v3.5h-3.5a.75.75 0 000 1.5h3.5v3.5a.75.75 0 001.5 0v-3.5h3.5a.75.75 0 000-1.5h-3.5v-3.5z" />
    </svg>
    <span class="relative">New Unit</span>
</button>
@endsection

@section('content')

@if(session('success'))
<div class="mb-4 rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-emerald-800 text-sm font-semibold">
    {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="mb-4 rounded-xl bg-rose-50 border border-rose-200 p-4 text-rose-800 text-sm font-semibold">
    {{ session('error') }}
</div>
@endif
@if($errors->any())
<div class="mb-4 rounded-xl bg-rose-50 border border-rose-200 p-4 text-rose-800 text-sm font-semibold">
    <ul class="list-disc pl-5">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="overflow-hidden rounded-[1.5rem] bg-white/80 ring-1 ring-slate-200/50 shadow-[0_8px_30px_-12px_rgba(0,0,0,0.06)] backdrop-blur-xl">

    @if($units->count() > 0)
    <div class="overflow-x-auto p-2">
        <table class="min-w-full text-sm border-separate border-spacing-y-1">
            <thead>
                <tr>
                    <th scope="col" class="whitespace-nowrap px-3 py-2 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">Unit Nomenclature</th>
                    <th scope="col" class="whitespace-nowrap px-3 py-2 text-center text-[10px] font-bold uppercase tracking-widest text-slate-400">Related Items</th>
                    <th scope="col" class="whitespace-nowrap px-3 py-2 text-right text-[10px] font-bold uppercase tracking-widest text-slate-400">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($units as $unit)
                @php
                    $itemCount = \App\Models\Item::where('unit', $unit->name)->count();
                @endphp
                <tr class="group transition-all duration-200 hover:bg-white rounded-xl shadow-sm hover:shadow-md hover:ring-1 hover:ring-slate-200/50">
                    <td class="px-3 py-3 rounded-l-xl w-full">
                        <span class="block text-base font-black text-slate-800 tracking-tight">{{ $unit->name }}</span>
                    </td>
                    <td class="whitespace-nowrap px-3 py-3 text-center">
                        <span class="inline-flex items-center justify-center rounded-lg bg-slate-100/80 px-2.5 py-1.5 text-[11px] font-bold text-slate-600 ring-1 ring-inset ring-slate-500/10">
                            {{ number_format($itemCount) }} Items
                        </span>
                    </td>
                    <td class="whitespace-nowrap px-3 py-3 text-right rounded-r-xl">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('units.edit', $unit) }}" class="inline-flex items-center rounded-lg bg-slate-50 px-3 py-2 text-xs font-bold text-slate-600 ring-1 ring-inset ring-slate-500/20 transition-all hover:bg-slate-800 hover:text-white hover:shadow-md hover:shadow-slate-800/20">Edit</a>
                            @if($itemCount === 0)
                            <form action="{{ route('units.destroy', $unit) }}" method="POST" class="m-0" onsubmit="return confirm('Delete this unit?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center rounded-lg bg-rose-50 px-3 py-2 text-xs font-bold text-rose-600 ring-1 ring-inset ring-rose-500/20 transition-all hover:bg-rose-500 hover:text-white hover:shadow-md hover:shadow-rose-500/20">Delete</button>
                            </form>
                            @else
                            <button disabled class="cursor-not-allowed opacity-50 inline-flex items-center rounded-lg bg-slate-50 px-3 py-2 text-xs font-bold text-slate-400 ring-1 ring-inset ring-slate-400/20" title="Cannot delete while items are using it">Delete</button>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="flex flex-col items-center justify-center py-20">
        <h3 class="font-bold text-slate-700">No units defined yet</h3>
    </div>
    @endif
</div>

{{-- Inline Modal for "New Unit" --}}
<div id="new-unit-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4">
    <div class="bg-white rounded-3xl shadow-xl w-full max-w-md overflow-hidden ring-1 ring-slate-200">
        <form action="{{ route('units.store') }}" method="POST">
            @csrf
            <div class="p-6">
                <h3 class="text-lg font-black tracking-tight text-slate-800 mb-4">Add New Unit</h3>
                <label class="block text-sm font-bold text-slate-700 mb-2">Unit Name</label>
                <input type="text" name="name" required class="block w-full rounded-xl border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm transition-all" placeholder="e.g. bundle, pack, carton">
            </div>
            <div class="bg-slate-50 p-4 border-t border-slate-100 flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('new-unit-modal').classList.add('hidden')" class="px-4 py-2 font-bold text-sm text-slate-500 hover:text-slate-700">Cancel</button>
                <button type="submit" class="rounded-xl bg-slate-900 px-5 py-2 text-sm font-bold text-white hover:bg-indigo-600 transition-colors">Save Unit</button>
            </div>
        </form>
    </div>
</div>
@endsection
