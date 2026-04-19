<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>IMISS Inventory — BGHMC</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=fira-code:400,500,600|plus-jakarta-sans:300,400,500,600,700,800" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Blueprint grid background — matches app layout */
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

        @keyframes pulse-ring {
            0%   { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); }
            70%  { box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
            100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }
        .status-pulse { animation: pulse-ring 2s infinite; }

        @keyframes scan {
            0%   { transform: translateY(-100%); opacity: 0; }
            10%  { opacity: 1; }
            90%  { opacity: 1; }
            100% { transform: translateY(400%); opacity: 0; }
        }
        .scan-line { animation: scan 4s linear infinite; }
    </style>
</head>

<body class="antialiased min-h-screen text-slate-900 overflow-hidden" style="font-family: 'Plus Jakarta Sans', sans-serif;">

    <div class="flex min-h-screen w-full">

        {{-- ======================== --}}
        {{-- LEFT PANEL (Info)        --}}
        {{-- ======================== --}}
        <div class="hidden lg:flex w-7/12 flex-col justify-between p-14 xl:p-20 bg-slate-900 relative overflow-hidden">

            {{-- Blueprint grid overlay on dark background --}}
            <div class="absolute inset-0 opacity-10" style="background-image: linear-gradient(rgba(99,102,241,0.3) 1px, transparent 1px), linear-gradient(90deg, rgba(99,102,241,0.3) 1px, transparent 1px); background-size: 40px 40px;"></div>

            {{-- Top accent bar --}}
            <div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-blue-600 to-indigo-500 z-10"></div>

            {{-- Corner node decorations --}}
            <div class="absolute top-5 right-5 w-3 h-3 border border-blue-500/40 bg-slate-900"></div>
            <div class="absolute bottom-5 left-5 w-3 h-3 border border-blue-500/40 bg-slate-900"></div>

            {{-- Scan line animation --}}
            <div class="absolute left-0 right-0 h-[1px] bg-gradient-to-r from-transparent via-blue-500/30 to-transparent scan-line z-0 pointer-events-none"></div>

            {{-- Branding --}}
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-[3px] h-8 bg-blue-500"></div>
                    <div>
                        <p class="font-mono text-[10px] font-semibold text-blue-400 tracking-[0.3em] uppercase">System://BGHMC</p>
                        <p class="text-[9px] font-mono text-slate-500 uppercase tracking-[0.15em]">Bataan General Hospital &amp; Medical Center</p>
                    </div>
                </div>
            </div>

            {{-- Hero Text --}}
            <div class="relative z-10">
                <p class="font-mono text-[10px] text-blue-500/70 tracking-[0.3em] uppercase mb-4">v1.0 // Inventory Module</p>

                <h1 class="text-6xl xl:text-7xl font-black leading-[0.9] tracking-tighter mb-6">
                    <span class="text-white">IMISS</span><br>
                    <span class="text-blue-500">INVENTORY</span>
                </h1>

                <p class="max-w-sm text-slate-400 text-base leading-relaxed font-medium border-l-2 border-blue-500/30 pl-4">
                    Centralized system for managing
                    <span class="text-blue-400">technical devices</span>,
                    <span class="text-blue-400">equipment</span>, and
                    <span class="text-blue-400">consumables</span>
                    with real-time tracking and secure access.
                </p>

                <div class="mt-8 flex gap-6">
                    <div class="border border-slate-700 px-4 py-2.5">
                        <p class="font-mono text-[9px] text-slate-500 uppercase tracking-widest mb-1">Module</p>
                        <p class="font-mono text-xs text-slate-300 font-bold">IMISS.v1</p>
                    </div>
                    <div class="border border-slate-700 px-4 py-2.5">
                        <p class="font-mono text-[9px] text-slate-500 uppercase tracking-widest mb-1">Facility</p>
                        <p class="font-mono text-xs text-slate-300 font-bold">BGHMC</p>
                    </div>
                    <div class="border border-slate-700 px-4 py-2.5">
                        <p class="font-mono text-[9px] text-slate-500 uppercase tracking-widest mb-1">Access</p>
                        <p class="font-mono text-xs text-slate-300 font-bold">RESTRICTED</p>
                    </div>
                </div>
            </div>

            {{-- Status Footer --}}
            <div class="relative z-10">
                <div class="flex items-center gap-3 border border-slate-800 bg-slate-800/50 w-fit px-4 py-2.5">
                    <span class="h-2 w-2 bg-emerald-400 status-pulse inline-block"></span>
                    <span class="font-mono text-[10px] text-slate-300 uppercase tracking-[0.2em]">SYS.ONLINE &amp; SECURE</span>
                </div>
                <p class="font-mono text-[9px] text-slate-600 mt-3 uppercase tracking-widest">Gateway: {{ Request::ip() }}</p>
            </div>
        </div>

        {{-- ======================== --}}
        {{-- RIGHT PANEL (Login Form) --}}
        {{-- ======================== --}}
        <div class="flex w-full lg:w-5/12 items-center justify-center p-8 relative">

            {{-- Corner nodes matching the layout style --}}
            <div class="absolute top-6 right-6 hidden lg:block">
                <div class="flex items-center gap-2 border border-slate-200 bg-white px-3 py-1.5">
                    <span class="h-1.5 w-1.5 bg-emerald-400 inline-block status-pulse"></span>
                    <span class="font-mono text-[10px] text-slate-500 uppercase tracking-widest">Auth.Portal</span>
                </div>
            </div>

            <div class="w-full max-w-sm">

                {{-- Form Card --}}
                <div class="bg-white border border-slate-200 relative shadow-sm">
                    {{-- Top accent --}}
                    <div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-blue-500 to-indigo-500"></div>
                    {{-- Left accent bar --}}
                    <div class="absolute top-0 left-0 bottom-0 w-1 bg-blue-500"></div>
                    {{-- Corner nodes --}}
                    <div class="absolute top-[-3px] right-[-3px] w-2 h-2 border border-blue-300 bg-white"></div>
                    <div class="absolute bottom-[-3px] right-[-3px] w-2 h-2 border border-slate-200 bg-white"></div>

                    <div class="p-8 pl-9">

                        {{-- Header --}}
                        <div class="mb-7">
                            <div class="flex items-center gap-3 mb-5">
                                {{-- Bare favicon — no border/ring --}}
                                <img src="{{ asset('favicon.ico') }}" alt="BGHMC" class="h-9 w-9 object-contain">
                                <div>
                                    <p class="font-mono text-[9px] text-blue-600 uppercase tracking-[0.25em] mb-0.5">System://Auth</p>
                                    <h2 class="text-lg font-black text-slate-800 tracking-tight">IMISS <span class="text-slate-400 font-normal">Inventory</span></h2>
                                </div>
                            </div>
                            <div class="border-t border-dashed border-slate-200 pt-5">
                                <h1 class="text-2xl font-black text-slate-800 tracking-tight mb-1">Sign In</h1>
                                <p class="font-mono text-[10px] text-slate-400 uppercase tracking-[0.2em]">// Authenticate to continue</p>
                            </div>
                        </div>

                        {{-- Error Alert --}}
                        @if ($errors->any())
                        <div class="mb-5 border border-rose-200 bg-rose-50 px-4 py-3 relative">
                            <div class="absolute top-0 left-0 w-1 h-full bg-rose-500"></div>
                            <p class="font-mono text-[10px] text-rose-600 uppercase tracking-widest mb-1">// Error</p>
                            @foreach ($errors->all() as $error)
                            <p class="text-sm text-rose-700 font-medium">{{ $error }}</p>
                            @endforeach
                        </div>
                        @endif

                        {{-- Form --}}
                        <form method="POST" action="{{ route('login') }}" class="space-y-4">
                            @csrf

                            {{-- Username --}}
                            <div>
                                <label class="font-mono text-[10px] text-slate-500 font-bold uppercase tracking-[0.2em] mb-1.5 block">
                                    Bio_ID / Username
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <input type="text" name="bio_id" required autofocus
                                        class="bg-slate-50 border border-slate-200 focus:bg-white focus:border-blue-500 focus:outline-none focus:ring-0 transition-all block w-full py-3 pl-10 pr-4 text-sm font-medium text-slate-800 font-mono placeholder:font-sans placeholder:text-slate-400"
                                        placeholder="Enter credentials">
                                </div>
                            </div>

                            {{-- Password --}}
                            <div>
                                <label class="font-mono text-[10px] text-slate-500 font-bold uppercase tracking-[0.2em] mb-1.5 block">
                                    Password
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                    </div>
                                    <input type="password" name="password" required
                                        class="bg-slate-50 border border-slate-200 focus:bg-white focus:border-blue-500 focus:outline-none focus:ring-0 transition-all block w-full py-3 pl-10 pr-4 text-sm font-medium text-slate-800 placeholder:tracking-normal tracking-widest"
                                        placeholder="••••••••">
                                </div>
                            </div>

                            {{-- Submit --}}
                            <div class="pt-3">
                                <button type="submit"
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold text-[11px] uppercase py-3.5 flex items-center justify-center gap-3 transition-colors tracking-[0.2em] font-mono border border-blue-700 relative group">
                                    <span>Login</span>
                                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </button>
                            </div>
                        </form>

                        {{-- Footer --}}
                        <div class="mt-6 pt-5 border-t border-dashed border-slate-200">
                            <p class="font-mono text-[9px] text-slate-400 uppercase tracking-widest text-center">
                                BGHMC-IMISS // Restricted Access
                            </p>
                        </div>

                    </div>
                </div>

                {{-- Below card --}}
                <div class="mt-3 flex justify-between items-center">
                    <span class="font-mono text-[9px] text-slate-400 uppercase tracking-widest">{{ now()->format('Y-m-d') }}</span>
                    <span class="font-mono text-[9px] text-slate-400 uppercase tracking-widest">{{ Request::ip() }}</span>
                </div>

            </div>
        </div>
    </div>

</body>
</html>