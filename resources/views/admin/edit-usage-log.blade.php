@extends('layouts.app')

@section('title', 'Edit Usage Log')

@section('actions')
    <a href="{{ route('admin.records.index', ['tab' => 'usage-logs']) }}"
        class="inline-flex items-center gap-2 border border-sky-100 bg-white px-4 py-2 text-[11px] font-mono font-bold text-slate-600 uppercase tracking-widest hover:bg-sky-50 transition-colors">
        ← Back to Records
    </a>
@endsection

@section('content')
<div class="bg-white rounded-2xl overflow-hidden border border-sky-100">

    {{-- Page Header --}}
    <div class="p-6 border-b border-sky-100 flex items-center justify-between shrink-0 mb-6">
        <div>
            <p class="font-mono text-[10px] font-bold uppercase tracking-widest text-sky-500 mb-1">Admin://Usage//Edit</p>
            <h3 class="text-xl font-black text-[#0f172a] tracking-tight">Edit Usage Log</h3>
        </div>
    </div>

    <div class="p-6 pt-0">
        <div class="mb-5">
        <p class="text-[10px] font-mono font-semibold text-rose-600 uppercase tracking-[0.25em] mb-1">Admin://Usage-Logs//Edit</p>
        <h1 class="text-xl font-bold text-[#0f172a] tracking-tight">Edit Usage Log</h1>
        <p class="text-xs text-slate-400 font-mono mt-0.5">Item: <span class="font-bold text-slate-600">{{ $usageLog->item->name ?? '—' }}</span></p>
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
        <div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-rose-400 to-rose-600"></div>
        <div class="ml-1">
            <div class="px-5 py-4 border-b border-sky-100">
                <div class="flex items-center gap-2">
                    <span class="h-2 w-2 bg-rose-500 inline-block"></span>
                    <p class="text-[10px] font-mono font-bold text-rose-600 uppercase tracking-widest">// Usage Log Fields</p>
                </div>
            </div>
            <form action="{{ route('admin.usage-logs.update', $usageLog) }}" method="POST" class="px-5 py-5 space-y-4">
                @csrf @method('PATCH')

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div>
                        <label for="quantity_used" class="block text-sm font-bold text-slate-700 mb-1.5">Qty Used</label>
                        <input type="number" name="quantity_used" id="quantity_used" value="{{ old('quantity_used', $usageLog->quantity_used) }}" min="0" @if(optional($usageLog->item)->item_type === 'device') readonly @endif
                            class="block w-full border border-sky-100 {{ optional($usageLog->item)->item_type === 'device' ? 'bg-sky-50 cursor-not-allowed' : 'bg-slate-50 focus:bg-white focus:border-rose-500 focus:outline-none' }} py-2.5 px-3 text-sm font-mono text-[#0f172a] transition-colors">
                        @error('quantity_used') <p class="mt-1 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror
                    </div>
                    
                    @if(optional($usageLog->item)->item_type === 'device')
                    <div class="sm:col-span-2">
                        <label for="stock_entry_id" class="block text-sm font-bold text-slate-700 mb-1.5">Serial Number</label>
                        <select name="stock_entry_id" id="stock_entry_id" class="block w-full border border-sky-100 bg-slate-50 focus:bg-white focus:border-rose-500 focus:outline-none py-2.5 px-3 text-sm font-mono text-[#0f172a] transition-colors">
                            <option value="">-- Select Serial Number --</option>
                            @foreach($stockEntries as $entry)
                                <option value="{{ $entry->id }}" {{ old('stock_entry_id', $usageLog->stock_entry_id) == $entry->id ? 'selected' : '' }}>
                                    SN: {{ $entry->serial_number }}
                                </option>
                            @endforeach
                        </select>
                        @error('stock_entry_id') <p class="mt-1 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror
                    </div>
                    @else
                    <div>
                        <label for="used_by" class="block text-sm font-bold text-slate-700 mb-1.5">Used By</label>
                        <input type="text" name="used_by" id="used_by" value="{{ old('used_by', $usageLog->used_by) }}"
                            class="block w-full border border-sky-100 bg-slate-50 focus:bg-white focus:border-rose-500 focus:outline-none py-2.5 px-3 text-sm text-[#0f172a] transition-colors">
                        @error('used_by') <p class="mt-1 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror
                    </div>
                    @endif
                    <div>
                        <label for="used_at" class="block text-sm font-bold text-slate-700 mb-1.5">Date Used</label>
                        <input type="date" name="used_at" id="used_at" value="{{ old('used_at', $usageLog->used_at?->format('Y-m-d')) }}"
                            class="block w-full border border-sky-100 bg-slate-50 focus:bg-white focus:border-rose-500 focus:outline-none py-2.5 px-3 text-sm font-mono text-[#0f172a] transition-colors">
                        @error('used_at') <p class="mt-1 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="notes" class="block text-sm font-bold text-slate-700 mb-1.5">Notes</label>
                    <textarea name="notes" id="notes" rows="3"
                        class="block w-full border border-sky-100 bg-slate-50 focus:bg-white focus:border-rose-500 focus:outline-none py-2.5 px-3 text-sm text-[#0f172a] transition-colors">{{ old('notes', $usageLog->notes) }}</textarea>
                    @error('notes') <p class="mt-1 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                    <a href="{{ route('admin.records.index', ['tab' => 'usage-logs']) }}"
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
