@extends('layouts.app')

@section('title', 'Storage Locations Dashboard')

@section('actions')
<a href="{{ route('locations.create') }}"
    class="inline-flex items-center gap-2 bg-sky-500 hover:bg-sky-600 text-white px-4 py-2 text-[11px] font-mono font-bold uppercase tracking-[0.15em] transition-colors border border-blue-700">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-3.5 w-3.5">
        <path d="M8.75 3.75a.75.75 0 00-1.5 0v3.5h-3.5a.75.75 0 000 1.5h3.5v3.5a.75.75 0 001.5 0v-3.5h3.5a.75.75 0 000-1.5h-3.5v-3.5z" />
    </svg>
    New_Location
</a>
@endsection

@section('content')
@php
    $totalItems = (int) \App\Models\Item::whereNotNull('storage_location')->count();
    
    $storageItems = $storages->map(function($s) {
        return [
            'name' => $s->name,
            'items_count' => \App\Models\Item::where('storage_location', $s->name)->count()
        ];
    })->filter(function($s) { return $s['items_count'] > 0; });
    
    $sectionItems = $sections->map(function($s) {
        return [
            'name' => $s->name,
            'items_count' => \App\Models\Item::where('storage_section', $s->name)->count()
        ];
    })->filter(function($s) { return $s['items_count'] > 0; });
    
    $topStorages = $storages->map(function($s) {
        return [
            'name' => $s->name,
            'items_count' => \App\Models\Item::where('storage_location', $s->name)->count()
        ];
    })->sortByDesc('items_count')->take(6)->values();
    
    $storageItemCounts = $storages->map(function($s) {
        return \App\Models\Item::where('storage_location', $s->name)->count();
    })->values()->toArray();
    
    $sectionItemCounts = $sections->map(function($s) {
        return \App\Models\Item::where('storage_section', $s->name)->count();
    })->values()->toArray();
    
    $allLocationNames = array_merge($storages->pluck('name')->values()->toArray(), $sections->pluck('name')->values()->toArray());
    $allItemCounts = array_merge($storageItemCounts, $sectionItemCounts);
@endphp

