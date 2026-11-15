<x-layouts.guest title="Masuk : Briefly">
    <div class="w-full max-w-md">
        <!-- Brand Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-[#003366] border border-amber-400/30 text-amber-400 shadow-xs mb-3">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-white">Masuk ke Briefly</h1>
            <p class="text-xs text-slate-400 mt-1">Platform Produksi Media dan Desain Acara Kampus</p>
        </div>

        <!-- Login Card (Solid Surface, Clean Border) -->
        <div class="surface-card rounded-xl p-6 sm:p-7">
            <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">
                        Alamat Email
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full px-3.5 py-2.5 rounded-lg bg-[#0B0F19] border border-slate-700 text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 text-sm transition-colors"
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
                        class="w-full px-3.5 py-2.5 rounded-lg bg-[#0B0F19] border border-slate-700 text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 text-sm transition-colors"
                        placeholder="••••••••">
                    @error('password')
                        <p class="text-rose-400 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded bg-slate-900 border-slate-700 text-blue-600 focus:ring-0 w-4 h-4 cursor-pointer">
                        <span class="text-xs text-slate-400">Ingat sesi saya</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-2.5 px-4 rounded-lg bg-[#003366] hover:bg-[#002244] text-white text-sm font-semibold transition-colors cursor-pointer shadow-xs">
                    Masuk ke Sistem
                </button>
            </form>

            <!-- One-Click Preset Credentials for Evaluation -->
            <div class="mt-6 pt-5 border-t border-slate-800">
                <div class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider text-center mb-3">
                    Akses Cepat Akun Demo (Evaluasi Sistem)
                </div>
                <div class="grid grid-cols-1 gap-2">
                    <form method="POST" action="{{ route('switch.role') }}">
                        @csrf
                        <input type="hidden" name="role" value="admin">
                        <button type="submit" class="w-full flex items-center justify-between px-3.5 py-2 rounded-lg bg-amber-500/10 hover:bg-amber-500/15 border border-amber-500/25 text-amber-200 text-xs transition-colors cursor-pointer">
                            <span class="font-medium">Administrator</span>
                            <span class="text-[10px] text-amber-400/80">admin@himpunan.or.id</span>
                        </button>
                    </form>

                    <form method="POST" action="{{ route('switch.role') }}">
                        @csrf
                        <input type="hidden" name="role" value="designer">
                        <button type="submit" class="w-full flex items-center justify-between px-3.5 py-2 rounded-lg bg-indigo-500/10 hover:bg-indigo-500/15 border border-indigo-500/25 text-indigo-200 text-xs transition-colors cursor-pointer">
                            <span class="font-medium">Lead Designer (HITL)</span>
                            <span class="text-[10px] text-indigo-400/80">designer@himpunan.or.id</span>
                        </button>
                    </form>

                    <form method="POST" action="{{ route('switch.role') }}">
                        @csrf
                        <input type="hidden" name="role" value="requester">
                        <button type="submit" class="w-full flex items-center justify-between px-3.5 py-2 rounded-lg bg-emerald-500/10 hover:bg-emerald-500/15 border border-emerald-500/25 text-emerald-200 text-xs transition-colors cursor-pointer">
                            <span class="font-medium">Panitia Acara (Requester)</span>
                            <span class="text-[10px] text-emerald-400/80">panitia@himpunan.or.id</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.guest>
