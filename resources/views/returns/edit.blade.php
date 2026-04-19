@extends('layouts.app')

@section('title', 'Process Return')

@section('actions')
    <a href="{{ route('in-out.index', ['tab' => 'return']) }}"
        class="inline-flex items-center gap-2 border border-slate-200 bg-white px-4 py-2 text-[11px] font-mono font-bold text-slate-600 uppercase tracking-widest hover:bg-slate-50 transition-colors">
        ← Back to Returns
    </a>
@endsection

@section('content')
<div class="mx-auto max-w-3xl">

    <div class="mb-5">
        <p class="text-[10px] font-mono font-semibold text-teal-600 uppercase tracking-[0.25em] mb-1">Returns://Process</p>
        <h1 class="text-xl font-bold text-slate-800 tracking-tight">Process Return</h1>
        <p class="text-xs text-slate-400 font-mono mt-0.5">Record items being returned to stock or marked as consumed.</p>
    </div>

    @if ($errors->any())
        <div class="mb-5 bg-rose-50 border border-rose-200 relative px-5 py-4">
            <div class="absolute top-0 left-0 w-1 h-full bg-rose-500"></div>
            <p class="font-mono text-[10px] text-rose-600 uppercase tracking-widest font-bold mb-2 ml-1">// Errors</p>
            <ul class="ml-1 space-y-1">
                @foreach ($errors->all() as $error)
                    <li class="text-sm text-rose-700">— {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @php
        $bNewOut      = $borrow->new_quantity  ?? 0;
        $bUsedOut     = $borrow->used_quantity ?? 0;
        $bPendingNew  = max(0, $bNewOut - $borrow->quantity_returned);
        $bPendingUsed = $bUsedOut;
        $totalPending = $borrow->quantity_borrowed - $borrow->quantity_returned - $borrow->quantity_used;
        $hasEntries   = $borrowEntries->count() > 0;
    @endphp

    <form action="{{ route('returns.update', $borrow) }}" method="POST"
        x-data="{
            hasEntries: {{ $hasEntries ? 'true' : 'false' }},
            selected: {},
            dispositions: {},
            conditions: {
                @foreach($borrowEntries as $e)
                '{{ $e->id }}': '{{ $e->original_condition }}',
                @endforeach
            },
            selectedCount() { return Object.values(this.selected).filter(Boolean).length; },
            allSet() {
                const sel = Object.keys(this.selected).filter(k => this.selected[k]);
                return sel.length > 0 && sel.every(k => this.dispositions[k]);
            },
            toggle(id) {
                this.selected[id] = !this.selected[id];
                if (!this.selected[id]) {
                    delete this.dispositions[id];
                } else if (this.conditions[id] === 'used') {
                    this.dispositions[id] = 'returned_used';
                }
            }
        }">
        @csrf
        @method('PUT')

        <div class="space-y-4">

            {{-- ======================== --}}
            {{-- SECTION 1: Summary      --}}
            {{-- ======================== --}}
            <div class="bg-white border border-slate-200 relative">
                <div class="absolute top-0 left-0 w-1 h-full bg-teal-500"></div>
                <div class="px-5 py-4 ml-1">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="h-2 w-2 bg-teal-500 inline-block"></span>
                        <p class="text-[10px] font-mono font-bold text-teal-600 uppercase tracking-widest">01 // Borrow Summary</p>
                    </div>

                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                        <div class="bg-slate-50 border border-slate-200 px-4 py-3">
                            <p class="text-[10px] font-mono font-bold uppercase tracking-widest text-slate-400 mb-1">Item</p>
                            <p class="text-sm font-bold text-slate-800 line-clamp-1">{{ $borrow->item->name }}</p>
                            <p class="text-[10px] font-mono text-slate-400 mt-0.5 uppercase tracking-wider">{{ strtoupper($borrow->item->item_type) }}</p>
                        </div>
                        <div class="bg-slate-50 border border-slate-200 px-4 py-3">
                            <p class="text-[10px] font-mono font-bold uppercase tracking-widest text-slate-400 mb-1">Borrowed By</p>
                            <p class="text-sm font-bold text-slate-800">{{ $borrow->borrower_name ?? $borrow->staff?->display_name ?? 'Unknown' }}</p>
                            <p class="text-[10px] font-mono text-slate-500 mt-0.5 uppercase tracking-wider">{{ $borrow->department ?? '' }}</p>
                        </div>
                        <div class="bg-teal-50 border border-teal-200 px-4 py-3 relative">
                            <div class="absolute top-0 left-0 w-1 h-full bg-teal-400"></div>
                            <p class="text-[10px] font-mono font-bold uppercase tracking-widest text-teal-600 mb-1 ml-1">Pending Return</p>
                            @if($bNewOut > 0 || $bUsedOut > 0)
                                @if($bPendingNew > 0)
                                <div class="flex items-center gap-1.5 ml-1 mt-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3 text-teal-600 shrink-0"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-11.25a.75.75 0 00-1.5 0v2.5h-2.5a.75.75 0 000 1.5h2.5v2.5a.75.75 0 001.5 0v-2.5h2.5a.75.75 0 000-1.5h-2.5v-2.5z" clip-rule="evenodd" /></svg>
                                    <span class="text-xl font-black text-teal-700">{{ $bPendingNew }}</span>
                                    <span class="text-[10px] font-mono font-bold text-teal-600 uppercase">NEW {{ $borrow->item->unit }}</span>
                                </div>
                                @endif
                                @if($bPendingUsed > 0)
                                <div class="flex items-center gap-1.5 ml-1 mt-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3 text-amber-600 shrink-0"><path fill-rule="evenodd" d="M15.312 11.424a5.5 5.5 0 01-9.201 2.466l-.312-.311h2.433a.75.75 0 000-1.5H3.989a.75.75 0 00-.75.75v4.242a.75.75 0 001.5 0v-2.43l.31.31a7 7 0 0011.712-3.138.75.75 0 00-1.449-.39zm1.23-3.723a.75.75 0 00.219-.53V2.929a.75.75 0 00-1.5 0V5.36l-.31-.31A7 7 0 003.239 8.188a.75.75 0 101.448.389A5.5 5.5 0 0113.89 6.11l.311.31h-2.432a.75.75 0 000 1.5h4.243a.75.75 0 00.53-.219z" clip-rule="evenodd" /></svg>
                                    <span class="text-xl font-black text-amber-700">{{ $bPendingUsed }}</span>
                                    <span class="text-[10px] font-mono font-bold text-amber-700 uppercase">USED {{ $borrow->item->unit }}</span>
                                </div>
                                @endif
                            @else
                                <div class="ml-1 mt-1">
                                    <span class="text-xl font-black text-teal-700">{{ $totalPending }}</span>
                                    <span class="text-[10px] font-mono font-bold text-teal-600 uppercase ml-1">{{ $borrow->item->unit }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- ============================================ --}}
            {{-- SECTION 2A: Device Per-Unit Checklist       --}}
            {{-- ============================================ --}}
            @if($hasEntries)
            <div class="bg-white border border-indigo-200 relative">
                <div class="absolute top-0 left-0 w-1 h-full bg-indigo-500"></div>
                <div class="ml-1">
                    <div class="px-5 py-4 border-b border-dashed border-slate-100 flex items-center justify-between">
                        <div>
                            <div class="flex items-center gap-2 mb-0.5">
                                <span class="h-2 w-2 bg-indigo-500 inline-block"></span>
                                <p class="text-[10px] font-mono font-bold text-indigo-600 uppercase tracking-widest">02 // Device Disposition</p>
                            </div>
                            <p class="text-xs text-slate-500">Check devices being returned now, then set their outcome.</p>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="text-2xl font-black font-mono text-indigo-600 leading-none" x-text="selectedCount()"></p>
                            <p class="text-[10px] font-mono text-slate-400 uppercase tracking-widest">of {{ $borrowEntries->count() }} returning</p>
                        </div>
                    </div>

                    {{-- Legend --}}
                    <div class="px-5 py-2.5 bg-slate-50 border-b border-slate-100 flex flex-wrap gap-4 text-[10px] font-mono">
                        <span class="flex items-center gap-1.5 text-emerald-700 font-bold">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-11.25a.75.75 0 00-1.5 0v2.5h-2.5a.75.75 0 000 1.5h2.5v2.5a.75.75 0 001.5 0v-2.5h2.5a.75.75 0 000-1.5h-2.5v-2.5z" clip-rule="evenodd" /></svg>
                            RETURN NEW → back to new stock
                        </span>
                        <span class="flex items-center gap-1.5 text-amber-700 font-bold">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3"><path fill-rule="evenodd" d="M15.312 11.424a5.5 5.5 0 01-9.201 2.466l-.312-.311h2.433a.75.75 0 000-1.5H3.989a.75.75 0 00-.75.75v4.242a.75.75 0 001.5 0v-2.43l.31.31a7 7 0 0011.712-3.138.75.75 0 00-1.449-.39zm1.23-3.723a.75.75 0 00.219-.53V2.929a.75.75 0 00-1.5 0V5.36l-.31-.31A7 7 0 003.239 8.188a.75.75 0 101.448.389A5.5 5.5 0 0113.89 6.11l.311.31h-2.432a.75.75 0 000 1.5h4.243a.75.75 0 00.53-.219z" clip-rule="evenodd" /></svg>
                            MARK USED → goes to used pool
                        </span>
                    </div>

                    <div class="divide-y divide-slate-100">
                        @foreach($borrowEntries as $entry)
                        @php $entryId = $entry->id; @endphp
                        <div class="transition-colors"
                            :class="selected['{{ $entryId }}'] ? 'bg-indigo-50/40 border-l-2 border-l-indigo-400' : 'border-l-2 border-l-transparent'">

                            {{-- Checkbox row --}}
                            <label class="flex items-center gap-4 px-5 py-3 cursor-pointer hover:bg-slate-50 transition-colors"
                                @click.prevent="toggle('{{ $entryId }}')"
                                :class="selected['{{ $entryId }}'] ? 'hover:bg-indigo-50/60' : ''">

                                {{-- Checkbox --}}
                                <div class="shrink-0 w-5 h-5 border-2 flex items-center justify-center transition-colors"
                                    :class="selected['{{ $entryId }}'] ? 'bg-indigo-600 border-indigo-600' : 'border-slate-300 bg-white'">
                                    <svg x-show="selected['{{ $entryId }}']" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5 text-white">
                                        <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                                    </svg>
                                </div>

                                {{-- Device Info --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        @if($entry->original_condition === 'new')
                                        <span class="flex items-center gap-1 text-[9px] font-mono font-bold text-teal-700 bg-teal-100 border border-teal-200 px-1.5 py-0.5 uppercase tracking-widest shrink-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-2.5 h-2.5"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-11.25a.75.75 0 00-1.5 0v2.5h-2.5a.75.75 0 000 1.5h2.5v2.5a.75.75 0 001.5 0v-2.5h2.5a.75.75 0 000-1.5h-2.5v-2.5z" clip-rule="evenodd" /></svg>
                                            WAS NEW
                                        </span>
                                        @else
                                        <span class="flex items-center gap-1 text-[9px] font-mono font-bold text-amber-700 bg-amber-100 border border-amber-200 px-1.5 py-0.5 uppercase tracking-widest shrink-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-2.5 h-2.5"><path fill-rule="evenodd" d="M15.312 11.424a5.5 5.5 0 01-9.201 2.466l-.312-.311h2.433a.75.75 0 000-1.5H3.989a.75.75 0 00-.75.75v4.242a.75.75 0 001.5 0v-2.43l.31.31a7 7 0 0011.712-3.138.75.75 0 00-1.449-.39zm1.23-3.723a.75.75 0 00.219-.53V2.929a.75.75 0 00-1.5 0V5.36l-.31-.31A7 7 0 003.239 8.188a.75.75 0 101.448.389A5.5 5.5 0 0113.89 6.11l.311.31h-2.432a.75.75 0 000 1.5h4.243a.75.75 0 00.53-.219z" clip-rule="evenodd" /></svg>
                                            WAS USED
                                        </span>
                                        @endif
                                        <p class="text-sm font-bold font-mono text-slate-800">
                                            {{ $entry->stockEntry->serial_number ?? ('Device #' . $entry->stockEntry->id) }}
                                        </p>
                                    </div>
                                    @if($entry->stockEntry->serial_number)
                                    <p class="text-[10px] font-mono text-slate-400 mt-0.5">SN: {{ $entry->stockEntry->serial_number }}</p>
                                    @endif
                                </div>

                                {{-- Not selected hint --}}
                                <p x-show="!selected['{{ $entryId }}']" class="text-[10px] font-mono text-slate-400 shrink-0">click to include</p>
                            </label>

                            {{-- Disposition (only when selected) --}}
                            <div x-show="selected['{{ $entryId }}']" class="px-5 pb-3 pl-[52px]">
                                <div class="flex flex-wrap gap-2">
                                    @if($entry->original_condition === 'new')
                                    <label class="flex items-center gap-1.5 cursor-pointer border px-3 py-2 text-[10px] font-mono font-bold uppercase tracking-widest transition-colors"
                                        :class="dispositions['{{ $entryId }}'] === 'returned_new' ? 'bg-emerald-600 border-emerald-600 text-white' : 'bg-white border-slate-200 text-slate-600 hover:border-emerald-400 hover:text-emerald-700'">
                                        <input type="radio" name="dispositions[{{ $entryId }}]" value="returned_new"
                                            x-model="dispositions['{{ $entryId }}']" class="sr-only">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-11.25a.75.75 0 00-1.5 0v2.5h-2.5a.75.75 0 000 1.5h2.5v2.5a.75.75 0 001.5 0v-2.5h2.5a.75.75 0 000-1.5h-2.5v-2.5z" clip-rule="evenodd" /></svg>
                                        Return New
                                    </label>
                                    @endif
                                    
                                    <label class="flex items-center gap-1.5 cursor-pointer border px-3 py-2 text-[10px] font-mono font-bold uppercase tracking-widest transition-colors"
                                        :class="dispositions['{{ $entryId }}'] === 'returned_used' ? 'bg-amber-500 border-amber-500 text-white' : 'bg-white border-slate-200 text-slate-600 hover:border-amber-400 hover:text-amber-700'">
                                        <input type="radio" name="dispositions[{{ $entryId }}]" value="returned_used"
                                            x-model="dispositions['{{ $entryId }}']" class="sr-only">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3"><path fill-rule="evenodd" d="M15.312 11.424a5.5 5.5 0 01-9.201 2.466l-.312-.311h2.433a.75.75 0 000-1.5H3.989a.75.75 0 00-.75.75v4.242a.75.75 0 001.5 0v-2.43l.31.31a7 7 0 0011.712-3.138.75.75 0 00-1.449-.39zm1.23-3.723a.75.75 0 00.219-.53V2.929a.75.75 0 00-1.5 0V5.36l-.31-.31A7 7 0 003.239 8.188a.75.75 0 101.448.389A5.5 5.5 0 0113.89 6.11l.311.31h-2.432a.75.75 0 000 1.5h4.243a.75.75 0 00.53-.219z" clip-rule="evenodd" /></svg>
                                        Mark Used
                                    </label>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- ============================================ --}}
            {{-- SECTION 2B: Qty-based (non-device / legacy) --}}
            {{-- ============================================ --}}
            @else
            <div class="bg-white border border-slate-200 relative"
                x-data="{
                    pending: {{ $totalPending }},
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
                <div class="absolute top-0 left-0 w-1 h-full bg-emerald-500"></div>
                <div class="px-5 py-4 ml-1">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="h-2 w-2 bg-emerald-500 inline-block"></span>
                        <p class="text-[10px] font-mono font-bold text-emerald-600 uppercase tracking-widest">02 // Process Quantities</p>
                    </div>

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1.5 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4 text-emerald-500"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-11.25a.75.75 0 00-1.5 0v2.5h-2.5a.75.75 0 000 1.5h2.5v2.5a.75.75 0 001.5 0v-2.5h2.5a.75.75 0 000-1.5h-2.5v-2.5z" clip-rule="evenodd" /></svg>
                                Quantity Returning Intact
                            </label>
                            <input type="number" name="quantity_returning" min="0" :max="pending"
                                x-model.number="intact" @input="onIntactChange()" required
                                class="block w-full border border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-500 focus:outline-none py-2.5 px-3 text-sm font-mono text-slate-800 transition-colors">
                            <p class="mt-1.5 text-[10px] font-mono text-emerald-600 font-bold">→ Returns to available stock</p>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1.5 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4 text-rose-500"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" /></svg>
                                Quantity Used / Consumed
                            </label>
                            <input type="number" name="quantity_using" min="0" :max="pending"
                                x-model.number="used" @input="onUsedChange()" required
                                class="block w-full border border-rose-200 bg-rose-50/30 focus:bg-white focus:border-rose-500 focus:outline-none py-2.5 px-3 text-sm font-mono text-slate-800 transition-colors">
                            <p class="mt-1.5 text-[10px] font-mono text-rose-600 font-bold">→ Deducted from stock</p>
                        </div>
                    </div>

                    <div class="mt-5 border px-4 py-3 flex items-center justify-between transition-all"
                        :class="overMax ? 'bg-rose-50 border-rose-200' : 'bg-slate-50 border-slate-200'">
                        <div class="flex items-center gap-3 font-mono text-sm">
                            <span :class="overMax ? 'text-rose-600 font-bold' : 'text-slate-600'">Total:</span>
                            <span class="font-black text-lg" :class="overMax ? 'text-rose-600' : 'text-slate-900'" x-text="total"></span>
                            <span class="text-slate-400 text-xs">of</span>
                            <span class="font-black text-lg text-teal-700" x-text="pending"></span>
                            <span class="text-slate-400 text-xs">pending</span>
                        </div>
                        <div x-show="overMax" x-cloak class="flex items-center gap-1 text-[11px] font-mono font-bold text-rose-600">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5"><path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" /></svg>
                            Exceeds pending
                        </div>
                        <div x-show="!overMax && total === pending" x-cloak class="flex items-center gap-1 text-[11px] font-mono font-bold text-emerald-600">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" /></svg>
                            Fully accounted
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- ======================== --}}
            {{-- SECTION 3: Date / Notes --}}
            {{-- ======================== --}}
            <div class="bg-white border border-slate-200 relative">
                <div class="absolute top-0 left-0 w-1 h-full bg-slate-400"></div>
                <div class="px-5 py-4 ml-1">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="h-2 w-2 bg-slate-400 inline-block"></span>
                        <p class="text-[10px] font-mono font-bold text-slate-500 uppercase tracking-widest">0{{ $hasEntries ? '3' : '3' }} // Time &amp; Notes</p>
                    </div>
                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-slate-700 mb-1.5">Date &amp; Time Returned <span class="text-rose-500">*</span></label>
                            <input type="datetime-local" name="returned_at" required
                                value="{{ old('returned_at', now()->format('Y-m-d\TH:i')) }}"
                                class="block w-full border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:outline-none py-2.5 px-3 text-sm font-mono text-slate-800 transition-colors">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-slate-700 mb-1.5">Notes <span class="font-normal text-slate-400">(Optional)</span></label>
                            <textarea name="notes" rows="3"
                                class="block w-full border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:outline-none py-2.5 px-3 text-sm text-slate-800 transition-colors placeholder:text-slate-400"
                                placeholder="Condition on return, context...">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('in-out.index', ['tab' => 'return']) }}"
                    class="px-5 py-2.5 text-sm font-mono font-bold text-slate-500 hover:text-slate-800 transition-colors border border-slate-200 hover:border-slate-300">
                    Cancel
                </a>
                @if($hasEntries)
                <button type="submit"
                    :disabled="!allSet()"
                    :class="!allSet() ? 'opacity-40 cursor-not-allowed bg-slate-400 border-slate-400' : 'bg-teal-600 hover:bg-teal-700 border-teal-700'"
                    class="inline-flex items-center gap-2 text-white px-6 py-2.5 text-[11px] font-mono font-bold uppercase tracking-[0.15em] transition-colors border">
                    Process Return
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                        <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                    </svg>
                </button>
                @else
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-teal-600 hover:bg-teal-700 border border-teal-700 text-white px-6 py-2.5 text-[11px] font-mono font-bold uppercase tracking-[0.15em] transition-colors">
                    Process Return
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                        <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                    </svg>
                </button>
                @endif
            </div>

        </div>
    </form>
</div>
@endsection