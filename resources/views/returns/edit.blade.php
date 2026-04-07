@extends('layouts.app')

@section('title', 'Process Return / Usage')

@section('actions')
<a href="{{ route('in-out.index', ['tab' => 'return']) }}"
    class="group inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2 text-sm font-semibold text-slate-600 shadow-sm ring-1 ring-inset ring-slate-300 transition-all hover:bg-slate-50 hover:text-slate-900">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
        class="h-4 w-4 transition-transform duration-300 group-hover:-translate-x-1">
        <path fill-rule="evenodd"
            d="M17 10a.75.75 0 01-.75.75H5.66l4.22 4.22a.75.75 0 11-1.06 1.06l-5.5-5.5a.75.75 0 010-1.06l5.5-5.5a.75.75 0 111.06 1.06l-4.22 4.22h10.59a.75.75 0 01.75.75z"
            clip-rule="evenodd" />
    </svg>
    Back to Returns
</a>
@endsection

@section('content')
<div class="mx-auto max-w-3xl">
    <form action="{{ route('returns.update', $borrow) }}" method="POST"
        class="overflow-hidden rounded-[2rem] bg-white ring-1 ring-slate-200 shadow-[0_8px_30px_-12px_rgba(0,0,0,0.1)]">
        @csrf
        @method('PUT')

        {{-- Header Section --}}
        <div class="border-b border-slate-100 bg-slate-50/50 px-8 py-6">
            <div class="flex items-center gap-4">
                <div
                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-blue-50 text-blue-600 ring-1 ring-inset ring-blue-500/20">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="h-6 w-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Process Return / Usage</h2>
                    <p class="text-sm text-slate-500 mt-0.5">Record items being returned to stock or marked as used.</p>
                </div>
            </div>
        </div>

        {{-- Main Form Body with Alpine --}}
        <div class="px-8 py-8 space-y-8"
            x-data="{
                pending: {{ $borrow->quantity_borrowed - $borrow->quantity_returned - $borrow->quantity_used }},
                intact: {{ old('quantity_returning', 0) }},
                used: {{ old('quantity_using', 0) }},
                get total() { return parseInt(this.intact || 0) + parseInt(this.used || 0); },
                get overMax() { return this.total > this.pending; },
                onIntactChange() {
                    let i = parseInt(this.intact) || 0;
                    let remaining = this.pending - i;
                    this.used = remaining >= 0 ? remaining : 0;
                    if (i > this.pending) this.intact = this.pending;
                },
                onUsedChange() {
                    let u = parseInt(this.used) || 0;
                    let remaining = this.pending - u;
                    this.intact = remaining >= 0 ? remaining : 0;
                    if (u > this.pending) this.used = this.pending;
                }
            }">


            {{-- SECTION: Borrow Summary --}}
            <div>
                <h3 class="mb-4 flex items-center gap-2 text-sm font-bold uppercase tracking-widest text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                        <path
                            d="M10 8a3 3 0 100-6 3 3 0 000 6zM3.465 14.493a1.23 1.23 0 00.41 1.412A9.957 9.957 0 0010 18c2.31 0 4.438-.784 6.131-2.1.43-.333.604-.903.408-1.41a7.002 7.002 0 00-13.074.003z" />
                    </svg>
                    Borrow Summary
                </h3>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5 shadow-sm">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Item</p>
                        <p class="mt-1 font-bold text-slate-900 line-clamp-1">{{ $borrow->item->name }}</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5 shadow-sm">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Borrowed By</p>
                        <p class="mt-1 font-bold text-slate-900 line-clamp-1">{{ $borrow->borrower_name ??
                            $borrow->staff?->display_name ?? 'Unknown Staff' }}</p>
                    </div>
                    <div
                        class="rounded-2xl border border-emerald-500/30 bg-emerald-50/50 p-5 shadow-sm ring-1 ring-inset ring-emerald-500/10">
                        <p class="text-xs font-bold uppercase tracking-wider text-emerald-600">Pending Return</p>
                        <p class="mt-1 flex items-baseline gap-1 text-2xl font-black text-emerald-700">
                            {{ $borrow->quantity_borrowed - $borrow->quantity_returned - $borrow->quantity_used }}
                            <span class="text-sm font-bold text-emerald-600/70">{{ $borrow->item->unit }}</span>
                        </p>
                    </div>
                </div>
            </div>

            <hr class="border-slate-100">

            {{-- SECTION: Return Quantities --}}
            <div>
                <h3 class="mb-4 flex items-center gap-2 text-sm font-bold uppercase tracking-widest text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                        <path fill-rule="evenodd"
                            d="M10 2a.75.75 0 01.75.75v5.59l1.95-2.1a.75.75 0 111.1 1.02l-3.25 3.5a.75.75 0 01-1.1 0L6.2 7.26a.75.75 0 111.1-1.02l1.95 2.1V2.75A.75.75 0 0110 2z"
                            clip-rule="evenodd" />
                        <path fill-rule="evenodd"
                            d="M4 10a.75.75 0 01.75.75v4.5a.75.75 0 00.75.75h9a.75.75 0 00.75-.75v-4.5a.75.75 0 011.5 0v4.5a2.25 2.25 0 01-2.25 2.25h-9A2.25 2.25 0 012 15.25v-4.5A.75.75 0 014 10z"
                            clip-rule="evenodd" />
                    </svg>
                    Process Quantities
                </h3>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    {{-- Quantity Returning Intact --}}
                    <div>
                        <label for="quantity_returning"
                            class="block text-sm font-bold leading-6 text-slate-700">Quantity Returning Intact</label>
                        <p class="text-xs text-slate-500 mb-2">Items going back to available stock.</p>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                <span class="text-emerald-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                        class="h-5 w-5">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-11.25a.75.75 0 00-1.5 0v2.5h-2.5a.75.75 0 000 1.5h2.5v2.5a.75.75 0 001.5 0v-2.5h2.5a.75.75 0 000-1.5h-2.5v-2.5z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </span>
                            </div>
                            <input type="number" name="quantity_returning" id="quantity_returning" min="0"
                                :max="pending"
                                x-model.number="intact"
                                @input="onIntactChange()"
                                required
                                class="block w-full rounded-xl border-0 py-3 pl-11 pr-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-inset focus:ring-emerald-500 sm:text-sm sm:leading-6 transition-all">
                        </div>
                        <p class="mt-1.5 text-[11px] text-emerald-600 font-semibold">→ Returns to available stock</p>
                    </div>

                    {{-- Quantity Used --}}
                    <div>
                        <label for="quantity_using" class="block text-sm font-bold leading-6 text-slate-700">Quantity
                            Used on Patient</label>
                        <p class="text-xs text-slate-500 mb-2">Items consumed and to be logged.</p>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                <span class="text-rose-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                        class="h-5 w-5">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </span>
                            </div>
                            <input type="number" name="quantity_using" id="quantity_using" min="0"
                                :max="pending"
                                x-model.number="used"
                                @input="onUsedChange()"
                                required
                                class="block w-full rounded-xl border-0 py-3 pl-11 pr-4 text-slate-900 shadow-sm ring-1 ring-inset ring-rose-200 focus:ring-2 focus:ring-inset focus:ring-rose-500 sm:text-sm sm:leading-6 transition-all">
                        </div>
                        <p class="mt-1.5 text-[11px] text-rose-500 font-semibold">→ Goes to Used Stock</p>
                    </div>
                </div>

                {{-- Live Total Summary --}}
                <div class="mt-5 flex items-center justify-between rounded-2xl px-5 py-4 ring-1 transition-all"
                    :class="overMax ? 'bg-rose-50 ring-rose-300' : 'bg-slate-50 ring-slate-200'">
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-bold" :class="overMax ? 'text-rose-700' : 'text-slate-700'">Total accounted:</span>
                        <span class="text-lg font-black" :class="overMax ? 'text-rose-600' : 'text-slate-900'" x-text="total"></span>
                        <span class="text-sm text-slate-400">of</span>
                        <span class="text-lg font-black text-emerald-600" x-text="pending"></span>
                        <span class="text-sm text-slate-500">pending</span>
                    </div>
                    <div x-show="overMax" x-cloak class="text-xs font-bold text-rose-600">⚠ Exceeds pending</div>
                    <div x-show="!overMax && total === pending" x-cloak class="text-xs font-bold text-emerald-600">✓ Fully accounted</div>
                </div>

            </div>

            <hr class="border-slate-100">

            {{-- SECTION: Additional Details --}}
            <div>
                <h3 class="mb-4 flex items-center gap-2 text-sm font-bold uppercase tracking-widest text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z"
                            clip-rule="evenodd" />
                    </svg>
                    Time & Notes
                </h3>

                <div class="space-y-6">
                    <div>
                        <label for="returned_at" class="mb-2 block text-sm font-bold text-slate-700">Date & Time
                            Returned / Used <span class="text-rose-500">*</span></label>
                        <input type="datetime-local" name="returned_at" id="returned_at" required
                            value="{{ old('returned_at', now()->format('Y-m-d\TH:i')) }}"
                            class="block w-full rounded-xl border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all">
                    </div>

                    <div>
                        <label for="notes" class="mb-2 block text-sm font-bold text-slate-700">Notes <span
                                class="text-slate-400 font-normal ml-1">(Optional)</span></label>
                        <textarea id="notes" name="notes" rows="3"
                            class="block w-full rounded-xl border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

        </div>

        {{-- Footer / Submit Area --}}
        <div class="bg-slate-50 px-8 py-5 flex items-center justify-end gap-3 border-t border-slate-100">
            <a href="{{ route('in-out.index', ['tab' => 'return']) }}"
                class="rounded-xl px-5 py-2.5 text-sm font-bold text-slate-500 transition-colors hover:bg-slate-200 hover:text-slate-900">
                Cancel
            </a>
            <button type="submit"
                class="group relative inline-flex items-center justify-center gap-2 overflow-hidden rounded-xl bg-slate-900 px-6 py-2.5 text-sm font-bold text-white shadow-md transition-all duration-300 hover:bg-blue-600 hover:shadow-lg hover:shadow-blue-500/30 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2">
                <span class="relative">Process Return</span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                    class="relative h-4 w-4 transition-transform duration-300 group-hover:translate-x-0.5">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-11.25a.75.75 0 00-1.5 0v2.5h-2.5a.75.75 0 000 1.5h2.5v2.5a.75.75 0 001.5 0v-2.5h2.5a.75.75 0 000-1.5h-2.5v-2.5z"
                        clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </form>
</div>
@endsection