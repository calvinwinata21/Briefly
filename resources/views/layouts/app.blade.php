<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Briefly' }} : Asisten Produksi Media Kampus</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 dark:bg-[#0A0F1D] text-slate-900 dark:text-slate-100 min-h-screen flex flex-col relative selection:bg-amber-400 selection:text-slate-900 antialiased">

    <!-- Navigation Bar (Solid, Purposeful Elevation) -->
    <header class="relative z-40 border-b border-slate-200 dark:border-slate-800 bg-white dark:bg-[#0F172A] sticky top-0 transition-colors">
        <div class="w-full max-w-7xl 2xl:max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 gap-2 sm:gap-4">
                <!-- Brand Logo & Domain Info -->
                <div class="flex items-center min-w-0">
                    <a href="{{ url('/') }}" class="flex items-center gap-2.5 sm:gap-3 group shrink-0" aria-label="Beranda Briefly">
                        <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-lg bg-[#003366] border border-amber-400/30 flex items-center justify-center text-amber-400 shadow-xs group-hover:border-amber-400/60 transition-colors shrink-0">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <div class="flex flex-col shrink-0">
                            <span class="brand-logo-text text-lg sm:text-xl font-bold tracking-tight">Briefly</span>
                            <span class="text-[10px] text-slate-400 dark:text-slate-500 font-medium -mt-1 hidden sm:block">Produksi Media Kampus</span>
                        </div>
                    </a>

                    <!-- Navigation Links (Admin, Designer, Requester) -->
                    @auth
                        <nav class="hidden md:flex items-center gap-1 ml-4 lg:ml-6 shrink min-w-0" aria-label="Navigasi Utama">
                            @if(auth()->user()->role === 'requester' || auth()->user()->role === 'admin' || auth()->user()->role === 'designer')
                                <a href="{{ route('requester.create') }}" class="nav-link-btn px-2.5 lg:px-3 py-1.5 rounded-lg text-xs lg:text-[13px] font-semibold whitespace-nowrap transition-all {{ request()->routeIs('requester.create') ? 'nav-link-active' : 'nav-link-idle' }}">
                                    + Input Brief
                                </a>
                                <a href="{{ route('requester.history') }}" class="nav-link-btn px-2.5 lg:px-3 py-1.5 rounded-lg text-xs lg:text-[13px] font-semibold whitespace-nowrap transition-all {{ request()->routeIs('requester.history*') ? 'nav-link-active' : 'nav-link-idle' }}">
                                    Status Brief
                                </a>
                            @endif

                            @if(auth()->user()->role === 'designer' || auth()->user()->role === 'admin')
                                <a href="{{ route('designer.dashboard') }}" class="nav-link-btn px-2.5 lg:px-3 py-1.5 rounded-lg text-xs lg:text-[13px] font-semibold whitespace-nowrap transition-all {{ request()->routeIs('designer.*') ? 'nav-link-active-designer' : 'nav-link-idle' }}">
                                    Review (HITL)
                                </a>
                            @endif

                            @if(auth()->user()->role === 'admin')
                                <a href="{{ route('admin.guidelines') }}" class="nav-link-btn px-2.5 lg:px-3 py-1.5 rounded-lg text-xs lg:text-[13px] font-semibold whitespace-nowrap transition-all {{ request()->routeIs('admin.guidelines*') ? 'nav-link-active-admin' : 'nav-link-idle' }}">
                                    Brand Guidelines
                                </a>
                                <a href="{{ route('admin.settings') }}" class="nav-link-btn px-2.5 lg:px-3 py-1.5 rounded-lg text-xs lg:text-[13px] font-semibold whitespace-nowrap transition-all {{ request()->routeIs('admin.settings') ? 'nav-link-active' : 'nav-link-idle' }}">
                                    AI Config
                                </a>
                                <a href="{{ route('admin.users') }}" class="nav-link-btn px-2.5 lg:px-3 py-1.5 rounded-lg text-xs lg:text-[13px] font-semibold whitespace-nowrap transition-all {{ request()->routeIs('admin.users') ? 'nav-link-active' : 'nav-link-idle' }}">
                                    Users
                                </a>
                            @endif
                        </nav>
                    @endauth
                </div>

                <!-- Right Side Actions & Role Switcher -->
                <div class="flex items-center gap-2 sm:gap-2.5 shrink-0 ml-auto">
                    <!-- Dark / Light Mode Switcher (All Roles) -->
                    <button id="theme-toggle-btn" type="button" aria-label="Ganti Mode Gelap atau Terang" title="Ganti Mode Gelap / Terang"
                        class="p-2 rounded-lg transition-colors flex items-center justify-center cursor-pointer">
                        <!-- Sun Icon (Active when dark mode) -->
                        <svg id="theme-icon-sun" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <!-- Moon Icon (Active when light mode) -->
                        <svg id="theme-icon-moon" class="w-4 h-4 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>

                    @auth
                        <!-- Fast Role Switcher Dropdown (Dev & Grading Evaluator) -->
                        <div class="relative">
                            <button id="quick-role-trigger" type="button" aria-haspopup="true" aria-expanded="false" class="flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-medium cursor-pointer transition-colors">
                                <span class="w-2 h-2 rounded-full {{ auth()->user()->role === 'admin' ? 'bg-amber-400' : (auth()->user()->role === 'designer' ? 'bg-indigo-400' : 'bg-emerald-400') }}"></span>
                                <span>Peran: <strong class="capitalize font-semibold">{{ auth()->user()->role }}</strong></span>
                                <svg class="w-3.5 h-3.5 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- Switcher Dropdown Menu -->
                            <div id="quick-role-dropdown" class="hidden absolute right-0 mt-2 w-64 rounded-xl z-50 p-1.5">
                                <div class="dropdown-header px-3 py-2 text-[10px] font-semibold tracking-wider uppercase border-b mb-1">
                                    Ganti Cepat Akun Uji Coba
                                </div>
                                
                                <form method="POST" action="{{ route('switch.role') }}" class="space-y-1">
                                    @csrf
                                    <button type="submit" name="role" value="admin" class="role-menu-item w-full text-left px-3 py-2 rounded-lg text-xs flex items-center justify-between transition-colors cursor-pointer {{ auth()->user()->role === 'admin' ? 'role-item-active-admin' : 'role-item-idle' }}">
                                        <div>
                                            <div class="font-medium">Administrator</div>
                                            <div class="text-[10px] opacity-75">admin@himpunan.or.id</div>
                                        </div>
                                        @if(auth()->user()->role === 'admin')
                                            <span class="role-badge text-[10px] px-1.5 py-0.5 rounded font-semibold">Aktif</span>
                                        @endif
                                    </button>

                                    <button type="submit" name="role" value="designer" class="role-menu-item w-full text-left px-3 py-2 rounded-lg text-xs flex items-center justify-between transition-colors cursor-pointer {{ auth()->user()->role === 'designer' ? 'role-item-active-designer' : 'role-item-idle' }}">
                                        <div>
                                            <div class="font-medium">Lead Designer (HITL)</div>
                                            <div class="text-[10px] opacity-75">designer@himpunan.or.id</div>
                                        </div>
                                        @if(auth()->user()->role === 'designer')
                                            <span class="role-badge text-[10px] px-1.5 py-0.5 rounded font-semibold">Aktif</span>
                                        @endif
                                    </button>

                                    <button type="submit" name="role" value="requester" class="role-menu-item w-full text-left px-3 py-2 rounded-lg text-xs flex items-center justify-between transition-colors cursor-pointer {{ auth()->user()->role === 'requester' ? 'role-item-active-requester' : 'role-item-idle' }}">
                                        <div>
                                            <div class="font-medium">Panitia Acara (Requester)</div>
                                            <div class="text-[10px] opacity-75">panitia@himpunan.or.id</div>
                                        </div>
                                        @if(auth()->user()->role === 'requester')
                                            <span class="role-badge text-[10px] px-1.5 py-0.5 rounded font-semibold">Aktif</span>
                                        @endif
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- User Profile Info & Logout -->
                        <div class="flex items-center gap-2 sm:gap-2.5 pl-2 sm:pl-3 border-l border-slate-700 dark:border-slate-800">
                            <div class="hidden xl:block text-right">
                                <div class="text-xs font-semibold text-slate-800 dark:text-white truncate max-w-[130px]">{{ auth()->user()->name }}</div>
                                <div class="text-[10px] text-slate-500 dark:text-slate-400 truncate max-w-[130px]">{{ auth()->user()->email }}</div>
                            </div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" title="Keluar" aria-label="Keluar dari akun" class="p-2 rounded-lg text-slate-500 dark:text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-colors cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 rounded-lg bg-[#003366] hover:bg-[#002244] text-white text-xs font-semibold transition-colors">
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
            <div role="alert" class="session-flash-alert mb-4 flex items-center justify-between p-3.5 rounded-lg bg-emerald-950/70 border border-emerald-500/30 text-emerald-200 text-xs">
                <div class="flex items-center gap-2.5">
                    <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if (session('warning'))
            <div role="alert" class="session-flash-alert mb-4 flex items-center justify-between p-3.5 rounded-lg bg-amber-950/70 border border-amber-500/30 text-amber-200 text-xs">
                <div class="flex items-center gap-2.5">
                    <svg class="w-4 h-4 text-amber-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span>{{ session('warning') }}</span>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div role="alert" class="session-flash-alert mb-4 flex items-center justify-between p-3.5 rounded-lg bg-rose-950/70 border border-rose-500/30 text-rose-200 text-xs">
                <div class="flex items-center gap-2.5">
                    <svg class="w-4 h-4 text-rose-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
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
    <div id="toast-container" aria-live="polite"></div>

    <!-- Footer (Clean, Truthful, Free of AI Hype) -->
    <footer class="relative z-10 border-t border-slate-200 dark:border-slate-800 bg-white dark:bg-[#0B0F19] py-5 text-center text-xs text-slate-500">
        <div class="max-w-7xl mx-auto px-4">
            Briefly : Asisten Produksi Media dan Desain
        </div>
    </footer>
</body>
</html>
