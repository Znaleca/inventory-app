<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Secure Access — BGHMC CathLab</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:300,400,500,600,700,800" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --brand-emerald: #10b981;
            --glass-bg: rgba(255, 255, 255, 0.85);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: radial-gradient(circle at top right, rgba(16, 185, 129, 0.08), transparent 40%),
                        radial-gradient(circle at bottom left, rgba(15, 23, 42, 0.03), transparent 40%),
                        #fcfcfd;
        }

        .ekg-path {
            stroke-dasharray: 1000;
            stroke-dashoffset: 1000;
            animation: dash 5s linear infinite;
        }

        @keyframes dash {
            from { stroke-dashoffset: 1000; }
            to { stroke-dashoffset: 0; }
        }

        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.7);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.05);
        }

        .medical-input {
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(15, 23, 42, 0.08);
        }

        .medical-input:focus {
            background: white;
            border-color: var(--brand-emerald);
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
        }

        .noise::before {
            content: "";
            position: absolute;
            inset: 0;
            opacity: 0.03;
            pointer-events: none;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3BaseFilter id='filter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/feFilter%3E%3Crect width='100%25' height='100%25' filter='url(%23filter)'/%3E%3C/svg%3E");
        }
    </style>
</head>
<body class="h-full antialiased selection:bg-emerald-500 selection:text-white noise">
    <div class="flex min-h-full">

        {{-- Left Side: Desktop Only --}}
        <div class="relative hidden w-1/2 overflow-hidden bg-slate-950 lg:flex flex-col justify-between p-20">
            <div class="absolute inset-0 z-0">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_50%_50%,rgba(16,185,129,0.1),transparent_70%)]"></div>
                <svg width="100%" height="100%" class="opacity-30">
                    <path class="ekg-path" d="M0 500 L400 500 L420 450 L440 550 L460 500 L600 500 L620 480 L640 520 L660 500 L1000 500" 
                          stroke="#10b981" stroke-width="2" fill="none" vector-effect="non-scaling-stroke"/>
                </svg>
            </div>

            <div class="relative z-10">
                <div class="flex items-center gap-5">
                    <img src="{{ asset('favicon.ico') }}" alt="Logo" class="h-10 w-10 brightness-110">
                    <div class="flex flex-col">
                        <span class="text-[10px] font-black text-emerald-500 tracking-[0.3em] uppercase leading-none mb-1">Bataan General Hospital and Medical Center</span>
                        <span class="text-2xl font-extrabold text-white tracking-widest uppercase leading-none">CathLab <span class="text-slate-500 font-light">Inventory</span></span>
                    </div>
                </div>
            </div>

            <div class="relative z-10">
                <h2 class="text-7xl font-extrabold text-white leading-[0.9] tracking-tighter mb-8">
                    Precision <br><span class="text-slate-600">for every</span> <br>
                    <span class="relative">Heartbeat.<span class="absolute -bottom-2 left-0 w-24 h-1 bg-emerald-500 rounded-full"></span></span>
                </h2>
                <p class="max-w-xs text-slate-400 text-base leading-relaxed font-light">Securing cardiovascular supply chains and patient data integrity.</p>
            </div>

            <div class="relative z-10 flex gap-10">
                <div class="flex flex-col">
                    <span class="text-[10px] text-slate-500 uppercase tracking-widest font-bold">Protocol</span>
                    <span class="text-xs text-slate-300 font-mono">L3-ENCRYPTED</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-[10px] text-slate-500 uppercase tracking-widest font-bold">Version</span>
                    <span class="text-xs text-slate-300 font-mono">v{{ config('app.version', '2.4.1') }}</span>
                </div>
            </div>
        </div>

        {{-- Right Side: Login (Optimized for Mobile) --}}
        <div class="flex w-full flex-col items-center justify-center px-5 sm:px-8 lg:w-1/2 relative py-10">
            
            {{-- Mobile Branding Header --}}
            <div class="flex flex-col items-center text-center mb-10 lg:hidden">
                <img src="{{ asset('favicon.ico') }}" alt="Logo" class="h-14 w-14 mb-4 drop-shadow-sm">
                <span class="text-[9px] font-black text-emerald-600 tracking-[0.25em] uppercase mb-1 px-4">Bataan General Hospital and Medical Center</span>
                <h1 class="text-3xl font-black text-slate-950 tracking-tight uppercase">CathLab <span class="text-emerald-500">Portal</span></h1>
            </div>

            <div class="w-full max-w-[440px]">
                <div class="glass-card p-8 sm:p-12 rounded-[2.5rem] relative overflow-hidden">
                    
                    {{-- Status Indicator --}}
                    <div class="absolute top-0 right-0 p-8">
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">System Live</span>
                            <div class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></div>
                        </div>
                    </div>

                    <header class="mb-10">
                        <h3 class="text-2xl font-extrabold text-slate-900">Sign In</h3>
                        <p class="text-sm text-slate-500 mt-1">Enter your credentials.</p>
                    </header>

                    {{-- Error Notification --}}
                    @if ($errors->any())
                        <div class="mb-6 flex items-start gap-3 rounded-2xl bg-rose-50 px-5 py-4 ring-1 ring-rose-200">
                            <div class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-rose-100">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-3 w-3 text-rose-600">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-rose-700">Invalid credentials</p>
                                <p class="text-[11px] font-medium text-rose-500 mt-0.5">The Bio ID or password you entered is incorrect. Please try again.</p>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf
                        
                        <div class="space-y-1.5">
                            <label for="bio_id" class="block text-[11px] font-bold text-slate-400 uppercase tracking-[0.1em] ml-1">Bio ID / Username</label>
                            <input type="text" name="bio_id" id="bio_id" required autofocus
                                class="medical-input block w-full rounded-2xl bg-white/50 py-4 px-6 text-sm font-semibold text-slate-900 placeholder:text-slate-300 focus:outline-none shadow-sm"
                                placeholder="e.g. 0123">
                        </div>

                        <div class="space-y-1.5">
                            <div class="flex items-center justify-between px-1">
                                <label for="password" class="block text-[11px] font-bold text-slate-400 uppercase tracking-[0.1em]">Password</label>
                            </div>
                            <input type="password" name="password" id="password" required
                                class="medical-input block w-full rounded-2xl bg-white/50 py-4 px-6 text-sm font-semibold text-slate-900 placeholder:text-slate-300 focus:outline-none shadow-sm"
                                placeholder="••••••••">
                        </div>

                        <div class="flex items-center justify-between py-2">
                            <label class="flex items-center cursor-pointer group">
                                <input type="checkbox" name="remember" class="w-5 h-5 border-slate-200 text-emerald-600 rounded-lg focus:ring-emerald-500/20 transition-all">
                                <span class="ms-3 text-xs font-bold text-slate-500 group-hover:text-slate-800 transition-colors">Stay logged in</span>
                            </label>
                        </div>

                        <button type="submit"
                            class="relative w-full overflow-hidden rounded-2xl bg-slate-950 py-4.5 text-xs font-black text-white uppercase tracking-[0.2em] shadow-xl shadow-slate-950/10 transition-all hover:bg-emerald-600 hover:shadow-emerald-500/30 active:scale-[0.97]">
LOGIN                        </button>
                    </form>
                </div>

                <footer class="mt-10 flex flex-col items-center gap-4">
                    <div class="px-5 py-2.5 rounded-full bg-slate-100/50 border border-slate-200 backdrop-blur-sm">
                        <span class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">
                            IP: {{ Request::ip() }}
                        </span>
                    </div>
                    <p class="text-[10px] text-slate-400 font-medium">© {{ date('Y') }} BGHMC </p>
                </footer>
            </div>
        </div>
    </div>
</body>
</html>