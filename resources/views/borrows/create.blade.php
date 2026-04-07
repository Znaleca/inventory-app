@extends('layouts.app')

@section('title', 'New Borrow')

@section('actions')
<a href="{{ route('in-out.index', ['tab' => 'borrow']) }}"
    class="group inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2 text-sm font-semibold text-slate-600 shadow-sm ring-1 ring-inset ring-slate-300 transition-all hover:bg-slate-50 hover:text-slate-900">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
        class="h-4 w-4 transition-transform duration-300 group-hover:-translate-x-1">
        <path fill-rule="evenodd"
            d="M17 10a.75.75 0 01-.75.75H5.66l4.22 4.22a.75.75 0 11-1.06 1.06l-5.5-5.5a.75.75 0 010-1.06l5.5-5.5a.75.75 0 111.06 1.06l-4.22 4.22h10.59a.75.75 0 01.75.75z"
            clip-rule="evenodd" />
    </svg>
    Back to Borrows
</a>
@endsection

@section('content')
<div class="mx-auto max-w-3xl">
    <form action="{{ route('borrows.store') }}" method="POST"
        x-data="{ borrowType: '{{ old('type', 'out') }}' }"
        class="overflow-hidden rounded-[2rem] bg-white ring-1 ring-slate-200 shadow-[0_8px_30px_-12px_rgba(0,0,0,0.1)]">
        @csrf

        {{-- Validation Error Summary --}}
        @if ($errors->any())
        <div class="mx-6 mt-6 rounded-xl bg-rose-50 px-5 py-4 ring-1 ring-inset ring-rose-200">
            <div class="flex gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5 shrink-0 text-rose-500 mt-0.5">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                </svg>
                <div>
                    <p class="text-sm font-bold text-rose-700">Please fix the following errors:</p>
                    <ul class="mt-1 list-disc list-inside text-sm text-rose-600 space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        {{-- Header Section --}}
        <div class="border-b border-slate-100 bg-slate-50/50 px-8 py-6">
            <div class="flex items-center gap-4">
                <div
                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-blue-50 text-blue-600 ring-1 ring-inset ring-blue-500/20">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="h-6 w-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H9.75" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Borrow Details</h2>
                    <p class="text-sm text-slate-500 mt-0.5">Record items borrowed by clinical staff. Returned items can
                        be restocked or marked as used later.</p>
                </div>
            </div>
        </div>

        {{-- Main Form Body --}}
        <div class="px-8 py-8 space-y-8">

            {{-- SECTION: Item Configuration --}}
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
                    Item Configuration
                </h3>

                <div class="space-y-6">
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-700">Borrow Type <span class="text-rose-500">*</span></label>
                            <select x-model="borrowType" name="type" required
                                class="block w-full rounded-xl border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all bg-white">
                                <option value="out">↑ Borrow Out (Lend an item)</option>
                                <option value="in">↓ Borrow In (Receive an item)</option>
                            </select>
                            <p class="mt-2 text-[11px] text-slate-400 font-medium" x-show="borrowType === 'out'">You are lending an item *to* someone else.</p>
                            <p class="mt-2 text-[11px] text-slate-400 font-medium" x-show="borrowType === 'in'" style="display: none;">You are borrowing an item *from* someone else.</p>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-700">Item <span
                                    class="text-rose-500">*</span></label>
                            <select id="item_id" name="item_id" required
                                class="block w-full rounded-xl border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all bg-white">
                                <option value="">Select an Item...</option>
                                @foreach($items as $item)
                                <option value="{{ $item->id }}" {{ old('item_id')==$item->id ? 'selected' : '' }}>
                                    {{ $item->name }} (Available: {{ $item->total_stock }} {{ $item->unit }})
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @error('item_id')<p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>@enderror
                    @error('type')<p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>@enderror

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-700">Quantity <span
                                    class="text-rose-500">*</span></label>
                            <input type="number" name="quantity_borrowed" id="quantity_borrowed" min="1"
                                value="{{ old('quantity_borrowed') }}" required
                                class="block w-full rounded-xl border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset {{ $errors->has('quantity_borrowed') ? 'ring-rose-400' : 'ring-slate-200' }} placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all">
                            @error('quantity_borrowed')<p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-700">Borrow Date <span
                                    class="text-rose-500">*</span></label>
                            <input type="datetime-local" name="borrowed_at" id="borrowed_at" required
                                value="{{ old('borrowed_at', now()->format('Y-m-d\TH:i')) }}"
                                class="block w-full rounded-xl border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all">
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-700">Expected Return <span
                                    class="text-slate-400 font-normal">(Optional)</span></label>
                            <input type="date" name="return_date" id="return_date"
                                value="{{ old('return_date') }}"
                                class="block w-full rounded-xl border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all">
                        </div>
                    </div>
                </div>
            </div>

            <hr class="border-slate-100">

            {{-- SECTION: Borrower Information --}}
            <div>
                <h3 class="mb-4 flex items-center gap-2 text-sm font-bold uppercase tracking-widest text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                        <path
                            d="M10 8a3 3 0 100-6 3 3 0 000 6zM3.465 14.493a1.23 1.23 0 00.41 1.412A9.957 9.957 0 0010 18c2.31 0 4.438-.784 6.131-2.1.43-.333.604-.903.408-1.41a7.002 7.002 0 00-13.074.003z" />
                    </svg>
                    Borrower Information
                </h3>

                <div class="space-y-6">
                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Name of Borrower <span
                                class="text-rose-500">*</span></label>
                        <input type="text" name="borrower_name" id="borrower_name" value="{{ old('borrower_name') }}"
                            required placeholder="e.g. Juan Dela Cruz"
                            class="block w-full rounded-xl border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset {{ $errors->has('borrower_name') ? 'ring-rose-400' : 'ring-slate-200' }} placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all">
                        @error('borrower_name')<p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-700">Borrower Bio ID <span
                                    class="text-rose-500">*</span></label>
                            <input type="text" name="bio_id" id="bio_id" value="{{ old('bio_id') }}" required
                                placeholder="e.g. 2024-X892"
                                class="block w-full rounded-xl border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all">
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-700">
                                <span x-text="borrowType === 'in' ? 'Destination Department' : 'Borrower Department'"></span> <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="department" id="department" value="{{ old('department') }}"
                                required :placeholder="borrowType === 'in' ? 'e.g. Storage/Internal' : 'e.g. Cardiology'"
                                class="block w-full rounded-xl border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all">
                        </div>
                    </div>

                    <div x-show="borrowType === 'in'" style="display: none;">
                        <label class="mb-2 block text-sm font-bold text-slate-700">Source Department (Lender) <span class="text-rose-500">*</span></label>
                        <input type="text" name="source_department" id="source_department" value="{{ old('source_department') }}"
                            :required="borrowType === 'in'" placeholder="e.g. ICU"
                            class="block w-full rounded-xl border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all">
                    </div>
                </div>
            </div>

            <hr class="border-slate-100">

            {{-- SECTION: Additional Context --}}
            <div>
                <h3 class="mb-4 flex items-center gap-2 text-sm font-bold uppercase tracking-widest text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z"
                            clip-rule="evenodd" />
                    </svg>
                    Additional Details
                </h3>

                <div class="space-y-6">
                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Processed / Approved By</label>
                        <input type="text" value="{{ Auth::user()->name }}" readonly
                            title="This is automatically recorded as your account."
                            class="block w-full rounded-xl border-0 py-3 px-4 text-slate-500 bg-slate-50 shadow-sm ring-1 ring-inset ring-slate-200 sm:text-sm sm:leading-6 cursor-not-allowed">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Notes <span
                                class="text-slate-400 font-normal ml-1">(Optional)</span></label>
                        <textarea id="notes" name="notes" rows="3"
                            class="block w-full rounded-xl border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all"
                            placeholder="Enter any additional context regarding this borrow...">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

        </div>

        {{-- Footer / Submit Area --}}
        <div class="bg-slate-50 px-8 py-5 flex items-center justify-end gap-3 border-t border-slate-100">
            <a href="{{ route('in-out.index', ['tab' => 'borrow']) }}"
                class="rounded-xl px-5 py-2.5 text-sm font-bold text-slate-500 transition-colors hover:bg-slate-200 hover:text-slate-900">
                Cancel
            </a>
            <button type="submit"
                class="group relative inline-flex items-center justify-center gap-2 overflow-hidden rounded-xl bg-slate-900 px-6 py-2.5 text-sm font-bold text-white shadow-md transition-all duration-300 hover:bg-blue-600 hover:shadow-lg hover:shadow-blue-500/30 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2">
                <span class="relative" x-text="borrowType === 'out' ? 'Confirm Borrow Out' : 'Confirm Borrow In'"></span>
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