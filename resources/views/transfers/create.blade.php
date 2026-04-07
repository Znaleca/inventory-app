@extends('layouts.app')

@section('title', 'New Transfer')

@section('actions')
<a href="{{ route('in-out.index', ['tab' => 'transfer']) }}"
    class="group inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2 text-sm font-semibold text-slate-600 shadow-sm ring-1 ring-inset ring-slate-300 transition-all hover:bg-slate-50 hover:text-slate-900">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
        class="h-4 w-4 transition-transform duration-300 group-hover:-translate-x-1">
        <path fill-rule="evenodd"
            d="M17 10a.75.75 0 01-.75.75H5.66l4.22 4.22a.75.75 0 11-1.06 1.06l-5.5-5.5a.75.75 0 010-1.06l5.5-5.5a.75.75 0 111.06 1.06l-4.22 4.22h10.59a.75.75 0 01.75.75z"
            clip-rule="evenodd" />
    </svg>
    Back to Transfers
</a>
@endsection

@section('content')
<div class="mx-auto max-w-3xl">
    <form action="{{ route('transfers.store') }}" method="POST"
        class="overflow-hidden rounded-[2rem] bg-white ring-1 ring-slate-200 shadow-[0_8px_30px_-12px_rgba(0,0,0,0.1)]"
        x-data="{
            type: '{{ old('type', 'out') }}',
            newQty: {{ old('new_quantity', 0) }},
            usedQty: {{ old('used_quantity', 0) }},
            get total() { return (parseInt(this.newQty)||0) + (parseInt(this.usedQty)||0); }
        }">
        @csrf
        <input type="hidden" name="type" :value="type">

        {{-- Header Section --}}
        <div class="border-b border-slate-100 bg-slate-50/50 px-8 py-6">
            <div class="flex items-center gap-4">
                <div
                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600 ring-1 ring-inset ring-indigo-500/20">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-6 w-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Transfer Details</h2>
                    <p class="text-sm text-slate-500 mt-0.5">Move items between departments or facilities securely.</p>
                </div>
            </div>
        </div>

        {{-- Main Form Body --}}
        <div class="px-8 py-8 space-y-8">

            {{-- SECTION: Item Configuration --}}
            <div>
                <h3 class="mb-4 flex items-center gap-2 text-sm font-bold uppercase tracking-widest text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                        <path fill-rule="evenodd" d="M15.312 11.424a5.5 5.5 0 01-9.201 2.466l-.312-.311h2.433a.75.75 0 000-1.5H3.989a.75.75 0 00-.75.75v4.242a.75.75 0 001.5 0V5.36l-.31-.31A7 7 0 003.239 8.188a.75.75 0 101.448.389A5.5 5.5 0 0113.89 6.11l.311.31h-2.432a.75.75 0 000 1.5h4.243a.75.75 0 00.53-.219z" clip-rule="evenodd" />
                    </svg>
                    Transfer Configuration
                </h3>

                <div class="space-y-6">
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-700">Transfer Direction <span class="text-rose-500">*</span></label>
                            <select x-model="type" required
                                class="block w-full rounded-xl border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all bg-white">
                                <option value="out">Transfer Out (Send stock)</option>
                                <option value="in">Transfer In (Receive stock)</option>
                            </select>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-700">Item <span class="text-rose-500">*</span></label>
                            <select id="item_id" name="item_id" required
                                class="block w-full rounded-xl border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all bg-white">
                                <option value="">Select an Item...</option>
                                @foreach($items as $item)
                                <option value="{{ $item->id }}" {{ old('item_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->name }} (New: {{ $item->total_stock }} · Used: {{ $item->effective_stock_used }} {{ $item->unit }})
                                </option>
                                @endforeach
                            </select>
                            @error('item_id') <p class="mt-1.5 text-xs font-bold text-rose-500">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-700">Transfer Date & Time <span class="text-rose-500">*</span></label>
                            <input type="datetime-local" name="transferred_at" id="transferred_at" required
                                value="{{ old('transferred_at', now()->format('Y-m-d\TH:i')) }}"
                                class="block w-full rounded-xl border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all">
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-700">New Stock Qty</label>
                            <input type="number" name="new_quantity" id="new_quantity" min="0" value="{{ old('new_quantity', 0) }}"
                                x-model="newQty"
                                class="block w-full rounded-xl border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all">
                            @error('new_quantity') <p class="mt-1.5 text-xs font-bold text-rose-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-700">Used Stock Qty</label>
                            <input type="number" name="used_quantity" id="used_quantity" min="0" value="{{ old('used_quantity', 0) }}"
                                x-model="usedQty"
                                class="block w-full rounded-xl border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all">
                            @error('used_quantity') <p class="mt-1.5 text-xs font-bold text-rose-500">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Total summary --}}
                    <div class="mt-2 flex items-center gap-2 rounded-xl bg-slate-50/50 border border-slate-100 px-4 py-2.5">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Total Transfer Quantity</span>
                        <span class="ml-auto text-sm font-black text-slate-800" x-text="total + ' unit(s)'"></span>
                    </div>
                </div>
            </div>

            <hr class="border-slate-100">

            {{-- SECTION: Party Information --}}
            <div>
                <h3 class="mb-4 flex items-center gap-2 text-sm font-bold uppercase tracking-widest text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                        <path d="M10 8a3 3 0 100-6 3 3 0 000 6zM3.465 14.493a1.23 1.23 0 00.41 1.412A9.957 9.957 0 0010 18c2.31 0 4.438-.784 6.131-2.1.43-.333.604-.903.408-1.41a7.002 7.002 0 00-13.074.003z" />
                    </svg>
                    <span x-text="type === 'out' ? 'Destination Information' : 'Source Information'"></span>
                </h3>

                <div class="space-y-6">
                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">
                            <span x-text="type === 'out' ? 'Transferred To (Full Name)' : 'Transferred From (Full Name)'"></span>
                            <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="transferred_to" id="transferred_to" value="{{ old('transferred_to') }}"
                            required :placeholder="type === 'out' ? 'e.g. Maria Clara' : 'e.g. Juan dela Cruz'"
                            class="block w-full rounded-xl border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all">
                    </div>

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-700">
                                <span x-text="type === 'out' ? 'Destination Department' : 'Source Department'"></span>
                                <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="department" id="department" value="{{ old('department') }}" required
                                placeholder="e.g. ICU"
                                class="block w-full rounded-xl border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all">
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-700">Bio ID <span class="text-rose-500">*</span></label>
                            <input type="text" name="bio_id" id="bio_id" value="{{ old('bio_id') }}" required
                                placeholder="e.g. 2024-X892"
                                class="block w-full rounded-xl border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all">
                        </div>
                    </div>
                </div>
            </div>

            <hr class="border-slate-100">

            {{-- SECTION: Additional Context --}}
            <div>
                <h3 class="mb-4 flex items-center gap-2 text-sm font-bold uppercase tracking-widest text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z" clip-rule="evenodd" />
                    </svg>
                    Additional Details
                </h3>

                <div class="space-y-6">
                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Processed By</label>
                        <input type="text" value="{{ Auth::user()->name }}" readonly
                            class="block w-full rounded-xl border-0 py-3 px-4 text-slate-500 bg-slate-50 shadow-sm ring-1 ring-inset ring-slate-200 sm:text-sm sm:leading-6 cursor-not-allowed">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Notes <span class="text-slate-400 font-normal ml-1">(Optional)</span></label>
                        <textarea id="notes" name="notes" rows="3"
                            class="block w-full rounded-xl border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all"
                            placeholder="Enter any additional context regarding this transfer...">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

        </div>

        {{-- Footer / Submit Area --}}
        <div class="bg-slate-50 px-8 py-5 flex items-center justify-end gap-3 border-t border-slate-100">
            <a href="{{ route('in-out.index', ['tab' => 'transfer']) }}"
                class="rounded-xl px-5 py-2.5 text-sm font-bold text-slate-500 transition-colors hover:bg-slate-200 hover:text-slate-900">
                Cancel
            </a>
            <button type="submit"
                class="group relative inline-flex items-center justify-center gap-2 overflow-hidden rounded-xl bg-slate-900 px-6 py-2.5 text-sm font-bold text-white shadow-md transition-all duration-300 hover:bg-blue-600 hover:shadow-lg hover:shadow-blue-500/30 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2"
                :disabled="total < 1">
                <span class="relative" x-text="type === 'out' ? 'Confirm Transfer Out' : 'Confirm Transfer In'"></span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                    class="relative h-4 w-4 transition-transform duration-300 group-hover:translate-x-0.5">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                        clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </form>
</div>
@endsection