<x-layouts.app title="Review Draf #{{ $draft->ai_output_json['job_id'] ?? $draft->id }} : HITL Workspace">
    @php
        $json = $draft->ai_output_json;
        $jobId = $json['job_id'] ?? 'JOB-' . $draft->id;
        $event = $json['event_metadata'] ?? [];
        $branding = $json['branding_rules_applied'] ?? [];
        $slides = $json['visual_slides_layout'] ?? [];
        $caption = $json['social_media_caption'] ?? [];
        $primaryColor = $branding['primary_color_hex'] ?? '#003366';
        $secondaryColor = $branding['secondary_color_hex'] ?? '#FFCC00';
    @endphp

    <div class="space-y-6">
        <!-- Top Navigation Bar & HITL Status -->
        <div class="pb-4 border-b border-white/10 space-y-3">
            <!-- Top Controls Row: Badges on Left, Actions on Right -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <!-- Badges & Navigation -->
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('designer.dashboard') }}" class="p-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 border border-slate-700/80 transition-colors" title="Kembali ke Dashboard Review">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>

                    <span class="text-xs font-mono font-semibold px-2.5 py-1 rounded-md bg-slate-800 text-slate-300 border border-slate-700" data-job-id="{{ $jobId }}">
                        {{ $jobId }}
                    </span>

                    @if($draft->approval_status === 'APPROVED')
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                            Disetujui (Approved)
                        </span>
                    @elseif($draft->approval_status === 'REJECTED')
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md text-xs font-semibold bg-rose-500/10 text-rose-400 border border-rose-500/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span>
                            Ditolak (Rejected)
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md text-xs font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                            Menunggu Review (HITL)
                        </span>
                    @endif

                    @if($draft->brief->mediaDrafts()->count() > 1)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md text-xs font-semibold bg-indigo-500/10 text-indigo-300 border border-indigo-500/20">
                            Revisi v{{ $draft->brief->mediaDrafts()->count() }}
                        </span>
                    @endif

                    @if($draft->brief->tone)
                        @php
                            $toneNames = [
                                'casual_energetic' => 'Kasual',
                                'formal_academic' => 'Formal',
                                'persuasive_urgent' => 'Urgent',
                            ];
                        @endphp
                        <span class="text-xs font-semibold px-2.5 py-0.5 rounded-md bg-slate-800 text-amber-300 border border-amber-500/20">
                            Tone: {{ $toneNames[$draft->brief->tone] ?? 'Standar' }}
                        </span>
                    @endif
                </div>

                <!-- Action Toolbar: Streamlined and Non-Wrapping -->
                <div class="flex items-center gap-2 shrink-0">
                    <!-- Toggle Edit Mode -->
                    <button type="button" id="toggle-edit-mode-btn" class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 text-xs font-semibold flex items-center gap-1.5 transition-colors">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <span>Sunting</span>
                    </button>

                    <!-- Export Dropdown Menu -->
                    <div class="relative" id="export-dropdown-container">
                        <button type="button" id="export-dropdown-btn" class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 text-xs font-semibold flex items-center gap-1.5 transition-colors">
                            <svg class="w-3.5 h-3.5 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            <span>Ekspor</span>
                            <svg class="w-3 h-3 text-slate-400 ml-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div id="export-dropdown-menu" class="hidden absolute right-0 mt-2 w-60 rounded-xl bg-slate-900 border border-slate-700 shadow-xl z-50 p-1.5 space-y-1">
                            <button type="button" id="download-current-slide-btn" class="w-full px-3 py-2 rounded-xl text-left text-xs font-medium text-slate-200 hover:bg-slate-800 flex items-center gap-2.5 transition-colors">
                                <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <div>
                                    <div class="font-semibold text-white">Unduh Slide Ini (PNG)</div>
                                    <div class="text-[10px] text-slate-400">Gambar resolusi tinggi 2x</div>
                                </div>
                            </button>

                            <button type="button" id="download-all-zip-btn" class="w-full px-3 py-2 rounded-xl text-left text-xs font-medium text-slate-200 hover:bg-slate-800 flex items-center gap-2.5 transition-colors">
                                <svg class="w-4 h-4 text-indigo-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                </svg>
                                <div>
                                    <div class="font-semibold text-white">Unduh Semua Slide (.ZIP)</div>
                                    <div class="text-[10px] text-slate-400">Arsip gambar semua slide</div>
                                </div>
                            </button>

                            <div class="border-t border-slate-800 my-1"></div>

                            <a href="{{ route('designer.export', [$draft, 'json']) }}" class="w-full px-3 py-2 rounded-xl text-left text-xs font-medium text-slate-200 hover:bg-slate-800 flex items-center gap-2.5 transition-colors">
                                <svg class="w-4 h-4 text-cyan-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                                </svg>
                                <div>
                                    <div class="font-semibold text-white">Ekspor Kontrak JSON</div>
                                    <div class="text-[10px] text-slate-400">Schema data terstruktur</div>
                                </div>
                            </a>

                            <a href="{{ route('designer.export', [$draft, 'markdown']) }}" class="w-full px-3 py-2 rounded-xl text-left text-xs font-medium text-slate-200 hover:bg-slate-800 flex items-center gap-2.5 transition-colors">
                                <svg class="w-4 h-4 text-amber-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <div>
                                    <div class="font-semibold text-white">Ekspor Paket Markdown</div>
                                    <div class="text-[10px] text-slate-400">Dokumentasi & caption</div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="h-4 w-px bg-slate-800 mx-0.5"></div>

                    <!-- Reject Button -->
                    <button type="button" id="open-reject-modal-btn" class="px-3 py-1.5 rounded-xl bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/30 text-rose-300 text-xs font-semibold flex items-center gap-1.5 transition-colors">
                        <svg class="w-3.5 h-3.5 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <span>Tolak</span>
                    </button>

                    <!-- Approve Button -->
                    <button type="button" id="open-approve-modal-btn" class="px-3.5 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold shadow-lg shadow-emerald-600/30 flex items-center gap-1.5 transition-all">
                        <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Setujui</span>
                    </button>
                </div>
            </div>

            <!-- Big Event Headline Row -->
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-white tracking-tight leading-tight">
                {{ $event['event_name'] ?? $draft->brief->event_title }}
            </h1>
        </div>

        <!-- Designer Feedback Banner if already reviewed -->
        @if($draft->designer_feedback)
            <div class="p-4 rounded-xl {{ $draft->approval_status === 'APPROVED' ? 'bg-emerald-950/40 border-emerald-500/30 text-emerald-200' : 'bg-rose-950/40 border-rose-500/30 text-rose-200' }} border text-xs">
                <span class="font-bold">Catatan Desainer ({{ $draft->reviewer->name ?? 'Lead Designer' }}):</span>
                <span class="ml-1 text-slate-300">{{ $draft->designer_feedback }}</span>
            </div>
        @endif

        <!-- MAIN VIEW MODE CONTAINER -->
        <div id="draft-view-mode" class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Left 7 Cols: Interactive Visual Slide Mockup -->
            <div class="lg:col-span-7 space-y-4">
                <div class="surface-card rounded-xl p-5 sm:p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h2 class="text-sm font-bold text-white uppercase tracking-wider flex items-center gap-2">
                                <svg class="w-4 h-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>Preview Slide Instagram Korsel (1:1 Aspect Ratio)</span>
                            </h2>
                            <p class="text-[11px] text-slate-400">Gunakan panah kiri/kanan keyboard atau tombol navigasi untuk berpindah slide.</p>
                        </div>

                        <div class="flex items-center gap-2">
                            <div class="text-xs font-mono font-semibold px-2.5 py-1 rounded-lg bg-slate-900 border border-slate-700 text-slate-300">
                                Slide <span id="current-slide-num">1</span> dari <span id="total-slide-num">{{ count($slides) }}</span>
                            </div>

                            <!-- Quick Download Buttons on Canvas Card -->
                            <button type="button" id="canvas-download-png-btn" class="px-2.5 py-1 rounded-lg bg-emerald-600/20 hover:bg-emerald-600/40 text-emerald-300 border border-emerald-500/30 text-xs font-semibold flex items-center gap-1 transition-colors" title="Download Slide Aktif ke PNG">
                                <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>PNG</span>
                            </button>

                            <button type="button" id="canvas-download-zip-btn" class="px-2.5 py-1 rounded-lg bg-indigo-600/20 hover:bg-indigo-600/40 text-indigo-300 border border-indigo-500/30 text-xs font-semibold flex items-center gap-1 transition-colors" title="Download Semua Slide dalam Format .ZIP">
                                <svg class="w-3.5 h-3.5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                </svg>
                                <span>ZIP</span>
                            </button>
                        </div>
                    </div>

                    <!-- Slide Carousel Display Canvas -->
                    <div id="slide-carousel" class="max-w-md mx-auto w-full">
                        @foreach($slides as $index => $slide)
                            <div class="slide-item {{ $index === 0 ? 'flex' : 'hidden' }} slide-canvas text-white"
                                 style="background: linear-gradient(145deg, {{ $primaryColor }} 0%, #070B14 70%, {{ $secondaryColor }}22 100%); border: 2px solid {{ $secondaryColor }}40;">
                                
                                <!-- Top Bar in Slide Mockup -->
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full flex items-center justify-center font-bold text-[10px] text-slate-900 shadow-md" style="background-color: {{ $secondaryColor }};">
                                            HM
                                        </div>
                                        <span class="text-[11px] font-semibold tracking-wide uppercase text-slate-200">
                                            {{ $branding['organization_name'] ?? 'Himpunan Kampus' }}
                                        </span>
                                    </div>

                                    <span class="text-[10px] font-mono uppercase px-2 py-0.5 rounded-full bg-black/40 border border-white/10 text-amber-300 font-semibold">
                                        {{ $slide['layout_template'] }}
                                    </span>
                                </div>

                                <!-- Slide Middle Content -->
                                <div class="my-auto py-4">
                                    @if($slide['layout_template'] === 'cover')
                                        <div class="space-y-3">
                                            <span class="inline-block text-[10px] uppercase font-bold tracking-widest px-2.5 py-0.5 rounded-md text-slate-900 shadow-sm" style="background-color: {{ $secondaryColor }};">
                                                Official Event
                                            </span>
                                            <h3 class="text-2xl font-extrabold leading-tight tracking-tight text-white drop-shadow-md">
                                                {{ $slide['headline'] }}
                                            </h3>
                                            @if(!empty($slide['body_text']))
                                                <div class="space-y-1.5 pt-2 text-xs text-slate-200">
                                                    @foreach($slide['body_text'] as $bt)
                                                        <p class="leading-relaxed">{{ $bt }}</p>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @elseif($slide['layout_template'] === 'quote')
                                        <div class="space-y-3">
                                            <svg class="w-8 h-8 text-amber-400 opacity-80" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                                            </svg>
                                            <h3 class="text-lg font-bold text-white leading-snug">
                                                {{ $slide['headline'] }}
                                            </h3>
                                            @if(!empty($slide['body_text']))
                                                <div class="space-y-1.5 text-xs text-slate-300 italic">
                                                    @foreach($slide['body_text'] as $bt)
                                                        <p>{{ $bt }}</p>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @elseif($slide['layout_template'] === 'cta')
                                        <div class="space-y-3">
                                            <span class="text-[10px] uppercase font-bold tracking-wider text-amber-400">Registrasi Terbuka</span>
                                            <h3 class="text-xl font-extrabold text-white leading-snug">
                                                {{ $slide['headline'] }}
                                            </h3>
                                            @if(!empty($slide['body_text']))
                                                <div class="space-y-1.5 text-xs text-slate-200">
                                                    @foreach($slide['body_text'] as $bt)
                                                        <div class="flex items-start gap-2">
                                                            <span class="text-amber-400">▸</span>
                                                            <span>{{ $bt }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            <div class="pt-3">
                                                <div class="w-full py-2 px-3 rounded-lg text-center font-bold text-xs text-slate-900 shadow-lg" style="background-color: {{ $secondaryColor }};">
                                                    Daftar Sekarang Melalui Link Bio
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <!-- content_list template -->
                                        <div class="space-y-3">
                                            <h3 class="text-lg font-bold text-white border-b border-white/20 pb-2">
                                                {{ $slide['headline'] }}
                                            </h3>
                                            @if(!empty($slide['body_text']))
                                                <ul class="space-y-2 text-xs text-slate-200">
                                                    @foreach($slide['body_text'] as $bt)
                                                        <li class="flex items-start gap-2 bg-black/20 p-2 rounded-lg border border-white/5">
                                                            <span class="w-1.5 h-1.5 rounded-full shrink-0 mt-1.5" style="background-color: {{ $secondaryColor }};"></span>
                                                            <span class="leading-relaxed">{{ $bt }}</span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </div>
                                    @endif
                                </div>

                                <!-- Slide Footer -->
                                <div class="flex items-center justify-between text-[10px] text-slate-300 border-t border-white/10 pt-2 font-mono">
                                    <span>Geser Slide</span>
                                    <span>{{ $slide['slide_number'] }} / {{ count($slides) }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Carousel Controls Bar -->
                    <div class="flex items-center justify-between mt-5 pt-4 border-t border-white/10">
                        <button type="button" id="slide-prev-btn" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-xs font-semibold text-white flex items-center gap-1.5 transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            <span>Sebelumnya</span>
                        </button>

                        <div id="slide-indicators" class="flex items-center gap-1.5">
                            <!-- Populated dynamically by JS -->
                        </div>

                        <button type="button" id="slide-next-btn" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-xs font-semibold text-white flex items-center gap-1.5 transition-colors">
                            <span>Selanjutnya</span>
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Structured Slides Layout Detail Accordion -->
                <div class="surface-card rounded-xl p-5 space-y-3">
                    <h3 class="text-xs font-semibold text-slate-300 uppercase tracking-wider">
                        Rincian Semua Slide Layout (Struktur JSON Terverifikasi)
                    </h3>
                    <div class="space-y-2">
                        @foreach($slides as $slide)
                            <div class="p-3 rounded-xl bg-slate-900/60 border border-slate-800 text-xs flex items-start justify-between gap-3">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="w-5 h-5 rounded-full bg-slate-800 border border-slate-700 flex items-center justify-center font-bold text-[10px] text-amber-400">
                                            {{ $slide['slide_number'] }}
                                        </span>
                                        <span class="font-semibold text-white">{{ $slide['headline'] }}</span>
                                        <span class="text-[10px] px-1.5 py-0.5 rounded bg-slate-800 text-slate-400 font-mono">
                                            {{ $slide['layout_template'] }}
                                        </span>
                                    </div>
                                    @if(!empty($slide['body_text']))
                                        <div class="pl-7 mt-1.5 text-slate-400 space-y-0.5">
                                            @foreach($slide['body_text'] as $b)
                                                <p>• {{ $b }}</p>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right 5 Cols: Social Media Caption & RAG Telemetry -->
            <div class="lg:col-span-5 space-y-4">
                <!-- Multi-Channel Copywriting Hub Card -->
                <div class="surface-card rounded-xl p-5 space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-white/10">
                        <div>
                            <h2 class="text-xs font-bold text-white uppercase tracking-wider flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                </svg>
                                <span>Copywriting Multi-Channel</span>
                            </h2>
                            <p class="text-[11px] text-slate-400 mt-0.5">Dihasilkan oleh Agen AI untuk Instagram, WhatsApp, dan LinkedIn.</p>
                        </div>

                        <!-- Channel Tab Buttons -->
                        <div class="flex items-center gap-1 p-1 bg-slate-900 rounded-lg border border-slate-800 text-[11px] font-semibold">
                            <button type="button" id="tab-btn-ig" class="channel-tab-btn active px-2.5 py-1 rounded-md bg-[#003366] text-white transition-all">
                                Instagram
                            </button>
                            <button type="button" id="tab-btn-wa" class="channel-tab-btn px-2.5 py-1 rounded-md text-slate-400 hover:text-white transition-all">
                                WhatsApp
                            </button>
                            <button type="button" id="tab-btn-li" class="channel-tab-btn px-2.5 py-1 rounded-md text-slate-400 hover:text-white transition-all">
                                LinkedIn
                            </button>
                        </div>
                    </div>

                    <!-- Instagram Panel -->
                    <div id="channel-content-ig" class="channel-panel space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-semibold text-indigo-300">Format Instagram Feed & Korsel</span>
                            <button type="button" id="copy-caption-btn" class="px-2.5 py-1 rounded-lg bg-indigo-600/30 hover:bg-indigo-600/60 border border-indigo-500/40 text-xs font-semibold text-indigo-200 transition-colors flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                </svg>
                                <span>Salin IG</span>
                            </button>
                        </div>
                        <div class="p-4 rounded-xl bg-slate-900/90 border border-slate-800 space-y-3 text-xs leading-relaxed">
                            <div>
                                <span class="text-[10px] uppercase font-bold text-amber-400 tracking-wider">Hook Kalimat Pembuka:</span>
                                <p id="caption-hook" class="font-semibold text-white mt-0.5">{{ $caption['hook_sentence'] ?? '-' }}</p>
                            </div>
                            <div>
                                <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Isi Deskripsi:</span>
                                <p id="caption-body" class="text-slate-300 whitespace-pre-line mt-0.5">{{ $caption['body_paragraph'] ?? '-' }}</p>
                            </div>
                            <div>
                                <span class="text-[10px] uppercase font-bold text-emerald-400 tracking-wider">Call to Action (CTA):</span>
                                <p id="caption-cta" class="font-semibold text-slate-200 mt-0.5">{{ $caption['call_to_action'] ?? '-' }}</p>
                            </div>
                            <div>
                                <span class="text-[10px] uppercase font-bold text-cyan-400 tracking-wider">Hashtags:</span>
                                <p id="caption-hashtags" class="text-cyan-300 font-medium mt-0.5">{{ implode(' ', $caption['hashtags'] ?? []) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- WhatsApp Panel -->
                    <div id="channel-content-wa" class="channel-panel hidden space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-semibold text-emerald-400">Format Broadcast WhatsApp Group</span>
                            <button type="button" id="copy-wa-btn" class="px-2.5 py-1 rounded-lg bg-emerald-600/30 hover:bg-emerald-600/60 border border-emerald-500/40 text-xs font-semibold text-emerald-200 transition-colors flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                </svg>
                                <span>Salin WA</span>
                            </button>
                        </div>
                        <div id="wa-broadcast-text" class="p-4 rounded-xl bg-slate-900/90 border border-slate-800 text-xs font-mono text-slate-200 whitespace-pre-wrap leading-relaxed">{{ $caption['whatsapp_broadcast'] ?? 'Belum ada format WhatsApp.' }}</div>
                    </div>

                    <!-- LinkedIn Panel -->
                    <div id="channel-content-li" class="channel-panel hidden space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-semibold text-blue-400">Postingan LinkedIn Edukatif</span>
                            <button type="button" id="copy-li-btn" class="px-2.5 py-1 rounded-lg bg-blue-600/30 hover:bg-blue-600/60 border border-blue-500/40 text-xs font-semibold text-blue-200 transition-colors flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                </svg>
                                <span>Salin LinkedIn</span>
                            </button>
                        </div>
                        <div id="li-post-text" class="p-4 rounded-xl bg-slate-900/90 border border-slate-800 text-xs text-slate-200 whitespace-pre-wrap leading-relaxed">{{ $caption['linkedin_post'] ?? 'Belum ada format LinkedIn.' }}</div>
                    </div>
                </div>

                <!-- Grounding & RAG Telemetry Panel -->
                <div class="surface-card rounded-xl p-5 space-y-3">
                    <h2 class="text-xs font-bold text-white uppercase tracking-wider flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        <span>RAG Grounding & Brand Guideline Rules</span>
                    </h2>

                    <div class="grid grid-cols-2 gap-3 text-xs">
                        <div class="p-3 rounded-xl bg-slate-900/60 border border-slate-800">
                            <span class="text-[10px] text-slate-400">Primary Color Hex</span>
                            <div class="flex items-center gap-2 mt-1 font-mono font-bold text-white">
                                <span class="w-4 h-4 rounded-full border border-white/20 shadow-sm" style="background-color: {{ $primaryColor }};"></span>
                                <span>{{ $primaryColor }}</span>
                            </div>
                        </div>

                        <div class="p-3 rounded-xl bg-slate-900/60 border border-slate-800">
                            <span class="text-[10px] text-slate-400">Secondary Color Hex</span>
                            <div class="flex items-center gap-2 mt-1 font-mono font-bold text-white">
                                <span class="w-4 h-4 rounded-full border border-white/20 shadow-sm" style="background-color: {{ $secondaryColor }};"></span>
                                <span>{{ $secondaryColor }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="p-3 rounded-xl bg-slate-900/60 border border-slate-800 text-xs">
                        <span class="text-[10px] text-slate-400">Rekomendasi Font:</span>
                        <div class="font-medium text-slate-200 mt-0.5">{{ $branding['font_family_recommendation'] ?? 'Instrument Sans' }}</div>
                    </div>

                    <!-- RAG Chunks Telemetry -->
                    @if(!empty($json['rag_context_used']))
                        <div class="pt-2">
                            <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Chunk RAG yang Dirujuk AI:</span>
                            <div class="space-y-1.5 mt-2">
                                @foreach($json['rag_context_used'] as $chunk)
                                    <div class="p-2.5 rounded-lg bg-slate-950/60 border border-slate-800/80 text-[11px] space-y-1">
                                        <div class="flex items-center justify-between text-indigo-300 font-semibold">
                                            <span>{{ $chunk['topic'] }}</span>
                                            <span class="text-amber-400 font-mono">Similarity: {{ round($chunk['similarity'] * 100) }}%</span>
                                        </div>
                                        <p class="text-slate-400">{{ $chunk['excerpt'] }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Raw JSON Inspector Accordion -->
                <div class="surface-card rounded-xl p-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Formal JSON Contract Inspector</span>
                        <button type="button" id="copy-json-btn" class="text-[11px] text-indigo-400 hover:text-indigo-300 font-medium">
                            Salin JSON
                        </button>
                    </div>
                    <pre id="raw-json-data" class="max-h-48 overflow-y-auto p-3 rounded-xl bg-slate-950/80 border border-slate-800 text-[11px] font-mono text-emerald-400 leading-relaxed">{{ json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) }}</pre>
                </div>
            </div>
        </div>

        <!-- MAIN EDIT MODE CONTAINER (HITL Editing) -->
        <div id="draft-edit-mode" class="hidden surface-card rounded-xl p-6 sm:p-8 space-y-6">
            <div class="flex items-center justify-between border-b border-white/10 pb-4">
                <div>
                    <h2 class="text-lg font-bold text-white flex items-center gap-2">
                        <span>Mode Sunting Konten (Human-in-the-Loop)</span>
                        <span class="text-xs px-2.5 py-0.5 rounded-md bg-indigo-500/10 text-indigo-300 border border-indigo-500/20">Edit Langsung</span>
                    </h2>
                    <p class="text-xs text-slate-400 mt-0.5">Ubah redaksi caption atau sesuaikan headline dan isi slide sebelum menyetujui.</p>
                </div>

                <button type="button" id="cancel-edit-mode-btn" class="px-3.5 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-xs font-semibold text-slate-300 transition-colors">
                    Batal Sunting
                </button>
            </div>

            <form method="POST" action="{{ route('designer.update', $draft) }}" class="space-y-6">
                @csrf

                <!-- Caption Editing Section -->
                <div class="space-y-4">
                    <h3 class="text-xs font-bold text-amber-400 uppercase tracking-wider">1. Sunting Teks Caption Instagram</h3>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Hook Kalimat Pembuka</label>
                        <input type="text" name="hook_sentence" value="{{ $caption['hook_sentence'] ?? '' }}" required
                            class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-sm focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Isi Paragraf Caption</label>
                        <textarea name="body_paragraph" rows="4" required
                            class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-sm focus:border-indigo-500">{{ $caption['body_paragraph'] ?? '' }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1">Call to Action (CTA)</label>
                            <input type="text" name="call_to_action" value="{{ $caption['call_to_action'] ?? '' }}" required
                                class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-sm focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1">Hashtags (Pisahkan spasi)</label>
                            <input type="text" name="hashtags" value="{{ implode(' ', $caption['hashtags'] ?? []) }}"
                                class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-cyan-300 text-sm focus:border-indigo-500">
                        </div>
                    </div>

                    <div class="space-y-3 pt-3 border-t border-slate-800">
                        <div>
                            <label class="block text-xs font-semibold text-emerald-400 mb-1">Pesan Broadcast WhatsApp Group</label>
                            <textarea name="whatsapp_broadcast" rows="4"
                                class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs font-mono focus:border-emerald-500">{{ $caption['whatsapp_broadcast'] ?? '' }}</textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-blue-400 mb-1">Postingan LinkedIn Edukatif</label>
                            <textarea name="linkedin_post" rows="4"
                                class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:border-blue-500">{{ $caption['linkedin_post'] ?? '' }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Slide Headlines Editing Section -->
                <div class="space-y-4 pt-4 border-t border-white/10">
                    <h3 class="text-xs font-bold text-indigo-400 uppercase tracking-wider">2. Sunting Struktur & Headline Slide Korsel</h3>

                    <div class="space-y-4">
                        @foreach($slides as $index => $slide)
                            <div class="p-4 rounded-xl bg-slate-900/70 border border-slate-800 space-y-3">
                                <div class="flex items-center justify-between text-xs font-semibold text-slate-300">
                                    <span>Slide {{ $slide['slide_number'] }} [{{ $slide['layout_template'] }}]</span>
                                    <span class="text-[11px] text-slate-500">Maks. 50 karakter</span>
                                </div>
                                <div>
                                    <label class="block text-[11px] text-slate-400 mb-1">Headline Slide</label>
                                    <input type="text" name="slides[{{ $index }}][headline]" maxlength="50" value="{{ $slide['headline'] }}"
                                        class="w-full px-3.5 py-2 rounded-lg bg-slate-950 border border-slate-700 text-white text-xs">
                                </div>
                                <div>
                                    <label class="block text-[11px] text-slate-400 mb-1">Body Text (Satu baris per poin)</label>
                                    <textarea name="slides[{{ $index }}][body_text]" rows="2"
                                        class="w-full px-3.5 py-2 rounded-lg bg-slate-950 border border-slate-700 text-white text-xs font-mono">{{ implode("\n", $slide['body_text'] ?? []) }}</textarea>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-white/10">
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold shadow-lg shadow-indigo-600/30 transition-all">
                        Simpan Perubahan Draf
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Approve Modal Dialog -->
    <div id="approve-modal" class="modal-container hidden fixed inset-0 z-50 bg-[#070B14]/80 items-center justify-center p-4">
        <div class="modal-backdrop absolute inset-0"></div>
        <div class="surface-card relative z-10 max-w-md w-full rounded-xl p-6 space-y-4 border border-emerald-500/30 shadow-xl">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-emerald-500/20 text-emerald-400 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-white">Setujui Draf Media (HITL)?</h3>
                    <p class="text-xs text-slate-400">Draf akan ditandai resmi disetujui dan siap diekspor ke format publikasi.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('designer.approve', $draft) }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Catatan Persetujuan (Opsional)</label>
                    <textarea name="designer_feedback" rows="3"
                        class="w-full px-3.5 py-2.5 rounded-lg bg-slate-900 border border-slate-700 text-white text-xs placeholder-slate-500 focus:outline-none focus:border-emerald-500"
                        placeholder="Contoh: Tata letak slide sesuai Brand Guideline HMIF 2026. Teks dan warna disetujui.">Disetujui oleh Lead Designer sesuai Brand Guideline resmi.</textarea>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button" id="cancel-approve-btn" class="px-4 py-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold transition-all">
                        Ya, Setujui Draf
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Reject Modal Dialog -->
    <div id="reject-modal" class="modal-container hidden fixed inset-0 z-50 bg-[#070B14]/80 items-center justify-center p-4">
        <div class="modal-backdrop absolute inset-0"></div>
        <div class="surface-card relative z-10 max-w-md w-full rounded-xl p-6 space-y-4 border border-rose-500/30 shadow-xl">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-rose-500/20 text-rose-400 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-white">Tolak Draf & Minta Revisi</h3>
                    <p class="text-xs text-slate-400">Panitia (Requester) akan menerima umpan balik untuk memperbaiki brief.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('designer.reject', $draft) }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Alasan Penolakan / Catatan Revisi <span class="text-rose-400">*</span></label>
                    <textarea name="designer_feedback" rows="3" required
                        class="w-full px-3.5 py-2.5 rounded-lg bg-slate-900 border border-slate-700 text-white text-xs placeholder-slate-500 focus:outline-none focus:border-rose-500"
                        placeholder="Contoh: Tanggal pelaksanaan belum tercantum dengan jelas atau poin pembicara perlu dirinci."></textarea>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button" id="cancel-reject-btn" class="px-4 py-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-lg bg-rose-600 hover:bg-rose-500 text-white text-xs font-semibold transition-all">
                        Tolak & Kirim Umpan Balik
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Client-Side Libraries for High-Res PNG & ZIP Generation -->
    <script src="https://cdn.jsdelivr.net/npm/html-to-image@1.11.11/dist/html-to-image.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
</x-layouts.app>
