<x-layouts.app title="Kelola Pengguna & Otorisasi — Briefly Admin">
    <div class="space-y-6">
        <!-- Admin Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-2 border-b border-white/10">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-white flex items-center gap-2">
                    <span>Manajemen Akun & Matriks Hak Akses (RBAC)</span>
                    <span class="text-xs px-2.5 py-0.5 rounded-full bg-amber-500/10 text-amber-300 border border-amber-500/20 font-semibold">Administrator</span>
                </h1>
                <p class="text-xs text-slate-400 mt-1">
                    Daftar pengguna terdaftar dan hak akses sesuai Role-Based Access Control matrix spesifikasi sistem.
                </p>
            </div>
        </div>

        <!-- RBAC Matrix Card (Specification 2) -->
        <div class="glass-panel rounded-2xl p-6 space-y-4">
            <h2 class="text-xs font-bold text-white uppercase tracking-wider flex items-center gap-2">
                <svg class="w-4 h-4 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                <span>Matriks Otorisasi Peran Pengguna</span>
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
                <div class="p-4 rounded-xl bg-slate-900/80 border border-amber-500/30 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-amber-300">👑 Administrator</span>
                        <span class="text-[10px] bg-amber-400/20 text-amber-300 px-2 py-0.5 rounded font-mono">Head of Media</span>
                    </div>
                    <ul class="text-slate-300 space-y-1.5 list-disc list-inside text-[11px]">
                        <li>Kelola Brand Guideline & SOP Vektor RAG</li>
                        <li>Konfigurasi Prompt Templates & Batasan AI</li>
                        <li>Kelola Akun & Hak Akses Pengguna</li>
                        <li>Akses penuh ke seluruh alur kerja</li>
                    </ul>
                </div>

                <div class="p-4 rounded-xl bg-slate-900/80 border border-indigo-500/30 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-indigo-300">🎨 Lead Designer Specialist</span>
                        <span class="text-[10px] bg-indigo-400/20 text-indigo-300 px-2 py-0.5 rounded font-mono">Tim Media</span>
                    </div>
                    <ul class="text-slate-300 space-y-1.5 list-disc list-inside text-[11px]">
                        <li>Terima & tinjau luaran draft dari Agen AI</li>
                        <li>Human-in-the-Loop review & edit slide/teks</li>
                        <li>Memberikan persetujuan (Approve) atau tolak</li>
                        <li>Ekspor draf final (JSON & Markdown)</li>
                    </ul>
                </div>

                <div class="p-4 rounded-xl bg-slate-900/80 border border-emerald-500/30 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-emerald-300">📝 Requester</span>
                        <span class="text-[10px] bg-emerald-400/20 text-emerald-300 px-2 py-0.5 rounded font-mono">Panitia / Member</span>
                    </div>
                    <ul class="text-slate-300 space-y-1.5 list-disc list-inside text-[11px]">
                        <li>Memasukkan brief mentah acara via form input</li>
                        <li>Memantau status pemrosesan draf</li>
                        <li>Menerima feedback catatan jika perlu revisi</li>
                        <li>Tidak memiliki akses langsung ke RAG / Prompt</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="glass-panel rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-900/80 text-[11px] uppercase tracking-wider text-slate-400 border-b border-white/5">
                        <tr>
                            <th class="px-6 py-4 font-semibold">Nama Lengkap</th>
                            <th class="px-6 py-4 font-semibold">Email</th>
                            <th class="px-6 py-4 font-semibold">Peran (Role)</th>
                            <th class="px-6 py-4 font-semibold">Terdaftar Sejak</th>
                            <th class="px-6 py-4 font-semibold text-right">Tingkat Akses</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5 text-slate-300">
                        @foreach($users as $user)
                            <tr class="hover:bg-white/[0.02] transition-colors">
                                <td class="px-6 py-4 font-semibold text-white">
                                    {{ $user->name }}
                                </td>
                                <td class="px-6 py-4 text-xs font-mono text-slate-400">
                                    {{ $user->email }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($user->role === 'admin')
                                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-500/20 text-amber-300 border border-amber-500/30">
                                            Administrator
                                        </span>
                                    @elseif($user->role === 'designer')
                                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">
                                            Designer Specialist
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                            Requester
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-400">
                                    {{ $user->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 text-right text-xs">
                                    <span class="text-slate-400">
                                        {{ $user->role === 'admin' ? 'Akses Penuh' : ($user->role === 'designer' ? 'Review & Ekspor' : 'Input Brief & Status') }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.app>
