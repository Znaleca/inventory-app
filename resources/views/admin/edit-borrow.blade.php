@extends('layouts.app')

@section('title', 'Edit Borrow Record')

@section('actions')
    <a href="{{ route('admin.records.index', ['tab' => 'borrows']) }}"
        class="inline-flex items-center gap-2 border border-slate-200 bg-white px-4 py-2 text-[11px] font-mono font-bold text-slate-600 uppercase tracking-widest hover:bg-slate-50 transition-colors">
        ← Back to Records
    </a>
@endsection

@section('content')
<div class="mx-auto max-w-2xl">

    <div class="mb-5">
        <p class="text-[10px] font-mono font-semibold text-blue-600 uppercase tracking-[0.25em] mb-1">Admin://Borrows//Edit</p>
        <h1 class="text-xl font-bold text-slate-800 tracking-tight">Edit Borrow Record</h1>
        <div class="flex items-center gap-3 mt-0.5">
            <p class="text-xs text-slate-400 font-mono">Item: <span class="font-bold text-slate-600">{{ $borrow->item->name ?? '—' }}</span></p>
            @if($borrow->staff->name ?? false)
            <span class="text-slate-300 font-mono text-xs">·</span>
            <p class="text-xs text-slate-400 font-mono">Staff: <span class="font-bold text-slate-600">{{ $borrow->staff->name }}</span></p>
            @endif
        </div>
    </div>

    @if ($errors->any())
        <div class="mb-5 bg-rose-50 border border-rose-200 relative px-5 py-4">
            <div class="absolute top-0 left-0 w-1 h-full bg-rose-500"></div>
            <ul class="ml-1 space-y-1">
                @foreach ($errors->all() as $error)
                    <li class="text-sm text-rose-700">— {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Quantities --}}
    <div class="bg-white border border-slate-200 relative mb-4">
        <div class="absolute top-0 left-0 w-1 h-full bg-blue-500"></div>
        <div class="ml-1">
            <div class="px-5 py-4 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <span class="h-2 w-2 bg-blue-500 inline-block"></span>
                    <p class="text-[10px] font-mono font-bold text-blue-600 uppercase tracking-widest">01 // Quantities</p>
                </div>
            </div>
            <div class="px-5 py-5">
                <form action="{{ route('admin.borrows.update', $borrow) }}" method="POST" id="borrow-edit-form">
                    @csrf @method('PATCH')

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                        <div>
                            <label for="quantity_borrowed" class="block text-sm font-bold text-slate-700 mb-1.5">Qty Borrowed</label>
                            <input type="number" name="quantity_borrowed" id="quantity_borrowed" value="{{ old('quantity_borrowed', $borrow->quantity_borrowed) }}" min="0"
                                class="block w-full border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:outline-none py-2.5 px-3 text-sm font-mono text-slate-800 transition-colors">
                            @error('quantity_borrowed') <p class="mt-1 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="quantity_returned" class="block text-sm font-bold text-slate-700 mb-1.5">Qty Returned</label>
                            <input type="number" name="quantity_returned" id="quantity_returned" value="{{ old('quantity_returned', $borrow->quantity_returned) }}" min="0"
                                class="block w-full border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:outline-none py-2.5 px-3 text-sm font-mono text-slate-800 transition-colors">
                            @error('quantity_returned') <p class="mt-1 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="quantity_used" class="block text-sm font-bold text-slate-700 mb-1.5">Qty Used</label>
                            <input type="number" name="quantity_used" id="quantity_used" value="{{ old('quantity_used', $borrow->quantity_used) }}" min="0"
                                class="block w-full border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:outline-none py-2.5 px-3 text-sm font-mono text-slate-800 transition-colors">
                            @error('quantity_used') <p class="mt-1 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Status & Dates --}}
    <div class="bg-white border border-slate-200 relative mb-4">
        <div class="absolute top-0 left-0 w-1 h-full bg-teal-500"></div>
        <div class="ml-1">
            <div class="px-5 py-4 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <span class="h-2 w-2 bg-teal-500 inline-block"></span>
                    <p class="text-[10px] font-mono font-bold text-teal-600 uppercase tracking-widest">02 // Status & Dates</p>
                </div>
            </div>
            <div class="px-5 py-5 grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div>
                    <label for="status" class="block text-sm font-bold text-slate-700 mb-1.5">Status</label>
                    <select name="status" id="status" form="borrow-edit-form"
                        class="block w-full border border-slate-200 bg-slate-50 focus:bg-white focus:border-teal-500 focus:outline-none py-2.5 px-3 text-sm text-slate-800 transition-colors">
                        <option value="active"   {{ old('status', $borrow->status) === 'active'   ? 'selected' : '' }}>Active</option>
                        <option value="partial"  {{ old('status', $borrow->status) === 'partial'  ? 'selected' : '' }}>Partial</option>
                        <option value="returned" {{ old('status', $borrow->status) === 'returned' ? 'selected' : '' }}>Returned</option>
                    </select>
                    @error('status') <p class="mt-1 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="borrowed_at" class="block text-sm font-bold text-slate-700 mb-1.5">Borrowed At</label>
                    <input type="date" name="borrowed_at" id="borrowed_at" form="borrow-edit-form" value="{{ old('borrowed_at', $borrow->borrowed_at?->format('Y-m-d')) }}"
                        class="block w-full border border-slate-200 bg-slate-50 focus:bg-white focus:border-teal-500 focus:outline-none py-2.5 px-3 text-sm font-mono text-slate-800 transition-colors">
                    @error('borrowed_at') <p class="mt-1 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="returned_at" class="block text-sm font-bold text-slate-700 mb-1.5">Returned At</label>
                    <input type="date" name="returned_at" id="returned_at" form="borrow-edit-form" value="{{ old('returned_at', $borrow->returned_at?->format('Y-m-d')) }}"
                        class="block w-full border border-slate-200 bg-slate-50 focus:bg-white focus:border-teal-500 focus:outline-none py-2.5 px-3 text-sm font-mono text-slate-800 transition-colors">
                    @error('returned_at') <p class="mt-1 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>
    </div>

    {{-- Party & Notes --}}
    <div class="bg-white border border-slate-200 relative mb-4">
        <div class="absolute top-0 left-0 w-1 h-full bg-slate-400"></div>
        <div class="ml-1">
            <div class="px-5 py-4 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <span class="h-2 w-2 bg-slate-400 inline-block"></span>
                    <p class="text-[10px] font-mono font-bold text-slate-500 uppercase tracking-widest">03 // Party & Notes</p>
                </div>
            </div>
            <div class="px-5 py-5 space-y-4">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="department" class="block text-sm font-bold text-slate-700 mb-1.5">Department</label>
                        <input type="text" name="department" id="department" form="borrow-edit-form" value="{{ old('department', $borrow->department) }}"
                            class="block w-full border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:outline-none py-2.5 px-3 text-sm text-slate-800 transition-colors">
                        @error('department') <p class="mt-1 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="bio_id" class="block text-sm font-bold text-slate-700 mb-1.5">Bio ID</label>
                        <input type="text" name="bio_id" id="bio_id" form="borrow-edit-form" value="{{ old('bio_id', $borrow->bio_id) }}"
                            class="block w-full border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:outline-none py-2.5 px-3 text-sm font-mono text-slate-800 transition-colors">
                        @error('bio_id') <p class="mt-1 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div>
                    <label for="notes" class="block text-sm font-bold text-slate-700 mb-1.5">Notes</label>
                    <textarea name="notes" id="notes" form="borrow-edit-form" rows="3"
                        class="block w-full border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:outline-none py-2.5 px-3 text-sm text-slate-800 transition-colors">{{ old('notes', $borrow->notes) }}</textarea>
                    @error('notes') <p class="mt-1 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>
    </div>

    {{-- Submit --}}
    <div class="flex items-center justify-end gap-3 pt-2">
        <a href="{{ route('admin.records.index', ['tab' => 'borrows']) }}"
            class="px-5 py-2.5 text-sm font-mono font-bold text-slate-500 hover:text-slate-800 border border-slate-200 hover:border-slate-300 transition-colors">Cancel</a>
        <button type="submit" form="borrow-edit-form"
            class="inline-flex items-center gap-2 bg-slate-900 px-6 py-2.5 text-[11px] font-mono font-bold text-white uppercase tracking-[0.15em] hover:bg-slate-700 transition-colors border border-slate-900">
            Save Changes
        </button>
    </div>

    </form>{{-- closes the borrow-edit-form opened in Quantities section --}}
</div>
@endsection
