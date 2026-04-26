@extends('layouts.app')

@section('title', $disposalType === 'new' ? 'Dispose Expired Items' : 'Dispose Used Items')

@section('actions')
<a href="{{ route('items.show', $item) }}"
    class="inline-flex items-center gap-2 border border-sky-100 bg-white px-4 py-2 text-[11px] font-mono font-bold text-slate-600 uppercase tracking-widest hover:bg-sky-50 transition-colors">
    ← Back to Item
</a>
@endsection

@section('content')
<div x-data="{
    selectedEntry: '{{ old('stock_entry_id', '') }}',
    manualQty: {{ old('quantity', 1) }},
    get selectedBatch() {
        return batches.find(b => String(b.id) === String(this.selectedEntry)) ?? null;
    },
    batches: @json($batches)
}">

    <div class="bg-white rounded-2xl overflow-hidden border border-sky-100">
        {{-- Page Header --}}
        <div class="p-6 border-b border-sky-100 flex items-center justify-between shrink-0 mb-6">
            <div>
                <p class="font-mono text-[10px] font-bold uppercase tracking-widest mb-1
                    {{ $disposalType === 'new' ? 'text-rose-500' : 'text-amber-500' }}">
                    Inventory://{{ $item->name }}//Dispose
                </p>
                <h3 class="text-xl font-black text-[#0f172a] tracking-tight">
                    {{ $disposalType === 'new' ? 'Dispose Expired Stock' : 'Dispose Used Items' }}
                </h3>
                <p class="text-xs text-slate-400 font-mono mt-1">
                    {{ $disposalType === 'new'
                        ? 'Select expired batch to remove from stock.'
                        : 'Select used item batch to permanently remove.' }}
                </p>
            </div>
        </div>

        <div class="p-6 pt-0">

    @if($errors->any())
    <div class="mb-5 bg-rose-50 border border-rose-200 relative px-5 py-4">
        <div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-rose-400 to-rose-600"></div>
        <p class="text-[10px] font-mono font-bold text-rose-600 uppercase tracking-widest mb-2">// Validation Errors</p>
        <ul class="space-y-1">
            @foreach($errors->all() as $error)
                <li class="text-sm text-rose-700 font-mono">— {{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('disposals.store') }}" method="POST">
        @csrf
        <input type="hidden" name="item_id" value="{{ $item->id }}">
        <input type="hidden" name="disposal_type" value="{{ $disposalType }}">

        {{-- Summary Banner --}}
        <div class="bg-white rounded-2xl overflow-hidden border border-sky-100 relative mb-4">
            <div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r {{ $disposalType === 'new' ? 'from-rose-400 to-rose-600' : 'from-amber-400 to-amber-600' }}"></div>
            <div class="px-5 py-4 flex items-center gap-4">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center
                    {{ $disposalType === 'new' ? 'bg-rose-50 text-rose-600' : 'bg-amber-50 text-amber-600' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-mono font-bold uppercase tracking-widest
                        {{ $disposalType === 'new' ? 'text-rose-600' : 'text-amber-600' }}">
                        {{ $disposalType === 'new' ? '// Expired Stock Disposal' : '// Used Item Disposal' }}
                    </p>
                    <p class="text-sm font-bold text-[#0f172a] mt-0.5">{{ $item->name }}</p>
                    <p class="text-xs font-mono text-slate-500 mt-0.5">
                        {{ $disposalType === 'new' ? 'Total expired:' : 'Total used stock:' }}
                        <span class="font-bold {{ $disposalType === 'new' ? 'text-rose-600' : 'text-amber-600' }}">
                            {{ $maxQty }} {{ $item->unit }}
                        </span>
                    </p>
                </div>
            </div>
        </div>

        {{-- Batch / Serial / Lot Picker --}}
        <div class="bg-white rounded-2xl overflow-hidden border border-sky-100 relative mb-4">
            <div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-sky-400 to-sky-600"></div>
            <div>
                <div class="px-5 py-4 border-b border-sky-100">
                    <div class="flex items-center gap-2">
                        <span class="h-2 w-2 {{ $disposalType === 'new' ? 'bg-rose-400' : 'bg-amber-400' }} inline-block"></span>
                        <p class="text-[10px] font-mono font-bold uppercase tracking-widest
                            {{ $disposalType === 'new' ? 'text-rose-600' : 'text-amber-600' }}">
                            {{ $item->item_type === 'device' ? '// Select Serial Number' : '// Select Batch / Lot' }}
                        </p>
                    </div>
                    <p class="text-xs font-mono text-slate-400 mt-1">
                        Choose the specific {{ $item->item_type === 'device' ? 'device (serial number)' : 'batch (lot number)' }} to dispose.
                        Leave unselected to dispose without batch tracking.
                    </p>
                </div>
                <div class="px-5 py-5">
                    @if(!empty($batches))
                    <div class="space-y-2">
                        {{-- None / unspecified option --}}
                        <label class="flex items-start gap-3 p-3 border border-sky-100 cursor-pointer transition-colors hover:bg-sky-50"
                            :class="selectedEntry === '' ? 'border-slate-400 bg-sky-50' : 'border-sky-100'">
                            <input type="radio" name="stock_entry_id" value=""
                                x-model="selectedEntry"
                                class="mt-0.5 accent-slate-600">
                            <div>
                                <p class="text-xs font-mono font-bold text-slate-600">— No specific batch</p>
                                <p class="text-[10px] font-mono text-slate-400 mt-0.5">Dispose without linking to a specific batch</p>
                            </div>
                        </label>

                        {{-- Per-batch options --}}
                        @foreach($batches as $batch)
                        <label class="flex items-start gap-3 p-3 border cursor-pointer transition-colors hover:bg-sky-50"
                            :class="selectedEntry === '{{ $batch['id'] }}' ?
                                '{{ $disposalType === 'new' ? 'border-rose-400 bg-rose-50' : 'border-amber-400 bg-amber-50' }}' :
                                'border-sky-100'">
                            <input type="radio" name="stock_entry_id" value="{{ $batch['id'] }}"
                                x-model="selectedEntry"
                                class="mt-0.5 {{ $disposalType === 'new' ? 'accent-rose-600' : 'accent-amber-600' }}">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    @if($item->item_type === 'device')
                                        <span class="text-xs font-mono font-bold text-[#0f172a]">
                                            SN: {{ $batch['serial_number'] ?? 'N/A' }}
                                        </span>
                                    @else
                                        <span class="text-xs font-mono font-bold text-[#0f172a]">
                                            Lot: {{ $batch['lot_number'] ?? 'No Lot #' }}
                                        </span>
                                    @endif
                                    <span class="inline-flex items-center border px-1.5 py-0.5 text-[9px] font-mono font-bold uppercase tracking-widest
                                        {{ $disposalType === 'new' ? 'border-rose-200 bg-rose-50 text-rose-700' : 'border-amber-200 bg-amber-50 text-amber-700' }}">
                                        {{ $batch['remaining'] }} {{ $item->unit }} available
                                    </span>
                                </div>
                                <div class="flex items-center gap-3 mt-1 text-[10px] font-mono text-slate-400 flex-wrap">
                                    @if($disposalType === 'new' && !empty($batch['expiry_date']))
                                        <span>Expired: {{ \Carbon\Carbon::parse($batch['expiry_date'])->format('M d, Y') }}</span>
                                    @endif
                                    @if(!empty($batch['received_date']))
                                        <span>Received: {{ \Carbon\Carbon::parse($batch['received_date'])->format('M d, Y') }}</span>
                                    @endif
                                </div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                    @else
                    <p class="text-xs font-mono text-slate-400">// No specific batches found. You can still proceed with a general disposal.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Amount & Time --}}
        <div class="bg-white rounded-2xl overflow-hidden border border-sky-100 relative mb-4">
            <div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-slate-300 to-slate-500"></div>
            <div>
                <div class="px-5 py-4 border-b border-sky-100">
                    <div class="flex items-center gap-2">
                        <span class="h-2 w-2 bg-slate-400 inline-block"></span>
                        <p class="text-[10px] font-mono font-bold text-slate-500 uppercase tracking-widest">// Amount &amp; Time</p>
                    </div>
                </div>
                <div class="px-5 py-5">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="quantity" class="block text-[10px] font-mono font-bold text-slate-500 uppercase tracking-widest mb-1.5">
                                Quantity to Dispose <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                {{-- For devices: auto-set to 1 when a batch is selected; otherwise manual --}}
                                @if($item->item_type === 'device')
                                <input type="number" name="quantity" id="quantity"
                                    :value="selectedEntry !== '' ? 1 : manualQty"
                                    :readonly="selectedEntry !== ''"
                                    min="1" max="{{ $maxQty }}"
                                    x-model="manualQty"
                                    class="block w-full border border-sky-100 bg-sky-50 focus:bg-white focus:border-slate-500 focus:outline-none py-2.5 px-3 pr-14 text-sm font-mono text-[#0f172a] transition-colors"
                                    :class="selectedEntry !== '' ? 'opacity-60 cursor-not-allowed' : ''"
                                    required>
                                @else
                                <input type="number" name="quantity" id="quantity"
                                    :max="selectedEntry !== '' ? (selectedBatch ? selectedBatch.remaining : {{ $maxQty }}) : {{ $maxQty }}"
                                    min="1" max="{{ $maxQty }}"
                                    value="{{ old('quantity', 1) }}"
                                    x-model="manualQty"
                                    class="block w-full border border-sky-100 bg-sky-50 focus:bg-white focus:border-slate-500 focus:outline-none py-2.5 px-3 pr-14 text-sm font-mono text-[#0f172a] transition-colors"
                                    required>
                                @endif
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                    <span class="text-slate-400 text-[10px] font-mono font-bold uppercase">{{ $item->unit }}</span>
                                </div>
                            </div>
                            @if($item->item_type === 'device')
                            <p class="mt-1 text-[10px] font-mono text-slate-400" x-show="selectedEntry !== ''">
                                // Devices: quantity auto-locked to 1 per batch
                            </p>
                            @endif
                            @error('quantity') <p class="mt-1 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="disposed_at" class="block text-[10px] font-mono font-bold text-slate-500 uppercase tracking-widest mb-1.5">
                                Date &amp; Time <span class="text-rose-500">*</span>
                            </label>
                            <input type="datetime-local" name="disposed_at" id="disposed_at"
                                value="{{ old('disposed_at', now()->format('Y-m-d\TH:i')) }}"
                                class="block w-full border border-sky-100 bg-sky-50 focus:bg-white focus:border-slate-500 focus:outline-none py-2.5 px-3 text-sm font-mono text-[#0f172a] transition-colors"
                                required>
                            @error('disposed_at') <p class="mt-1 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Documentation --}}
        <div class="bg-white rounded-2xl overflow-hidden border border-sky-100 relative mb-4">
            <div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-slate-300 to-slate-500"></div>
            <div>
                <div class="px-5 py-4 border-b border-sky-100">
                    <div class="flex items-center gap-2">
                        <span class="h-2 w-2 bg-slate-400 inline-block"></span>
                        <p class="text-[10px] font-mono font-bold text-slate-500 uppercase tracking-widest">// Documentation</p>
                    </div>
                </div>
                <div class="px-5 py-5 space-y-4">
                    <div>
                        <label for="disposed_by" class="block text-[10px] font-mono font-bold text-slate-500 uppercase tracking-widest mb-1.5">
                            Disposed By <span class="text-rose-500">*</span>
                        </label>
                        <select name="disposed_by" id="disposed_by" required
                            class="block w-full border border-sky-100 bg-sky-50 focus:bg-white focus:border-slate-500 focus:outline-none py-2.5 px-3 text-sm text-[#0f172a] transition-colors">
                            <option value="" disabled {{ old('disposed_by') ? '' : 'selected' }}>— Select staff member —</option>
                            @foreach($staffList as $staff)
                            <option value="{{ $staff->display_name }}"
                                {{ old('disposed_by') === $staff->display_name ? 'selected' : '' }}>
                                {{ $staff->display_name }}@if($staff->specialization) — {{ $staff->specialization }}@endif
                            </option>
                            @endforeach
                        </select>
                        @error('disposed_by') <p class="mt-1 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="reason" class="block text-[10px] font-mono font-bold text-slate-500 uppercase tracking-widest mb-1.5">
                            Reason for Disposal <span class="text-rose-500">*</span>
                        </label>
                        <textarea id="reason" name="reason" rows="3"
                            class="block w-full border border-sky-100 bg-sky-50 focus:bg-white focus:border-slate-500 focus:outline-none py-2.5 px-3 text-sm text-[#0f172a] placeholder:text-slate-400 transition-colors"
                            placeholder="{{ $disposalType === 'new' ? 'e.g. Expired stock, past safe use date...' : 'e.g. End of procedure, damage, contamination...' }}"
                            required>{{ old('reason', $disposalType === 'new' ? 'Expired stock' : '') }}</textarea>
                        @error('reason') <p class="mt-1 text-xs font-mono font-bold text-rose-500">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('items.show', $item) }}"
                class="px-5 py-2.5 text-sm font-mono font-bold text-slate-500 hover:text-[#0f172a] border border-sky-100 hover:border-slate-300 transition-colors">
                Cancel
            </a>
            <button type="submit"
                class="inline-flex items-center gap-2 px-6 py-2.5 text-[11px] font-mono font-bold text-white uppercase tracking-[0.15em] transition-colors border
                {{ $disposalType === 'new'
                    ? 'bg-rose-600 hover:bg-rose-700 border-rose-600'
                    : 'bg-amber-600 hover:bg-amber-700 border-amber-600' }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-3.5 w-3.5">
                    <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 006 3.75V4H2.75a.75.75 0 000 1.5h.3l.815 8.15A1.5 1.5 0 005.357 15h5.285a1.5 1.5 0 001.493-1.35l.815-8.15h.3a.75.75 0 000-1.5H10v-.25a2.75 2.75 0 00-2.75-2.75zM7.5 3.75V4h1v-.25a1.25 1.25 0 00-1.25-1.25z" clip-rule="evenodd"/>
                </svg>
                {{ $disposalType === 'new' ? 'Dispose Expired Stock' : 'Confirm Disposal' }}
            </button>
        </div>
    </form>

</div>
@endsection