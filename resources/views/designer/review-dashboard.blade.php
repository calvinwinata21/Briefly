<x-layouts.app title="Review Dashboard (HITL) — Lead Designer">
    <div class="space-y-6">
        <!-- Dashboard Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-2 border-b border-white/10">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-white flex items-center gap-2">
                    <span>Review Dashboard (HITL)</span>
                    <span class="text-xs px-2.5 py-0.5 rounded-full bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 font-semibold">Lead Designer Specialist</span>
                </h1>
                <p class="text-xs text-slate-400 mt-1">
                    Tinjau, sunting, setujui (Approve), atau tolak (Reject) draf konten media hasil olahan AI Agent sebelum diekspor.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('requester.create') }}" class="px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-xs font-medium text-slate-200 border border-slate-700 transition-colors">
                    + Input Uji Coba Brief
                </a>
            </div>
        </div>

        <!-- Metric KPI Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="glass-panel rounded-2xl p-4">
                <div class="text-[11px] uppercase tracking-wider text-slate-400 font-semibold">Total Draf AI</div>
                <div class="text-2xl font-bold text-white mt-1">{{ $stats['total'] }}</div>
                <div class="text-[10px] text-slate-500 mt-0.5">Seluruh luaran pipeline</div>
            </div>

            <div class="glass-panel rounded-2xl p-4 border-amber-500/20">
                <div class="text-[11px] uppercase tracking-wider text-amber-300 font-semibold flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                    Menunggu Review
                </div>
                <div class="text-2xl font-bold text-amber-400 mt-1">{{ $stats['pending'] }}</div>
                <div class="text-[10px] text-slate-400 mt-0.5">Memerlukan verifikasi HITL</div>
            </div>

            <div class="glass-panel rounded-2xl p-4 border-emerald-500/20">
                <div class="text-[11px] uppercase tracking-wider text-emerald-300 font-semibold">Disetujui (Approved)</div>
                <div class="text-2xl font-bold text-emerald-400 mt-1">{{ $stats['approved'] }}</div>
                <div class="text-[10px] text-slate-400 mt-0.5">Siap publikasi / ekspor</div>
            </div>

            <div class="glass-panel rounded-2xl p-4 border-rose-500/20">
                <div class="text-[11px] uppercase tracking-wider text-rose-300 font-semibold">Ditolak (Rejected)</div>
                <div class="text-2xl font-bold text-rose-400 mt-1">{{ $stats['rejected'] }}</div>
                <div class="text-[10px] text-slate-400 mt-0.5">Perlu perbaikan brief</div>
            </div>
        </div>

        <!-- Status Filter Tabs -->
        <div class="flex items-center gap-2 border-b border-white/5 pb-2">
            <a href="{{ route('designer.dashboard', ['status' => 'all']) }}"
                class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all {{ $statusFilter === 'all' ? 'bg-white/10 text-white shadow-sm' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                Semua Draf ({{ $stats['total'] }})
            </a>
            <a href="{{ route('designer.dashboard', ['status' => 'PENDING_REVIEW']) }}"
                class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all {{ $statusFilter === 'PENDING_REVIEW' ? 'bg-amber-500/20 text-amber-300 border border-amber-500/30' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                Menunggu Review ({{ $stats['pending'] }})
            </a>
            <a href="{{ route('designer.dashboard', ['status' => 'APPROVED']) }}"
                class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all {{ $statusFilter === 'APPROVED' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                Disetujui ({{ $stats['approved'] }})
            </a>
            <a href="{{ route('designer.dashboard', ['status' => 'REJECTED']) }}"
                class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all {{ $statusFilter === 'REJECTED' ? 'bg-rose-500/20 text-rose-300 border border-rose-500/30' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                Ditolak ({{ $stats['rejected'] }})
            </a>
        </div>

        <!-- Drafts Grid -->
        @if($drafts->isEmpty())
            <div class="glass-panel rounded-2xl p-12 text-center">
                <svg class="w-12 h-12 text-slate-600 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <h3 class="text-base font-semibold text-white">Tidak Ada Draf Ditemukan</h3>
                <p class="text-xs text-slate-400 mt-1">Tidak ada draf media dengan filter status ini.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($drafts as $draft)
                    @php
                        $json = $draft->ai_output_json;
                        $jobId = $json['job_id'] ?? 'JOB-' . $draft->id;
                        $eventName = $json['event_metadata']['event_name'] ?? $draft->brief->event_title;
                        $eventDate = $json['event_metadata']['event_date'] ?? 'N/A';
                        $primaryHex = $json['branding_rules_applied']['primary_color_hex'] ?? '#003366';
                        $secondaryHex = $json['branding_rules_applied']['secondary_color_hex'] ?? '#FFCC00';
                        $slideCount = count($json['visual_slides_layout'] ?? []);
                    @endphp

                    <div class="glass-panel-interactive rounded-2xl p-5 flex flex-col justify-between relative group">
                        <!-- Card Top Info -->
                        <div>
                            <div class="flex items-center justify-between gap-2 mb-3">
                                <span class="text-[10px] font-mono font-semibold px-2 py-0.5 rounded bg-slate-800 text-slate-300 border border-slate-700">
                                    {{ $jobId }}
                                </span>

                                @if($draft->approval_status === 'APPROVED')
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                        ● Disetujui
                                    </span>
                                @elseif($draft->approval_status === 'REJECTED')
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-full bg-rose-500/20 text-rose-300 border border-rose-500/30">
                                        ● Ditolak
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-full bg-amber-500/20 text-amber-300 border border-amber-500/30">
                                        ● Perlu Review
                                    </span>
                                @endif
                            </div>

                            <h3 class="text-base font-bold text-white group-hover:text-indigo-300 transition-colors line-clamp-2">
                                {{ $eventName }}
                            </h3>

                            <div class="text-xs text-slate-400 mt-1 flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>{{ $eventDate }}</span>
                            </div>

                            <!-- Branding Color Swatches Applied -->
                            <div class="mt-4 pt-3 border-t border-white/5 flex items-center justify-between text-xs text-slate-400">
                                <div class="flex items-center gap-2">
                                    <span class="text-[11px]">Palet Warna:</span>
                                    <span class="w-3.5 h-3.5 rounded-full border border-white/20 inline-block shadow-sm" style="background-color: {{ $primaryHex }};" title="Primary: {{ $primaryHex }}"></span>
                                    <span class="w-3.5 h-3.5 rounded-full border border-white/20 inline-block shadow-sm" style="background-color: {{ $secondaryHex }};" title="Secondary: {{ $secondaryHex }}"></span>
                                </div>
                                <div>
                                    <span class="text-[11px] font-semibold text-slate-300">{{ $slideCount }} Slide Korsel</span>
                                </div>
                            </div>
                        </div>

                        <!-- Card Footer Action -->
                        <div class="mt-5 pt-3 border-t border-white/10 flex items-center justify-between">
                            <span class="text-[10px] text-slate-500">
                                {{ $draft->created_at->diffForHumans() }}
                            </span>

                            <a href="{{ route('designer.detail', $draft) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-indigo-600/30 hover:bg-indigo-600 border border-indigo-500/40 text-xs font-semibold text-white transition-all">
                                <span>Buka Review HITL</span>
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($drafts->hasPages())
                <div class="pt-4 border-t border-white/5">
                    {{ $drafts->links() }}
                </div>
            @endif
        @endif
    </div>
</x-layouts.app>
