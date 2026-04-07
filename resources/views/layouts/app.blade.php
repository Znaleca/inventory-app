<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50 selection:bg-emerald-500 selection:text-white">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'BGHMC CathLab') — Bataan General Hospital</title>

    <script>
        if (localStorage.getItem('darkMode') === 'true' || (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:300,400,500,600,700,800" rel="stylesheet" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="{{ asset('styles/custom.css') }}" rel="stylesheet" />
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="min-h-full antialiased text-slate-800 overflow-hidden"
    x-data="{ sidebarExpanded: true, mobileOpen: false, darkMode: document.documentElement.classList.contains('dark') }"
    x-init="$watch('darkMode', val => { localStorage.setItem('darkMode', val); val ? document.documentElement.classList.add('dark') : document.documentElement.classList.remove('dark'); })">

    <div class="ambient-glow-1"></div>
    <div class="ambient-glow-2"></div>

    <div class="absolute top-0 inset-x-0 h-[350px] pointer-events-none z-0 opacity-60 dark:opacity-30" 
         style="mask-image: linear-gradient(to bottom, black 20%, transparent 100%); -webkit-mask-image: linear-gradient(to bottom, black 20%, transparent 100%);">
        <svg class="absolute inset-0 w-full h-full" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="top-grid-pattern" width="32" height="32" patternUnits="userSpaceOnUse" x="0" y="0">
                    <circle cx="2" cy="2" r="1.5" class="fill-emerald-600/30 dark:fill-emerald-400/40"></circle>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#top-grid-pattern)"></rect>
        </svg>
    </div>

    <div class="flex h-screen relative z-10 p-0 lg:p-4 gap-4">

        <div x-show="mobileOpen" x-transition.opacity
            class="fixed inset-0 z-40 bg-slate-900/40 backdrop-blur-sm lg:hidden" @click="mobileOpen = false"></div>

        {{-- Floating Sidebar --}}
        <aside
            class="fixed inset-y-0 left-0 z-50 flex flex-col glass-panel rounded-none lg:rounded-3xl transition-all duration-300 ease-in-out lg:static lg:translate-x-0 h-full lg:h-auto border-r border-slate-200/50 shadow-sm w-64"
            :class="{
                '-translate-x-full': !mobileOpen,
                'translate-x-0': mobileOpen,
                'w-64': sidebarExpanded,
                'w-[88px]': !sidebarExpanded
            }"
            x-cloak>

            <div class="absolute inset-x-0 bottom-0 pointer-events-none z-0 flex items-end justify-center overflow-hidden h-32 opacity-20 transition-opacity duration-300"
                :class="sidebarExpanded ? 'opacity-20' : 'opacity-10'">
                <svg width="100%" height="100%" viewBox="0 0 1000 200" preserveAspectRatio="none">
                    <path class="ekg-path"
                        d="M0 100 L300 100 L330 40 L360 160 L390 100 L700 100 L720 70 L740 130 L760 100 L1000 100"
                        stroke="#10b981" stroke-width="1.5" fill="none" vector-effect="non-scaling-stroke"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>

            <div class="relative z-10 flex flex-col h-full w-full">

                {{-- Sidebar Header / Branding with Dashboard Link --}}
                <div class="flex items-center h-20 px-4 shrink-0 overflow-hidden transition-all mt-4 mb-2"
                    :class="sidebarExpanded ? 'justify-start px-6' : 'justify-center'">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 shrink-0 group cursor-pointer">
                        <div class="relative flex items-center justify-center shrink-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3">
                            <img src="{{ asset('favicon.ico') }}" alt="Logo" class="h-10 w-10 object-contain drop-shadow-md">
                        </div>
                        <div class="flex flex-col justify-center whitespace-nowrap transition-all duration-300"
                            :class="sidebarExpanded ? 'opacity-100 translate-x-0 w-auto ml-1' : 'opacity-0 -translate-x-4 w-0 ml-0 hidden'">
                            <span class="text-[10px] font-black text-emerald-600 tracking-[0.25em] uppercase leading-none mb-1">BGHMC</span>
                            <span class="text-[15px] font-extrabold text-slate-800 tracking-tight leading-none">
                                CathLab <span class="text-emerald-500 font-semibold">Inventory</span>
                            </span>
                        </div>
                    </a>
                </div>

                <nav class="flex-1 overflow-y-auto sidebar-scroll p-4 space-y-1.5 flex flex-col relative z-10"
                    :class="sidebarExpanded ? 'items-stretch' : 'items-center'">

                    @php
                        $navLinks = [
                            ['route' => 'dashboard', 'pattern' => 'dashboard', 'label' => 'Dashboard', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />'],
                            ['route' => 'items.index', 'pattern' => 'items.*', 'label' => 'Items', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />'],
                            ['route' => 'categories.index', 'pattern' => 'categories.*', 'label' => 'Categories', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />'],
                            ['route' => 'units.index', 'pattern' => 'units.*', 'label' => 'Units', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0 1 18 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3 1.5 1.5 3-3.75" />'],
                            ['route' => 'in-out.index', 'pattern' => ['in-out.*', 'transfers.*', 'borrows.*', 'returns.*'], 'label' => 'In / Out', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />'],
                            ['route' => 'staff.index', 'pattern' => 'staff.*', 'label' => 'Staff', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />'],
                            ['route' => 'logs.index', 'pattern' => 'logs.*', 'label' => 'History', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />'],
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
                            @click="if(window.location.href === '{{ $targetUrl }}') $event.preventDefault()"
                            class="relative flex items-center rounded-xl transition-all duration-300 group overflow-hidden border border-transparent
                            {{ $isActive ? 'bg-emerald-500 text-white shadow-md shadow-emerald-500/30 ring-1 ring-emerald-400/50' : 'text-slate-500 hover:bg-emerald-50/80 hover:text-emerald-700 hover:translate-x-1 hover:border-emerald-100' }}"
                            :class="sidebarExpanded ? 'w-full px-3 py-2.5 justify-start' : 'w-11 h-11 justify-center'"
                            title="{{ $link['label'] }}">

                            <svg class="shrink-0 transition-transform duration-300 group-hover:scale-110 {{ $isActive ? 'text-white w-5 h-5' : 'text-slate-400 group-hover:text-emerald-600 w-[22px] h-[22px]' }}"
                                fill="none" viewBox="0 0 24 24" stroke-width="{{ $isActive ? '2.5' : '2' }}"
                                stroke="currentColor">
                                {!! $link['icon'] !!}
                            </svg>

                            <span class="text-[14px] font-semibold whitespace-nowrap transition-all duration-300"
                                :class="sidebarExpanded ? 'opacity-100 translate-x-0 w-auto ml-3.5' : 'opacity-0 -translate-x-4 w-0 ml-0 hidden'">
                                {{ $link['label'] }}
                            </span>
                        </a>
                    @endforeach

                    @if(Auth::user() && Auth::user()->role === 'admin')
                        <div class="w-full flex items-center gap-2 my-4 px-2"
                            :class="sidebarExpanded ? 'justify-start' : 'justify-center'">
                            <div class="h-px bg-slate-200/80 flex-1 transition-all"></div>
                            <span class="text-[10px] font-bold text-slate-400 tracking-widest uppercase" x-show="sidebarExpanded">Admin</span>
                            <div class="h-px bg-slate-200/80 flex-1 transition-all" x-show="sidebarExpanded"></div>
                        </div>

                        @php
                            $adminLinks = [
                                ['route' => 'users.index', 'pattern' => 'users.*', 'label' => 'Users', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />'],
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
                                class="relative flex items-center rounded-xl transition-all duration-300 group overflow-hidden border border-transparent
                                {{ $isActive ? 'bg-slate-700 text-white shadow-md shadow-slate-700/30 ring-1 ring-slate-600' : 'text-slate-500 hover:bg-slate-100/80 hover:text-slate-800 hover:translate-x-1 hover:border-slate-200' }}"
                                :class="sidebarExpanded ? 'w-full px-3 py-2.5 justify-start' : 'w-11 h-11 justify-center'"
                                title="{{ $link['label'] }}">

                                <svg class="shrink-0 transition-transform duration-300 group-hover:scale-110 {{ $isActive ? 'text-white w-5 h-5' : 'text-slate-400 group-hover:text-slate-600 w-[22px] h-[22px]' }}"
                                    fill="none" viewBox="0 0 24 24" stroke-width="{{ $isActive ? '2.5' : '2' }}"
                                    stroke="currentColor">
                                    {!! $link['icon'] !!}
                                </svg>

                                <span class="text-[14px] font-semibold whitespace-nowrap transition-all duration-300"
                                    :class="sidebarExpanded ? 'opacity-100 translate-x-0 w-auto ml-3.5' : 'opacity-0 -translate-x-4 w-0 ml-0 hidden'">
                                    {{ $link['label'] }}
                                </span>
                            </a>
                        @endforeach
                    @endif
                </nav>

                <button @click="sidebarExpanded = !sidebarExpanded"
    class="hidden lg:flex absolute top-1/2 -translate-y-1/2 -right-4 z-50 items-center justify-center w-8 h-8 bg-white text-emerald-600 rounded-full shadow-md hover:shadow-lg hover:scale-110 focus:outline-none transition-all duration-300 border border-emerald-100 group">
    <svg class="w-4 h-4 transition-transform duration-300 group-hover:text-emerald-500"
        :class="sidebarExpanded ? 'rotate-0' : 'rotate-180'" fill="none" viewBox="0 0 24 24"
        stroke-width="2.5" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
    </svg>
</button>
            </div>
        </aside>

        <div class="flex-1 flex flex-col h-full overflow-hidden bg-transparent rounded-3xl">
            <header
                class="relative z-30 flex h-[4.5rem] items-center justify-between gap-4 px-6 mb-4 glass-panel rounded-none lg:rounded-3xl shrink-0">
                <div class="flex items-center gap-4">
                    <button @click="mobileOpen = true" type="button"
                        class="lg:hidden p-2 -ml-2 text-slate-500 hover:bg-emerald-50 hover:text-emerald-600 rounded-xl focus:outline-none transition-colors">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>
                    <div class="flex flex-col">
                        <h2 class="text-xl font-extrabold text-slate-800 tracking-tight leading-snug mb-0 flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.8)] hidden sm:block shrink-0"></div>
                            @yield('title', 'Dashboard')
                        </h2>
                    </div>
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    <button @click="darkMode = !darkMode" type="button"
                        class="dark-toggle-btn p-2.5 rounded-xl text-slate-500 hover:bg-emerald-50 hover:text-emerald-600 focus:outline-none transition-colors duration-200">
                        <svg x-show="darkMode" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="2" stroke="currentColor" class="w-5 h-5 text-emerald-400">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                        </svg>
                        <svg x-show="!darkMode" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="2" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
                        </svg>
                    </button>

                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.outside="open = false"
                            class="flex items-center gap-3 bg-white/50 border border-slate-200/60 rounded-full py-1.5 pl-4 pr-2 hover:bg-white hover:shadow-md hover:border-emerald-300 group focus:outline-none transition-all duration-300">
                            <div class="hidden md:flex flex-col items-end">
                                <span class="text-[13px] font-bold text-slate-700 group-hover:text-emerald-600 transition-colors leading-tight">{{ Auth::user()->name ?? 'User' }}</span>
                                <span class="text-[9px] font-black text-emerald-500 uppercase tracking-widest">{{ Auth::user()->role ?? 'Staff' }}</span>
                            </div>
                            <div class="relative">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-tr from-emerald-500 to-teal-400 text-sm font-bold text-white shadow-inner group-hover:scale-105 transition-transform duration-300">
                                    {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                                </div>
                                <div class="absolute -bottom-0.5 -right-0.5 h-3.5 w-3.5 rounded-full bg-emerald-400 border-2 border-white shadow-sm"></div>
                            </div>
                            <svg viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 text-slate-400 group-hover:text-emerald-500 transition-transform duration-300" :class="{ 'rotate-180': open }">
                                <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div x-show="open" x-transition class="absolute right-0 top-full mt-3 w-56 rounded-2xl border border-white/10 shadow-2xl py-2 z-50 overflow-hidden profile-dropdown" style="display: none; background-color: #13141B;">
                            <a href="{{ route('profile.show') }}" class="flex items-center gap-3 px-5 py-3 text-sm font-bold text-slate-300 hover:bg-emerald-500/10 hover:text-emerald-400 transition-colors border-b border-white/5">My Profile</a>
                            <form method="POST" action="{{ route('logout') }}" class="m-0">
                                @csrf
                                <button type="submit" class="flex w-full items-center gap-3 px-5 py-3 text-sm font-bold text-slate-300 hover:bg-rose-500/10 hover:text-rose-400 transition-colors text-left">Sign Out</button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto relative z-10 glass-panel lg:rounded-3xl border-0 lg:border">
                <div class="p-4 sm:p-6 lg:p-8 max-w-[1600px] mx-auto w-full">
                    @hasSection('actions')
                        <div class="mb-6 md:mb-8 flex flex-wrap items-center justify-end gap-3">
                            @yield('actions')
                        </div>
                    @endif
                    <div class="w-full relative">
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