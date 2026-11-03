<x-layouts.app title="Masuk — Briefly AI">
    <div class="max-w-md mx-auto my-12">
        <!-- Brand Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-tr from-[#003366] via-indigo-600 to-[#FFCC00] p-[2px] shadow-2xl shadow-indigo-500/20 mb-4">
                <div class="w-full h-full bg-[#0E1626] rounded-[14px] flex items-center justify-center">
                    <svg class="w-7 h-7 text-amber-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-white">Masuk ke Briefly AI</h1>
            <p class="text-xs text-slate-400 mt-1">Platform Asisten Produksi Media & Desain Kampus Berbasis AI Agent</p>
        </div>

        <!-- Login Card -->
        <div class="glass-panel rounded-2xl p-6 sm:p-8 relative">
            <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">
                        Alamat Email
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full px-4 py-2.5 rounded-xl bg-slate-900/80 border border-slate-700/80 text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm transition-all"
                        placeholder="contoh: designer@himpunan.or.id">
                    @error('email')
                        <p class="text-rose-400 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider">
                            Kata Sandi
                        </label>
                        <span class="text-[11px] text-slate-500">Default: password123</span>
                    </div>
                    <input type="password" id="password" name="password" required
                        class="w-full px-4 py-2.5 rounded-xl bg-slate-900/80 border border-slate-700/80 text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm transition-all"
                        placeholder="••••••••">
                    @error('password')
                        <p class="text-rose-400 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded bg-slate-900 border-slate-700 text-indigo-600 focus:ring-0 w-4 h-4">
                        <span class="text-xs text-slate-400">Ingat sesi saya</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-indigo-600 via-blue-700 to-[#003366] text-white text-sm font-semibold shadow-lg shadow-indigo-600/30 hover:shadow-indigo-600/50 hover:brightness-110 active:scale-[0.99] transition-all">
                    Masuk ke Sistem
                </button>
            </form>

            <!-- One-Click Preset Credentials for Evaluation -->
            <div class="mt-8 pt-6 border-t border-white/10">
                <div class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider text-center mb-3">
                    Akses Cepat Akun Demo (Evaluasi Sistem)
                </div>
                <div class="grid grid-cols-1 gap-2">
                    <form method="POST" action="{{ route('switch.role') }}">
                        @csrf
                        <input type="hidden" name="role" value="admin">
                        <button type="submit" class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl bg-amber-500/10 hover:bg-amber-500/20 border border-amber-500/30 text-amber-200 text-xs transition-colors">
                            <span class="font-medium">👑 Head of Media Admin</span>
                            <span class="text-[10px] text-amber-400/80">admin@himpunan.or.id</span>
                        </button>
                    </form>

                    <form method="POST" action="{{ route('switch.role') }}">
                        @csrf
                        <input type="hidden" name="role" value="designer">
                        <button type="submit" class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl bg-indigo-500/10 hover:bg-indigo-500/20 border border-indigo-500/30 text-indigo-200 text-xs transition-colors">
                            <span class="font-medium">🎨 Lead Designer Specialist</span>
                            <span class="text-[10px] text-indigo-400/80">designer@himpunan.or.id</span>
                        </button>
                    </form>

                    <form method="POST" action="{{ route('switch.role') }}">
                        @csrf
                        <input type="hidden" name="role" value="requester">
                        <button type="submit" class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl bg-emerald-500/10 hover:bg-emerald-500/20 border border-emerald-500/30 text-emerald-200 text-xs transition-colors">
                            <span class="font-medium">📝 Panitia Acara (Requester)</span>
                            <span class="text-[10px] text-emerald-400/80">panitia@himpunan.or.id</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
