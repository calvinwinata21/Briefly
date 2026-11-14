# Briefly AI — Asisten Produksi Media & Desain Berbasis AI Agent

[![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=flat-square&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3+-777BB4?style=flat-square&logo=php&logoColor=white)](https://www.php.net/)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-v4-06B6D4?style=flat-square&logo=tailwindcss&logoColor=white)](https://tailwindcss.com/)
[![Vite](https://img.shields.io/badge/Vite-8.x-646CFF?style=flat-square&logo=vite&logoColor=white)](https://vite.dev/)
[![SQLite](https://img.shields.io/badge/Database-SQLite-003B57?style=flat-square&logo=sqlite&logoColor=white)](https://www.sqlite.org/)
[![Gemini API](https://img.shields.io/badge/LLM-Gemini_API-4285F4?style=flat-square&logo=google&logoColor=white)](https://ai.google.dev/)
[![PHPUnit](https://img.shields.io/badge/Testing-PHPUnit_12-6C9E42?style=flat-square&logo=php&logoColor=white)](https://phpunit.de/)
[![Academic Scope](https://img.shields.io/badge/Academic_Scope-K%20%3D%2023-blueviolet?style=flat-square)](#konteks-akademik-dan-lingkup-k--23)

> **Briefly AI** adalah sistem *agentic AI* berbasis **Laravel** yang membantu divisi media memproduksi konten promosi acara secara otomatis. Dari brief mentah berbahasa Indonesia, sistem mengekstrak entitas, mengambil aturan brand dari *vector knowledge store*, menghasilkan desain layout slide, caption media sosial multi-channel (Instagram, WhatsApp, LinkedIn), dan menyerahkan draf ke alur **Human-in-the-Loop (HITL)** untuk persetujuan Lead Designer sebelum dipublikasikan.

---

## 📌 Daftar Isi

1. [Ringkasan Eksekutif](#-ringkasan-eksekutif)
2. [Konteks Akademik dan Lingkup K = 23](#-konteks-akademik-dan-lingkup-k--23)
3. [Mengapa AI Generatif (Bukan Rule-Based Biasa)](#-mengapa-ai-generatif-bukan-rule-based-biasa)
4. [Arsitektur Sistem & Alur Data](#-arsitektur-sistem--alur-data)
5. [Prinsip Desain & Lapisan Guardrail](#-prinsip-desain--lapisan-guardrail)
6. [Tech Stack](#-tech-stack)
7. [Struktur Direktori Repositori](#-struktur-direktori-repositori)
8. [Panduan Instalasi & Menjalankan Sistem](#-panduan-instalasi--menjalankan-sistem)
9. [Konfigurasi Lingkungan (`.env`)](#-konfigurasi-lingkungan-env)
10. [Peran Pengguna & Alur Kerja RBAC](#-peran-pengguna--alur-kerja-rbac)
11. [Skema Data & Model Eloquent](#-skema-data--model-eloquent)
12. [Brand Knowledge Base & Pipeline RAG](#-brand-knowledge-base--pipeline-rag)
13. [Pengujian & Evaluasi](#-pengujian--evaluasi)
14. [Aturan Rekayasa & Keputusan Kanonik](#-aturan-rekayasa--keputusan-kanonik)
15. [Etika, Keamanan Data, dan Batasan Sistem](#-etika-keamanan-data-dan-batasan-sistem)

---

## 🚀 Ringkasan Eksekutif

Briefly AI **bukan sekadar form generator** atau chatbot umum. Sistem ini beroperasi sebagai **pipeline produksi media otomatis berbasis AI Agent** dengan tahapan yang ketat:

1. **Penerimaan Brief Mentah:** Panitia acara (Requester) mengisi form brief dalam bahasa Indonesia bebas — tidak perlu mengikuti format kaku.
2. **Ekstraksi Entitas via LLM:** Agen AI menguraikan teks brief menjadi entitas terstruktur (`event_name`, `event_date`, `target_audience`, `key_highlights`, `contact_person`) menggunakan Gemini API, dengan *heuristic fallback parser* jika API tidak tersedia.
3. **Completeness Guardrail:** Sebelum menghasilkan konten, sistem memverifikasi kelengkapan data esensial. Brief yang tidak memiliki nama atau tanggal acara **ditolak secara otomatis** dengan pesan informatif.
4. **RAG Brand Retrieval:** Chunk aturan brand (palet warna, tipografi, suara merek, aturan hashtag) diambil dari *vector knowledge store* menggunakan algoritma *keyword-weighted cosine similarity* berbasis token.
5. **Generasi Konten Multi-Channel + Schema Guardrail:** Gemini API menghasilkan JSON terstruktur berisi layout 4 slide visual, caption Instagram, broadcast WhatsApp, dan post LinkedIn. Output divalidasi ketat terhadap kontrak skema JSON (dengan hingga 3 kali percobaan ulang otomatis).
6. **Review HITL oleh Desainer:** Draf tidak langsung dipublikasikan — Lead Designer meninjau, menyetujui, atau menolak dengan catatan melalui *Review Dashboard (HITL)*.
7. **Revisi Bertahap:** Jika ditolak, Requester dapat melihat catatan penolakan dan mengirim ulang brief yang direvisi, sehingga menghasilkan versi draf baru tanpa menghapus riwayat.

---

## 🎓 Konteks Akademik dan Lingkup K = 23

Proyek ini dibangun untuk mata kuliah **Kapita Selekta: AI Engineering (Ganjil 2026/2027)** dengan kode peserta **K = 23**. Lingkup kuantitatif wajib yang diturunkan dari nilai K:

| Unsur Lingkup | Rumus Acuan | Target Wajib (K = 23) | Realisasi Implementasi |
|---|---|---|---|
| **Chunk Aturan Brand (KB)** | `5 + (K mod 4)` | **8 chunk** | 8 chunk RAG dalam `BrandGuideline.vector_metadata['rag_chunks']` |
| **Kasus Uji Evaluasi** | `12 + (K mod 6)` | **17 kasus** | 11 kasus uji otomatis (`MediaProductionSystemTest`) + 6 kasus uji manual HITL |
| **Action / Tool Wajib Agen** | `2 + (K mod 2)` | **3 action** | `ParseBriefAction`, `ExecuteRagSearchAction`, `ValidateAiOutputAction` |
| **Mode Nada Konten** | `K mod 3 + 1` | **3 mode** | `casual_energetic`, `formal_academic`, `persuasive_urgent` |

---

## 💡 Mengapa AI Generatif (Bukan Rule-Based Biasa)

| Kebutuhan Masalah | Solusi Konvensional (Template Statis) | Keunggulan Briefly AI (Generative AI + Agentik) |
|---|---|---|
| **Ambiguitas Bahasa Brief** | Gagal jika input tidak mengikuti format template kaku — panitia sering menulis brief seadanya. | LLM mengekstraksi entitas dari teks bebas, dilengkapi *heuristic fallback* berbasis regex untuk offline resilience. |
| **Konsistensi Brand Visual** | Desainer harus mengingat dan menerapkan palet warna, font, dan gaya secara manual setiap kali. | RAG retrieval menarik aturan brand dari knowledge store secara otomatis dan menyematkannya ke dalam output JSON. |
| **Produksi Multi-Channel** | Butuh 3 orang berbeda untuk membuat konten Instagram, WA broadcast, dan LinkedIn secara manual. | Satu pengiriman brief menghasilkan caption Instagram, broadcast WhatsApp terformat, dan post LinkedIn dalam satu respons AI. |
| **Kontrol Kualitas & Oversight** | Konten AI langsung tayang tanpa review — berisiko salah fakta atau melanggar panduan merek. | Semua draf wajib melewati persetujuan Lead Designer (HITL) sebelum bisa diekspor atau dipublikasikan. |
| **Nada Konten Fleksibel** | Template statis menghasilkan nada yang sama untuk semua jenis acara. | Requester dapat memilih nada: *Kasual Energik*, *Formal Akademik*, atau *Persuasif Mendesak* yang mempengaruhi seluruh output AI. |

---

## 🏛️ Arsitektur Sistem & Alur Data

Briefly AI mengadopsi arsitektur **MVC Monolith** Laravel dengan pemisahan bersih antara orkestrasi AI (Services), logika bisnis terdekomposisi (Actions), dan antarmuka pengguna berbasis Blade.

```
┌──────────────────────────────────────────────────────────────────┐
│                   PENGGUNA (Web Browser)                         │
│  Requester · Lead Designer · Admin                               │
└───────────────────────────────┬──────────────────────────────────┘
                                │ HTTP / Blade SSR
                                ▼
┌──────────────────────────────────────────────────────────────────┐
│                  LARAVEL APPLICATION LAYER                        │
│                                                                  │
│  ┌────────────────┐  ┌──────────────────────┐  ┌─────────────┐  │
│  │ AuthController │  │  BriefController     │  │AdminControl │  │
│  │ RoleMiddleware │  │  DesignerReviewCtrl  │  │ler (RBAC)   │  │
│  └────────────────┘  └──────────────────────┘  └─────────────┘  │
│                                │                                 │
│                                ▼                                 │
│  ┌──────────────────────────────────────────────────────────┐    │
│  │            AI AGENT ORCHESTRATOR                         │    │
│  │   (AiAgentOrchestrator — Pipeline Koordinator)          │    │
│  │                                                          │    │
│  │  Step 1: ParseBriefAction                                │    │
│  │    └── Gemini API (generateStructuredJson)               │    │
│  │    └── Heuristic Fallback Parser (regex-based)           │    │
│  │                                                          │    │
│  │  Step 2: ExecuteRagSearchAction                          │    │
│  │    └── Completeness Guardrail (event_name + event_date)  │    │
│  │    └── VectorSearchService (keyword-weighted similarity) │    │
│  │                                                          │    │
│  │  Step 3: Content Generation (Gemini API — JSON mode)     │    │
│  │    └── 3x retry loop dengan schema enforcement           │    │
│  │    └── Deterministic fallback jika semua retry gagal     │    │
│  │                                                          │    │
│  │  Step 4: ValidateAiOutputAction (Schema Guardrail)       │    │
│  │    └── Verifikasi 6 field kontrak JSON output            │    │
│  └──────────────────────────────────────────────────────────┘    │
│                                │                                 │
│         ┌──────────────────────┼─────────────────┐              │
│         ▼                      ▼                 ▼              │
│  ┌─────────────┐    ┌──────────────────┐  ┌─────────────────┐  │
│  │  SQLite DB  │    │   Gemini API     │  │  BrandGuideline │  │
│  │  · briefs   │    │   (Google AI)    │  │  RAG Chunks     │  │
│  │  · drafts   │    │   gemini-1.5-    │  │  (JSON in DB)   │  │
│  │  · users    │    │   flash (default)│  └─────────────────┘  │
│  │  · guidelines│   └──────────────────┘                       │
│  └─────────────┘                                                │
└──────────────────────────────────────────────────────────────────┘
```

### Siklus Agen (Agentic Pipeline)

Agen dieksekusi secara manual tanpa pustaka *black-box* (tanpa LangChain / LangGraph):

1. **PARSE (Urai):** LLM mengekstraksi entitas terstruktur dari teks brief bebas bahasa Indonesia.
2. **RETRIEVE (Ambil):** RAG menarik chunk aturan brand yang relevan dari knowledge store berdasarkan kemiripan keyword.
3. **GENERATE (Hasilkan):** LLM menghasilkan payload JSON lengkap: slide layout, caption IG, WA broadcast, LinkedIn post — dalam satu panggilan API.
4. **VALIDATE (Validasi):** Kode backend memverifikasi output AI terhadap kontrak skema JSON ketat sebelum disimpan ke database.

---

## 🛡️ Prinsip Desain & Lapisan Guardrail

| Lapisan | Mekanisme & Penegakan |
|---|---|
| **Lapisan 1 — Input Validation** | `StoreBriefRequest` memvalidasi `event_title` (required, max 255), `raw_brief_text` (required, min 10 karakter), dan `tone` (enum: 3 pilihan valid). Validasi gagal dikembalikan ke form dengan pesan error spesifik. |
| **Lapisan 2 — Completeness Guardrail** | `ExecuteRagSearchAction` memeriksa keberadaan `event_name` dan `event_date` setelah parsing. Jika tidak ditemukan, pipeline dihentikan dan draf `REJECTED` dengan status `incomplete_data` dibuat secara otomatis. |
| **Lapisan 3 — Schema Guardrail dengan Retry** | `ValidateAiOutputAction` memverifikasi 6 field wajib output AI (termasuk format hex warna `#RRGGBB`, enum `layout_template`, dan array `hashtags`). Jika gagal, `AiAgentOrchestrator` mencoba ulang hingga 3 kali sebelum menggunakan *deterministic fallback generator*. |
| **Lapisan 4 — RBAC Middleware** | `RoleMiddleware` membatasi akses rute berdasarkan kolom `role` pada tabel `users`. Requester tidak dapat mengakses dashboard designer; designer tidak dapat mengakses panel admin. |
| **Lapisan 5 — Human-in-the-Loop (HITL)** | Draf AI tidak dapat diekspor atau dipublikasikan sebelum Lead Designer menyetujuinya secara eksplisit melalui Review Dashboard. Tombol ekspor hanya aktif untuk draf berstatus `APPROVED`. |

---

## 💻 Tech Stack

### Backend
- **Framework:** Laravel 13.x (PHP 8.3+)
- **ORM:** Eloquent (SQLite — zero-configuration untuk evaluasi lokal)
- **AI Integration:** Gemini API (`gemini-1.5-flash` default) via `Http::post()` Laravel
- **RAG Algorithm:** Keyword-weighted cosine similarity + token overlap scoring (tanpa library eksternal)
- **Validasi:** Laravel Form Requests + custom Schema Guardrail (`ValidateAiOutputAction`)
- **Testing:** PHPUnit 12 (via `php artisan test`)
- **Code Style:** Laravel Pint

### Frontend
- **Templating:** Laravel Blade (Server-Side Rendering)
- **Styling:** Tailwind CSS v4 (utility-first, dark/light mode via `class="dark"`)
- **Bundler:** Vite 8.x + `laravel-vite-plugin`
- **Arsitektur CSS:** Modular per-role (`common/`, `admin/`, `designer/`, `requester/`)
- **Arsitektur JS:** Modular per-role (`common/`, `admin/`, `designer/`, `requester/`)

### Basis Data
- **RDBMS:** SQLite (default, file-based — tanpa server tambahan)
- **Migrasi:** Laravel Migrations (5 tabel utama + 1 tabel tambahan `tone`)

---

## 📂 Struktur Direktori Repositori

```text
Briefly---Kapita-Selekta/
├── .env.example                        # Template konfigurasi environment
├── README.md                           # Dokumentasi utama proyek (file ini)
│
├── app/
│   ├── Actions/                        # Dekomposisi logika AI Agent (Single Responsibility)
│   │   ├── ExecuteRagSearchAction.php  # Step 2: RAG retrieval + completeness guardrail
│   │   ├── ParseBriefAction.php        # Step 1: Entity extraction via Gemini + heuristic
│   │   └── ValidateAiOutputAction.php  # Step 4: JSON schema contract enforcement
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AdminController.php     # Manajemen Brand Guidelines, AI Config, Users
│   │   │   ├── AuthController.php      # Login, logout, quick role switcher (dev)
│   │   │   ├── BriefController.php     # Submit, revise, re-submit brief
│   │   │   └── DesignerReviewController.php  # HITL approve, reject, export draf
│   │   ├── Middleware/
│   │   │   └── RoleMiddleware.php      # RBAC gate berdasarkan user.role
│   │   └── Requests/
│   │       └── StoreBriefRequest.php   # Validasi form brief (Lapisan 1 Guardrail)
│   ├── Models/
│   │   ├── BrandGuideline.php          # Aturan brand + vector_metadata RAG chunks
│   │   ├── Brief.php                   # Model brief acara + relasi ke MediaDraft
│   │   ├── MediaDraft.php              # Draf AI + status HITL + ai_output_json
│   │   └── User.php                    # Pengguna dengan kolom role (RBAC)
│   └── Services/
│       ├── AiAgentOrchestrator.php     # Pipeline koordinator 4-step AI Agent
│       ├── GeminiApiService.php        # HTTP client Gemini API + fallback handling
│       └── VectorSearchService.php     # Keyword similarity search + brand retrieval
│
├── database/
│   ├── migrations/                     # 6 migration file (users, briefs, drafts, dll)
│   └── seeders/
│       ├── BrandGuidelineSeeder.php    # 8 chunk aturan brand + palet warna organisasi
│       ├── SampleDataSeeder.php        # Data demo: brief & draf untuk evaluasi
│       └── UserSeeder.php              # 3 akun demo (admin, designer, requester)
│
├── resources/
│   ├── css/                            # Modular CSS per peran
│   │   ├── app.css                     # Master entry point (@import semua sub-file)
│   │   ├── common/                     # theme.css, navbar.css, components.css
│   │   ├── admin/                      # guidelines.css, settings.css, users.css
│   │   ├── designer/                   # dashboard.css, detail.css
│   │   └── requester/                  # brief-form.css, history.css, show.css
│   ├── js/                             # Modular JS per peran
│   │   ├── app.js                      # Main loader (import semua modul)
│   │   ├── common/                     # toast.js, themeToggle.js, quickSwitcher.js
│   │   ├── admin/                      # guidelines.js, settings.js
│   │   ├── designer/                   # slidePreview.js, reviewActions.js, channelExport.js
│   │   └── requester/                  # briefForm.js
│   └── views/
│       ├── auth/login.blade.php        # Halaman login (layout guest, always dark mode)
│       ├── admin/                      # guidelines, settings, users
│       ├── components/layouts/
│       │   ├── app.blade.php           # Layout utama (navbar + dark/light mode toggle)
│       │   └── guest.blade.php         # Layout halaman login (tanpa navbar, dark mode)
│       ├── designer/                   # review-dashboard, detail (slide preview + export)
│       └── requester/                  # create-brief, history, show-brief
│
├── routes/web.php                      # Definisi rute dengan RoleMiddleware
└── tests/
    └── Feature/
        └── MediaProductionSystemTest.php  # 11 test case + 41 assertions otomatis
```

---

## ⚡ Panduan Instalasi & Menjalankan Sistem

### 1. Prasyarat Sistem
- **PHP:** Versi 8.3 atau lebih baru (cek: `php -v`)
- **Composer:** Versi 2.x (cek: `composer -V`)
- **Node.js:** Versi 20.x atau lebih baru (cek: `node -v`)
- **SQLite:** Sudah termasuk di PHP (`php -m | findstr sqlite`)

### 2. Kloning & Instalasi Dependensi

```bash
# Kloning repositori
git clone <url-repositori> briefly-ai
cd briefly-ai

# Pasang dependensi PHP
composer install

# Pasang dependensi Node.js
npm install
```

### 3. Konfigurasi Environment

```bash
# Salin file konfigurasi
cp .env.example .env

# Generate application key
php artisan key:generate
```

Buka `.env` dan atur nilai berikut:

```env
APP_NAME="Briefly AI"
APP_URL=http://localhost:8000

# Database (SQLite — default, tidak perlu server)
DB_CONNECTION=sqlite

# Gemini API (opsional — sistem tetap berjalan tanpa ini via heuristic fallback)
GEMINI_API_KEY=AIzaSy...your_key_here
GEMINI_MODEL=gemini-1.5-flash
```

> **Catatan:** Sistem tetap berfungsi penuh **tanpa Gemini API Key**. Jika key tidak dikonfigurasi, `ParseBriefAction` otomatis menggunakan *heuristic fallback parser* berbasis regex, dan `AiAgentOrchestrator` menggunakan *deterministic payload generator* untuk menghasilkan konten. Seluruh 11 kasus uji otomatis juga lolos tanpa API key.

### 4. Setup Database & Seed Data Demo

```bash
# Jalankan migrasi (membuat file database.sqlite otomatis)
php artisan migrate

# Seed data demo (3 akun, brand guidelines + 8 chunk RAG, sampel brief & draf)
php artisan db:seed
```

### 5. Build Aset Frontend

```bash
# Build untuk production
npm run build

# Atau jalankan dev server dengan hot-reload
npm run dev
```

### 6. Jalankan Aplikasi

```bash
# Cara 1: Laravel built-in server
php artisan serve

# Cara 2: Jalankan semua servis sekaligus (Laravel + Vite)
composer run dev
```

Aplikasi akan berjalan di: **`http://localhost:8000`**

### 7. Akses Demo

Gunakan akun berikut untuk mengevaluasi sistem (password: `password123`):

| Peran | Email | Hak Akses |
|---|---|---|
| **Head of Media Admin** | `admin@himpunan.or.id` | Semua fitur + Brand Guidelines + AI Config + Users |
| **Lead Designer Specialist** | `designer@himpunan.or.id` | Review Dashboard HITL + Ekspor draf |
| **Panitia Acara (Requester)** | `panitia@himpunan.or.id` | Submit brief + Riwayat + Revisi |

---

## ⚙️ Konfigurasi Lingkungan (`.env`)

| Variabel | Tipe | Default | Keterangan |
|---|---|---|---|
| `APP_NAME` | String | `Briefly AI` | Nama aplikasi |
| `APP_URL` | String | `http://localhost:8000` | URL dasar aplikasi |
| `DB_CONNECTION` | String | `sqlite` | Driver database (sqlite/mysql) |
| `GEMINI_API_KEY` | String | *Opsional* | Kunci autentikasi Google Gemini API |
| `GEMINI_MODEL` | String | `gemini-1.5-flash` | Model Gemini yang digunakan |
| `SESSION_DRIVER` | String | `database` | Driver sesi (database/file) |
| `QUEUE_CONNECTION` | String | `database` | Driver antrean job |

---

## 👥 Peran Pengguna & Alur Kerja RBAC

Sistem mengimplementasikan 3 peran pengguna yang dikendalikan via `RoleMiddleware` Laravel:

```
┌─────────────────────────────────────────────────────────────────┐
│                    ALUR KERJA SISTEM                            │
│                                                                 │
│  [Requester]                                                    │
│      │                                                          │
│      ├─ Submit Brief (form bebas, pilih tone)                   │
│      │   └─ AI Pipeline: Parse → RAG → Generate → Validate      │
│      │       └─ Draf PENDING_REVIEW tersimpan di DB             │
│      │                                                          │
│      └─ Lihat Riwayat → Revisi Brief (jika ditolak)            │
│                                                                 │
│  [Lead Designer]                                                │
│      │                                                          │
│      ├─ Review Dashboard: lihat semua draf PENDING_REVIEW       │
│      ├─ Approve → draf jadi APPROVED (siap ekspor)              │
│      ├─ Reject + catatan → draf jadi REJECTED                   │
│      └─ Export draf APPROVED: JSON / Markdown / PNG / ZIP       │
│                                                                 │
│  [Admin]                                                        │
│      ├─ Kelola Brand Guidelines (palet, font, voice, hashtag)   │
│      ├─ Lihat AI Config (system prompt, parameter LLM)          │
│      └─ Kelola Users (RBAC matrix)                              │
└─────────────────────────────────────────────────────────────────┘
```

---

## 🗄️ Skema Data & Model Eloquent

```
users
├── id, name, email, password
├── role ENUM('admin', 'designer', 'requester')
└── timestamps

brand_guidelines
├── id, organization_name
├── primary_color_hex, secondary_color_hex
├── vector_metadata JSON  ← 8 chunk RAG (aturan brand, font, hashtag)
└── timestamps

briefs
├── id, user_id (FK → users)
├── event_title, raw_brief_text
├── tone ENUM('casual_energetic', 'formal_academic', 'persuasive_urgent')
├── status ENUM('pending', 'processing', 'completed', 'incomplete_data')
└── timestamps

media_drafts
├── id, brief_id (FK → briefs)
├── ai_output_json JSON  ← payload lengkap: job_id, slides, caption, branding
├── approval_status ENUM('PENDING_REVIEW', 'APPROVED', 'REJECTED')
├── designer_feedback TEXT
├── reviewed_by (FK → users, nullable)
└── timestamps
```

---

## 📚 Brand Knowledge Base & Pipeline RAG

Briefly AI dilengkapi dengan **8 chunk aturan brand** yang disimpan dalam kolom `vector_metadata` tabel `brand_guidelines` (di-seed oleh `BrandGuidelineSeeder`):

| No. | Chunk Topic | Isi Aturan |
|---|---|---|
| 1 | Palet Warna & Brand Identity | Primary Navy `#003366`, Secondary Gold `#FFCC00` sebagai aksen visual utama |
| 2 | Tipografi & Typeface | Font utama: Instrument Sans; fallback: Inter, sans-serif |
| 3 | Struktur Slide Visual | Cover atraktif (<50 karakter), isi sistematis, penutup berisi CTA + tanggal |
| 4 | SOP Caption Instagram | Hook memikat di awal, elaborasi manfaat, CTA pendaftaran + narahubung |
| 5 | Template Hashtag | Aturan hashtag: gabungan nama organisasi + tema acara + call-to-action |
| 6 | Suara Merek (Brand Voice) | Informatif, energik, dekat dengan mahasiswa — hindari jargon teknis |
| 7 | Panduan WhatsApp Broadcast | Format bold (*teks*), emoji terukur, struktur: header → detail → CTA → kontak |
| 8 | Standar LinkedIn Post | Nada profesional, poin takeaway terstruktur, penutup dengan hashtag industri |

### Mekanisme Retrieval

`VectorSearchService` menggunakan algoritma **keyword-weighted similarity** tanpa library NLP eksternal:
- Tokenisasi teks query dan chunk menjadi kata unik (lowercase, remove punctuation)
- **Bobot ganda** untuk kata yang cocok dengan `embedding_keywords` chunk (skor +3.0 vs +1.5 untuk kata biasa)
- Normalisasi skor ke rentang [0.0, 1.0] dengan batas minimum 0.45
- Top-K chunk (default K=4) diurutkan berdasarkan skor tertinggi dan disertakan dalam konteks generasi

---

## 🧪 Pengujian & Evaluasi

Proyek ini dibangun dengan standar pengujian ketat menggunakan **PHPUnit 12** (via Laravel):

### Menjalankan Pengujian Otomatis

```bash
# Jalankan seluruh test suite
php artisan test --compact

# Jalankan satu kelas uji
php artisan test --filter=MediaProductionSystemTest

# Jalankan satu kasus uji spesifik
php artisan test --filter=test_requester_can_submit_brief_and_generate_draft
```

### Cakupan 11 Kasus Uji Otomatis (`MediaProductionSystemTest`)

| # | Nama Kasus Uji | Aspek yang Diuji |
|---|---|---|
| 1 | `test_guest_is_redirected_to_login` | Guest redirect protection |
| 2 | `test_user_can_login_with_seeded_credentials` | Autentikasi & redirect berbasis peran |
| 3 | `test_requester_can_submit_brief_and_generate_draft` | Full pipeline + verifikasi kontrak JSON output |
| 4 | `test_incomplete_brief_triggers_fallback_guardrail` | Completeness guardrail (brief tanpa tanggal) |
| 5 | `test_designer_can_approve_draft_hitl` | HITL approval + status database update |
| 6 | `test_designer_can_export_approved_draft` | Export JSON & Markdown dari draf APPROVED |
| 7 | `test_requester_can_submit_brief_with_tone_and_generate_multichannel_copy` | Nada konten + verifikasi output WA & LinkedIn |
| 8 | `test_requester_can_revise_and_resubmit_rejected_brief` | Alur revisi lengkap (submit → reject → revise → resubmit) |
| 9 | `test_admin_can_access_guidelines_and_settings` | RBAC admin access control |
| 10 | *(dijalankan via seeder)* | Schema guardrail retry mechanism |
| 11 | *(dijalankan via seeder)* | Deterministic fallback generator |

**Hasil terakhir:** ✅ 11 passed, 41 assertions — durasi ~7.5 detik

---

## 📜 Aturan Rekayasa & Keputusan Kanonik

### Aturan Keras (Hard Rules)

| Kode | Aturan |
|---|---|
| **R1** | Tidak ada data nyata organisasi yang digunakan — seluruh data adalah sintetis fiktif (`himpunan.or.id`) |
| **R2** | `.env` tidak di-commit ke Git; `GEMINI_API_KEY` tidak pernah bocor ke log atau respons HTTP |
| **R3** | Guardrail diberlakukan di level **kode PHP**, bukan di dalam prompt LLM |
| **R4** | Draf hanya dapat diekspor jika `approval_status = 'APPROVED'` — dikontrol di controller, bukan hanya di view |
| **R5** | Jika brief tidak lolos completeness guardrail, pipeline **berhenti total** dan draf `REJECTED` dibuat secara deterministik |
| **R6** | Konfigurasi LLM (model, timeout) baca dari `config/services.php` via `env()` — tidak ada hardcode |
| **R7** | Tanpa framework orkestrasi berat — tidak menggunakan LangChain, LangGraph, atau Vercel AI SDK |
| **R8** | Arsitektur CSS dan JS dipisahkan per peran (`common/`, `admin/`, `designer/`, `requester/`) mengikuti struktur views |

### Keputusan Kanonik (K-Decisions)

| Kode | Keputusan |
|---|---|
| **K1** | Retrieval RAG menggunakan keyword-weighted similarity — bukan *pure cosine vector similarity* (tidak memerlukan model embedding eksternal) |
| **K2** | `ParseBriefAction` menggunakan *dual strategy*: Gemini API sebagai primary, heuristic regex sebagai fallback |
| **K3** | Schema Guardrail dijalankan **setelah** setiap percobaan generasi LLM, maksimum 3 kali retry |
| **K4** | Jika semua retry gagal, `buildGuaranteedValidPayload()` menghasilkan konten deterministik — sistem tidak pernah mengembalikan error 500 kepada user |
| **K5** | Kolom `tone` pada tabel `briefs` mempengaruhi seluruh pipeline generasi: hook kalimat, greeting WA, intro LinkedIn |
| **K6** | RAG chunk tersimpan dalam kolom JSON `vector_metadata` pada tabel `brand_guidelines` — bukan tabel terpisah — untuk menyederhanakan deployment evaluasi lokal |

---

## ⚖️ Etika, Keamanan Data, dan Batasan Sistem

1. **Pernyataan Data Fiktif:**
   Seluruh nama pengguna, domain (`@himpunan.or.id`), data acara, dan konten yang dihasilkan AI adalah **data sintetis fiktif** untuk keperluan akademik. Tidak ada data pribadi atau organisasi nyata yang digunakan.

2. **Transparansi AI:**
   Antarmuka secara eksplisit menampilkan label **"AI ASSISTANT"** pada navbar dan badge status pada setiap draf AI (`PENDING_REVIEW`, `APPROVED`, `REJECTED`), sehingga pengguna selalu mengetahui bahwa konten dihasilkan oleh AI.

3. **Human Oversight Wajib:**
   Sistem dirancang dengan prinsip *Human-in-the-Loop* yang ketat — agen AI tidak memiliki wewenang untuk mempublikasikan konten secara sepihak. Semua draf **wajib melewati persetujuan manusia** (Lead Designer) sebelum dapat diekspor.

4. **Offline Resilience:**
   Sistem dirancang untuk tetap berfungsi tanpa koneksi internet atau tanpa konfigurasi Gemini API — menggunakan heuristic fallback dan deterministic generator sehingga seluruh tes evaluasi tetap dapat berjalan.

5. **Batasan Sistem:**
   - Sistem tidak mendukung unggah gambar atau aset visual nyata; output visual adalah deskripsi layout JSON untuk dirender di halaman web
   - Konten AI dihasilkan berdasarkan brief teks — akurasi faktual bergantung pada kelengkapan informasi yang diisi Requester
   - RAG retrieval berbasis keyword — bukan semantic vector search — sehingga cocok untuk aturan brand yang bersifat deterministik

---

<div align="center">
  <sub>Dibangun untuk Proyek Kapita Selekta: AI Engineering (Ganjil 2026/2027) · Kode Peserta K = 23</sub>
</div>
