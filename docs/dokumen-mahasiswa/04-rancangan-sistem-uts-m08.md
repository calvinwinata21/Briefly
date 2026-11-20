# DOKUMEN RANCANGAN SISTEM (UTS) — BRIEFLY AI
**Mata Kuliah:** Kapita Selekta: AI Engineering (Ganjil 2026/2027)  
**Komponen Penilaian:** Ujian Tengah Semester (Bobot: 10% Nilai Akhir)  
**Mahasiswa:** Calvin Winata (NIM: 10241016) | Kode Peserta K = 23  
**Program Studi:** Sistem Informasi — Institut Teknologi Kalimantan  

---

## 1. Ringkasan Eksekutif & Struktur Presentasi 8 Slide (UTS)

Sesuai aturan ketat pelaksanaan UTS (Modul Minggu 8 bagian 8.1 & 8.2), presentasi dirancang tepat dalam 8 slide terstruktur:

| Slide # | Judul Slide | Pertanyaan Inti yang Terjawab |
|:---:|---|---|
| **1** | **Persoalan Nyata** | Panitia pelaksana acara sering mengirim brief via chat WhatsApp informal; desainer media HMSI membutuhkan 4–8 jam per acara untuk ekstraksi manual, mengingat aturan brand, dan mengetik 3 versi caption terpisah. |
| **2** | **Mengapa AI Generatif** | Brief ditulis dengan bahasa bebas penuh singkatan (*"bsk lusa di sekre bwt baksos"*). Sistem form kaku atau regex gagal menangani variasi semantik bahasa, sementara AI generatif mampu menormalisasi entitas dengan toleransi tinggi. |
| **3** | **Arsitektur Sistem & Alur Data** | Pipeline orkestrasi Laravel terdekomposisi: *Requester Brief $\rightarrow$ ParseBriefAction $\rightarrow$ ExecuteRagSearchAction $\rightarrow$ Gemini Model Gateway $\rightarrow$ ValidateAiOutputAction $\rightarrow$ Review Dashboard (HITL)*. |
| **4** | **Kendali Output & Skema Data** | Kontrak JSON RFC 8259 ketat (`job_id`, `event_metadata`, `branding_rules_applied`, tepat 4 slide carousel, 3 kanal caption). Dilengkapi mekanisme pemulihan 3-langkah (*retry loop & heuristic fallback*). |
| **5** | **Spesifikasi & Batas Akses Tools** | 3 Tools resmi: `ParseBriefAction` (read-only input), `ExecuteRagSearchAction` (read-only tabel brand guidelines), dan `ValidateAiOutputAction` (in-memory schema check). Dilarang mengakses data pengguna. |
| **6** | **Grounding (RAG 8 Chunk Brand)** | Basis pengetahuan resmi 8 chunk HMSI: warna primer Navy `#0F172A` & Blue `#3B82F6`, tipografi Plus Jakarta Sans/Inter, dan 3 mode nada komunikasi. Aturan tegas: jika informasi tidak ada di dokumen, wajib menolak mengarang. |
| **7** | **Bukti: 1 Keberhasilan & 1 Kegagalan** | **Keberhasilan:** Ekstraksi akurat pada brief gaul ekstrem (TC-08) dalam 1,8 detik.  <br>**Kegagalan:** Tanggal relatif *"minggu depan"* (TC-15) sempat dihalusinasikan menjadi tanggal acak pada v1, kini diperbaiki dengan penolakan data bolong (*incomplete data guardrail*). |
| **8** | **Kajian Risiko & Rencana Blok D–E** | Titik bahaya terbesar: *Prompt Injection* dan *Publishing Bypass*. Solusi: Penguncian status draf selalu `PENDING_REVIEW` pada level database; status `APPROVED` hanya dapat diubah oleh Lead Designer terautentikasi. |

---

## 2. Diagram Alur Sistem Komprehensif (Slide 3 Artifact)

