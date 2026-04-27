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

            0%,
            100% {
                filter: drop-shadow(0 0 4px rgba(59, 130, 246, 0.3));
            }

            50% {
                filter: drop-shadow(0 0 10px rgba(59, 130, 246, 0.6));
            }
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
            0% {
                transform: translateX(-100%);
                opacity: 0;
            }

            20% {
                opacity: 1;
            }

            80% {
                opacity: 1;
            }

            100% {
                transform: translateX(400%);
                opacity: 0;
            }
        }

        .scan-line {
            animation: scan-sweep 4s ease-in-out infinite;
        }

        /* Blinking cursor for brand text */
        @keyframes blink {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0;
            }
        }

        .cursor-blink {
            animation: blink 1s step-end infinite;
        }

        /* Sidebar logo accent ring pulse */
        @keyframes accent-ring {
            0% {
                box-shadow: 0 0 0 0 rgba(99, 102, 241, 0.4);
            }

            70% {
                box-shadow: 0 0 0 6px rgba(99, 102, 241, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(99, 102, 241, 0);
            }
        }

        .logo-ring {
            animation: accent-ring 2.5s ease-out infinite;
        }
    </style>
</head>

<body class="min-h-full antialiased text-slate-800 overflow-hidden"
    style="font-family: 'Plus Jakarta Sans', sans-serif;" x-data="{ sidebarExpanded: true, mobileOpen: false }">

    <div class="flex h-screen w-full overflow-hidden gap-0">

        {{-- Mobile Overlay --}}
        <div x-show="mobileOpen" x-transition.opacity
            class="fixed inset-0 z-40 bg-slate-900/20 backdrop-blur-sm lg:hidden" @click="mobileOpen = false"></div>

        {{-- ==================== --}}
        {{-- SIDEBAR (Dark Tech Theme) --}}
        {{-- ==================== --}}
        <aside
            class="fixed inset-y-0 left-0 z-50 flex flex-col transition-all duration-300 ease-[cubic-bezier(0.4,0,0.2,1)] lg:relative lg:translate-x-0 h-full drop-shadow-[8px_0_24px_rgba(15,23,42,0.5)] rounded-r-[1.25rem] border-r border-sky-500/20"
            :class="{
        '-translate-x-full': !mobileOpen,
        'translate-x-0': mobileOpen,
        'w-64': sidebarExpanded,
        'w-[80px]': !sidebarExpanded
    }" x-cloak>

            {{-- Main Sidebar Background & Content Wrapper --}}
            <div class="relative flex flex-col h-full w-full overflow-hidden rounded-r-[1.25rem] backdrop-blur-xl ring-1 ring-white/10"
                style="background: linear-gradient(145deg, #0f172a 0%, #1e3a8a 100%);">

                {{-- Top Premium Accent Bar (Sky Blue/White Shine) --}}
                <div class="absolute top-0 left-0 right-0 h-[2px] z-20 pointer-events-none shadow-[0_0_15px_rgba(14,165,233,0.6)]"
                    style="background: linear-gradient(90deg, #0ea5e9, #ffffff, #7dd3fc, #0ea5e9); background-size: 200% auto; animation: shine 3s linear infinite;">
                </div>

                {{-- Blueprint grid overlay --}}
                <div class="absolute inset-0 z-0 pointer-events-none opacity-20"
                    style="background-image: linear-gradient(rgba(14, 165, 233, 0.1) 1px, transparent 1px), linear-gradient(90deg, rgba(14, 165, 233, 0.1) 1px, transparent 1px); background-size: 32px 32px;">
                </div>

                <div class="relative flex flex-col h-full w-full z-10">

                    {{-- Branding Header --}}
                    <div
                        class="flex items-center shrink-0 px-6 h-[80px] border-b border-white/10 bg-white/[0.02] relative justify-center lg:justify-start">

                        <a href="{{ route('dashboard') }}" class="flex items-center gap-4 group w-full"
                            :class="sidebarExpanded ? '' : 'justify-center'">

                            {{-- Simple, Clean Logo --}}
                            <img src="{{ asset('favicon.ico') }}" alt="IMISS"
                                class="flex-shrink-0 h-8 w-8 object-contain brightness-110 group-hover:brightness-150 transition-all duration-300">

                            <div x-show="sidebarExpanded" x-transition:enter="transition-opacity duration-300"
                                class="flex flex-col leading-none overflow-hidden whitespace-nowrap">

                                {{-- Technical Subtitle: Small, Monospaced, and Wide-spaced --}}
                                <span
                                    class="text-[10px] font-mono font-bold text-sky-400/90 uppercase tracking-[0.4em] mb-1">
                                    BGHMC System
                                </span>

                                {{-- Main Brand: Bold, Clean, and High Contrast --}}
                                <div class="flex items-baseline gap-2">
                                    <span class="text-[22px] font-extrabold tracking-tighter text-white">
                                        IMISS
                                    </span>

                                    {{-- Separator Dot --}}
                                    <span class="text-sky-500 font-black text-[18px] leading-none">•</span>

                                    {{-- Secondary Title: Lighter weight, Sky Blue --}}
                                    <span class="text-sky-300 font-semibold text-[14px] tracking-tight">
                                        Inventory
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>

                    {{-- Nav Links --}}
                    <nav class="flex-1 overflow-y-auto sidebar-scroll py-6 px-4 space-y-2 flex flex-col relative [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none]"
                        :class="sidebarExpanded ? 'items-stretch' : 'items-center'">

                        {{-- Vertical tracking line --}}
                        <div class="absolute left-9 top-8 bottom-8 w-[1px] bg-gradient-to-b from-transparent via-sky-500/20 to-transparent -z-10"
                            x-show="sidebarExpanded" x-transition.opacity></div>

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
                                @click="if(window.location.href === '{{ $targetUrl }}') $event.preventDefault()" class="relative flex items-center transition-all duration-300 group rounded-xl outline-none focus-visible:ring-2 focus-visible:ring-sky-400
                                                {{ $isActive ? 'bg-sky-500/20' : 'hover:bg-white/10' }}"
                                :class="sidebarExpanded ? 'w-full px-3 py-2.5' : 'w-12 h-12 justify-center'"
                                aria-label="{{ $link['label'] }}">

                                {{-- Floating Tooltip --}}
                                <div x-show="!sidebarExpanded"
                                    class="absolute left-16 px-3 py-1.5 bg-white text-slate-900 text-xs font-bold rounded-lg opacity-0 -translate-x-2 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-300 pointer-events-none z-50 shadow-xl border border-sky-100 whitespace-nowrap flex items-center">
                                    {{ $link['label'] }}
                                    <div
                                        class="absolute -left-1 top-1/2 -translate-y-1/2 w-2 h-2 bg-white border-l border-b border-sky-100 rotate-45">
                                    </div>
                                </div>

                                {{-- Active Indicator (White Glow) --}}
                                @if($isActive)
                                    <div
                                        class="absolute left-0 top-1/2 -translate-y-1/2 w-[3px] h-3/4 bg-white rounded-r-full shadow-[0_0_12px_rgba(255,255,255,0.8)]">
                                    </div>
                                @endif

                                {{-- Icon Wrapper --}}
                                <div
                                    class="relative z-10 flex items-center justify-center p-2 rounded-lg transition-transform duration-300 group-hover:scale-110
                                                    {{ $isActive ? 'bg-sky-400 text-white shadow-lg ring-1 ring-white/20' : 'bg-slate-900/40 text-sky-200 group-hover:text-white ring-1 ring-white/5' }}">
                                    <svg class="shrink-0 w-5 h-5 transition-colors duration-300" fill="none"
                                        viewBox="0 0 24 24" stroke-width="{{ $isActive ? '2.5' : '2' }}"
                                        stroke="currentColor">
                                        {!! $link['icon'] !!}
                                    </svg>
                                </div>

                                {{-- Text Label --}}
                                <span
                                    class="text-[14px] font-medium tracking-wide whitespace-nowrap transition-all duration-300
                                                    {{ $isActive ? 'text-white' : 'text-sky-100/80 group-hover:text-white' }}"
                                    :class="sidebarExpanded ? 'opacity-100 ml-4 translate-x-0' : 'opacity-0 w-0 ml-0 -translate-x-4 hidden'">
                                    {{ $link['label'] }}
                                </span>
                            </a>
                        @endforeach

                        @if(Auth::user() && Auth::user()->role === 'admin')
                            <div class="pt-8 pb-3 relative" :class="sidebarExpanded ? 'px-3' : 'flex justify-center'">
                                <span x-show="sidebarExpanded"
                                    class="text-[10px] font-mono font-bold text-sky-300 uppercase tracking-[0.2em] flex items-center gap-3">
                                    Admin System
                                    <span class="h-px bg-gradient-to-r from-sky-500/30 to-transparent flex-1"></span>
                                </span>
                                <div x-show="!sidebarExpanded" class="w-6 h-[2px] bg-sky-500/30 rounded-full"></div>
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
                                    @click="if(window.location.href === '{{ $targetUrl }}') $event.preventDefault()"
                                    class="relative flex items-center transition-all duration-300 group rounded-xl outline-none focus-visible:ring-2 focus-visible:ring-sky-400
                                                                        {{ $isActive ? 'bg-white/20 shadow-inner' : 'hover:bg-white/10' }}"
                                    :class="sidebarExpanded ? 'w-full px-3 py-2.5' : 'w-12 h-12 justify-center'"
                                    aria-label="{{ $link['label'] }}">

                                    <div x-show="!sidebarExpanded"
                                        class="absolute left-16 px-3 py-1.5 bg-white text-slate-900 text-xs font-bold rounded-lg opacity-0 -translate-x-2 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-300 pointer-events-none z-50 shadow-xl border border-sky-100 whitespace-nowrap flex items-center">
                                        {{ $link['label'] }}
                                        <div
                                            class="absolute -left-1 top-1/2 -translate-y-1/2 w-2 h-2 bg-white border-l border-b border-sky-100 rotate-45">
                                        </div>
                                    </div>

                                    @if($isActive)
                                        <div
                                            class="absolute left-0 top-1/2 -translate-y-1/2 w-[3px] h-3/4 bg-white rounded-r-full shadow-[0_0_12px_rgba(255,255,255,0.9)]">
                                        </div>
                                    @endif

                                    <div
                                        class="relative z-10 flex items-center justify-center p-2 rounded-lg transition-transform duration-300 group-hover:scale-110
                                                                        {{ $isActive ? 'bg-white text-sky-600 shadow-md' : 'bg-slate-900/40 text-sky-200 group-hover:text-white' }}">
                                        <svg class="shrink-0 w-5 h-5 transition-colors duration-300" fill="none"
                                            viewBox="0 0 24 24" stroke-width="{{ $isActive ? '2.5' : '2' }}"
                                            stroke="currentColor">
                                            {!! $link['icon'] !!}
                                        </svg>
                                    </div>

                                    <span
                                        class="text-[14px] font-medium tracking-wide whitespace-nowrap transition-all duration-300
                                                                        {{ $isActive ? 'text-white' : 'text-sky-100/80 group-hover:text-white' }}"
                                        :class="sidebarExpanded ? 'opacity-100 ml-4 translate-x-0' : 'opacity-0 w-0 ml-0 -translate-x-4 hidden'">
                                        {{ $link['label'] }}
                                    </span>
                                </a>
                            @endforeach
                        @endif
                    </nav>
                </div>
            </div>

            {{-- Floating Toggle Button (Navy/Sky Blue) --}}
            <button @click="sidebarExpanded = !sidebarExpanded"
                class="hidden lg:flex absolute -right-3.5 top-1/2 -translate-y-1/2 z-[60] items-center justify-center w-7 h-7 bg-[#0f172a] border border-sky-400/50 rounded-full text-sky-400 hover:text-white hover:bg-sky-500 hover:shadow-[0_0_12px_rgba(14,165,233,0.5)] transition-all duration-300 shadow-lg focus:outline-none group focus:ring-2 focus:ring-sky-400"
                aria-label="Toggle Sidebar">

                <div
                    class="absolute inset-0 rounded-full bg-sky-400/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                </div>

                <svg class="w-3.5 h-3.5 transition-transform duration-500 ease-[cubic-bezier(0.34,1.56,0.64,1)] relative z-10"
                    :class="sidebarExpanded ? 'rotate-0' : 'rotate-180'" fill="none" viewBox="0 0 24 24"
                    stroke-width="3" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
            </button>
        </aside>

        <style>
            @keyframes shine {
                to {
                    background-position: 200% center;
                }
            }

            .sidebar-scroll::-webkit-scrollbar {
                display: none;
            }
        </style>

        {{-- ==================== --}}
        {{-- MAIN CONTENT AREA --}}
        {{-- ==================== --}}
        <div class="flex-1 flex flex-col h-full overflow-hidden relative bg-[#F0F4F8] rounded-l-3xl">
            {{-- Top Header (Clean Modern High-Contrast) --}}
            <header
                class="relative z-30 flex h-[75px] items-center justify-between gap-4 px-8 bg-white/80 backdrop-blur-md border-b border-sky-100 shrink-0 shadow-sm">

                {{-- Technical Accent Bar --}}
                <div
                    class="absolute top-0 left-0 w-full h-[3px] bg-gradient-to-r from-[#0f172a] via-[#0ea5e9] to-transparent">
                </div>

                {{-- Mobile menu + Page title --}}
                <div class="flex items-center gap-6">
                    <button @click="mobileOpen = true" type="button"
                        class="lg:hidden p-2 text-sky-600 hover:bg-sky-50 border border-sky-100 rounded-lg transition-all active:scale-95">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>

                    {{-- Decorative Tech Visual --}}
                    <div class="hidden sm:flex items-center gap-3 pl-2" aria-hidden="true">
                        {{-- Network Node --}}


                        {{-- Tracing Path --}}


                        {{-- Status Chip --}}
                    </div>
                </div>

                {{-- Right Utility Section --}}
                <div class="flex items-center gap-6 shrink-0">
                    {{-- Status Badge (Sky Blue Tone) --}}


                    {{-- User Dropdown --}}
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.outside="open = false"
                            class="flex items-center gap-3.5 group transition-all">

                            {{-- Avatar --}}
                            <div
                                class="h-10 w-10 bg-[#0f172a] rounded-xl flex items-center justify-center text-white font-bold text-sm shadow-md group-hover:bg-[#1e3a8a] transition-colors border-2 border-white ring-1 ring-sky-100">
                                {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                            </div>

                            <div class="hidden lg:flex flex-col items-start leading-tight">
                                <span
                                    class="text-[14px] font-extrabold text-[#0f172a] group-hover:text-sky-600 transition-colors">
                                    {{ Auth::user()->name ?? 'Administrator' }}
                                </span>
                                <span class="text-[10px] font-bold text-sky-400 uppercase tracking-widest">
                                    {{ Auth::user()->role ?? 'Root' }}
                                </span>
                            </div>

                            <svg viewBox="0 0 20 20" fill="currentColor"
                                class="w-5 h-5 text-sky-300 transition-transform duration-300"
                                :class="{ 'rotate-180': open }">
                                <path fill-rule="evenodd"
                                    d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>

                        {{-- Dropdown Menu (Navy/Sky/White) --}}
                        <div x-show="open" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                            class="absolute right-0 top-[calc(100%+12px)] w-60 bg-white border border-sky-100 shadow-[0_20px_50px_rgba(15,23,42,0.15)] rounded-2xl z-50 overflow-hidden"
                            style="display: none;">

                            <div class="px-5 py-4 bg-sky-50/30 border-b border-sky-50">
                                <p class="text-[10px] font-bold text-sky-400 uppercase tracking-[0.2em]">User
                                    Account
                                </p>
                            </div>

                            <div class="p-2">
                                <a href="{{ route('profile.show') }}"
                                    class="flex items-center gap-3 px-4 py-3 text-sm font-bold text-[#0f172a] hover:bg-sky-50 hover:text-sky-600 rounded-xl transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Profile Details
                                </a>

                                <form method="POST" action="{{ route('logout') }}" class="m-0">
                                    @csrf
                                    <button type="submit"
                                        class="flex w-full items-center gap-3 px-4 py-3 text-sm font-bold text-rose-500 hover:bg-rose-50 rounded-xl transition-all text-left">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Main Content Area --}}
            <main class="flex-1 overflow-y-auto relative z-10 p-4 lg:p-8">
                <div class="w-full max-w-[1600px] mx-auto">

                    @hasSection('actions')
                        <div class="mb-6 flex flex-wrap items-center justify-end gap-3">
                            @yield('actions')
                        </div>
                    @endif

                    @yield('content')
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