@extends('layouts.app')

@section('title', 'Edit Disposal Record')

@section('actions')
    <a href="{{ route('admin.records.index', ['tab' => 'disposals']) }}"
        class="inline-flex items-center gap-2 border border-slate-200 bg-white px-4 py-2 text-[11px] font-mono font-bold text-slate-600 uppercase tracking-widest hover:bg-slate-50 transition-colors">
        ← Back to Records
    </a>
@endsection

@section('content')
<div>

    <div class="mb-5">
        <p class="text-[10px] font-mono font-semibold text-slate-600 uppercase tracking-[0.25em] mb-1">Admin://Disposals//Edit</p>
        <h1 class="text-xl font-bold text-slate-800 tracking-tight">Edit Disposal Record</h1>
        <p class="text-xs text-slate-400 font-mono mt-0.5">Item: <span class="font-bold text-slate-600">{{ $disposal->item->name ?? '—' }}</span></p>
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

    <div class="bg-white border border-slate-200 relative">
        <div class="absolute top-0 left-0 w-1 h-full bg-slate-600"></div>
        <div class="ml-1">
            <div class="px-5 py-4 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <span class="h-2 w-2 bg-slate-600 inline-block"></span>
                    <p class="text-[10px] font-mono font-bold text-slate-600 uppercase tracking-widest">// Disposal Fields</p>
                </div>
            </div>
            <form action="{{ route('admin.disposals.update', $disposal) }}" method="POST" class="px-5 py-5 space-y-4">
                @csrf @method('PATCH')

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="quantity" class="block text-sm font-bold text-slate-700 mb-1.5">Quantity</label>
                        <input type="number" name="quantity" id="quantity" value="{{ old('quantity', $disposal->quantity) }}" min="0" @if(optional($disposal->item)->item_type === 'device') readonly @endif
                            class="block w-full border border-slate-200 {{ optional($disposal->item)->item_type === 'device' ? 'bg-slate-100 cursor-not-allowed' : 'bg-slate-50 focus:bg-white focus:border-slate-500 focus:outline-none' }} py-2.5 px-3 text-sm font-mono text-slate-800 transition-colors">
                        @error('quantity') <p class="mt-1 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror
                    </div>
                    
                    @if(optional($disposal->item)->item_type === 'device')
                    <div class="sm:col-span-2">
                        <label for="stock_entry_id" class="block text-sm font-bold text-slate-700 mb-1.5">Serial Number</label>
                        <select name="stock_entry_id" id="stock_entry_id" class="block w-full border border-slate-200 bg-slate-50 focus:bg-white focus:border-slate-500 focus:outline-none py-2.5 px-3 text-sm font-mono text-slate-800 transition-colors">
                            <option value="">-- Select Serial Number --</option>
                            @foreach($stockEntries as $entry)
                                <option value="{{ $entry->id }}" {{ old('stock_entry_id', $disposal->stock_entry_id) == $entry->id ? 'selected' : '' }}>
                                    SN: {{ $entry->serial_number }}
                                </option>
                            @endforeach
                        </select>
                        @error('stock_entry_id') <p class="mt-1 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror
                    </div>
                    @endif

                    <div>
                        <label for="disposed_by" class="block text-sm font-bold text-slate-700 mb-1.5">Disposed By</label>
                        <input type="text" name="disposed_by" id="disposed_by" value="{{ old('disposed_by', $disposal->disposed_by) }}"
                            class="block w-full border border-slate-200 bg-slate-50 focus:bg-white focus:border-slate-500 focus:outline-none py-2.5 px-3 text-sm text-slate-800 transition-colors">
                        @error('disposed_by') <p class="mt-1 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label for="disposed_at" class="block text-sm font-bold text-slate-700 mb-1.5">Date</label>
                        <input type="date" name="disposed_at" id="disposed_at" value="{{ old('disposed_at', $disposal->disposed_at?->format('Y-m-d')) }}"
                            class="block w-full border border-slate-200 bg-slate-50 focus:bg-white focus:border-slate-500 focus:outline-none py-2.5 px-3 text-sm font-mono text-slate-800 transition-colors">
                        @error('disposed_at') <p class="mt-1 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="reason" class="block text-sm font-bold text-slate-700 mb-1.5">Reason</label>
                    <textarea name="reason" id="reason" rows="3"
                        class="block w-full border border-slate-200 bg-slate-50 focus:bg-white focus:border-slate-500 focus:outline-none py-2.5 px-3 text-sm text-slate-800 transition-colors">{{ old('reason', $disposal->reason) }}</textarea>
                    @error('reason') <p class="mt-1 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                    <a href="{{ route('admin.records.index', ['tab' => 'disposals']) }}"
                        class="px-5 py-2.5 text-sm font-mono font-bold text-slate-500 hover:text-slate-800 border border-slate-200 hover:border-slate-300 transition-colors">Cancel</a>
                    <button type="submit"
                        class="inline-flex items-center gap-2 bg-slate-900 px-6 py-2.5 text-[11px] font-mono font-bold text-white uppercase tracking-[0.15em] hover:bg-slate-700 transition-colors border border-slate-900">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
