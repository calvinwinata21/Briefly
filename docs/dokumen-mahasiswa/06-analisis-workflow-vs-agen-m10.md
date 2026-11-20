# Analisis Arsitektur: Workflow vs. Autonomous Agent — Briefly AI
**Mata Kuliah:** Kapita Selekta: AI Engineering (Ganjil 2026/2027)  
**Tahap Modul:** Minggu 10 (Blok D — Dari Workflow ke Agent)  
**Mahasiswa:** Calvin Winata (NIM: 10241016) | Kode Peserta K = 23  

---

## 1. Latar Belakang & Pernyataan Masalah

Pada modul Minggu 10, diperkenalkan dua paradigma perancangan sistem kecerdasan buatan:
1. **Workflow Terarah (Deterministic Pipeline):** Urutan pemanggilan model dan fungsi telah ditentukan secara pasti di awal oleh pengembang dalam kode program.
2. **Autonomous Agent (Dynamic ReAct Loop):** Model bahasa besar bertindak sebagai perencana otonom yang secara dinamis menentukan langkah berikutnya, memilih tool, mengamati hasil, dan memutuskan kapan tugas dianggap selesai.

Tugas minggu ini adalah menganalisis kedua pendekatan untuk kasus **Briefly AI** (otomasi produksi draf media dan materi desain acara kampus) serta menentukan arsitektur mana yang paling layak dipertahankan.

---

## 2. Diagram Perbandingan Kedua Rancangan

### Rancangan A: Deterministic Pipeline Workflow (Pendekatan Terpilih)

```
[INPUT BRIEF PANITIA]
        │
        ▼
[Langkah 1: ParseBriefAction] (Deterministik / Ekstraksi Entitas)
        │ ──► [Jika Data Bolong] ──► Berhenti (Status: incomplete_data)
        ▼ [Jika Lengkap]
[Langkah 2: ExecuteRagSearchAction] (Ambil 3 Chunk Brand HMSI)
        │
        ▼
[Langkah 3: LLM Synthesis Dispatch] (Gemini 2.5 Flash, Temp: 0.2)
        │
        ▼
[Langkah 4: ValidateAiOutputAction] (Validasi Kontrak Skema JSON)
        │ ──► [Jika Gagal] ──► Heuristic Fallback Engine
        ▼ [Jika Valid]
[Langkah 5: Simpan ke DB dengan Status PENDING_REVIEW (HITL Review Gate)]
```

### Rancangan B: Autonomous Multi-Step ReAct Agent (Pendekatan Alternatif)

```
                       ┌───────────────────────────────┐
                       │   Autonomous Agent Core       │
                       │   (LLM ReAct Loop: max 10)    │
                       └──────┬─────────────────▲──────┘
                              │                 │
                1. Apa langkah│                 │ 3. Observasi
                berikutnya?   │                 │    Hasil Tool
                              ▼                 │
                       ┌────────────────────────┴──────┐
                       │     Tool Dispatcher           │
                       │ - parse_brief                 │
                       │ - search_brand_kb             │
                       │ - generate_slide_layout       │
                       │ - write_captions              │
                       │ - validate_json               │
                       └───────────────────────────────┘
```

---

## 3. Matriks Perbandingan 5 Dimensi Rekayasa

