import re

with open('resources/views/items/show.blade.php', 'r') as f:
    content = f.read()

# 1. Update the KPI Stats grid
old_kpi = """        {{-- Stock Summary Cards --}}
        <div class="grid grid-cols-2 gap-3 mb-5 lg:grid-cols-5">
            {{-- New Stock --}}
            <div class="bg-white border border-sky-100 relative">
                <div class="absolute top-0 left-0 w-1 h-full bg-emerald-400"></div>
                <div class="p-4 pl-5">
                    <p class="text-[10px] font-mono font-bold text-slate-400 uppercase tracking-widest mb-1">New Stock</p>
                    <p class="text-2xl font-black font-mono text-emerald-600">{{ $item->total_stock }}</p>
                    <p class="text-[10px] font-mono text-slate-500 mt-0.5">{{ $item->unit }}</p>
                </div>
            </div>
            {{-- Used Stock --}}
            <div class="bg-white border border-sky-100 relative">
                <div class="absolute top-0 left-0 w-1 h-full bg-amber-400"></div>
                <div class="p-4 pl-5">
                    <p class="text-[10px] font-mono font-bold text-slate-400 uppercase tracking-widest mb-1">Used Stock</p>
                    <p class="text-2xl font-black font-mono text-amber-500">{{ $item->effective_stock_used }}</p>
                    <p class="text-[10px] font-mono text-slate-500 mt-0.5">{{ $item->unit }}</p>
                </div>
            </div>
            {{-- Lent Out --}}
            <div class="bg-white border border-sky-100 relative">
                <div class="absolute top-0 left-0 w-1 h-full bg-indigo-400"></div>
                <div class="p-4 pl-5">
                    <p class="text-[10px] font-mono font-bold text-slate-400 uppercase tracking-widest mb-1">Lent Out</p>
                    <p class="text-2xl font-black font-mono text-sky-600">{{ $item->active_lent_out }}</p>
                    <p class="text-[10px] font-mono text-slate-500 mt-0.5">{{ $item->unit }}</p>
                </div>
            </div>

            {{-- Category --}}
            <div class="bg-white border border-sky-100 relative">
                <div class="absolute top-0 left-0 w-1 h-full bg-fuchsia-400"></div>
                <div class="p-4 pl-5">
                    <p class="text-[10px] font-mono font-bold text-slate-400 uppercase tracking-widest mb-1">Category</p>
                    <p class="text-sm font-bold font-mono text-fuchsia-600 truncate">{{ $item->category?->name ?? 'Uncategorized' }}</p>
                </div>
            </div>
            {{-- Status --}}
            <div class="bg-white border border-sky-100 relative">
                @php
                    $newStock  = max(0, $item->total_stock);
                    $usedStock = max(0, $item->effective_stock_used);
                    $totalQty  = $newStock + $usedStock;
                @endphp
                <div class="absolute top-0 left-0 w-1 h-full
                    @if($totalQty <= 0) bg-rose-500 @elseif($item->is_low_stock && $totalQty <= $item->reorder_level) bg-amber-400 @else bg-emerald-400 @endif"></div>
                <div class="p-4 pl-5">
                    <p class="text-[10px] font-mono font-bold text-slate-400 uppercase tracking-widest mb-1">Status</p>
                    @if($totalQty <= 0)
                        <span class="text-[10px] font-mono font-bold uppercase tracking-wider text-rose-600 bg-rose-50 px-2 py-1 border border-rose-200">Out_of_Stock</span>
                    @elseif($item->total_stock <= $item->reorder_level)
                        <span class="text-[10px] font-mono font-bold uppercase tracking-wider text-amber-600 bg-amber-50 px-2 py-1 border border-amber-200">Reorder</span>
                    @else
                        <span class="text-[10px] font-mono font-bold uppercase tracking-wider text-emerald-600 bg-emerald-50 px-2 py-1 border border-emerald-200">In_Stock</span>
                    @endif
                </div>
            </div>
        </div>"""

