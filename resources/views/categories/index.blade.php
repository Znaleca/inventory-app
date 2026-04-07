@extends('layouts.app')

@section('title', 'Categories')

@section('actions')
<a href="{{ route('categories.create') }}"
    class="group relative inline-flex items-center gap-2 overflow-hidden rounded-2xl bg-gradient-to-br from-slate-800 to-slate-900 px-5 py-2.5 text-sm font-bold text-white shadow-[0_8px_16px_-6px_rgba(15,23,42,0.5)] transition-all duration-300 hover:-translate-y-0.5 hover:shadow-[0_12px_20px_-6px_rgba(15,23,42,0.6)] focus:outline-none focus:ring-2 focus:ring-slate-900 focus:ring-offset-2">
    <div
        class="absolute inset-0 bg-gradient-to-b from-white/10 to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100">
    </div>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
        class="relative h-4 w-4 transition-transform duration-300 group-hover:rotate-90">
        <path
            d="M8.75 3.75a.75.75 0 00-1.5 0v3.5h-3.5a.75.75 0 000 1.5h3.5v3.5a.75.75 0 001.5 0v-3.5h3.5a.75.75 0 000-1.5h-3.5v-3.5z" />
    </svg>
    <span class="relative">New Category</span>
</a>
@endsection

@section('content')
{{-- Main Table Card --}}
<div
    class="overflow-hidden rounded-[1.5rem] bg-white/80 ring-1 ring-slate-200/50 shadow-[0_8px_30px_-12px_rgba(0,0,0,0.06)] backdrop-blur-xl">

    @if($categories->count() > 0)
    <div class="overflow-x-auto p-2">
        <table class="min-w-full text-sm border-separate border-spacing-y-1">
            <thead>
                <tr>
                    <th scope="col"
                        class="whitespace-nowrap px-3 py-2 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">
                        Category Identity</th>
                    <th scope="col"
                        class="whitespace-nowrap px-3 py-2 text-left text-[10px] font-bold uppercase tracking-widest text-slate-400">
                        Description</th>
                    <th scope="col"
                        class="whitespace-nowrap px-3 py-2 text-center text-[10px] font-bold uppercase tracking-widest text-slate-400">
                        Asset Count</th>
                    <th scope="col"
                        class="whitespace-nowrap px-3 py-2 text-right text-[10px] font-bold uppercase tracking-widest text-slate-400">
                        Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                <tr
                    class="group transition-all duration-200 hover:bg-white rounded-xl shadow-sm hover:shadow-md hover:ring-1 hover:ring-slate-200/50">

                    {{-- Name --}}
                    <td class="px-3 py-2.5 rounded-l-xl">
                        <span class="block text-sm font-bold text-slate-800">{{ $category->name }}</span>
                    </td>

                    {{-- Description --}}
                    <td class="px-3 py-2.5">
                        <span class="block text-xs font-medium text-slate-500 line-clamp-1 max-w-[250px]">
                            {{ $category->description ?: '—' }}
                        </span>
                    </td>

                    {{-- Item Count --}}
                    <td class="whitespace-nowrap px-3 py-2.5 text-center">
                        <span
                            class="inline-flex items-center justify-center rounded-lg bg-slate-100/80 px-2.5 py-1.5 text-[11px] font-bold text-slate-600 ring-1 ring-inset ring-slate-500/10">
                            {{ number_format($category->items_count) }} Items
                        </span>
                    </td>

                    {{-- Actions --}}
                    <td class="whitespace-nowrap px-3 py-2.5 text-right rounded-r-xl">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('categories.edit', $category) }}"
                                class="inline-flex items-center rounded-lg bg-slate-50 px-3 py-2 text-xs font-bold text-slate-600 ring-1 ring-inset ring-slate-500/20 transition-all hover:bg-slate-800 hover:text-white hover:shadow-md hover:shadow-slate-800/20">
                                Edit
                            </a>
                            <form action="{{ route('categories.destroy', $category) }}" method="POST" class="m-0"
                                onsubmit="return confirm('Delete this category?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center rounded-lg bg-rose-50 px-3 py-2 text-xs font-bold text-rose-600 ring-1 ring-inset ring-rose-500/20 transition-all hover:bg-rose-500 hover:text-white hover:shadow-md hover:shadow-rose-500/20">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    {{-- Glowing Empty State --}}
    <div class="relative flex flex-col items-center justify-center px-3 py-22 text-center group">
        <div class="absolute inset-0 flex items-center justify-center opacity-40">
            <div
                class="h-48 w-48 rounded-full bg-gradient-to-br from-indigo-200 to-purple-200 blur-3xl transition-transform duration-700 group-hover:scale-110">
            </div>
        </div>

        <div
            class="relative z-10 mb-6 flex h-20 w-20 items-center justify-center rounded-[1.5rem] bg-white ring-1 ring-slate-200/80 shadow-xl shadow-slate-200/40">
            <div class="absolute inset-0 rounded-[1.5rem] bg-gradient-to-br from-indigo-50/50 to-transparent"></div>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="relative h-8 w-8 text-indigo-400">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25a2.25 2.25 0 01-2.25 2.25h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25h-2.25a2.25 2.25 0 01-2.25-2.25v-2.25z" />
            </svg>
        </div>
        <h3 class="relative z-10 text-lg font-black text-slate-800">No categories found</h3>
        <p class="relative z-10 mt-2 max-w-sm text-sm font-medium leading-relaxed text-slate-500">
            Begin organizing your inventory by creating your first asset category.
        </p>

        <a href="{{ route('categories.create') }}"
            class="relative z-10 mt-8 group inline-flex items-center gap-2 overflow-hidden rounded-2xl bg-gradient-to-br from-slate-800 to-slate-900 px-3 py-2 text-sm font-bold text-white shadow-[0_8px_16px_-6px_rgba(15,23,42,0.5)] transition-all duration-300 hover:-translate-y-0.5 hover:shadow-[0_12px_20px_-6px_rgba(15,23,42,0.6)]">
            <div
                class="absolute inset-0 bg-gradient-to-b from-white/10 to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100">
            </div>
            <span class="relative">Create First Category</span>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                stroke="currentColor"
                class="relative h-4 w-4 transition-transform duration-300 group-hover:translate-x-1">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
            </svg>
        </a>
    </div>
    @endif
</div>
@endsection