<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Briefly' }} — Agentic AI Media Production Assistant</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#070B14] text-slate-100 min-h-screen flex flex-col relative selection:bg-amber-400 selection:text-slate-900 antialiased">
    <!-- Ambient Lighting Backdrop -->
    <div class="ambient-glow"></div>

    <!-- Navigation Bar -->
    <header class="relative z-40 border-b border-white/10 bg-[#0A0F1D]/80 backdrop-blur-xl sticky top-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Brand Logo & Domain Info -->
                <div class="flex items-center gap-3">
                    <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                        <div class="brand-logo-box w-10 h-10 rounded-xl bg-gradient-to-tr from-[#003366] via-indigo-600 to-[#FFCC00] p-[2px] shadow-lg shadow-indigo-950/50 group-hover:scale-105 transition-transform duration-200">
                            <div class="brand-logo-inner w-full h-full bg-[#0E1626] rounded-[10px] flex items-center justify-center">
                                <svg class="w-5 h-5 text-amber-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <span class="brand-logo-text text-xl font-bold tracking-tight bg-gradient-to-r from-white via-slate-200 to-amber-300 bg-clip-text text-transparent">Briefly</span>
                            <span class="brand-badge-text text-[10px] uppercase tracking-widest px-2 py-0.5 ml-2 rounded-full bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 font-semibold">AI Assistant</span>
                        </div>
                    </a>

                    <!-- Navigation Links -->
                    @auth
                        <nav class="hidden md:flex items-center gap-1 ml-8">
                            @if(auth()->user()->role === 'requester' || auth()->user()->role === 'admin' || auth()->user()->role === 'designer')
                                <a href="{{ route('requester.create') }}" class="px-3 py-1.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('requester.create') ? 'bg-white/10 text-white' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                                    + Input Brief
                                </a>
                                <a href="{{ route('requester.history') }}" class="px-3 py-1.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('requester.history*') ? 'bg-white/10 text-white' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                                    Status Brief
                                </a>
                            @endif

                            @if(auth()->user()->role === 'designer' || auth()->user()->role === 'admin')
                                <a href="{{ route('designer.dashboard') }}" class="px-3 py-1.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('designer.*') ? 'bg-indigo-600/20 text-indigo-300 border border-indigo-500/30' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                                    Review Dashboard (HITL)
                                </a>
                            @endif

                            @if(auth()->user()->role === 'admin')
                                <a href="{{ route('admin.guidelines') }}" class="px-3 py-1.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('admin.guidelines*') ? 'bg-amber-500/20 text-amber-300 border border-amber-500/30' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                                    RAG & Brand Guidelines
                                </a>
                                <a href="{{ route('admin.settings') }}" class="px-3 py-1.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('admin.settings') ? 'bg-white/10 text-white' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                                    AI Config
                                </a>
                                <a href="{{ route('admin.users') }}" class="px-3 py-1.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('admin.users') ? 'bg-white/10 text-white' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                                    Users
                                </a>
                            @endif
                        </nav>
                    @endauth
                </div>

                <!-- Right Side Actions & Role Switcher -->
                <div class="flex items-center gap-3">
                    <!-- Dark / Light Mode Switcher (All Roles) -->
                    <button id="theme-toggle-btn" type="button" aria-label="Ganti Mode Gelap / Terang" title="Ganti Mode Gelap / Terang"
                        class="p-2 rounded-xl bg-slate-800/80 hover:bg-slate-700/80 border border-slate-700 text-amber-400 hover:text-amber-300 transition-all flex items-center justify-center cursor-pointer shadow-sm">
                        <!-- Sun Icon (Active when dark mode) -->
                        <svg id="theme-icon-sun" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <!-- Moon Icon (Active when light mode) -->
                        <svg id="theme-icon-moon" class="w-4 h-4 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>

                    @auth
                        <!-- Fast Role Switcher Dropdown (Dev & Grading Evaluator) -->
                        <div class="relative">
                            <button id="quick-role-trigger" type="button" class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-800/80 hover:bg-slate-700/80 border border-slate-700 text-xs font-medium text-slate-200 transition-all">
                                <span class="w-2 h-2 rounded-full {{ auth()->user()->role === 'admin' ? 'bg-amber-400 animate-pulse' : (auth()->user()->role === 'designer' ? 'bg-indigo-400' : 'bg-emerald-400') }}"></span>
                                <span>Peran: <strong class="capitalize text-white">{{ auth()->user()->role }}</strong></span>
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- Switcher Dropdown Menu -->
                            <div id="quick-role-dropdown" class="hidden absolute right-0 mt-2 w-64 rounded-xl bg-[#0F172A] border border-slate-700/80 shadow-2xl z-50 p-2">
                                <div class="px-3 py-2 text-[11px] font-semibold tracking-wider text-slate-400 uppercase border-b border-slate-800 mb-1">
                                    Ganti Cepat Akun Uji Coba
                                </div>
                                
                                <form method="POST" action="{{ route('switch.role') }}" class="space-y-1">
                                    @csrf
                                    <button type="submit" name="role" value="admin" class="w-full text-left px-3 py-2 rounded-lg text-xs flex items-center justify-between transition-colors {{ auth()->user()->role === 'admin' ? 'bg-amber-500/20 text-amber-300 font-semibold' : 'text-slate-300 hover:bg-slate-800' }}">
                                        <div>
                                            <div class="font-medium">Administrator</div>
                                            <div class="text-[10px] text-slate-400">admin@himpunan.or.id</div>
                                        </div>
                                        @if(auth()->user()->role === 'admin')
                                            <span class="text-[10px] bg-amber-400/20 text-amber-300 px-1.5 py-0.5 rounded">Aktif</span>
                                        @endif
                                    </button>

                                    <button type="submit" name="role" value="designer" class="w-full text-left px-3 py-2 rounded-lg text-xs flex items-center justify-between transition-colors {{ auth()->user()->role === 'designer' ? 'bg-indigo-500/20 text-indigo-300 font-semibold' : 'text-slate-300 hover:bg-slate-800' }}">
                                        <div>
                                            <div class="font-medium">Lead Designer (HITL)</div>
                                            <div class="text-[10px] text-slate-400">designer@himpunan.or.id</div>
                                        </div>
                                        @if(auth()->user()->role === 'designer')
                                            <span class="text-[10px] bg-indigo-400/20 text-indigo-300 px-1.5 py-0.5 rounded">Aktif</span>
                                        @endif
                                    </button>

                                    <button type="submit" name="role" value="requester" class="w-full text-left px-3 py-2 rounded-lg text-xs flex items-center justify-between transition-colors {{ auth()->user()->role === 'requester' ? 'bg-emerald-500/20 text-emerald-300 font-semibold' : 'text-slate-300 hover:bg-slate-800' }}">
                                        <div>
                                            <div class="font-medium">Panitia Acara (Requester)</div>
                                            <div class="text-[10px] text-slate-400">panitia@himpunan.or.id</div>
                                        </div>
                                        @if(auth()->user()->role === 'requester')
                                            <span class="text-[10px] bg-emerald-400/20 text-emerald-300 px-1.5 py-0.5 rounded">Aktif</span>
                                        @endif
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- User Profile Info & Logout -->
                        <div class="flex items-center gap-3 pl-3 border-l border-white/10">
                            <div class="hidden sm:block text-right">
                                <div class="text-xs font-semibold text-white">{{ auth()->user()->name }}</div>
                                <div class="text-[10px] text-slate-400">{{ auth()->user()->email }}</div>
                            </div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" title="Keluar" class="p-2 rounded-lg text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 transition-colors">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 rounded-xl bg-gradient-to-r from-indigo-600 to-[#003366] text-white text-xs font-semibold shadow-lg hover:shadow-indigo-500/25 hover:scale-105 transition-all">
                            Masuk
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Session Flash Alerts -->
    <div class="relative z-30 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 w-full">
        @if (session('success'))
            <div class="session-flash-alert mb-4 flex items-center justify-between p-4 rounded-xl bg-emerald-950/80 border border-emerald-500/40 text-emerald-200 text-sm shadow-xl backdrop-blur-md">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if (session('warning'))
            <div class="session-flash-alert mb-4 flex items-center justify-between p-4 rounded-xl bg-amber-950/80 border border-amber-500/40 text-amber-200 text-sm shadow-xl backdrop-blur-md">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-amber-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span>{{ session('warning') }}</span>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="session-flash-alert mb-4 flex items-center justify-between p-4 rounded-xl bg-rose-950/80 border border-rose-500/40 text-rose-200 text-sm shadow-xl backdrop-blur-md">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-rose-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif
    </div>

    <!-- Main Page Content -->
    <main class="relative z-10 flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 w-full">
        {{ $slot }}
    </main>

    <!-- Global Toast Container -->
    <div id="toast-container"></div>

    <!-- Footer -->
    <footer class="relative z-10 border-t border-white/5 bg-[#0A0F1D]/60 py-6 text-center text-xs text-slate-500">
        <div class="max-w-7xl mx-auto px-4">
            Briefly — Agentic AI Design & Media Production Assistant • Tech Stack: Laravel 13, RAG Vector Knowledge & Gemini LLM
        </div>
    </footer>
</body>
</html>