```
[INPUT PENGGUNA]
Teks Brief Bebas / Informal dari Panitia Acara
       │
       ▼
[FASE 1: PARSING & ENTITY EXTRACTION]
`App\Actions\ParseBriefAction`
- Ekstraksi 5 Entitas (Nama, Tanggal, Audiens, Highlights, Kontak)
- Uji Kelengkapan (Completeness Guardrail)
       │
       ├─────────────────────────────────► [DATA TIDAK LENGKAP]
       │                                    Status: `incomplete_data`
       ▼ [LENGKAP]                          Minta Requester Lengkapi
[FASE 2: VECTOR RETRIEVAL RAG]
`App\Actions\ExecuteRagSearchAction`
- Pencarian Cosine Similarity terhadap 8 Chunk Brand Guidelines HMSI
- Ambang Batas: Similarity >= 0.40
- Mengambil 3 Chunk Relevan Teratas (Palet Warna, Font, Brand Voice)
       │
       ▼
[FASE 3: MODEL GATEWAY SYNTHESIS]
`App\Services\GeminiApiService` (gemini-2.5-flash, Temp: 0.2)
- Menyuntikkan Konteks RAG + Brief Terstruktur + 3 Mode Nada
- Menghasilkan 4 Slide Visual Layout + 3 Kanal Salinan Teks
       │
       ▼
[FASE 4: CONTRACT VALIDATION & RECOVERY]
`App\Actions\ValidateAiOutputAction`
- Validasi Kontrak Skema JSON RFC 8259
- Mekanisme Coba Ulang Terarah (Hingga 3x Percobaan)
- Heuristic Fallback Engine jika API/Sintaks Rusak
       │
       ▼
[FASE 5: PERSISTENSI & TAHAP HITL REVIEW]
Tersimpan di Basis Data: `media_drafts` (Status: `PENDING_REVIEW`)
       │
       ▼
[DASHBOARD LEAD DESIGNER]
Lead Designer Memeriksa Draf Visual & Salinan Media Sosial:
- [SETUJUI (APPROVE)] ──► Rilis & Ekspor Multi-Kanal (IG, WA, LinkedIn)
- [TOLAK (REJECT)]    ──► Beri Catatan Revisi ke Requester
```

---

## 3. Pertanggungjawaban Keputusan Desain (Bank Pertanyaan Lampiran E)

### Q1: Sebutkan satu keputusan rancangan Anda yang dapat dibuat berbeda, dan jelaskan mengapa Anda memilih yang ini!
*Jawaban:*  
Saya dapat saja merancang sistem ini sebagai **Fully Autonomous Multi-Agent** (misal: Agent Penulis Caption, Agent Desainer Warna, dan Agent Publisher yang saling berinteraksi secara otonom). Namun, alternatif tersebut **sengaja saya tolak**. Saya memilih arsitektur **Hybrid Agentic-Workflow terarah dengan satu model orchestrator**.  
*Alasannya:* Multi-agent otonom melipatgandakan biaya token hingga 4x lipat, menimbulkan latensi di atas 15 detik, dan sangat sulit dicari titik kegagalannya saat evaluasi. Untuk kebutuhan produksi media kampus, alur kerja deterministik yang diperkuat AI pada titik ekstraksi dan sitasi brand jauh lebih hemat biaya, cepat, dan mudah dipertanggungjawabkan.

### Q2: Tunjukkan satu input yang membuat sistem Anda gagal, dan jelaskan penyebab utamanya!
*Jawaban:*  
Input yang pernah membuat sistem v1 gagal adalah: *"Nanti tanggal 18 Januari 2027 kumpul di lab ya, mau ngobrol santai aja bareng anak-anak SI"* (Kasus TC-16).  
*Penyebab Kegagalan:* Model mengekstrak kata *"Nanti"* sebagai nama resmi acara, karena tidak ada batasan minimal panjang karakter atau kata kerja operasional pada prompt v1.  
*Perbaikan yang Dilakukan:* Saya memasang aturan validasi di `ParseBriefAction` yang memeriksa apakah judul acara memiliki substansi kegiatan dan memuat panjang minimal 5 karakter; jika tidak, sistem memicu status penolakan `incomplete_data` untuk meminta judul yang definitif.

### Q3: Bagian mana dari karya Anda yang dibuat dengan bantuan AI, dan bagaimana Anda memverifikasinya?
*Jawaban:*  
AI (Claude / Gemini) membantu saya dalam:
1. Menghasilkan kode boilerplate skema JSON validation dan fungsi perhitungan Cosine Similarity matematika di PHP.
2. Membantu *brainstorming* variasi prompt injection untuk set uji penyerangan.  
*Cara Verifikasi:* Setiap baris kode yang dihasilkan AI diuji melalui unit test otomatis (`MediaProductionSystemTest.php`) yang memverifikasi 41 assertions secara lulus, serta diperiksa manual baris per baris untuk memastikan tidak ada fungsi `eval()` berbahaya atau celah SQL injection.
