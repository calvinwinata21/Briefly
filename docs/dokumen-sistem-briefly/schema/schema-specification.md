# Spesifikasi Skema Kontrak JSON Briefly AI
**Mata Kuliah:** Kapita Selekta: AI Engineering  
**Tahap Modul:** Minggu 5 (Structured Output & Schema sebagai Aturan Format)  
**Produk:** Briefly AI — Asisten Produksi Media & Desain Acara  
**Mahasiswa:** Calvin Winata (NIM: 10241016) | Kode Peserta K = 23  

---

## 1. Latar Belakang & Kebutuhan Skema
Dalam rekayasa sistem AI (AI Engineering), output model bahasa besar tidak boleh dibiarkan berupa teks bebas apabila output tersebut ditujukan untuk dikonsumsi oleh subsistem lain (seperti komponen rendering Blade, basis data SQLite, atau pipeline ekspor media sosial).

Skema JSON pada Briefly AI berfungsi sebagai **kontrak data (data contract)** yang ketat:
1. Menjamin integritas data sebelum disimpan ke tabel `media_drafts`.
2. Menyediakan mekanisme penolakan dini (*fail-fast*) apabila terjadi kegagalan generasi.
3. Memastikan semua field visual, sitasi RAG, dan caption multi-channel hadir secara lengkap.

---

## 2. Struktur Anatomi Field Skema

### A. Identitas Pekerjaan & Nada Konten
- `job_id` (string): Identifikator unik pemrosesan brief (format: `job-<uuid>`).
- `execution_timestamp` (ISO 8601 string): Waktu generasi dilakukan.
- `selected_tone` (enum): Mode nada komunikasi terpilih, wajib salah satu dari 3 mode ($K \bmod 3 + 1 = 3$):
  - `casual_energetic`
  - `formal_academic`
  - `persuasive_urgent`

### B. Metadata Acara (`event_metadata`)
- `name` (string, min 3 char): Nama resmi kegiatan yang diekstraksi dari brief.
- `date` (string, min 3 char): Tanggal dan jam pelaksanaan.
- `target` (string): Segmentasi audiens sasaran kegiatan.
- `highlights` (array of string, min 1 item): Poin-poin pembeda / sorotan utama kegiatan.
- `contact` (string): Narahubung / kontak pendaftaran.

### C. Aturan Brand yang Diterapkan (`branding_rules_applied`)
- `primary_color` (hex string): Warna dasar visual (cth: `#0F172A`).
- `secondary_color` (hex string): Warna pendukung (cth: `#3B82F6`).
- `accent_color` (hex string): Warna aksen kontras (cth: `#F59E0B`).
- `font_heading` & `font_body` (string): Pasangan tipografi resmi organisasi.
- `rag_chunks_cited` (array of integer, 1–8): Daftar ID chunk knowledge store yang dijadikan dasar penentuan aturan branding.

### D. Tata Letak Visual 4 Slide (`visual_slides_layout`)
Struktur visual carousel selalu terdiri atas tepat 4 slide berurutan:
1. **Slide 1 (`HOOK_HEADLINE`):** Judul utama yang menarik perhatian (*eye-catching hook*) dan branding acara.
2. **Slide 2 (`PROBLEM_CONTEXT`):** Latar belakang atau tantangan yang dihadapi target audiens.
3. **Slide 3 (`CORE_OFFERING`):** Solusi, silabus materi, narasumber, atau fasilitas yang didapatkan.
4. **Slide 4 (`CALL_TO_ACTION`):** Ajakan mendaftar, batas waktu pendaftaran, dan narahubung.

Setiap slide memuat atribut: `slide_number`, `slide_type`, `headline`, `subheadline`, `body_copy`, `visual_notes`, dan `citation_chunk` (referensi nomor chunk panduan visual).

### E. Salinan Media Sosial Multi-Channel (`social_media_caption`)
Menghasilkan materi salinan teks spesifik kanal:
- `instagram`: Teks caption yang menarik, dilengkapi daftar tagar (`hashtags`).
- `whatsapp_broadcast`: Teks broadcast terformat tebal/miring (*bold/italic*) yang siap sebar di grup percakapan.
- `linkedin_post`: Teks post berwawasan profesional dengan fokus *networking* dan *career growth*.

### F. Telemetri Orkestrator & Status HITL
- `orchestrator_telemetry`: Jumlah langkah yang dijalankan, daftar action tool yang dipanggil, nama model AI, dan perkiraan biaya token dalam Rupiah (IDR).
- `workflow_status`: Selalu dikunci pada nilai tunggal `PENDING_REVIEW` untuk memastikan draf tertahan di meja review Lead Designer.

---

## 3. Penanganan Kegagalan 3-Langkah (3-Step Schema Failure Recovery)

Sesuai standar keandalan pada Modul Minggu 5 bagian 5.1, Briefly AI menerapkan siklus penanganan kegagalan otomatis yang diimplementasikan di `ValidateAiOutputAction.php`:

```
               ┌───────────────────────────────┐
               │ Model Menghasilkan Payload    │
               └───────────────┬───────────────┘
                               │
                               ▼
               ┌───────────────────────────────┐
  Percobaan 1  │ Validasi Kontrak Schema JSON  ├────► [VALID] ──► Simpan ke DB (PENDING_REVIEW)
               └───────────────┬───────────────┘
                               │ [GAGAL VALIDASI / SYNTAX ERROR]
                               ▼
               ┌───────────────────────────────┐
  Percobaan 2  │ Reprompting Terarah           │
               │ Kirim pesan error JSON Schema │
               │ dan minta regenerasi ketat    │
               └───────────────┬───────────────┘
                               │
                               ▼
               ┌───────────────────────────────┐
               │ Validasi Kontrak Schema Ulang ├────► [VALID] ──► Simpan ke DB (PENDING_REVIEW)
               └───────────────┬───────────────┘
                               │ [MASIH GAGAL]
                               ▼
               ┌───────────────────────────────┐
  Percobaan 3  │ Deterministic Heuristic       │
               │ Fallback Engine               │
               │ (Ekstraksi Regex + Default    │
               │  Brand Template Chunks)       │
               └───────────────┬───────────────┘
                               │
                               ▼
               ┌───────────────────────────────┐
               │ Draf Disimpan dengan Label:   │
               │ "FALLBACK_HEURISTIC_PARSED"   │
               │ Lead Designer Diberi Peringat-│
               │ an Visual di Dashboard        │
               └───────────────────────────────┘
```

Dengan alur ini:
- Sistem tidak pernah melempar error HTTP 500 mentah ke hadapan pengguna.
- Kegagalan tercatat secara transparan di log sistem dan dashboard review.
- Tidak ada data fiktif yang disembunyikan.
