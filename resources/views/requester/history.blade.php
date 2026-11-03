<x-layouts.app title="Riwayat & Status Brief — Briefly AI">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-white/10">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-white flex items-center gap-2">
                    <span>Status & Riwayat Brief</span>
                    <span class="text-xs px-2.5 py-0.5 rounded-full bg-slate-800 text-slate-300 border border-slate-700 font-medium">Monitoring</span>
                </h1>
                <p class="text-xs text-slate-400 mt-1">
                    Pantau tahapan pemrosesan draf konten Anda dari tahap AI Generation hingga persetujuan Lead Designer.
                </p>
            </div>

            <a href="{{ route('requester.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-gradient-to-r from-indigo-600 to-[#003366] text-white text-xs font-semibold shadow-lg hover:shadow-indigo-500/25 transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Input Brief Baru</span>
            </a>
        </div>

        <!-- Briefs Table Card -->
        <div class="glass-panel rounded-2xl overflow-hidden">
            @if($briefs->isEmpty())
                <div class="text-center py-16 px-4">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-slate-900 border border-slate-800 flex items-center justify-center text-slate-500">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-base font-semibold text-white">Belum Ada Brief yang Diajukan</h3>
                    <p class="text-xs text-slate-400 max-w-sm mx-auto mt-1 mb-6">Mulai dengan mengunggah instruksi kegiatan pertama Anda ke Agen AI.</p>
                    <a href="{{ route('requester.create') }}" class="px-5 py-2.5 rounded-xl bg-indigo-600 text-white text-xs font-semibold shadow-lg hover:bg-indigo-500 transition-colors">
                        + Buat Brief Sekarang
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-900/80 text-[11px] uppercase tracking-wider text-slate-400 border-b border-white/5">
                            <tr>
                                <th class="px-6 py-4 font-semibold">Judul Kegiatan</th>
                                <th class="px-6 py-4 font-semibold">Status AI Pipeline</th>
                                <th class="px-6 py-4 font-semibold">Status Approval (HITL)</th>
                                <th class="px-6 py-4 font-semibold">Waktu Pengajuan</th>
                                <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5 text-slate-300">
                            @foreach($briefs as $brief)
                                @php
                                    $latestDraft = $brief->latestDraft;
                                @endphp
                                <tr class="hover:bg-white/[0.02] transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-white">{{ $brief->event_title }}</div>
                                        <div class="text-xs text-slate-400 line-clamp-1 max-w-xs mt-0.5">
                                            {{ Str::limit($brief->raw_brief_text, 70) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($brief->status === 'completed')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                                Selesai Diproses AI
                                            </span>
                                        @elseif($brief->status === 'processing')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-ping"></span>
                                                Sedang Diproses AI
                                            </span>
                                        @elseif($brief->status === 'incomplete_data')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-rose-500/10 text-rose-400 border border-rose-500/20">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span>
                                                Data Tidak Lengkap
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-slate-500/10 text-slate-400 border border-slate-500/20">
                                                Menunggu Antrean
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if(!$latestDraft)
                                            <span class="text-xs text-slate-500">- Belum ada draf -</span>
                                        @elseif($latestDraft->approval_status === 'APPROVED')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                                <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                Disetujui Desainer
                                            </span>
                                        @elseif($latestDraft->approval_status === 'REJECTED')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-500/20 text-rose-300 border border-rose-500/30">
                                                <svg class="w-3.5 h-3.5 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                                Perlu Revisi
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-500/20 text-amber-300 border border-amber-500/30">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                                                Menunggu Review Desainer
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-xs text-slate-400">
                                        {{ $brief->created_at->format('d M Y, H:i') }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('requester.show', $brief) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-xs font-medium text-slate-200 border border-slate-700 transition-colors">
                                            <span>Detail</span>
                                            <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($briefs->hasPages())
                    <div class="p-4 border-t border-white/5">
                        {{ $briefs->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</x-layouts.app>
