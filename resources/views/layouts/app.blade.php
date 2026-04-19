<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'BGHMC IMISS') — Bataan General Hospital and Medical Center</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=fira-code:400,500,600|plus-jakarta-sans:300,400,500,600,700,800"
        rel="stylesheet" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] {
            display: none !important;
        }

        /* Blueprint / Technical Grid Lines Background */
        body {
            background-color: #f8fafc;
            background-image:
                linear-gradient(rgba(148, 163, 184, 0.08) 1px, transparent 1px),
                linear-gradient(90deg, rgba(148, 163, 184, 0.08) 1px, transparent 1px),
                linear-gradient(rgba(59, 130, 246, 0.05) 2px, transparent 2px),
                linear-gradient(90deg, rgba(59, 130, 246, 0.05) 2px, transparent 2px);
            background-size: 20px 20px, 20px 20px, 100px 100px, 100px 100px;
            background-position: -1px -1px, -1px -1px, -2px -2px, -2px -2px;
        }

        /* High-tech custom scrollbar - Light Mode */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 0px;
        }

        /* Squared off for tech feel */
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .sidebar-scroll {
            scrollbar-width: none;
        }

        .sidebar-scroll::-webkit-scrollbar {
            display: none;
        }

        /* Tech Corner Nodes */
        .tech-container {
            position: relative;
        }

        .tech-container::before,
        .tech-container::after {
            content: '';
            position: absolute;
            width: 6px;
            height: 6px;
            border: 1px solid #3b82f6;
            background: #fff;
            z-index: 10;
        }

        .tech-container::before {
            top: -3px;
            left: -3px;
        }

        .tech-container::after {
            bottom: -3px;
            right: -3px;
        }

        /* Server Pulse Animation */
        @keyframes pulse-ring {
            0% {
                transform: scale(0.8);
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4);
            }

            70% {
                transform: scale(1);
                box-shadow: 0 0 0 6px rgba(16, 185, 129, 0);
            }

            100% {
                transform: scale(0.8);
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
            }
        }

        .status-pulse {
            animation: pulse-ring 2s infinite;
        }

        /* Logo glow + hover spin */
        @keyframes logo-glow {
            0%, 100% { filter: drop-shadow(0 0 4px rgba(59, 130, 246, 0.3)); }
            50%       { filter: drop-shadow(0 0 10px rgba(59, 130, 246, 0.6)); }
        }

        .logo-img {
            animation: logo-glow 3s ease-in-out infinite;
            transition: transform 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .logo-img:hover {
            transform: rotate(10deg) scale(1.12);
        }

        /* Scanning sweep line */
        @keyframes scan-sweep {
            0%   { transform: translateX(-100%); opacity: 0; }
            20%  { opacity: 1; }
            80%  { opacity: 1; }
            100% { transform: translateX(400%); opacity: 0; }
        }

        .scan-line {
            animation: scan-sweep 4s ease-in-out infinite;
        }

        /* Blinking cursor for brand text */
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50%       { opacity: 0; }
        }

        .cursor-blink {
            animation: blink 1s step-end infinite;
        }

        /* Sidebar logo accent ring pulse */
        @keyframes accent-ring {
            0%   { box-shadow: 0 0 0 0 rgba(99, 102, 241, 0.4); }
            70%  { box-shadow: 0 0 0 6px rgba(99, 102, 241, 0); }
            100% { box-shadow: 0 0 0 0 rgba(99, 102, 241, 0); }
        }

        .logo-ring {
            animation: accent-ring 2.5s ease-out infinite;
        }
    </style>
</head>