<div x-data="{ search: '' }">
    @if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 relative px-5 py-3">
        <div class="absolute top-0 left-0 w-1 h-full bg-emerald-500"></div>
        <p class="text-sm font-mono font-bold text-emerald-700 ml-1">{{ session('success') }}</p>
    </div>
    @endif

    <div class="bg-white rounded-2xl overflow-hidden border border-slate-200 shadow-sm mb-6">
        <div class="grid grid-cols-1 border-slate-200 sm:grid-cols-2 lg:grid-cols-4">
            <div class="group relative overflow-hidden bg-white p-4 border-r border-b border-slate-100 transition-all hover:bg-slate-50 hover:shadow-inner lg:[&:nth-child(4n)]:border-r-0 sm:[&:nth-child(2n)]:border-r-0 lg:border-b-0 sm:border-b">
                <div class="flex items-center justify-between mb-3">
                    <p class="font-semibold text-xs text-slate-500 uppercase tracking-wider">Total Locations</p>
                    <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-slate-50 text-slate-400 transition-colors group-hover:bg-slate-200 group-hover:text-slate-600">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                           <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                        </svg>
                    </div>
                </div>
                <div class="z-10">
                    <p class="text-2xl font-black tracking-tight text-slate-800">{{ $storages->count() + $sections->count() }}</p>
                    <p class="mt-0.5 text-[10px] font-medium text-slate-400">All registered locations</p>
                </div>
            </div>

            <div class="group relative overflow-hidden bg-white p-4 border-r border-b border-slate-100 transition-all hover:bg-slate-50 hover:shadow-inner lg:[&:nth-child(4n)]:border-r-0 sm:[&:nth-child(2n)]:border-r-0 lg:border-b-0 sm:border-b">
                <div class="flex items-center justify-between mb-3">
                    <p class="font-semibold text-xs text-slate-500 uppercase tracking-wider">Storage Places</p>
                    <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 transition-colors group-hover:bg-emerald-100">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6z" />
                        </svg>
                    </div>
                </div>
                <div class="z-10">
                    <p class="text-2xl font-black tracking-tight text-slate-800">{{ $storages->count() }}</p>
                    <p class="mt-0.5 text-[10px] font-medium text-slate-400">Primary locations</p>
                </div>
            </div>

            <div class="group relative overflow-hidden bg-white p-4 border-r border-b border-slate-100 transition-all hover:bg-slate-50 hover:shadow-inner lg:[&:nth-child(4n)]:border-r-0 sm:[&:nth-child(2n)]:border-r-0 lg:border-b-0">
                <div class="flex items-center justify-between mb-3">
                    <p class="font-semibold text-xs text-slate-500 uppercase tracking-wider">Sections / Bins</p>
                    <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-violet-50 text-violet-600 transition-colors group-hover:bg-violet-100">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M12 10.5v6m3-3H9m4.06-7.19l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z" />
                        </svg>
                    </div>
                </div>
                <div class="z-10">
                    <p class="text-2xl font-black tracking-tight text-slate-800">{{ $sections->count() }}</p>
                    <p class="mt-0.5 text-[10px] font-medium text-slate-400">Sub-locations</p>
                </div>
            </div>

            <div class="group relative overflow-hidden bg-white p-4 border-r border-b border-slate-100 transition-all hover:bg-slate-50 hover:shadow-inner lg:[&:nth-child(4n)]:border-r-0 sm:[&:nth-child(2n)]:border-r-0 lg:border-b-0">
                <div class="flex items-center justify-between mb-3">
                    <p class="font-semibold text-xs text-slate-500 uppercase tracking-wider">Total Items Stored</p>
                    <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-sky-50 text-sky-600 transition-colors group-hover:bg-sky-100">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375M3.75 16.125v4.125C3.75 22.653 7.444 24.75 12 24.75s8.25-2.097 8.25-4.625v-4.125" />
                        </svg>
                    </div>
                </div>
                <div class="z-10">
                    <p class="text-2xl font-black tracking-tight text-slate-800">{{ number_format($totalItems) }}</p>
                    <p class="mt-0.5 text-[10px] font-medium text-slate-400">Assigned to locations</p>
                </div>
            </div>
        </div>
    </div>



    <div class="bg-white rounded-2xl border border-sky-100 shadow-sm mb-6 overflow-x-auto">
        <div class="p-5 border-b border-sky-100 flex items-center gap-3 bg-gradient-to-r from-slate-50 to-white rounded-t-2xl">
            <div class="bg-blue-100 p-2 rounded-lg text-blue-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                </svg>
            </div>
            <div>
                <p class="text-[10px] font-semibold text-blue-600 uppercase tracking-widest mb-0.5">Facility Mapping</p>
                <h3 class="text-sm font-bold text-slate-800">Storage Floor Plans</h3>
            </div>
        </div>
        <div class="p-10 md:p-16 grid grid-cols-1 xl:grid-cols-3 gap-16 bg-slate-50/50 min-w-[700px] xl:min-w-0 rounded-b-2xl">
            <!-- Storage 1 -->
            <div class="flex flex-col items-center xl:items-start border-b xl:border-b-0 xl:border-r border-slate-200 pb-10 xl:pb-0 xl:pr-12">
                <div class="w-full text-center xl:text-left mb-6">
                    <h4 class="text-lg font-black text-slate-800 tracking-tight">Storage 1</h4>
                    <p class="text-[11px] font-medium text-slate-500 mt-1">Secondary small storage</p>
                </div>
                
                <div class="relative w-full max-w-[280px] aspect-square bg-white border-4 border-slate-700 shadow-[8px_8px_0_0_rgba(15,23,42,0.1)] flex items-center justify-center mt-4">
                    <!-- Grid Pattern -->
                    <div class="absolute inset-0 flex items-center justify-center opacity-[0.04] pointer-events-none">
                        <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                            <pattern id="gridPatternSmall" width="30" height="30" patternUnits="userSpaceOnUse">
                                <path d="M 30 0 L 0 0 0 30" fill="none" stroke="currentColor" stroke-width="1"/>
                            </pattern>
                            <rect width="100%" height="100%" fill="url(#gridPatternSmall)" />
                        </svg>
                    </div>

                    <div class="absolute inset-0 m-6 border-2 border-dashed border-slate-300 bg-slate-50/50 flex flex-col items-center justify-center z-10">
                        <div class="bg-white p-3 rounded-full border border-slate-200 shadow-sm mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                            </svg>
                        </div>
                        <span class="text-sm font-black text-slate-700 uppercase tracking-widest text-center">
                            Storage 1
                        </span>
                        <span class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-1.5 bg-white px-2 py-0.5 rounded border border-slate-100">No Sections</span>
                    </div>
                    
                    <!-- Door -->
                    <div class="absolute bottom-[-4px] right-[25%] w-[45px] h-[4px] bg-white z-20"></div>
                    <div class="absolute bottom-[-4px] right-[25%] w-[4px] h-[45px] bg-slate-800 origin-top rotate-[-45deg] shadow-lg z-20"></div>
                    <div class="absolute -bottom-[45px] right-[25%] w-[40px] h-[40px] border-t-2 border-r-2 border-slate-300 border-dashed rounded-tr-full pointer-events-none z-10"></div>
                    <span class="absolute -bottom-[30px] right-[5%] text-[9px] font-bold text-slate-400 uppercase tracking-widest">Door</span>
                </div>
            </div>

            <!-- Storage 2 (Formerly B) -->
            <div class="xl:col-span-2 flex flex-col items-center xl:items-end">
                <div class="w-full flex justify-between items-end mb-6">
                    <div>
                        <h4 class="text-lg font-black text-slate-800 tracking-tight">Storage 2</h4>
                        <p class="text-[11px] font-medium text-slate-500 mt-1">Main storage area with categorized sections</p>
                    </div>
                    <div class="px-3 py-1 bg-white border border-slate-200 rounded text-[10px] font-bold text-slate-600 shadow-sm">
                        Total Sections: 6
                    </div>
                </div>
                
                <div class="relative w-full max-w-[550px] aspect-[4/3] bg-white border-4 border-slate-700 shadow-[8px_8px_0_0_rgba(15,23,42,0.1)]">
                    <!-- Grid Pattern -->
                    <div class="absolute inset-0 flex items-center justify-center opacity-[0.04] pointer-events-none">
                        <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                            <pattern id="gridPattern" width="40" height="40" patternUnits="userSpaceOnUse">
                                <path d="M 40 0 L 0 0 0 40" fill="none" stroke="currentColor" stroke-width="1"/>
                            </pattern>
                            <rect width="100%" height="100%" fill="url(#gridPattern)" />
                        </svg>
                    </div>

                    <!-- Floor Labels -->
                    <div class="absolute top-[20%] -left-[60px] flex items-center gap-2">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Floor</span>
                        <div class="w-[15px] h-[1px] bg-slate-300"></div>
                    </div>
                    <div class="absolute top-[30%] -right-[60px] flex items-center gap-2">
                        <div class="w-[15px] h-[1px] bg-slate-300"></div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Floor</span>
                    </div>
                    <div class="absolute -bottom-[35px] left-[45%] flex flex-col items-center gap-2">
                        <div class="w-[1px] h-[15px] bg-slate-300"></div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Floor</span>
                    </div>

                    <!-- Cabinet D -->
                    <div class="absolute top-0 right-[5%] w-[40%] h-[8%] bg-amber-50 border-b-2 border-x-2 border-slate-700 flex items-center justify-center shadow-sm">
                        <span class="text-[10px] font-bold text-slate-700 tracking-wider">cabinet D</span>
                    </div>

                    <!-- Section A -->
                    <div class="absolute top-0 left-0 w-[45%] h-[35%] bg-sky-50 border-b-2 border-r-2 border-slate-700 flex flex-col items-center justify-center transition-colors hover:bg-sky-100 group cursor-pointer">
                        <span class="font-black text-slate-800 text-2xl md:text-3xl group-hover:scale-110 transition-transform">A</span>
                    </div>

                    <!-- Section B -->
                    <div class="absolute top-[8%] right-0 w-[55%] h-[52%] bg-indigo-50 border-b-2 border-l-2 border-slate-700 flex flex-col items-center justify-center transition-colors hover:bg-indigo-100 group cursor-pointer">
                        <span class="font-black text-slate-800 text-3xl md:text-5xl group-hover:scale-110 transition-transform">B</span>
                    </div>

                    <!-- Section C -->
                    <div class="absolute top-[35%] left-0 w-[22%] h-[40%] bg-emerald-50 border-b-2 border-r-2 border-slate-700 flex flex-col items-center justify-center transition-colors hover:bg-emerald-100 group cursor-pointer">
                        <span class="font-black text-slate-800 text-xl md:text-2xl group-hover:scale-110 transition-transform">C</span>
                    </div>

                    <!-- Table -->
                    <div class="absolute top-[50%] -left-[20px] flex items-center">
                        <div class="absolute -left-[35px] text-[9px] font-bold text-slate-400 uppercase tracking-widest -rotate-90">Table</div>
                        <div class="w-[20px] h-[60px] bg-slate-100 border-2 border-slate-700 shadow-sm"></div>
                    </div>

                    <!-- Section Cr / Restroom -->
                    <div class="absolute bottom-0 left-0 w-[30%] h-[25%] bg-white border-t-2 border-r-2 border-slate-700 flex flex-col items-center justify-center transition-colors hover:bg-slate-50 group cursor-pointer overflow-hidden p-1">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 110 90" class="w-[80%] h-[80%]">
                            <!-- Figures group: scaled down and centered -->
                            <g transform="translate(55,42) scale(0.62) translate(-55,-38)">
                                <!-- Female head -->
                                <circle cx="27" cy="8" r="8" fill="#1e293b"/>
                                <!-- Female dress body -->
                                <path d="M13 52 L19 26 Q27 20 35 26 L41 52 Z" fill="#1e293b"/>
                                <!-- Vertical divider -->
                                <line x1="55" y1="0" x2="55" y2="58" stroke="#1e293b" stroke-width="3"/>
                                <!-- Male head -->
                                <circle cx="83" cy="8" r="8" fill="#1e293b"/>
                                <!-- Male torso -->
                                <rect x="74" y="20" width="18" height="20" rx="2" fill="#1e293b"/>
                                <!-- Male left leg -->
                                <rect x="74" y="38" width="8" height="16" rx="2" fill="#1e293b"/>
                                <!-- Male right leg -->
                                <rect x="84" y="38" width="8" height="16" rx="2" fill="#1e293b"/>
                            </g>
                            <!-- RESTROOM text always readable -->
                            <text x="55" y="84" font-family="Arial, sans-serif" font-size="8.5" font-weight="900" fill="#1e293b" text-anchor="middle" letter-spacing="1.2">RESTROOM</text>
                        </svg>
                    </div>

                    <!-- Section E -->
                    <div class="absolute bottom-0 left-[30%] w-[35%] h-[25%] bg-violet-50 border-t-2 border-r-2 border-slate-700 flex flex-col items-center justify-center transition-colors hover:bg-violet-100 group cursor-pointer">
                        <span class="font-black text-slate-800 text-xl md:text-2xl group-hover:scale-110 transition-transform">E</span>
                    </div>

                    <!-- Door -->
                    <div class="absolute bottom-[15%] -right-[4px] w-[4px] h-[45px] bg-white"></div>
                    <div class="absolute bottom-[15%] right-0 w-[45px] h-[4px] bg-slate-800 origin-left rotate-[45deg] shadow-lg"></div>
                    <div class="absolute bottom-[15%] -right-[40px] w-[40px] h-[40px] border-b-2 border-l-2 border-slate-300 border-dashed rounded-bl-full pointer-events-none"></div>
                    <span class="absolute bottom-[8%] -right-[45px] text-[9px] font-bold text-slate-400 uppercase tracking-widest">Door</span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-5 mb-6">
        <div class="bg-white rounded-2xl overflow-hidden border border-sky-100 shadow-sm">
            <div class="p-5 border-b border-sky-100">
                <p class="text-[10px] font-semibold text-indigo-600 uppercase tracking-widest mb-0.5">Chart.01</p>
                <h3 class="text-sm font-bold text-slate-800">Storage Locations &amp; Sections Trend</h3>
            </div>
            <div class="p-5">
                <div class="h-[240px]"><canvas id="storageLineChart"></canvas></div>
            </div>
        </div>
        <div class="bg-white rounded-2xl overflow-hidden border border-sky-100 shadow-sm">
            <div class="p-5 border-b border-sky-100">
                <p class="text-[10px] font-semibold text-teal-600 uppercase tracking-widest mb-0.5">Chart.02</p>
                <h3 class="text-sm font-bold text-slate-800">Items Distribution by Location</h3>
            </div>
            <div class="p-5">
                <div class="h-[240px]"><canvas id="distributionPieChart"></canvas></div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-5 mb-6">
        <div class="bg-white rounded-2xl overflow-hidden border border-sky-100 shadow-sm">
            <div class="p-5 border-b border-sky-100 flex items-center justify-between gap-4">
                <div>
                    <p class="text-[10px] font-semibold text-emerald-600 uppercase tracking-widest mb-0.5">Registry</p>
                    <h3 class="text-sm font-bold text-slate-800">Storage Places</h3>
                </div>
                <span class="font-mono text-xs font-bold text-emerald-600 bg-emerald-50 border border-emerald-100 px-3 py-1.5">{{ $storages->count() }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="bg-sky-50/80 border-b border-sky-100">
                            <th class="px-6 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 text-left">Location Name</th>
                            <th class="px-6 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 text-center">Items</th>
                            <th class="px-6 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-sky-50" id="storagesTableBody">
                        @forelse($storages as $storage)
                        @php $itemCount = \App\Models\Item::where('storage_location', $storage->name)->count(); @endphp
                        <tr class="hover:bg-sky-50 transition-colors">
                            <td class="px-6 py-4 font-bold text-[#0f172a]">{{ $storage->name }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-center">
                                <span class="font-mono text-[11px] font-bold text-slate-700">{{ number_format($itemCount) }}</span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('locations.edit', $storage) }}" class="inline-flex items-center border border-sky-100 bg-white px-2.5 py-1.5 text-[10px] font-mono font-bold text-slate-700 hover:bg-sky-50 transition-colors">Edit</a>
                                    <form action="{{ route('locations.destroy', $storage) }}" method="POST" class="m-0 inline"
                                        onsubmit="return confirm('Delete this storage location?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center border border-rose-200 bg-rose-50 px-2.5 py-1.5 text-[10px] font-mono font-bold text-rose-600 hover:bg-rose-500 hover:text-white hover:border-rose-500 transition-colors">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-16 text-center">
                                <p class="font-mono text-[10px] text-slate-400 uppercase tracking-widest">// No storage locations found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-2xl overflow-hidden border border-sky-100 shadow-sm">
            <div class="p-5 border-b border-sky-100 flex items-center justify-between gap-4">
                <div>
                    <p class="text-[10px] font-semibold text-violet-600 uppercase tracking-widest mb-0.5">Registry</p>
                    <h3 class="text-sm font-bold text-slate-800">Sections / Bins</h3>
                </div>
                <span class="font-mono text-xs font-bold text-violet-600 bg-violet-50 border border-violet-100 px-3 py-1.5">{{ $sections->count() }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="bg-sky-50/80 border-b border-sky-100">
                            <th class="px-6 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 text-left">Section Name</th>
                            <th class="px-6 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 text-center">Items</th>
                            <th class="px-6 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-sky-50" id="sectionsTableBody">
                        @forelse($sections as $section)
                        @php $itemCount = \App\Models\Item::where('storage_section', $section->name)->count(); @endphp
                        <tr class="hover:bg-sky-50 transition-colors">
                            <td class="px-6 py-4 font-bold text-[#0f172a]">{{ $section->name }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-center">
                                <span class="font-mono text-[11px] font-bold text-slate-700">{{ number_format($itemCount) }}</span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('locations.edit', $section) }}" class="inline-flex items-center border border-sky-100 bg-white px-2.5 py-1.5 text-[10px] font-mono font-bold text-slate-700 hover:bg-sky-50 transition-colors">Edit</a>
                                    <form action="{{ route('locations.destroy', $section) }}" method="POST" class="m-0 inline"
                                        onsubmit="return confirm('Delete this section?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center border border-rose-200 bg-rose-50 px-2.5 py-1.5 text-[10px] font-mono font-bold text-rose-600 hover:bg-rose-500 hover:text-white hover:border-rose-500 transition-colors">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-16 text-center">
                                <p class="font-mono text-[10px] text-slate-400 uppercase tracking-widest">// No sections found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const createVerticalGradient = (ctx, startColor, endColor) => {
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, startColor);
        gradient.addColorStop(1, endColor);
        return gradient;
    };

    const createHorizontalGradient = (ctx, startColor, midColor, endColor) => {
        const gradient = ctx.createLinearGradient(0, 0, 400, 0);
        gradient.addColorStop(0, startColor);
        gradient.addColorStop(0.5, midColor);
        gradient.addColorStop(1, endColor);
        return gradient;
    };

    const baseTickStyle = { color: '#94a3b8', font: { family: "'Fira Code', monospace", size: 10 } };
    const baseGrid = { color: 'rgba(15, 23, 42, 0.04)', drawBorder: false };
    const tooltipStyle = {
        backgroundColor: 'rgba(15, 23, 42, 0.95)',
        titleColor: '#f8fafc',
        bodyColor: '#e2e8f0',
        borderColor: '#334155',
        borderWidth: 1,
        padding: 12,
        boxPadding: 6,
        usePointStyle: true,
        cornerRadius: 8,
        bodyFont: { family: "'Fira Code', monospace", size: 11 }
    };

    // LINE CHART
    const lineCanvas = document.getElementById('storageLineChart');
    if (lineCanvas) {
        const lctx = lineCanvas.getContext('2d');
        
        const storageLabels = @json($storages->pluck('name')->values());
        const sectionLabels = @json($sections->pluck('name')->values());
        const allLabels = [...storageLabels, ...sectionLabels];
        
        const storageCounts = @json($storages->map(function($s) { return \App\Models\Item::where('storage_location', $s->name)->count(); })->values());
        const sectionCounts = @json($sections->map(function($s) { return \App\Models\Item::where('storage_section', $s->name)->count(); })->values());
        
        new Chart(lctx, {
            type: 'line',
            data: {
                labels: allLabels,
                datasets: [
                    {
                        label: 'Storage Locations',
                        data: [...storageCounts, ...Array(sectionCounts.length).fill(null)],
                        borderColor: '#059669', // Emerald
                        backgroundColor: createVerticalGradient(lctx, 'rgba(16, 185, 129, 0.2)', 'rgba(16, 185, 129, 0.0)'),
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointBackgroundColor: '#059669',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointHoverRadius: 6,
                        segment: {
                            borderDash: (ctx) => ctx.p0DataIndex >= storageLabels.length ? [5, 5] : []
                        }
                    },
                    {
                        label: 'Sections / Bins',
                        data: [...Array(storageCounts.length).fill(null), ...sectionCounts],
                        borderColor: '#6366f1', // Indigo
                        backgroundColor: createVerticalGradient(lctx, 'rgba(99, 102, 241, 0.2)', 'rgba(99, 102, 241, 0.0)'),
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointBackgroundColor: '#6366f1',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointHoverRadius: 6,
                        segment: {
                            borderDash: (ctx) => ctx.p0DataIndex < storageLabels.length ? [5, 5] : []
                        }
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            usePointStyle: true,
                            color: '#334155',
                            font: { family: "ui-sans-serif, system-ui, sans-serif", size: 12, weight: '600' }
                        }
                    },
                    tooltip: tooltipStyle
                },
                scales: {
                    y: { beginAtZero: true, grid: baseGrid, border: { display: false }, ticks: baseTickStyle },
                    x: { grid: { display: false }, border: { display: false }, ticks: baseTickStyle }
                }
            }
        });
    }

    // PIE CHART
    const pieCanvas = document.getElementById('distributionPieChart');
    if (pieCanvas) {
        const pctx = pieCanvas.getContext('2d');
        
        const storageLabels = @json($storages->pluck('name')->values());
        const sectionLabels = @json($sections->pluck('name')->values());
        const storageCounts = @json($storages->map(function($s) { return \App\Models\Item::where('storage_location', $s->name)->count(); })->values());
        const sectionCounts = @json($sections->map(function($s) { return \App\Models\Item::where('storage_section', $s->name)->count(); })->values());
        
        // Premium horizontal gradients for doughnut slices
        const colorPalette = [
            createHorizontalGradient(pctx, '#1e3a8a', '#0ea5e9', '#7dd3fc'),
            createHorizontalGradient(pctx, '#0284c7', '#0ea5e9', '#bae6fd'),
            createHorizontalGradient(pctx, '#0f172a', '#1e3a8a', '#38bdf8'),
            createHorizontalGradient(pctx, '#ef4444', '#fb7185', '#fecaca'),
            createHorizontalGradient(pctx, '#f59e0b', '#fbbf24', '#fde68a'),
            createHorizontalGradient(pctx, '#10b981', '#34d399', '#a7f3d0'),
            createHorizontalGradient(pctx, '#4c1d95', '#7c3aed', '#c4b5fd')
        ];
        
        const storageBackgroundColors = storageLabels.map((_, idx) => colorPalette[idx % colorPalette.length]);
        const sectionBackgroundColors = sectionLabels.map((_, idx) => colorPalette[(idx + 2) % colorPalette.length]); // Offset slightly for variety
        
        new Chart(pctx, {
            type: 'doughnut',
            data: {
                labels: [...storageLabels, ...sectionLabels],
                datasets: [
                    {
                        label: 'Storage Locations',
                        data: [...storageCounts, ...Array(sectionCounts.length).fill(0)],
                        backgroundColor: [...storageBackgroundColors, ...Array(sectionCounts.length).fill('transparent')],
                        borderColor: '#ffffff',
                        borderWidth: 3,
                        hoverOffset: 6
                    },
                    {
                        label: 'Sections / Bins',
                        data: [...Array(storageCounts.length).fill(0), ...sectionCounts],
                        backgroundColor: [...Array(storageCounts.length).fill('transparent'), ...sectionBackgroundColors],
                        borderColor: '#ffffff',
                        borderWidth: 3,
                        hoverOffset: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: tooltipStyle
                }
            }
        });
    }
});
</script>
@endsection
