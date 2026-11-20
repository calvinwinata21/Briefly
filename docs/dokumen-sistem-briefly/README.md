# Dokumen Sistem Briefly AI — Artefak Rekayasa Sistem
### Asisten Produksi Media & Desain Berbasis AI Agent
**Mata Kuliah:** Kapita Selekta: AI Engineering (Ganjil 2026/2027)  
**Lingkup Wajib K = 23:** 8 Chunk RAG, 17 Kasus Uji, 3 Tools, 3 Mode Nada  
**Mahasiswa:** Calvin Winata (NIM: 10241016) | Institut Teknologi Kalimantan  

Folder ini memuat seluruh artefak teknis spesifikasi sistem, skema kontrak data, pustaka prompt berversi, basis pengetahuan brand RAG, set uji, dan trace audit yang mendasari sistem **Briefly AI**.

---

## 🏛️ Struktur Direktori Artefak Sistem

```
docs/dokumen-sistem-briefly/
├── arsitektur-sistem-briefly.md       # Spesifikasi arsitektur komprehensif (CPL-6 & CPMK-1)
├── rancangan-rag.md                  # Desain alur RAG (6 keputusan, threshold, multi-dokumen)
├── instruksi/                         # Evolusi prompt sistem berversi
│   ├── v1.md                          # v1.0: 6 Komponen dasar (Minggu 4)
│   ├── v2.md                          # v2.0: Schema-enforced & Fail-fast (Minggu 5)
│   ├── v3-rag.md                      # v3.0: Grounding RAG 8 chunk brand (Minggu 7 & 9)
│   ├── v4-agentic.md                  # v4.0: Full Agentic Orchestrator + 3 Nada (Minggu 10-13)
│   └── CATATAN.md                     # Changelog riwayat versi dan efek pada output
├── schema/                            # Kontrak data terstruktur
│   ├── briefly-output-schema.json     # Kontrak skema JSON RFC 8259 resmi
│   └── schema-specification.md        # Dokumentasi field, enum, dan 3-step error recovery
├── tools-spec/                        # Spesifikasi 3 Action Tools wajib
│   └── tools-specification.md         # Tanda tangan fungsi, batas akses, dan aturan dilarang
├── brand-guidelines-kb/               # Basis pengetahuan RAG resmi HMSI
│   ├── brand-guidelines-8-chunks.json # 8 chunk vektor terindeks (K=23)
│   └── brand-guidelines-manual.md     # Buku panduan identitas visual HMSI 2026
├── set-uji/                           # Set uji evaluasi sistematis
│   ├── set-uji-17-kasus.json          # 17 kasus uji terstruktur (K=23)
│   └── set-uji-17-kasus.md            # Dokumentasi acuan ground truth & 5 kriteria biner
└── trace-agent/                       # Perekaman audit ReAct trace multi-langkah
    ├── trace-01-tugas-sukses.md       # Trace tugas multi-langkah berhasil
    ├── trace-02-tugas-gagal-terdeteksi.md # Trace fail-fast guardrail (brief tanpa tanggal)
    └── trace-03-tugas-batas-langkah.md   # Trace batas langkah & pemulihan heuristic fallback
```

---

## ⚙️ Keterkaitan dengan Kode Implementasi Laravel

- **Tool 1:** `app/Actions/ParseBriefAction.php` $\leftrightarrow$ Mengacu pada [`tools-spec/tools-specification.md`](tools-spec/tools-specification.md)
- **Tool 2:** `app/Actions/ExecuteRagSearchAction.php` & `app/Services/VectorSearchService.php` $\leftrightarrow$ Mengacu pada [`brand-guidelines-kb/brand-guidelines-8-chunks.json`](brand-guidelines-kb/brand-guidelines-8-chunks.json) & [`rancangan-rag.md`](rancangan-rag.md)
- **Tool 3:** `app/Actions/ValidateAiOutputAction.php` $\leftrightarrow$ Mengacu pada [`schema/briefly-output-schema.json`](schema/briefly-output-schema.json)
- **Model Gateway:** `app/Services/GeminiApiService.php` $\leftrightarrow$ Menggunakan prompt [`instruksi/v4-agentic.md`](instruksi/v4-agentic.md)
- **HITL Review:** `app/Http/Controllers/DesignerReviewController.php` $\leftrightarrow$ Menegakkan Lapis 5 Guardrail pada draf `PENDING_REVIEW`
