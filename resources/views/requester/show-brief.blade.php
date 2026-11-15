<x-layouts.app title="Detail Brief : {{ $brief->event_title }}">
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
                    <a href="{{ route('designer.detail', $brief->latestDraft) }}" class="px-3.5 py-1.5 rounded-lg bg-indigo-600/30 hover:bg-indigo-600/50 border border-indigo-500/40 text-xs font-semibold text-indigo-200 transition-colors">
                        Buka di Review Dashboard (HITL)
                    </a>
                @endif
            @endif
        </div>

        <!-- Header Card -->
        <div class="surface-card rounded-xl p-6 space-y-4">
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
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                            AI Processing Selesai
                        </span>
                    @elseif($brief->status === 'incomplete_data')
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold bg-rose-500/10 text-rose-400 border border-rose-500/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span>
                            Data Tidak Lengkap
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                            Sedang Diproses
                        </span>
                    @endif
                </div>
            </div>

            <!-- Feedback Alert if rejected or incomplete -->
            @php $draft = $brief->latestDraft; @endphp
            @if(($brief->status === 'incomplete_data') || ($draft && $draft->approval_status === 'REJECTED'))
                <div class="p-5 rounded-xl bg-rose-950/40 border border-rose-500/30 text-rose-200 text-xs space-y-3">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="space-y-1">
                            <div class="font-bold flex items-center gap-2 text-rose-300">
                                <svg class="w-4 h-4 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <span>Catatan Revisi dari Desainer / Sistem:</span>
                            </div>
                            <p class="pl-6 text-slate-300 font-mono leading-relaxed">
                                {{ $draft->designer_feedback ?? 'Data kegiatan tidak lengkap untuk menghasilkan konten yang memenuhi Brand SOP.' }}
                            </p>
                        </div>

                        <a href="{{ route('requester.revise', $brief) }}" class="shrink-0 px-4 py-2.5 rounded-lg bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs flex items-center gap-2 transition-all">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            <span>Perbaiki & Ajukan Revisi</span>
                        </a>
                    </div>
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

                @php $caption = $draft->ai_output_json['social_media_caption'] ?? []; @endphp
                @if(!empty($caption))
                    <!-- Multi-Channel Approved Distribution Packets -->
                    <div class="surface-card rounded-xl p-6 space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-white/10">
                            <div>
                                <h3 class="text-xs font-bold text-white uppercase tracking-wider flex items-center gap-2">
                                    <span class="text-emerald-400">✓</span> Paket Publikasi Siap Sebar (Multi-Channel)
                                </h3>
                                <p class="text-[11px] text-slate-400 mt-0.5">Salin teks resmi yang sudah disetujui untuk disebarkan ke kanal sosial media panitia.</p>
                            </div>
                        </div>

                        <!-- Channel Selector Tabs -->
                        <div class="space-y-3">
                            @if(!empty($caption['whatsapp_broadcast']))
                                <div class="p-4 rounded-xl bg-slate-900/90 border border-emerald-500/30 space-y-2">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-bold text-emerald-400 flex items-center gap-1.5">
                                            Format Broadcast WhatsApp Group
                                        </span>
                                        <button type="button" onclick="navigator.clipboard.writeText(document.getElementById('requester-wa-text').innerText); this.innerText = '✓ Tersalin!'; setTimeout(() => this.innerText = 'Salin Teks WA', 2000)"
                                            class="px-3 py-1 rounded-lg bg-emerald-600/30 hover:bg-emerald-600/50 text-emerald-200 border border-emerald-500/40 text-[11px] font-semibold transition-all">
                                            Salin Teks WA
                                        </button>
                                    </div>
                                    <div id="requester-wa-text" class="p-3 rounded-lg bg-black/40 text-slate-200 text-xs font-mono whitespace-pre-wrap leading-relaxed">{{ $caption['whatsapp_broadcast'] }}</div>
                                </div>
                            @endif

                            @if(!empty($caption['hook_sentence']))
                                <div class="p-4 rounded-xl bg-slate-900/90 border border-indigo-500/30 space-y-2">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-bold text-indigo-400 flex items-center gap-1.5">
                                            Caption Instagram Feed & Korsel
                                        </span>
                                        <button type="button" onclick="navigator.clipboard.writeText(document.getElementById('requester-ig-text').innerText); this.innerText = '✓ Tersalin!'; setTimeout(() => this.innerText = 'Salin Caption IG', 2000)"
                                            class="px-3 py-1 rounded-lg bg-indigo-600/30 hover:bg-indigo-600/50 text-indigo-200 border border-indigo-500/40 text-[11px] font-semibold transition-all">
                                            Salin Caption IG
                                        </button>
                                    </div>
                                    <div id="requester-ig-text" class="p-3 rounded-lg bg-black/40 text-slate-200 text-xs whitespace-pre-wrap leading-relaxed">
<strong>{{ $caption['hook_sentence'] }}</strong>

{{ $caption['body_paragraph'] }}

CTA: {{ $caption['call_to_action'] }}

{{ implode(' ', $caption['hashtags'] ?? []) }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            @endif
        </div>

        <!-- Raw Brief Text -->
        <div class="surface-card rounded-xl p-6 space-y-3">
            <h3 class="text-xs font-semibold text-slate-300 uppercase tracking-wider">Teks Brief Mentah yang Dikirim</h3>
            <div class="p-4 rounded-xl bg-slate-900/80 border border-slate-800 text-slate-200 text-xs font-mono whitespace-pre-wrap leading-relaxed">
                {{ $brief->raw_brief_text }}
            </div>
        </div>
    </div>
</x-layouts.app>
