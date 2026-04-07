@extends('layouts.app')

@section('title', $disposalType === 'new' ? 'Dispose Expired Items' : 'Dispose Used Items')

@section('actions')
<a href="{{ route('items.show', $item) }}"
    class="group inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2 text-sm font-semibold text-slate-600 shadow-sm ring-1 ring-inset ring-slate-300 transition-all hover:bg-slate-50 hover:text-slate-900">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
        class="h-4 w-4 transition-transform duration-300 group-hover:-translate-x-1">
        <path fill-rule="evenodd"
            d="M17 10a.75.75 0 01-.75.75H5.66l4.22 4.22a.75.75 0 11-1.06 1.06l-5.5-5.5a.75.75 0 010-1.06l5.5-5.5a.75.75 0 111.06 1.06l-4.22 4.22h10.59a.75.75 0 01.75.75z"
            clip-rule="evenodd" />
    </svg>
    Back to Item
</a>
@endsection

@section('content')
<div class="mx-auto max-w-2xl">
    <form action="{{ route('disposals.store') }}" method="POST"
        class="overflow-hidden rounded-[2rem] bg-white ring-1 ring-slate-200 shadow-[0_8px_30px_-12px_rgba(0,0,0,0.1)]">
        @csrf
        <input type="hidden" name="item_id" value="{{ $item->id }}">
        <input type="hidden" name="disposal_type" value="{{ $disposalType }}">

        {{-- Header --}}
        <div class="border-b border-slate-100 bg-slate-50/50 px-8 py-6">
            <div class="flex items-center gap-4">
                @if($disposalType === 'new')
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-rose-50 text-rose-600 ring-1 ring-inset ring-rose-500/20">
                @else
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-amber-50 text-amber-600 ring-1 ring-inset ring-amber-500/20">
                @endif
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-6 w-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                    </svg>
                </div>
                <div>
                    @if($disposalType === 'new')
                    <h2 class="text-lg font-bold text-slate-900">Dispose Expired Stock</h2>
                    <p class="text-sm text-slate-500 mt-0.5">
                        Expired quantity available: <span class="font-bold text-rose-600">{{ $maxQty }} {{ $item->unit }}</span>
                    </p>
                    @else
                    <h2 class="text-lg font-bold text-slate-900">Dispose Used Item</h2>
                    <p class="text-sm text-slate-500 mt-0.5">
                        Current used stock: <span class="font-bold text-amber-600">{{ $maxQty }} {{ $item->unit }}</span>
                    </p>
                    @endif
                </div>
            </div>
        </div>

        <div class="px-8 py-8 space-y-8">
            {{-- Amount & Time --}}
            <div>
                <h3 class="mb-4 flex items-center gap-2 text-sm font-bold uppercase tracking-widest text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-11.25a.75.75 0 00-1.5 0v4.59L7.47 9.56a.75.75 0 00-1.06 1.06l3.25 3.25a.75.75 0 001.06 0l3.25-3.25a.75.75 0 10-1.06-1.06l-1.78 1.78V6.75z"
                            clip-rule="evenodd" />
                    </svg>
                    Amount & Time
                </h3>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Quantity to Dispose <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <input type="number" name="quantity" id="quantity" min="1" max="{{ $maxQty }}"
                                value="{{ old('quantity', 1) }}"
                                class="block w-full rounded-xl border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-inset {{ $disposalType === 'new' ? 'focus:ring-rose-400' : 'focus:ring-amber-400' }} sm:text-sm sm:leading-6 transition-all"
                                required>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4">
                                <span class="text-slate-400 text-xs font-semibold uppercase">{{ $item->unit }}</span>
                            </div>
                        </div>
                        @error('quantity') <p class="mt-1.5 text-xs font-bold text-rose-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Date & Time <span class="text-rose-500">*</span></label>
                        <input type="datetime-local" name="disposed_at" id="disposed_at"
                            value="{{ old('disposed_at', now()->format('Y-m-d\TH:i')) }}"
                            class="block w-full rounded-xl border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-inset {{ $disposalType === 'new' ? 'focus:ring-rose-400' : 'focus:ring-amber-400' }} sm:text-sm sm:leading-6 transition-all"
                            required>
                    </div>
                </div>
            </div>

            <hr class="border-slate-100">

            {{-- Documentation --}}
            <div>
                <h3 class="mb-4 flex items-center gap-2 text-sm font-bold uppercase tracking-widest text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                        <path d="M10 9a3 3 0 100-6 3 3 0 000 6zM6 8a2 2 0 11-4 0 2 2 0 014 0zM1.49 15.326a.78.78 0 01-.358-.442 3 3 0 014.308-3.516 6.484 6.484 0 00-1.905 3.959c-.023.222-.014.442.025.654a4.97 4.97 0 01-2.07-.655zM16.44 15.98a4.97 4.97 0 002.07-.654.78.78 0 00.357-.442 3 3 0 00-4.308-3.517 6.484 6.484 0 011.907 3.96 2.32 2.32 0 01-.026.654z" />
                    </svg>
                    Documentation
                </h3>
                <div class="space-y-6">
                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Disposed By <span class="text-rose-500">*</span></label>
                        <select name="disposed_by" id="disposed_by" required
                            class="block w-full rounded-xl border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-inset {{ $disposalType === 'new' ? 'focus:ring-rose-400' : 'focus:ring-amber-400' }} sm:text-sm sm:leading-6 transition-all bg-white">
                            <option value="" disabled {{ old('disposed_by') ? '' : 'selected' }}>— Select staff member —</option>
                            @foreach($staffList as $staff)
                                <option value="{{ $staff->display_name }}"
                                    {{ old('disposed_by') === $staff->display_name ? 'selected' : '' }}>
                                    {{ $staff->display_name }}
                                    @if($staff->specialization) — {{ $staff->specialization }} @endif
                                </option>
                            @endforeach
                        </select>
                        @error('disposed_by') <p class="mt-1.5 text-xs font-bold text-rose-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Reason for Disposal <span class="text-rose-500">*</span></label>
                        <textarea id="reason" name="reason" rows="3"
                            class="block w-full rounded-xl border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset {{ $disposalType === 'new' ? 'focus:ring-rose-400' : 'focus:ring-amber-400' }} sm:text-sm sm:leading-6 transition-all"
                            placeholder="{{ $disposalType === 'new' ? 'e.g. Expired stock, past safe use date...' : 'e.g. End of procedure, damage, contamination...' }}"
                            required>{{ old('reason', $disposalType === 'new' ? 'Expired stock' : '') }}</textarea>
                        @error('reason') <p class="mt-1.5 text-xs font-bold text-rose-500">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="bg-slate-50 px-8 py-5 flex items-center justify-end gap-3 border-t border-slate-100">
            <a href="{{ route('items.show', $item) }}"
                class="rounded-xl px-5 py-2.5 text-sm font-bold text-slate-500 transition-colors hover:bg-slate-200 hover:text-slate-900">
                Cancel
            </a>
            <button type="submit"
                class="group relative inline-flex items-center justify-center gap-2 overflow-hidden rounded-xl px-6 py-2.5 text-sm font-bold text-white shadow-md transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2
                {{ $disposalType === 'new' ? 'bg-rose-500 hover:bg-rose-600 focus:ring-rose-400' : 'bg-amber-500 hover:bg-amber-600 focus:ring-amber-400' }}">
                <span class="relative">{{ $disposalType === 'new' ? 'Dispose Expired Stock' : 'Confirm Disposal' }}</span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                    class="relative h-4 w-4 transition-transform duration-300 group-hover:translate-y-0.5">
                    <path fill-rule="evenodd"
                        d="M8.75 1A2.75 2.75 0 006 3.75V4H2.75a.75.75 0 000 1.5h.3l.815 8.15A1.5 1.5 0 005.357 15h5.285a1.5 1.5 0 001.493-1.35l.815-8.15h.3a.75.75 0 000-1.5H10v-.25a2.75 2.75 0 00-2.75-2.75zM7.5 3.75V4h1v-.25a1.25 1.25 0 00-1.25-1.25z"
                        clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </form>
</div>
@endsection