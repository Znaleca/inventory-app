@extends('layouts.app')

@section('title', 'Categories')

@section('actions')
    <a href="{{ route('categories.create') }}"
        class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 text-[11px] font-mono font-bold uppercase tracking-[0.15em] transition-colors border border-blue-700">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-3.5 w-3.5">
            <path d="M8.75 3.75a.75.75 0 00-1.5 0v3.5h-3.5a.75.75 0 000 1.5h3.5v3.5a.75.75 0 001.5 0v-3.5h3.5a.75.75 0 000-1.5h-3.5v-3.5z" />
        </svg>
        New_Category
    </a>
@endsection

@section('content')

{{-- Page Header --}}
<div class="mb-5 flex items-end justify-between">
    <div>
        <p class="text-[10px] font-mono font-semibold text-blue-600 uppercase tracking-[0.25em] mb-1">Inventory://Categories</p>
        <h1 class="text-xl font-bold text-slate-800 tracking-tight">Category Registry</h1>
    </div>
    <span class="text-[10px] font-mono text-slate-400">{{ $categories->count() }} records</span>
</div>

@if(session('success'))
    <div class="mb-4 bg-emerald-50 border border-emerald-200 relative px-5 py-3">
        <div class="absolute top-0 left-0 w-1 h-full bg-emerald-500"></div>
        <p class="text-sm font-mono font-bold text-emerald-700 ml-1">{{ session('success') }}</p>
    </div>
@endif

{{-- Main Table --}}
<div class="bg-white border border-slate-200 relative overflow-hidden">
    <div class="absolute top-0 left-0 w-1 h-full bg-indigo-500"></div>

    @if($categories->count() > 0)
    @php
        $deviceCats = $categories->where('item_type', 'device');
        $consumableCats = $categories->where('item_type', 'consumable');
        $groups = collect([
            ['label' => 'Device / Equipment', 'color' => 'violet', 'items' => $deviceCats],
            ['label' => 'Consumable', 'color' => 'indigo', 'items' => $consumableCats],
        ])->filter(fn($g) => $g['items']->count() > 0);
    @endphp

    <div class="overflow-x-auto">
        @foreach($groups as $group)
        {{-- Group header --}}
        <div class="flex items-center gap-2 px-5 py-2.5 border-b border-dashed border-slate-100 bg-slate-50/50 ml-1">
            <span class="h-2 w-2 inline-block {{ $group['color'] === 'violet' ? 'bg-violet-500' : 'bg-indigo-500' }}"></span>
            <span class="text-[10px] font-mono font-bold uppercase tracking-widest {{ $group['color'] === 'violet' ? 'text-violet-600' : 'text-indigo-600' }}">
                {{ $group['label'] }}
            </span>
            <span class="text-[10px] font-mono text-slate-400">{{ $group['items']->count() }} {{ Str::plural('category', $group['items']->count()) }}</span>
        </div>

        <table class="min-w-full text-sm">
            <thead>
                <tr class="border-b border-slate-100 bg-slate-50/80">
                    <th scope="col" class="whitespace-nowrap pl-5 pr-3 py-3 text-left text-[10px] font-mono font-bold uppercase tracking-[0.2em] text-slate-400">Category Name</th>
                    <th scope="col" class="whitespace-nowrap px-3 py-3 text-left text-[10px] font-mono font-bold uppercase tracking-[0.2em] text-slate-400">Description</th>
                    <th scope="col" class="whitespace-nowrap px-3 py-3 text-center text-[10px] font-mono font-bold uppercase tracking-[0.2em] text-slate-400">Items</th>
                    <th scope="col" class="whitespace-nowrap px-3 pr-5 py-3 text-right text-[10px] font-mono font-bold uppercase tracking-[0.2em] text-slate-400">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($group['items'] as $category)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="pl-5 pr-3 py-3">
                        <span class="text-sm font-bold text-slate-800">{{ $category->name }}</span>
                    </td>
                    <td class="px-3 py-3">
                        <span class="text-xs font-mono text-slate-500 line-clamp-1 max-w-[280px]">{{ $category->description ?: '—' }}</span>
                    </td>
                    <td class="whitespace-nowrap px-3 py-3 text-center">
                        <span class="font-mono text-[11px] font-bold text-slate-600 bg-slate-50 border border-slate-200 px-2.5 py-1.5">
                            {{ number_format($category->items_count) }}
                        </span>
                    </td>
                    <td class="whitespace-nowrap px-3 pr-5 py-3 text-right">
                        <div class="flex items-center justify-end gap-1.5">
                            <a href="{{ route('categories.edit', $category) }}"
                                class="inline-flex items-center border border-slate-200 bg-slate-50 px-2.5 py-1.5 text-[10px] font-mono font-bold text-slate-600 hover:bg-slate-800 hover:text-white hover:border-slate-800 transition-colors">
                                Edit
                            </a>
                            <form action="{{ route('categories.destroy', $category) }}" method="POST" class="m-0"
                                onsubmit="return confirm('Delete this category?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center border border-rose-200 bg-rose-50 px-2.5 py-1.5 text-[10px] font-mono font-bold text-rose-600 hover:bg-rose-500 hover:text-white hover:border-rose-500 transition-colors">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if(!$loop->last)
            <div class="border-t border-slate-100 ml-1"></div>
        @endif
        @endforeach
    </div>

    @else
    {{-- Empty State --}}
    <div class="flex flex-col items-center justify-center py-20 text-center ml-1">
        <div class="h-14 w-14 border border-slate-200 bg-slate-50 flex items-center justify-center text-slate-400 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-7 w-7">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25h-2.25a2.25 2.25 0 01-2.25-2.25v-2.25z" />
            </svg>
        </div>
        <p class="font-mono text-[10px] text-slate-400 uppercase tracking-widest mb-1">// No records found</p>
        <p class="text-sm font-semibold text-slate-500 mt-1">No categories yet. Create the first one.</p>
        <a href="{{ route('categories.create') }}"
            class="mt-6 inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 text-[11px] font-mono font-bold uppercase tracking-widest transition-colors border border-blue-700">
            + New Category
        </a>
    </div>
    @endif
</div>

@endsection