<x-layouts.app title="Brand Guidelines & RAG Management — Briefly Admin">
    @php
        $meta = $guideline->vector_metadata ?? [];
        $chunks = $meta['rag_chunks'] ?? [];
    @endphp

    <div class="space-y-6">
        <!-- Admin Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-2 border-b border-white/10">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-white flex items-center gap-2">
                    <span>Brand Guidelines & RAG Knowledge Store</span>
                    <span class="text-xs px-2.5 py-0.5 rounded-full bg-amber-500/10 text-amber-300 border border-amber-500/20 font-semibold">Administrator</span>
                </h1>
                <p class="text-xs text-slate-400 mt-1">
                    Kelola aturan identitas visual organisasi, palet warna HEX, SOP publikasi, dan chunk vektor basis pengetahuan untuk grounding Agen AI.
                </p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.guidelines.update') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Organization & Colors Card -->
            <div class="glass-panel rounded-2xl p-6 sm:p-8 space-y-5">
                <h2 class="text-sm font-bold text-white uppercase tracking-wider flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                    </svg>
                    <span>1. Identitas Visual & Palet Warna Resmi</span>
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                            Nama Organisasi / Lembaga <span class="text-rose-400">*</span>
                        </label>
                        <input type="text" name="organization_name" value="{{ old('organization_name', $guideline->organization_name) }}" required
                            class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-sm focus:border-amber-400">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                            Primary Color HEX <span class="text-rose-400">*</span>
                        </label>
                        <div class="flex items-center gap-2">
                            <input type="color" value="{{ old('primary_color_hex', $guideline->primary_color_hex) }}"
                                class="w-10 h-10 rounded-lg cursor-pointer bg-transparent border-0 p-0"
                                oninput="this.nextElementSibling.value = this.value">
                            <input type="text" name="primary_color_hex" value="{{ old('primary_color_hex', $guideline->primary_color_hex) }}" required pattern="^#[A-Fa-f0-9]{6}$"
                                class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white font-mono text-sm focus:border-amber-400">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                            Secondary Color HEX <span class="text-rose-400">*</span>
                        </label>
                        <div class="flex items-center gap-2">
                            <input type="color" value="{{ old('secondary_color_hex', $guideline->secondary_color_hex) }}"
                                class="w-10 h-10 rounded-lg cursor-pointer bg-transparent border-0 p-0"
                                oninput="this.nextElementSibling.value = this.value">
                            <input type="text" name="secondary_color_hex" value="{{ old('secondary_color_hex', $guideline->secondary_color_hex) }}" required pattern="^#[A-Fa-f0-9]{6}$"
                                class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white font-mono text-sm focus:border-amber-400">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 pt-2">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                            Tone of Voice (Gaya Bahasa Brand) <span class="text-rose-400">*</span>
                        </label>
                        <input type="text" name="brand_voice" value="{{ old('brand_voice', $meta['brand_voice'] ?? 'Informatif, energik, profesional, dan ramah mahasiswa.') }}" required
                            class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-sm focus:border-amber-400">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                            Rekomendasi Font <span class="text-rose-400">*</span>
                        </label>
                        <input type="text" name="font_recommendation" value="{{ old('font_recommendation', $meta['font_recommendation'] ?? 'Instrument Sans, Inter, sans-serif') }}" required
                            class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-sm focus:border-amber-400">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                        Dokumen Panduan (PDF / Markdown)
                    </label>
                    <div class="p-3 rounded-xl bg-slate-900/60 border border-slate-800 text-xs text-slate-400 flex items-center justify-between">
                        <span class="font-mono">{{ $guideline->document_path }}</span>
                        <input type="file" name="document_file" accept=".pdf,.md,.txt" class="text-xs text-slate-400">
                    </div>
                </div>
            </div>

            <!-- Existing RAG Vector Chunks -->
            <div class="glass-panel rounded-2xl p-6 sm:p-8 space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-bold text-white uppercase tracking-wider flex items-center gap-2">
                            <svg class="w-4 h-4 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            <span>2. Chunk Aturan & SOP dalam Vector Store (RAG)</span>
                        </h2>
                        <p class="text-xs text-slate-400 mt-0.5">Potongan dokumen ini dihitung tingkat kemiripannya (similarity search K=3-5) saat Agen AI merancang konten.</p>
                    </div>

                    <span class="text-xs font-mono font-semibold px-2.5 py-1 rounded-full bg-slate-800 text-cyan-300 border border-slate-700">
                        {{ count($chunks) }} Chunk Aktif
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($chunks as $chunk)
                        <div class="p-4 rounded-xl bg-slate-900/80 border border-slate-800 space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="font-semibold text-xs text-indigo-300">{{ $chunk['topic'] }}</span>
                                <span class="text-[10px] font-mono px-2 py-0.5 rounded bg-slate-800 text-slate-400">{{ $chunk['chunk_id'] }}</span>
                            </div>
                            <p class="text-xs text-slate-300 leading-relaxed">{{ $chunk['content'] }}</p>
                            @if(!empty($chunk['embedding_keywords']))
                                <div class="flex flex-wrap gap-1 pt-1">
                                    @foreach($chunk['embedding_keywords'] as $kw)
                                        <span class="text-[10px] px-1.5 py-0.5 rounded bg-slate-800 text-slate-400">#{{ $kw }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Add New RAG Chunk Card -->
            <div class="glass-panel rounded-2xl p-6 sm:p-8 space-y-4 border border-indigo-500/20">
                <h2 class="text-sm font-bold text-white uppercase tracking-wider flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>3. Tambah Chunk Pengetahuan / Aturan Baru ke Vector Store</span>
                </h2>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">
                            Topik / Judul Aturan
                        </label>
                        <input type="text" name="new_chunk_topic" placeholder="Contoh: Larangan Penggunaan Logo atau SOP Carousel"
                            class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:border-indigo-400">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">
                            Isi Teks Aturan / SOP
                        </label>
                        <textarea name="new_chunk_content" rows="3" placeholder="Tuliskan aturan yang harus ditaati oleh AI saat memproduksi media..."
                            class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:border-indigo-400"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">
                            Kata Kunci Embedding / Similarity (Pisahkan dengan koma)
                        </label>
                        <input type="text" name="new_chunk_keywords" placeholder="contoh: logo, warna, larangan, resolusi, headline"
                            class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-cyan-300 text-xs focus:border-indigo-400">
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end">
                <button type="submit" class="px-8 py-3.5 rounded-xl bg-gradient-to-r from-amber-500 via-indigo-600 to-[#003366] text-white text-xs font-bold shadow-xl hover:brightness-110 transition-all">
                    Simpan Seluruh Konfigurasi Brand & RAG
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
