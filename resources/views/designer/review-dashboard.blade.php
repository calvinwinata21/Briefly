<x-layouts.app title="Review Dashboard (HITL) : Lead Designer">
    <div class="space-y-6">
        <!-- Dashboard Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-3 border-b border-slate-800 dark:border-slate-800 border-slate-200">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-white dark:text-white flex items-center gap-2">
                    <span>Review Dashboard (HITL)</span>
                    <span class="text-xs px-2 py-0.5 rounded-md bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 font-semibold">Lead Designer</span>
                </h1>
                <p class="text-xs text-slate-400 mt-1">
                    Tinjau, sunting, setujui (Approve), atau tolak (Reject) draf konten media sebelum publikasi.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('requester.create') }}" class="px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-xs font-medium text-slate-200 border border-slate-700 transition-colors">
                    + Input Uji Coba Brief
                </a>
            </div>
        </div>

        <!-- Metric KPI Cards (Solid Surface, Clean Elevation) -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="surface-card rounded-xl p-4">
                <div class="text-[11px] uppercase tracking-wider text-slate-400 font-semibold">Total Draf</div>
                <div class="text-2xl font-bold text-white mt-1">{{ $stats['total'] }}</div>
                <div class="text-[10px] text-slate-500 mt-0.5">Seluruh luaran sistem</div>
            </div>

            <div class="surface-card rounded-xl p-4 border-amber-500/30">
                <div class="text-[11px] uppercase tracking-wider text-amber-300 font-semibold flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                    Menunggu Review
                </div>
                <div class="text-2xl font-bold text-amber-400 mt-1">{{ $stats['pending'] }}</div>
                <div class="text-[10px] text-slate-400 mt-0.5">Memerlukan verifikasi desainer</div>
            </div>

            <div class="surface-card rounded-xl p-4 border-emerald-500/30">
                <div class="text-[11px] uppercase tracking-wider text-emerald-300 font-semibold flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    Disetujui
                </div>
                <div class="text-2xl font-bold text-emerald-400 mt-1">{{ $stats['approved'] }}</div>
                <div class="text-[10px] text-slate-400 mt-0.5">Siap publikasi atau ekspor</div>
            </div>

            <div class="surface-card rounded-xl p-4 border-rose-500/30">
                <div class="text-[11px] uppercase tracking-wider text-rose-300 font-semibold flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-rose-400"></span>
                    Ditolak
                </div>
                <div class="text-2xl font-bold text-rose-400 mt-1">{{ $stats['rejected'] }}</div>
                <div class="text-[10px] text-slate-400 mt-0.5">Perlu perbaikan brief</div>
            </div>
        </div>

        <!-- Status Filter Tabs -->
        <div class="flex items-center gap-1.5 border-b border-slate-800 pb-2 overflow-x-auto">
            <a href="{{ route('designer.dashboard', ['status' => 'all']) }}"
                class="filter-tab-btn px-3 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition-colors {{ $statusFilter === 'all' ? 'filter-tab-active-all' : 'filter-tab-idle' }}">
                Semua Draf ({{ $stats['total'] }})
            </a>
            <a href="{{ route('designer.dashboard', ['status' => 'PENDING_REVIEW']) }}"
                class="filter-tab-btn px-3 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition-colors {{ $statusFilter === 'PENDING_REVIEW' ? 'filter-tab-active-pending' : 'filter-tab-idle' }}">
                Menunggu Review ({{ $stats['pending'] }})
            </a>
            <a href="{{ route('designer.dashboard', ['status' => 'APPROVED']) }}"
                class="filter-tab-btn px-3 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition-colors {{ $statusFilter === 'APPROVED' ? 'filter-tab-active-approved' : 'filter-tab-idle' }}">
                Disetujui ({{ $stats['approved'] }})
            </a>
            <a href="{{ route('designer.dashboard', ['status' => 'REJECTED']) }}"
                class="filter-tab-btn px-3 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition-colors {{ $statusFilter === 'REJECTED' ? 'filter-tab-active-rejected' : 'filter-tab-idle' }}">
                Ditolak ({{ $stats['rejected'] }})
            </a>
        </div>

        <!-- Drafts Grid -->
        @if($drafts->isEmpty())
            <div class="surface-card rounded-xl p-12 text-center">
                <svg class="w-10 h-10 text-slate-600 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <h3 class="text-base font-semibold text-white">Tidak Ada Draf Ditemukan</h3>
                <p class="text-xs text-slate-400 mt-1">Tidak ada draf media dengan filter status ini.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
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

                    <div class="surface-card-interactive rounded-xl p-4 sm:p-5 flex flex-col justify-between relative group">
                        <!-- Card Top Info -->
                        <div>
                            <div class="flex items-center justify-between gap-2 mb-3">
                                <div class="flex items-center gap-1.5">
                                    <span class="text-[10px] font-mono font-semibold px-2 py-0.5 rounded bg-slate-800 text-slate-300 border border-slate-700">
                                        {{ $jobId }}
                                    </span>
                                    @if($draft->brief->mediaDrafts()->count() > 1)
                                        <span class="text-[10px] px-1.5 py-0.5 rounded bg-indigo-500/15 text-indigo-300 border border-indigo-500/25 font-semibold" title="Pengajuan revisi dari pemohon">
                                            v{{ $draft->brief->mediaDrafts()->count() }}
                                        </span>
                                    @endif
                                </div>

                                <div class="flex items-center gap-1.5">
                                    @if($draft->brief->tone)
                                        @php
                                            $tBadge = match($draft->brief->tone) {
                                                'formal_academic' => ['Formal', 'text-amber-300 border-amber-500/30'],
                                                'persuasive_urgent' => ['Urgent', 'text-rose-300 border-rose-500/30'],
                                                default => ['Kasual', 'text-indigo-300 border-indigo-500/30'],
                                            };
                                        @endphp
                                        <span class="text-[10px] px-1.5 py-0.5 rounded border bg-slate-800/80 font-medium {{ $tBadge[1] }}" title="Tone: {{ $draft->brief->tone }}">
                                            {{ $tBadge[0] }}
                                        </span>
                                    @endif

                                    @if($draft->approval_status === 'APPROVED')
                                        <span class="inline-flex items-center gap-1 text-[10px] font-semibold px-2 py-0.5 rounded-md bg-emerald-500/15 text-emerald-300 border border-emerald-500/30">
                                            Disetujui
                                        </span>
                                    @elseif($draft->approval_status === 'REJECTED')
                                        <span class="inline-flex items-center gap-1 text-[10px] font-semibold px-2 py-0.5 rounded-md bg-rose-500/15 text-rose-300 border border-rose-500/30">
                                            Ditolak
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-[10px] font-semibold px-2 py-0.5 rounded-md bg-amber-500/15 text-amber-300 border border-amber-500/30">
                                            Perlu Review
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <h3 class="text-base font-bold text-white group-hover:text-blue-400 transition-colors line-clamp-2">
                                {{ $eventName }}
                            </h3>

                            <div class="text-xs text-slate-400 mt-1.5 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>{{ $eventDate }}</span>
                            </div>

                            <!-- Branding Color Swatches Applied -->
                            <div class="mt-4 pt-3 border-t border-slate-800 flex items-center justify-between text-xs text-slate-400">
                                <div class="flex items-center gap-2">
                                    <span class="text-[11px]">Warna:</span>
                                    <span class="w-3 h-3 rounded-full border border-white/20 inline-block shadow-2xs" style="background-color: {{ $primaryHex }};" title="Primary: {{ $primaryHex }}"></span>
                                    <span class="w-3 h-3 rounded-full border border-white/20 inline-block shadow-2xs" style="background-color: {{ $secondaryHex }};" title="Secondary: {{ $secondaryHex }}"></span>
                                </div>
                                <div>
                                    <span class="text-[11px] font-semibold text-slate-300">{{ $slideCount }} Slide</span>
                                </div>
                            </div>
                        </div>

                        <!-- Card Footer Action -->
                        <div class="mt-4 pt-3 border-t border-slate-800 flex items-center justify-between">
                            <span class="text-[10px] text-slate-500">
                                {{ $draft->created_at->diffForHumans() }}
                            </span>

                            <a href="{{ route('designer.detail', $draft) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-[#003366] hover:bg-[#002244] text-xs font-semibold text-white transition-colors shadow-xs">
                                <span>Buka Review</span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($drafts->hasPages())
                <div class="pt-4 border-t border-slate-800">
                    {{ $drafts->links() }}
                </div>
            @endif
        @endif
    </div>
</x-layouts.app>