<body class="min-h-full antialiased text-slate-800 overflow-hidden"
    style="font-family: 'Plus Jakarta Sans', sans-serif;" x-data="{ sidebarExpanded: true, mobileOpen: false }">

    <div class="flex h-screen w-full overflow-hidden">

        {{-- Mobile Overlay --}}
        <div x-show="mobileOpen" x-transition.opacity
            class="fixed inset-0 z-40 bg-slate-900/20 backdrop-blur-sm lg:hidden" @click="mobileOpen = false"></div>

        {{-- ==================== --}}
        {{-- SIDEBAR (Dark Tech Theme) --}}
        {{-- ==================== --}}
        <aside
            class="fixed inset-y-0 left-0 z-50 flex flex-col bg-slate-900 border-r border-slate-800 transition-all duration-300 ease-in-out lg:relative lg:translate-x-0 h-full shadow-[4px_0_24px_rgba(0,0,0,0.2)]"
            :class="{
                '-translate-x-full': !mobileOpen,
                'translate-x-0': mobileOpen,
                'w-64': sidebarExpanded,
                'w-[76px]': !sidebarExpanded
            }" x-cloak>

            {{-- Blueprint grid overlay on dark background --}}
            <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image: linear-gradient(rgba(99,102,241,0.3) 1px, transparent 1px), linear-gradient(90deg, rgba(99,102,241,0.3) 1px, transparent 1px); background-size: 30px 30px;"></div>

            <div class="relative flex flex-col h-full w-full z-10">

                {{-- Branding --}}
                <div class="flex items-center shrink-0 px-4 h-[70px] border-b border-slate-800 bg-slate-900/50 relative overflow-hidden"
                    :class="sidebarExpanded ? 'justify-start gap-3' : 'justify-center'">

                    {{-- Decorative top gradient line --}}
                    <div class="absolute top-0 left-0 w-full h-[2px] bg-gradient-to-r from-blue-600 to-indigo-500 z-10"></div>

                    {{-- Animated scan sweep --}}
                    <div class="absolute top-0 left-0 h-full w-1/4 bg-gradient-to-r from-transparent via-blue-500/30 to-transparent pointer-events-none scan-line"></div>

                    {{-- Corner accent nodes --}}
                    <div class="absolute bottom-0 left-0 w-2 h-2 border-r border-t border-blue-500/40"></div>
                    <div class="absolute bottom-0 right-0 w-2 h-2 border-l border-t border-indigo-500/40"></div>

                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group w-full relative z-10">

                        {{-- Logo: no border, just glow animation --}}
                        <div class="relative flex-shrink-0">
                            <img src="{{ asset('favicon.ico') }}" alt="IMISS"
                                class="h-8 w-8 object-contain logo-img brightness-150">
                            {{-- Subtle ring pulse behind icon --}}
                            <div class="absolute inset-0 rounded-sm logo-ring opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </div>

                        <div x-show="sidebarExpanded" x-transition:enter="transition-all duration-200 ease-out"
                            x-transition:enter-start="opacity-0 translate-x-2"
                            x-transition:enter-end="opacity-100 translate-x-0"
                            x-transition:leave="transition-all duration-100"
                            class="flex flex-col leading-none overflow-hidden whitespace-nowrap">
                            <span class="text-[9px] font-mono font-semibold text-blue-400 uppercase tracking-[0.25em] mb-1 flex items-center gap-1">
                                <span class="h-1 w-1 bg-emerald-400 inline-block rounded-full status-pulse"></span>
                                System://BGHMC
                            </span>
                            <span class="text-[17px] font-black tracking-tight text-white flex items-baseline gap-1">
                                IMISS
                                <span class="text-slate-400 font-normal text-[14px]">Inventory</span>
                                <span class="text-blue-500 font-mono text-[16px] cursor-blink leading-none">_</span>
                            </span>
                        </div>
                    </a>
                </div>

                {{-- Nav Links --}}
                <nav class="flex-1 overflow-y-auto sidebar-scroll py-6 px-3 space-y-1.5 flex flex-col relative"
                    :class="sidebarExpanded ? 'items-stretch' : 'items-center'">

                    {{-- Vertical tech line accent on the left --}}
                    <div class="absolute left-3 top-6 bottom-6 w-px bg-slate-800 -z-10" x-show="sidebarExpanded"></div>

                    @php
                        $navLinks = [
                            ['route' => 'dashboard', 'pattern' => 'dashboard', 'label' => 'Dashboard', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />'],
                            ['route' => 'items.index', 'pattern' => 'items.*', 'label' => 'Items', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />'],
                            ['route' => 'categories.index', 'pattern' => 'categories.*', 'label' => 'Categories', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />'],
                            ['route' => 'units.index', 'pattern' => 'units.*', 'label' => 'Units', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0 1 18 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3 1.5 1.5 3-3.75" />'],
                            ['route' => 'locations.index', 'pattern' => 'locations.*', 'label' => 'Location', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />'],
                            ['route' => 'in-out.index', 'pattern' => ['in-out.*', 'transfers.*', 'borrows.*', 'returns.*'], 'label' => 'In & Out', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />'],
                            ['route' => 'staff.index', 'pattern' => 'staff.*', 'label' => 'Staff', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />'],
                            ['route' => 'logs.index', 'pattern' => 'logs.*', 'label' => 'Logs', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />'],
                        ];
                    @endphp

                    @foreach($navLinks as $link)
                                    @php
                                        $targetUrl = route($link['route']);
                                        $isActive = is_array($link['pattern'])
                                            ? collect($link['pattern'])->contains(fn($p) => request()->routeIs($p))
                                            : request()->routeIs($link['pattern']);
                                    @endphp
                                    <a href="{{ $targetUrl }}"
                                        @click="if(window.location.href === '{{ $targetUrl }}') $event.preventDefault()" class="relative flex items-center transition-all duration-300 group
                                                                                                                                                                                                                            {{ $isActive ? 'bg-slate-800/80 text-blue-400' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}"
                                        :class="sidebarExpanded ? 'w-full px-3 py-2.5 border border-transparent' : 'w-11 h-11 justify-center rounded-sm'"
                                        title="{{ $link['label'] }}">

                                        {{-- Active Tech Accents --}}
                                        @if($isActive)
                                            <div class="absolute left-0 top-0 bottom-0 w-[2px] bg-blue-500"></div>
                                            <template x-if="sidebarExpanded">
                                                <div>
                                                    <div class="absolute right-0 top-0 bottom-0 w-[1px] bg-blue-500/20"></div>
                                                    <div class="absolute top-0 left-0 right-0 h-[1px] bg-blue-500/20"></div>
                                                    <div class="absolute bottom-0 left-0 right-0 h-[1px] bg-blue-500/20"></div>
                                                    <div
                                                        class="absolute top-[-2px] right-[-2px] w-[5px] h-[5px] bg-slate-900 border border-blue-500/50">
                                                    </div>
                                                    <div
                                                        class="absolute bottom-[-2px] right-[-2px] w-[5px] h-[5px] bg-slate-900 border border-blue-500/50">
                                                    </div>
                                                </div>
                                            </template>
                                        @endif

                                        <div
                                            class="relative z-10 flex items-center justify-center bg-slate-900 p-1 rounded-sm shadow-[0_1px_2px_rgba(0,0,0,0.2)] border {{ $isActive ? 'border-blue-500/30' : 'border-slate-800 group-hover:border-slate-700' }}">
                                            <svg class="shrink-0 transition-colors {{ $isActive ? 'text-blue-400 w-[18px] h-[18px]' : 'text-slate-500 group-hover:text-slate-300 w-[18px] h-[18px]' }}"
                                                fill="none" viewBox="0 0 24 24" stroke-width="{{ $isActive ? '2.5' : '2' }}"
                                                stroke="currentColor">
                                                {!! $link['icon'] !!}
                                            </svg>
                                        </div>

                                        <span class="text-[13px] font-semibold whitespace-nowrap transition-all duration-200"
                                            :class="sidebarExpanded ? 'opacity-100 ml-3 w-auto' : 'opacity-0 w-0 ml-0 hidden'">
                                            {{ $link['label'] }}
                                        </span>
                                    </a>
                    @endforeach

                    @if(Auth::user() && Auth::user()->role === 'admin')
                        <div class="pt-6 pb-2" :class="sidebarExpanded ? 'px-2' : 'flex justify-center'">
                            <span x-show="sidebarExpanded"
                                class="text-[10px] font-mono font-bold text-slate-500 uppercase tracking-[0.15em] flex items-center gap-2">
                                <span class="h-px bg-slate-800 flex-1 border-t border-dashed border-slate-700"></span>
                                Admin Console
                                <span class="h-px bg-slate-800 flex-1 border-t border-dashed border-slate-700"></span>
                            </span>
                            <div x-show="!sidebarExpanded"
                                class="h-px w-6 bg-slate-800 border-t border-dashed border-slate-700"></div>
                        </div>

                        @php
                            $adminLinks = [
                                ['route' => 'users.index', 'pattern' => 'users.*', 'label' => 'Accounts', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />'],
                                ['route' => 'admin.records.index', 'pattern' => ['admin.records.*', 'admin.stock-entries.*', 'admin.usage-logs.*', 'admin.borrows.*', 'admin.transfers.*', 'admin.disposals.*'], 'label' => 'Records', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />'],
                            ];
                        @endphp

                        @foreach($adminLinks as $link)
                                    @php
                                        $targetUrl = route($link['route']);
                                        $isActive = is_array($link['pattern'])
                                            ? collect($link['pattern'])->contains(fn($p) => request()->routeIs($p))
                                            : request()->routeIs($link['pattern']);
                                    @endphp
                                    <a href="{{ $targetUrl }}"
                                        @click="if(window.location.href === '{{ $targetUrl }}') $event.preventDefault()" class="relative flex items-center transition-all duration-300 group
                                                                                                                                                                                                                            {{ $isActive ? 'bg-slate-800/80 text-blue-400' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}"
                                        :class="sidebarExpanded ? 'w-full px-3 py-2.5 border border-transparent' : 'w-11 h-11 justify-center rounded-sm'"
                                        title="{{ $link['label'] }}">

                                        @if($isActive)
                                            <div class="absolute left-0 top-0 bottom-0 w-[2px] bg-blue-500"></div>
                                            <template x-if="sidebarExpanded">
                                                <div>
                                                    <div class="absolute right-0 top-0 bottom-0 w-[1px] bg-blue-500/20"></div>
                                                    <div class="absolute top-0 left-0 right-0 h-[1px] bg-blue-500/20"></div>
                                                    <div class="absolute bottom-0 left-0 right-0 h-[1px] bg-blue-500/20"></div>
                                                    <div
                                                        class="absolute top-[-2px] right-[-2px] w-[5px] h-[5px] bg-slate-900 border border-blue-500/50">
                                                    </div>
                                                    <div
                                                        class="absolute bottom-[-2px] right-[-2px] w-[5px] h-[5px] bg-slate-900 border border-blue-500/50">
                                                    </div>
                                                </div>
                                            </template>
                                        @endif

                                        <div
                                            class="relative z-10 flex items-center justify-center bg-slate-900 p-1 rounded-sm shadow-[0_1px_2px_rgba(0,0,0,0.2)] border {{ $isActive ? 'border-blue-500/30' : 'border-slate-800 group-hover:border-slate-700' }}">
                                            <svg class="shrink-0 transition-colors {{ $isActive ? 'text-blue-400 w-[18px] h-[18px]' : 'text-slate-500 group-hover:text-slate-300 w-[18px] h-[18px]' }}"
                                                fill="none" viewBox="0 0 24 24" stroke-width="{{ $isActive ? '2.5' : '2' }}"
                                                stroke="currentColor">
                                                {!! $link['icon'] !!}
                                            </svg>
                                        </div>

                                        <span class="text-[13px] font-semibold whitespace-nowrap transition-all duration-200"
                                            :class="sidebarExpanded ? 'opacity-100 ml-3 w-auto' : 'opacity-0 w-0 ml-0 hidden'">
                                            {{ $link['label'] }}
                                        </span>
                                    </a>
                        @endforeach
                    @endif
                </nav>

                {{-- Collapse Toggle (Light Tech Style) --}}
                <button @click="sidebarExpanded = !sidebarExpanded"
                    class="hidden lg:flex absolute top-1/2 -translate-y-1/2 -right-3.5 z-50 items-center justify-center w-7 h-7 bg-slate-900 border border-slate-700 text-slate-400 rounded-sm hover:text-blue-400 hover:border-blue-500 hover:shadow-sm focus:outline-none transition-all duration-300">
                    <svg class="w-3.5 h-3.5 transition-transform duration-300"
                        :class="sidebarExpanded ? 'rotate-0' : 'rotate-180'" fill="none" viewBox="0 0 24 24"
                        stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>
                </button>
            </div>
        </aside>

        {{-- ==================== --}}
        {{-- MAIN CONTENT AREA --}}
        {{-- ==================== --}}
        <div class="flex-1 flex flex-col h-full overflow-hidden relative">

            {{-- Top Header (Blueprint accented) --}}
            <header
                class="relative z-30 flex h-[70px] items-center justify-between gap-4 px-6 bg-white/80 backdrop-blur-md border-b border-slate-200 shrink-0">

                {{-- Decorative bottom line grid-marker --}}
                <div class="absolute bottom-[-1px] left-6 w-12 h-[2px] bg-blue-500 z-10"></div>
                <div class="absolute bottom-[-2px] left-16 w-1 h-1 bg-white border border-blue-500 z-10"></div>

                {{-- Mobile menu + Page title --}}
                <div class="flex items-center gap-4">
                    <button @click="mobileOpen = true" type="button"
                        class="lg:hidden p-2 -ml-1 text-slate-500 hover:bg-slate-100 hover:text-blue-600 rounded-sm border border-transparent hover:border-slate-200 transition-colors focus:ring-2 focus:ring-blue-500/20">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>
                    <h2 class="text-lg font-bold text-slate-800 tracking-tight flex items-center gap-2">
                        <span class="text-slate-300 font-mono text-xl opacity-70">&gt;</span>
                        @yield('title', 'System_Dashboard')
                    </h2>
                </div>

                {{-- User Menu --}}
                <div class="flex items-center gap-4 shrink-0">

                    {{-- Status Indicator --}}
                    <div
                        class="hidden sm:flex items-center gap-2 px-3 py-1.5 bg-white rounded-sm border border-slate-200 shadow-sm relative">
                        <div class="absolute top-0 right-0 w-2 h-2 border-l border-b border-slate-200"></div>
                        <div class="h-1.5 w-1.5 bg-emerald-500 status-pulse rounded-full"></div>
                        <span
                            class="text-[10px] font-mono text-slate-600 font-semibold tracking-widest">SYS.ONLINE</span>
                    </div>

                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.outside="open = false"
                            class="flex items-center gap-3 bg-white border border-slate-200 rounded-sm py-1 pl-3 pr-2 hover:border-blue-300 hover:shadow-sm group focus:outline-none transition-all duration-300">
                            <div class="hidden md:flex flex-col items-end leading-tight">
                                <span
                                    class="text-[13px] font-bold text-slate-800">{{ Auth::user()->name ?? 'Administrator' }}</span>
                                <span
                                    class="text-[9px] font-mono font-bold text-blue-600 uppercase tracking-[0.1em] mt-0.5">{{ Auth::user()->role ?? 'Root' }}</span>
                            </div>
                            <div class="relative tech-container">
                                <div
                                    class="flex h-8 w-8 items-center justify-center bg-slate-50 border border-slate-200 text-sm font-bold text-slate-600 shadow-inner group-hover:bg-blue-50 group-hover:text-blue-600 transition-colors">
                                    {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                                </div>
                            </div>
                            <svg viewBox="0 0 20 20" fill="currentColor"
                                class="w-4 h-4 text-slate-400 group-hover:text-blue-500 transition-colors"
                                :class="{ 'rotate-180': open }">
                                <path fill-rule="evenodd"
                                    d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div x-show="open" x-transition.opacity.duration.200ms
                            class="absolute right-0 top-full mt-2 w-56 rounded-sm border border-slate-200 shadow-xl py-1 z-50 bg-white"
                            style="display: none;">
                            <div class="px-4 py-2.5 border-b border-slate-100 bg-slate-50">
                                <p
                                    class="text-[10px] font-mono text-slate-500 uppercase tracking-widest flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 bg-emerald-400 inline-block"></span> Active Session
                                </p>
                            </div>
                            <a href="{{ route('profile.show') }}"
                                class="flex items-center gap-3 px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50 hover:text-blue-600 hover:border-l-2 hover:border-blue-500 transition-all border-l-2 border-transparent">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor" class="h-4 w-4 text-slate-400">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0" />
                                </svg>
                                User Profile
                            </a>
                            <div class="h-px bg-slate-100 mx-2"></div>
                            <form method="POST" action="{{ route('logout') }}" class="m-0">
                                @csrf
                                <button type="submit"
                                    class="flex w-full items-center gap-3 px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-rose-50 hover:text-rose-600 hover:border-l-2 hover:border-rose-500 transition-all border-l-2 border-transparent text-left group">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2" stroke="currentColor"
                                        class="h-4 w-4 text-slate-400 group-hover:text-rose-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
                                    </svg>
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Page Content --}}
            <main class="flex-1 overflow-y-auto relative z-10 p-5 sm:p-6 lg:p-8">
                <div class="max-w-[1600px] mx-auto w-full relative">
                    @hasSection('actions')
                        <div class="mb-6 flex flex-wrap items-center justify-end gap-3">
                            @yield('actions')
                        </div>
                    @endif

                    {{-- Container for content with a slight blueprint frame --}}
                    <div
                        class="w-full relative bg-white/90 backdrop-blur-sm shadow-sm border border-slate-200 p-1 tech-container">
                        @yield('content')
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('customDropdown', (initOptions, initSelected, inputName, isUnit) => ({
                isOpen: false,
                isAdding: false,
                search: '',
                newValue: '',
                options: initOptions,
                selectedId: initSelected,
                inputName: inputName,
                get filteredOptions() {
                    if (this.search === '') return this.options;
                    return this.options.filter(opt => opt.name.toLowerCase().includes(this.search.toLowerCase()));
                },
                get selectedName() {
                    let opt = this.options.find(o => o.id == this.selectedId);
                    if (opt) return opt.name;
                    return this.selectedId || '';
                },
                toggle() {
                    this.isOpen = !this.isOpen;
                    if (this.isOpen) {
                        this.search = '';
                        this.isAdding = false;
                        this.$nextTick(() => this.$refs.searchInput && this.$refs.searchInput.focus());
                    }
                },
                close() {
                    this.isOpen = false;
                    this.isAdding = false;
                },
                selectOption(opt) {
                    this.selectedId = opt.id;
                    this.close();
                },
                saveNewOption() {
                    let val = this.newValue.trim();
                    if (!val) return;
                    let newId = isUnit ? val : val;
                    this.options.push({ id: newId, name: val });
                    this.selectedId = newId;
                    this.newValue = '';
                    this.close();
                }
            }));
        });
    </script>
</body>

</html>