| Dimensi Penilaian | Rancangan A (Deterministic Workflow) | Rancangan B (Autonomous Agent) | Pemenang untuk Kasus Briefly AI |
|---|---|---|:---:|
| **1. Kemampuan Ditebak (Predictability)** | **Sangat Tinggi.** Urutan langkah selalu sama: Parse $\rightarrow$ Retrieve $\rightarrow$ Synthesize $\rightarrow$ Validate $\rightarrow$ HITL. | **Rendah.** Agent dapat memutuskan urutan acak (misal: mencari brand dulu sebelum mengekstrak entitas), atau mencoba tool yang tidak perlu. | **Rancangan A** |
| **2. Efisiensi Biaya Token (Cost)** | **Sangat Efisien.** Tepat 1 kali pemanggilan LLM utama per transaksi brief (~1.420 token in / ~650 token out). | **Boros.** Setiap siklus ReAct mengirim ulang seluruh riwayat percakapan. Rata-rata 4–6 kali panggilan LLM per transaksi. | **Rancangan A** (Hemat 70–80%) |
| **3. Kemudahan Pengujian (Testability)** | **Mudah.** Setiap fungsi Action dapat diuji unit test (`tests/Feature/MediaProductionSystemTest.php`) secara terisolasi. | **Sangat Sulit.** Kombinasi status percakapan dan urutan aksi terlalu banyak, sulit mereplikasi kegagalan secara konsisten. | **Rancangan A** |
| **4. Penanganan Kegagalan (Failure Handling)** | **Pasti.** Jika parsing gagal, alur langsung dialihkan ke Heuristic Fallback Engine yang aman tanpa macet. | **Rentan Infinite Loop.** Jika tool mengembalikan pesan ambigu, agent rawan memanggil tool yang sama berulang kali hingga batas langkah. | **Rancangan A** |
| **5. Kualitas Hasil Akhir (Output Quality)** | **Stabil & Terjamin.** Ketaatan terhadap 4 slide layout dan skema JSON mencapai 100% berkat validator terisolasi. | **Variatif.** Terkadang sangat kreatif, namun kerap melanggar salah satu butir skema JSON karena context drift. | **Rancangan A** |

---

## 4. Keputusan Arsitektur yang Dipilih & Rationale

**Bentuk yang Dipilih:** **Rancangan A (Deterministic Pipeline Workflow dengan Guardrails Berbasis AI).**

### Kekurangan yang Diterima Secara Sadar:
Sistem tidak memiliki fleksibilitas untuk menangani tugas di luar domain alur kerja yang sudah diprogram (misalnya: jika pengguna meminta *"carikan sponsor minuman untuk acara ini"*, sistem tidak bisa secara cerdas mencari daftar sponsor eksternal karena alur kerja tidak memiliki cabang tersebut).

### Alasan Memilih:
Tujuan organisasi kemahasiswaan membutuhkan **kecepatan, biaya murah, kepastian format, dan keandalan**. Memproduksi materi promosi kegiatan kampus memiliki prosedur baku yang sudah pasti: butuh nama acara, butuh tanggal, butuh aturan warna himpunan, dan butuh persetujuan ketua tim media. Memaksakan arsitektur autonomous agent pada prosedur yang sudah pasti adalah bentuk pemborosan komputasi (*over-engineering*) tanpa nilai tambah nyata.

---

## 5. Tantangan Wajib: Perbandingan Biaya Nyata untuk 100 Permintaan

Mengacu pada data riil pencatatan pemakaian token sistem Briefly AI (`catatan-pemakaian.md`):

| Parameter Operasional | Rancangan A (Workflow Terpilih) | Rancangan B (Autonomous Agent ReAct) |
|---|:---:|:---:|
| Rata-rata Pemanggilan LLM per Transaksi | **1 Kali** | **4,2 Kali** (Siklus ReAct) |
| Rata-rata Token Input per Transaksi | 1.420 token | 5.860 token (Akumulasi riwayat ReAct) |
| Rata-rata Token Output per Transaksi | 650 token | 1.950 token (Penalaran + Eksekusi Tool) |
| **Biaya per 1 Transaksi Brief** | **Rp 4,82** | **Rp 16,39** |
| **Biaya untuk 100 Permintaan Brief** | **Rp 482,00** | **Rp 1.639,00** |
| **Faktor Pengali Biaya** | **1.0x (Baseline Hemat)** | **3.4x Lebih Mahal** |

### Analisis Nilai Tambah vs Selisih Biaya:
Selisih biaya sebesar **3,4 kali lipat** pada Rancangan B sama sekali tidak menghasilkan peningkatan kualitas layout visual slide maupun caption Instagram. Uang tambahan tersebut hanya terbuang untuk token penalaran *"Reasoning: Saya akan memanggil tool RAG sekarang"*. Oleh karena itu, keputusan mempertahankan arsitektur Workflow Terarah adalah keputusan rekayasa yang paling rasional dan bertanggung jawab secara finansial.
