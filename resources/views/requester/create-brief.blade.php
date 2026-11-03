<x-layouts.app title="Input Brief Acara — Briefly AI">
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Header Banner -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-2 border-b border-white/10">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-white flex items-center gap-2">
                    <span>Input Brief Acara / Konten</span>
                    <span class="text-xs px-2.5 py-0.5 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 font-semibold">Step 1 — Requester</span>
                </h1>
                <p class="text-xs text-slate-400 mt-1">
                    Kirimkan teks mentah kegiatan kepanitiaan Anda. Agen AI akan mereferensikan Brand Guideline kampus dan membuat draft visual serta caption.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('requester.history') }}" class="px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-xs font-medium text-slate-200 border border-slate-700 transition-colors">
                    Lihat Status Brief Saya →
                </a>
            </div>
        </div>

        <!-- Quick Sample Brief Templates -->
        <div class="glass-panel rounded-2xl p-4">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-slate-300 uppercase tracking-wider flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    Muat Contoh Brief Cepat (Klik untuk Isi Otomatis)
                </span>
                <span class="text-[11px] text-slate-400">Pilih salah satu untuk pengujian instan</span>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                <button type="button" data-template="seminar" class="template-btn text-left p-3 rounded-xl bg-slate-900/60 hover:bg-indigo-950/40 border border-slate-800 hover:border-indigo-500/40 transition-all group">
                    <div class="text-xs font-semibold text-indigo-300 group-hover:text-indigo-200">🎓 Seminar Teknologi</div>
                    <div class="text-[11px] text-slate-400 mt-1 line-clamp-1">Seminar Future of Agentic AI</div>
                </button>
                <button type="button" data-template="lomba" class="template-btn text-left p-3 rounded-xl bg-slate-900/60 hover:bg-amber-950/40 border border-slate-800 hover:border-amber-500/40 transition-all group">
                    <div class="text-xs font-semibold text-amber-300 group-hover:text-amber-200">🏆 Hackathon & Lomba</div>
                    <div class="text-[11px] text-slate-400 mt-1 line-clamp-1">Campus Hackathon 2026</div>
                </button>
                <button type="button" data-template="oprec" class="template-btn text-left p-3 rounded-xl bg-slate-900/60 hover:bg-emerald-950/40 border border-slate-800 hover:border-emerald-500/40 transition-all group">
                    <div class="text-xs font-semibold text-emerald-300 group-hover:text-emerald-200">📢 Open Recruitment</div>
                    <div class="text-[11px] text-slate-400 mt-1 line-clamp-1">Relawan Tim Media & Kreatif</div>
                </button>
            </div>
        </div>

        <!-- Main Submission Form -->
        <form id="brief-form" method="POST" action="{{ route('requester.store') }}" class="glass-panel rounded-2xl p-6 sm:p-8 space-y-5">
            @csrf

            <!-- Event Title -->
            <div>
                <label for="event_title" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                    Judul Acara / Topik Konten <span class="text-rose-400">*</span>
                </label>
                <input type="text" id="event_title" name="event_title" value="{{ old('event_title') }}" required
                    class="w-full px-4 py-3 rounded-xl bg-slate-900/90 border border-slate-700/80 text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm transition-all"
                    placeholder="contoh: National Tech Seminar: Future of Agentic AI 2026">
                @error('event_title')
                    <p class="text-rose-400 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- Raw Brief Textarea -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label for="raw_brief_text" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider">
                        Instruksi & Brief Mentah Acara <span class="text-rose-400">*</span>
                    </label>
                    <span id="char-count" class="text-xs text-slate-500">0 karakter</span>
                </div>
                <p class="text-[11px] text-slate-400 mb-2">
                    Tuliskan bebas: tanggal kegiatan, deskripsi, pembicara, fasilitas, link pendaftaran, atau kontak panitia. AI akan merapikannya secara otomatis.
                </p>
                <textarea id="raw_brief_text" name="raw_brief_text" rows="10" required
                    class="w-full px-4 py-3 rounded-xl bg-slate-900/90 border border-slate-700/80 text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm leading-relaxed transition-all font-mono"
                    placeholder="Tempel atau ketik teks mentah acara di sini...">{{ old('raw_brief_text') }}</textarea>
                @error('raw_brief_text')
                    <p class="text-rose-400 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- Workflow Info Card -->
            <div class="p-4 rounded-xl bg-slate-900/50 border border-slate-800 flex items-start gap-3 text-xs text-slate-400">
                <svg class="w-5 h-5 text-indigo-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="space-y-1">
                    <div class="text-slate-200 font-medium">Alur Human-in-the-Loop (HITL):</div>
                    <div>Setelah Anda mengirim form ini, AI Orchestrator akan memproses brief, lalu draf akan masuk ke antrean verifikasi <strong>Lead Designer Specialist</strong> sebelum disetujui untuk dipublikasikan.</div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
                <button type="submit" id="submit-brief-btn" class="w-full sm:w-auto px-8 py-3.5 rounded-xl bg-gradient-to-r from-indigo-600 via-blue-600 to-[#003366] text-white text-sm font-semibold shadow-lg shadow-indigo-600/30 hover:shadow-indigo-600/50 hover:brightness-110 active:scale-[0.99] transition-all flex items-center justify-center gap-2">
                    <svg class="w-5 h-5 text-amber-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    <span>Kirim & Jalankan Agen AI</span>
                </button>
            </div>
        </form>
    </div>

    <!-- AI Processing Modal Overlay -->
    <div id="ai-loading-overlay" class="hidden fixed inset-0 z-50 bg-[#070B14]/90 backdrop-blur-md items-center justify-center p-4">
        <div class="glass-panel max-w-md w-full rounded-2xl p-6 text-center space-y-5 border border-indigo-500/30 shadow-2xl shadow-indigo-950/80">
            <div class="relative w-16 h-16 mx-auto">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-[#003366] via-indigo-600 to-[#FFCC00] animate-spin p-[2px]">
                    <div class="w-full h-full bg-[#0E1626] rounded-[14px]"></div>
                </div>
                <svg class="w-7 h-7 text-amber-400 absolute inset-0 m-auto" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>

            <div>
                <h3 class="text-lg font-bold text-white">Agen AI Sedang Bekerja...</h3>
                <p id="loading-status-text" class="text-xs text-indigo-300 mt-2 font-mono">
                    1/4 Menganalisis entitas & intensi dari brief...
                </p>
            </div>

            <div class="space-y-2 text-left bg-slate-900/70 p-3.5 rounded-xl border border-slate-800 text-[11px] text-slate-400 font-mono">
                <div class="flex items-center gap-2 text-indigo-300">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 animate-ping"></span>
                    <span>Intent & Entity Parser (Gemini API)</span>
                </div>
                <div class="flex items-center gap-2 text-amber-300">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                    <span>RAG Vector Grounding (Brand Guidelines & SOP)</span>
                </div>
                <div class="flex items-center gap-2 text-cyan-300">
                    <span class="w-1.5 h-1.5 rounded-full bg-cyan-400"></span>
                    <span>Content & Slide Generator + Schema Guardrails</span>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
