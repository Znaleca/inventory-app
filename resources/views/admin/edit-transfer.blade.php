@extends('layouts.app')

@section('title', 'Edit Transfer Record')

@section('actions')
    <a href="{{ route('admin.records.index', ['tab' => 'transfers']) }}"
        class="inline-flex items-center gap-2 border border-sky-100 bg-white px-4 py-2 text-[11px] font-mono font-bold text-slate-600 uppercase tracking-widest hover:bg-sky-50 transition-colors">
        ← Back to Records
    </a>
@endsection

@section('content')
<div class="bg-white rounded-2xl overflow-hidden border border-sky-100">

    {{-- Page Header --}}
    <div class="p-6 border-b border-sky-100 flex items-center justify-between shrink-0 mb-6">
        <div>
            <p class="font-mono text-[10px] font-bold uppercase tracking-widest text-sky-500 mb-1">Admin://Transfers//Edit</p>
            <h3 class="text-xl font-black text-[#0f172a] tracking-tight">Edit Transfer Record</h3>
        </div>
    </div>

    <div class="p-6 pt-0">
        <div class="mb-5">

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
        <div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-amber-400 to-amber-600"></div>
        <div class="ml-1">
            <div class="px-5 py-4 border-b border-sky-100">
                <div class="flex items-center gap-2">
                    <span class="h-2 w-2 bg-amber-500 inline-block"></span>
                    <p class="text-[10px] font-mono font-bold text-amber-600 uppercase tracking-widest">// Transfer Fields</p>
                </div>
            </div>
            <form action="{{ route('admin.transfers.update', $transfer) }}" method="POST" class="px-5 py-5 space-y-4">
                @csrf @method('PATCH')

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="quantity" class="block text-sm font-bold text-slate-700 mb-1.5">Quantity</label>
                        <input type="number" name="quantity" id="quantity" value="{{ old('quantity', $transfer->quantity) }}" min="0"
                            class="block w-full border border-sky-100 bg-slate-50 focus:bg-white focus:border-amber-500 focus:outline-none py-2.5 px-3 text-sm font-mono text-[#0f172a] transition-colors">
                        @error('quantity') <p class="mt-1 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="destination" class="block text-sm font-bold text-slate-700 mb-1.5">Destination</label>
                        <input type="text" name="destination" id="destination" value="{{ old('destination', $transfer->destination) }}"
                            class="block w-full border border-sky-100 bg-slate-50 focus:bg-white focus:border-amber-500 focus:outline-none py-2.5 px-3 text-sm text-[#0f172a] transition-colors">
                        @error('destination') <p class="mt-1 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="transferred_by" class="block text-sm font-bold text-slate-700 mb-1.5">Transferred By</label>
                        <input type="text" name="transferred_by" id="transferred_by" value="{{ old('transferred_by', $transfer->transferred_by) }}"
                            class="block w-full border border-sky-100 bg-slate-50 focus:bg-white focus:border-amber-500 focus:outline-none py-2.5 px-3 text-sm text-[#0f172a] transition-colors">
                        @error('transferred_by') <p class="mt-1 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="transferred_at" class="block text-sm font-bold text-slate-700 mb-1.5">Date</label>
                        <input type="date" name="transferred_at" id="transferred_at" value="{{ old('transferred_at', $transfer->transferred_at?->format('Y-m-d')) }}"
                            class="block w-full border border-sky-100 bg-slate-50 focus:bg-white focus:border-amber-500 focus:outline-none py-2.5 px-3 text-sm font-mono text-[#0f172a] transition-colors">
                        @error('transferred_at') <p class="mt-1 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="bio_id" class="block text-sm font-bold text-slate-700 mb-1.5">Bio ID <span class="font-normal text-slate-400">(Optional)</span></label>
                        <input type="text" name="bio_id" id="bio_id" value="{{ old('bio_id', $transfer->bio_id) }}"
                            class="block w-full border border-sky-100 bg-slate-50 focus:bg-white focus:border-amber-500 focus:outline-none py-2.5 px-3 text-sm font-mono text-[#0f172a] transition-colors">
                        @error('bio_id') <p class="mt-1 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    @if(optional($transfer->item)->item_type === 'device')
                    <div class="sm:col-span-2">
                        <label for="serial_number" class="block text-sm font-bold text-slate-700 mb-1.5">Serial Number(s)</label>
                        
                        @if($transfer->type === 'out')
                        @php
                            $currentSerials = ($transfer->serial_number && $transfer->serial_number !== 'N/A') ? array_map('trim', explode(',', $transfer->serial_number)) : [];
                            $oldSerials = old('serial_number');
                            $initialSerials = is_array($oldSerials) ? $oldSerials : $currentSerials;
                        @endphp
                        <div x-data="{ selectedSerials: {{ json_encode($initialSerials) }} }" class="border border-sky-100 bg-slate-50 max-h-60 overflow-y-auto divide-y divide-sky-50">
                            @forelse($deviceSerials ?? [] as $serial)
                            <label class="flex items-center gap-3 px-4 py-3 cursor-pointer hover:bg-white transition-colors" :class="selectedSerials.includes('{{ addslashes($serial) }}') ? 'bg-amber-50 border-l-2 border-l-amber-500 -ml-[2px]' : ''">
                                <input type="checkbox" name="serial_number[]" value="{{ $serial }}" class="w-4 h-4 accent-amber-600" x-model="selectedSerials">
                                <span class="text-sm font-mono text-[#0f172a] font-bold">SN: {{ $serial }}</span>
                            </label>
                            @empty
                            <div class="px-4 py-6 text-center text-slate-500 text-sm font-mono bg-white">
                                <p class="text-[11px] font-mono text-slate-400">// No known serial numbers for this device</p>
                            </div>
                            @endforelse
                        </div>
                        @else
                        <input type="text" name="serial_number" id="serial_number" value="{{ old('serial_number', $transfer->serial_number === 'N/A' ? '' : $transfer->serial_number) }}"
                            class="block w-full border border-sky-100 bg-slate-50 focus:bg-white focus:border-amber-500 focus:outline-none py-2.5 px-3 text-sm font-mono text-[#0f172a] transition-colors" placeholder="Leave blank for N/A">
                        @endif

                        @error('serial_number') <p class="mt-1 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror
                    </div>
                    @endif
                </div>

                <div>
                    <label for="notes" class="block text-sm font-bold text-slate-700 mb-1.5">Notes</label>
                    <textarea name="notes" id="notes" rows="3"
                        class="block w-full border border-sky-100 bg-slate-50 focus:bg-white focus:border-amber-500 focus:outline-none py-2.5 px-3 text-sm text-[#0f172a] transition-colors">{{ old('notes', $transfer->notes) }}</textarea>
                    @error('notes') <p class="mt-1 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                    <a href="{{ route('admin.records.index', ['tab' => 'transfers']) }}"
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