new_kpi = """        {{-- Stock Summary Cards --}}
        <div class="grid grid-cols-2 lg:grid-cols-5 border-y border-x border-sky-100 mb-6 bg-white overflow-hidden rounded-xl">
            
            {{-- New Stock --}}
            <div class="group relative overflow-hidden bg-white p-5 border-r border-b lg:border-b-0 border-sky-100 transition-colors duration-300 hover:bg-sky-50/30">
                <div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-emerald-400 to-emerald-600"></div>
                <div class="flex flex-col h-full justify-between gap-2">
                    <p class="font-mono text-[10px] font-bold uppercase tracking-widest text-sky-500">New Stock</p>
                    <div class="flex items-baseline gap-1.5 mt-1">
                        <p class="text-3xl font-black tracking-tight text-[#0f172a]">{{ $item->total_stock }}</p>
                        <p class="text-[10px] font-mono text-slate-400">{{ $item->unit }}</p>
                    </div>
                </div>
            </div>
            
            {{-- Used Stock --}}
            <div class="group relative overflow-hidden bg-white p-5 border-r border-b lg:border-b-0 border-sky-100 transition-colors duration-300 hover:bg-sky-50/30">
                <div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-amber-400 to-amber-600"></div>
                <div class="flex flex-col h-full justify-between gap-2">
                    <p class="font-mono text-[10px] font-bold uppercase tracking-widest text-sky-500">Used Stock</p>
                    <div class="flex items-baseline gap-1.5 mt-1">
                        <p class="text-3xl font-black tracking-tight text-[#0f172a]">{{ $item->effective_stock_used }}</p>
                        <p class="text-[10px] font-mono text-slate-400">{{ $item->unit }}</p>
                    </div>
                </div>
            </div>
            
            {{-- Lent Out --}}
            <div class="group relative overflow-hidden bg-white p-5 border-r border-b lg:border-b-0 border-sky-100 transition-colors duration-300 hover:bg-sky-50/30">
                <div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-indigo-400 to-indigo-600"></div>
                <div class="flex flex-col h-full justify-between gap-2">
                    <p class="font-mono text-[10px] font-bold uppercase tracking-widest text-sky-500">Lent Out</p>
                    <div class="flex items-baseline gap-1.5 mt-1">
                        <p class="text-3xl font-black tracking-tight text-[#0f172a]">{{ $item->active_lent_out }}</p>
                        <p class="text-[10px] font-mono text-slate-400">{{ $item->unit }}</p>
                    </div>
                </div>
            </div>

            {{-- Category --}}
            <div class="group relative overflow-hidden bg-white p-5 border-r border-b lg:border-b-0 border-sky-100 transition-colors duration-300 hover:bg-sky-50/30">
                <div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-fuchsia-400 to-fuchsia-600"></div>
                <div class="flex flex-col h-full justify-between gap-2">
                    <p class="font-mono text-[10px] font-bold uppercase tracking-widest text-sky-500">Category</p>
                    <p class="text-base font-black tracking-tight text-[#0f172a] truncate mt-1">{{ $item->category?->name ?? 'Uncategorized' }}</p>
                </div>
            </div>

            {{-- Status --}}
            @php
                $newStock  = max(0, $item->total_stock);
                $usedStock = max(0, $item->effective_stock_used);
                $totalQty  = $newStock + $usedStock;
                if ($totalQty <= 0) {
                    $statusTone = 'from-rose-500 to-rose-700';
                } elseif ($item->is_low_stock && $totalQty <= $item->reorder_level) {
                    $statusTone = 'from-amber-400 to-amber-600';
                } else {
                    $statusTone = 'from-emerald-400 to-emerald-600';
                }
            @endphp
            <div class="group relative overflow-hidden bg-white p-5 transition-colors duration-300 hover:bg-sky-50/30">
                <div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r {{ $statusTone }}"></div>
                <div class="flex flex-col h-full justify-between gap-2 items-start">
                    <p class="font-mono text-[10px] font-bold uppercase tracking-widest text-sky-500">Status</p>
                    <div class="mt-1">
                    @if($totalQty <= 0)
                        <span class="text-[10px] font-mono font-bold uppercase tracking-wider text-rose-600 bg-rose-50 px-2 py-1 border border-rose-200">Out_of_Stock</span>
                    @elseif($item->total_stock <= $item->reorder_level)
                        <span class="text-[10px] font-mono font-bold uppercase tracking-wider text-amber-600 bg-amber-50 px-2 py-1 border border-amber-200">Reorder</span>
                    @else
                        <span class="text-[10px] font-mono font-bold uppercase tracking-wider text-emerald-600 bg-emerald-50 px-2 py-1 border border-emerald-200">In_Stock</span>
                    @endif
                    </div>
                </div>
            </div>
            
        </div>"""

if old_kpi in content:
    content = content.replace(old_kpi, new_kpi)

# 2. Update panel borders
content = content.replace('<div class="bg-white border border-sky-100 relative">', '<div class="bg-white border border-sky-100 relative overflow-hidden">')

# Replace the left-borders with top-borders
borders_map = {
    '<div class="absolute top-0 left-0 w-1 h-full bg-blue-500"></div>': '<div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-blue-400 to-blue-600"></div>',
    '<div class="absolute top-0 left-0 w-1 h-full bg-sky-500"></div>': '<div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-sky-400 to-sky-600"></div>',
    '<div class="absolute top-0 left-0 w-1 h-full bg-rose-500"></div>': '<div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-rose-400 to-rose-600"></div>',
    '<div class="absolute top-0 left-0 w-1 h-full bg-teal-500"></div>': '<div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-teal-400 to-teal-600"></div>',
}

for old_border, new_border in borders_map.items():
    content = content.replace(old_border, new_border)

# Remove the 'ml-1' wrappers if present since we don't have left border anymore
# Actually they are '<div class="ml-1">' after the border.
content = content.replace('<div class="ml-1">', '<div>')

with open('resources/views/items/show.blade.php', 'w') as f:
    f.write(content)

print("Done updating show.blade.php layout")
