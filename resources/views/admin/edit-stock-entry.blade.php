@extends('layouts.app')

@section('title', 'Edit Stock Entry')

@section('actions')
    <a href="{{ route('admin.records.index', ['tab' => 'stock-entries']) }}"
        class="inline-flex items-center gap-2 border border-sky-100 bg-white px-4 py-2 text-[11px] font-mono font-bold text-slate-600 uppercase tracking-widest hover:bg-sky-50 transition-colors">
        ← Back to Records
    </a>
@endsection

@section('content')
<div class="bg-white rounded-2xl overflow-hidden border border-sky-100">

    {{-- Page Header --}}
    <div class="p-6 border-b border-sky-100 flex items-center justify-between shrink-0 mb-6">
        <div>
            <p class="font-mono text-[10px] font-bold uppercase tracking-widest text-sky-500 mb-1">Admin://Stock//Edit</p>
            <h3 class="text-xl font-black text-[#0f172a] tracking-tight">Edit Stock Entry</h3>
        </div>
    </div>

    <div class="p-6 pt-0">
        <div class="mb-5">
        <p class="text-[10px] font-mono font-semibold text-emerald-600 uppercase tracking-[0.25em] mb-1">Admin://Stock-Entries//Edit</p>
        <h1 class="text-xl font-bold text-[#0f172a] tracking-tight">Edit Stock Entry</h1>
        <p class="text-xs text-slate-400 font-mono mt-0.5">Item: <span class="font-bold text-slate-600">{{ $stockEntry->item->name ?? '—' }}</span></p>
    </div>

    @if ($errors->any())
        <div class="mb-5 bg-rose-50 border border-rose-200 relative px-5 py-4">
            <div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-rose-400 to-rose-600"></div>
            <ul class="space-y-1">
                @foreach ($errors->all() as $error)
                    <li class="text-sm text-rose-700">— {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white border border-sky-100 relative">
        <div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-emerald-400 to-emerald-600"></div>
        <div class="ml-1">
            <div class="px-5 py-4 border-b border-sky-100">
                <div class="flex items-center gap-2">
                    <span class="h-2 w-2 bg-emerald-500 inline-block"></span>
                    <p class="text-[10px] font-mono font-bold text-emerald-600 uppercase tracking-widest">// Stock Entry Fields</p>
                </div>
            </div>
            <form action="{{ route('admin.stock-entries.update', $stockEntry) }}" method="POST" class="px-5 py-5 space-y-4">
                @csrf @method('PATCH')

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="quantity" class="block text-sm font-bold text-slate-700 mb-1.5">Quantity</label>
                        <input type="number" name="quantity" id="quantity" value="{{ old('quantity', $stockEntry->quantity) }}" min="0" @if($stockEntry->item->item_type === 'device') readonly @endif
                            class="block w-full border border-sky-100 {{ $stockEntry->item->item_type === 'device' ? 'bg-sky-50 cursor-not-allowed' : 'bg-slate-50 focus:bg-white focus:border-emerald-500 focus:outline-none' }} py-2.5 px-3 text-sm font-mono text-[#0f172a] transition-colors">
                        @error('quantity') <p class="mt-1 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror
                    </div>
                    
                    @if($stockEntry->item->item_type === 'device')
                    <div>
                        <label for="serial_number" class="block text-sm font-bold text-slate-700 mb-1.5">Serial Number <span class="text-slate-400 font-normal">(Optional)</span></label>
                        <input type="text" name="serial_number" id="serial_number" value="{{ old('serial_number', $stockEntry->serial_number === 'N/A' ? '' : $stockEntry->serial_number) }}"
                            class="block w-full border border-sky-100 bg-slate-50 focus:bg-white focus:border-emerald-500 focus:outline-none py-2.5 px-3 text-sm font-mono text-[#0f172a] transition-colors" placeholder="Leave blank for N/A">
                        @error('serial_number') <p class="mt-1 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror
                    </div>
                    @else
                    <div>
                        <label for="lot_number" class="block text-sm font-bold text-slate-700 mb-1.5">Lot Number</label>
                        <input type="text" name="lot_number" id="lot_number" value="{{ old('lot_number', $stockEntry->lot_number) }}"
                            class="block w-full border border-sky-100 bg-slate-50 focus:bg-white focus:border-emerald-500 focus:outline-none py-2.5 px-3 text-sm font-mono text-[#0f172a] transition-colors">
                        @error('lot_number') <p class="mt-1 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="expiry_date" class="block text-sm font-bold text-slate-700 mb-1.5">Expiry Date</label>
                        <input type="date" name="expiry_date" id="expiry_date" value="{{ old('expiry_date', $stockEntry->expiry_date?->format('Y-m-d')) }}"
                            class="block w-full border border-sky-100 bg-slate-50 focus:bg-white focus:border-emerald-500 focus:outline-none py-2.5 px-3 text-sm font-mono text-[#0f172a] transition-colors">
                        @error('expiry_date') <p class="mt-1 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror
                    </div>
                    @endif
                    <div>
                        <label for="received_date" class="block text-sm font-bold text-slate-700 mb-1.5">Received Date</label>
                        <input type="date" name="received_date" id="received_date" value="{{ old('received_date', $stockEntry->received_date?->format('Y-m-d')) }}"
                            class="block w-full border border-sky-100 bg-slate-50 focus:bg-white focus:border-emerald-500 focus:outline-none py-2.5 px-3 text-sm font-mono text-[#0f172a] transition-colors">
                        @error('received_date') <p class="mt-1 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div>
                    <label for="notes" class="block text-sm font-bold text-slate-700 mb-1.5">Notes</label>
                    <textarea name="notes" id="notes" rows="3"
                        class="block w-full border border-sky-100 bg-slate-50 focus:bg-white focus:border-emerald-500 focus:outline-none py-2.5 px-3 text-sm text-[#0f172a] transition-colors">{{ old('notes', $stockEntry->notes) }}</textarea>
                    @error('notes') <p class="mt-1 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                    <a href="{{ route('admin.records.index', ['tab' => 'stock-entries']) }}"
                        class="px-5 py-2.5 text-sm font-mono font-bold text-slate-500 hover:text-[#0f172a] border border-sky-100 hover:border-slate-300 transition-colors">Cancel</a>
                    <button type="submit"
                        class="inline-flex items-center gap-2 bg-slate-900 px-6 py-2.5 text-[11px] font-mono font-bold text-white uppercase tracking-[0.15em] hover:bg-slate-700 transition-colors border border-slate-900">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
    </div>
</div>
@endsection
