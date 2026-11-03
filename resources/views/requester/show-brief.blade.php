<x-layouts.app title="Detail Brief — {{ $brief->event_title }}">
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Top Breadcrumb & Actions -->
        <div class="flex items-center justify-between pb-2 border-b border-white/10">
            <a href="{{ route('requester.history') }}" class="inline-flex items-center gap-1.5 text-xs text-slate-400 hover:text-white transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                <span>Kembali ke Riwayat Brief</span>
            </a>

            @if(auth()->user()->role === 'designer' || auth()->user()->role === 'admin')
                @if($brief->latestDraft)
                    <a href="{{ route('designer.detail', $brief->latestDraft) }}" class="px-3.5 py-1.5 rounded-xl bg-indigo-600/30 hover:bg-indigo-600/50 border border-indigo-500/40 text-xs font-semibold text-indigo-200 transition-colors">
                        Buka di Review Dashboard (HITL) →
                    </a>
                @endif
            @endif
        </div>

        <!-- Header Card -->
        <div class="glass-panel rounded-2xl p-6 space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <span class="text-[11px] uppercase tracking-wider text-slate-400 font-semibold">ID Pengajuan #{{ $brief->id }}</span>
                    <h1 class="text-xl sm:text-2xl font-bold text-white mt-0.5">{{ $brief->event_title }}</h1>
                    <div class="text-xs text-slate-400 mt-1 flex items-center gap-3">
                        <span>Diajukan oleh: <strong class="text-slate-300">{{ $brief->user->name ?? 'Panitia' }}</strong></span>
                        <span>•</span>
                        <span>{{ $brief->created_at->format('d M Y, H:i WIB') }}</span>
                    </div>
                </div>

                <div>
                    @if($brief->status === 'completed')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                            AI Processing Selesai
                        </span>
                    @elseif($brief->status === 'incomplete_data')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-rose-500/20 text-rose-300 border border-rose-500/30">
                            <span class="w-2 h-2 rounded-full bg-rose-400"></span>
                            Data Tidak Lengkap
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-amber-500/20 text-amber-300 border border-amber-500/30">
                            <span class="w-2 h-2 rounded-full bg-amber-400 animate-ping"></span>
                            Sedang Diproses
                        </span>
                    @endif
                </div>
            </div>

            <!-- Feedback Alert if rejected or incomplete -->
            @php $draft = $brief->latestDraft; @endphp
            @if($draft && $draft->approval_status === 'REJECTED')
                <div class="p-4 rounded-xl bg-rose-950/40 border border-rose-500/30 text-rose-200 text-xs space-y-1">
                    <div class="font-semibold flex items-center gap-2">
                        <svg class="w-4 h-4 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span>Catatan Revisi dari Desainer / Sistem:</span>
                    </div>
                    <p class="pl-6 text-slate-300">{{ $draft->designer_feedback }}</p>
                </div>
            @endif

            @if($draft && $draft->approval_status === 'APPROVED')
                <div class="p-4 rounded-xl bg-emerald-950/40 border border-emerald-500/30 text-emerald-200 text-xs space-y-1">
                    <div class="font-semibold flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Draf Disetujui (Approved) oleh Lead Designer:</span>
                    </div>
                    <p class="pl-6 text-slate-300">{{ $draft->designer_feedback ?? 'Draf telah memenuhi seluruh kaidah Brand Guideline kampus.' }}</p>
                </div>
            @endif
        </div>

        <!-- Raw Brief Text -->
        <div class="glass-panel rounded-2xl p-6 space-y-3">
            <h3 class="text-xs font-semibold text-slate-300 uppercase tracking-wider">Teks Brief Mentah yang Dikirim</h3>
            <div class="p-4 rounded-xl bg-slate-900/80 border border-slate-800 text-slate-200 text-xs font-mono whitespace-pre-wrap leading-relaxed">
                {{ $brief->raw_brief_text }}
            </div>
        </div>
    </div>
</x-layouts.app>
