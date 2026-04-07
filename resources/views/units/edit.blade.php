@extends('layouts.app')

@section('title', 'Edit Unit')

@section('actions')
<a href="{{ route('units.index') }}" class="group inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2 text-sm font-semibold text-slate-600 shadow-sm ring-1 ring-inset ring-slate-300 transition-all hover:bg-slate-50 hover:text-slate-900">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4 transition-transform duration-300 group-hover:-translate-x-1">
        <path fill-rule="evenodd" d="M17 10a.75.75 0 01-.75.75H5.66l4.22 4.22a.75.75 0 11-1.06 1.06l-5.5-5.5a.75.75 0 010-1.06l5.5-5.5a.75.75 0 111.06 1.06l-4.22 4.22h10.59a.75.75 0 01.75.75z" clip-rule="evenodd" />
    </svg>
    Back to Units
</a>
@endsection

@section('content')
<div class="mx-auto max-w-2xl">
    <form action="{{ route('units.update', $unit) }}" method="POST" class="overflow-hidden rounded-[2rem] bg-white ring-1 ring-slate-200 shadow-[0_8px_30px_-12px_rgba(0,0,0,0.1)]">
        @csrf
        @method('PUT')

        <div class="border-b border-slate-100 bg-slate-50/50 px-8 py-6">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600 ring-1 ring-inset ring-indigo-500/20">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-6 w-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Edit Unit Identity</h2>
                    <p class="text-sm text-slate-500">Updating the name of this unit will globally update all inventory items associated with it.</p>
                </div>
            </div>
        </div>

        <div class="px-8 py-8">
            <div class="mb-4">
                <label class="mb-2 block text-sm font-bold text-slate-700">Unit Name <span class="text-rose-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $unit->name) }}" class="block w-full rounded-xl border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm transition-all" required autofocus>
                @error('name') <p class="mt-1.5 text-xs font-bold text-rose-500">{{ $message }}</p> @enderror
            </div>

            @php
                $itemCount = \App\Models\Item::where('unit', $unit->name)->count();
            @endphp
            @if($itemCount > 0)
            <div class="mt-6 rounded-2xl bg-amber-50 border border-amber-200/60 p-4">
                <h4 class="text-sm font-bold text-amber-800 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 text-amber-500">
                        <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                    </svg>
                    Caution: Widespread Update
                </h4>
                <p class="mt-1 text-sm font-medium text-amber-700/80">
                    Modifying this name string will immediately alter the visible unit metadata for <strong>{{ number_format($itemCount) }} current inventory items</strong> attached to literal "{{ $unit->name }}".
                </p>
            </div>
            @endif
        </div>

        <div class="bg-slate-50 px-8 py-5 flex justify-end gap-3 border-t border-slate-100">
            <a href="{{ route('units.index') }}" class="rounded-xl px-5 py-2.5 text-sm font-bold text-slate-500 hover:bg-slate-200 hover:text-slate-900">Cancel</a>
            <button type="submit" class="rounded-xl bg-slate-900 px-6 py-2.5 text-sm font-bold text-white shadow-md hover:bg-indigo-600 transition-colors">Apply Global Change</button>
        </div>
    </form>
</div>
@endsection
