# Arsitektur Sistem Briefly AI
### Asisten Produksi Media & Desain Berbasis AI Agent
**Mata Kuliah:** Kapita Selekta: AI Engineering (Ganjil 2026/2027)  
**Konteks Akademik:** Kode Peserta K = 23 | Institut Teknologi Kalimantan  
**Penyusun:** Calvin Winata (NIM: 10241016) | Program Studi Sistem Informasi  

---

## 1. Peta Penyelarasan Kurikulum (Curriculum Alignment)

Sistem Briefly AI dirancang secara sadar untuk memenuhi Capaian Pembelajaran Lulusan (**CPL-6**) dan Capaian Pembelajaran Mata Kuliah (**CPMK-1**):

```
[CPL-6] Mampu menghasilkan rekomendasi manajemen dan SI/TI dengan mempertimbangkan
        keselarasan bisnis dan SI/TI serta mengevaluasi dampak dan penggunaannya
        untuk mencapai tujuan organisasi.
          │
          ▼
[CPMK-1] Mahasiswa mampu menerapkan teknologi mutakhir pada domain Sistem Informasi
         untuk menjawab kebutuhan organisasi, serta mengevaluasi kesesuaian, dampak,
         risiko, dan implikasi etis penerapannya secara bertanggung jawab. (C5)
          │
          ├─► Sub-CPMK-1 (Blok A - M01-03): Memahami dasar LLM, token, biaya, & model gateway.
          ├─► Sub-CPMK-2 (Blok B - M04-06): Prompt terstruktur, JSON contract schema, & tool calling.
          ├─► Sub-CPMK-3 (Blok C - M07-09): RAG Vector Retrieval berbasis 8 chunk panduan brand.
          ├─► Sub-CPMK-4 (Blok D - M10-12): Agentic ReAct cycle, multi-step orchestration, state.
          ├─► Sub-CPMK-5 (Blok E - M14-14): Evaluasi sistematis 17 kasus uji & kontrol biaya.
          └─► Sub-CPMK-6 (Blok E - M15-16): Guardrails 5 lapis, mitigasi risiko, & HITL governance.
```

---

## 2. Diagram Arsitektur Komponen & Alur Data

Sistem mengadopsi arsitektur MVC Laravel yang terdekomposisi bersih dengan modul layanan AI terisolasi:

```
┌────────────────────────────────────────────────────────────────────────┐
│                        LAPISAN PENGGUNA (ROLE)                         │
│   [Requester / Panitia]       [Lead Designer]         [Admin Media]    │
└──────────────────┬───────────────────┬──────────────────────┬──────────┘
                   │                   │                      │
                   ▼                   ▼                      ▼
┌────────────────────────────────────────────────────────────────────────┐
│                   LARAVEL HTTP & ROUTING CONTROLLERS                   │
│   BriefController            DesignerReviewController  AdminController │
│   - StoreBriefRequest        - Approve / Reject Draf   - Manage Rules  │
│   - Riwayat & Detail         - Ekspor Multi-Kanal      - User RBAC     │
└──────────────────────────────────────┬─────────────────────────────────┘
                                       │
                                       ▼
┌────────────────────────────────────────────────────────────────────────┐
│                    AI AGENT ORCHESTRATOR SUBSYSTEM                     │
│                    (app/Services/AiAgentOrchestrator.php)              │
│                                                                        │
│   ┌────────────────────────────────────────────────────────────────┐   │
│   │ 1. ParseBriefAction                                            │   │
│   │    - Ekstraksi entitas teks mentah                             │   │
│   │    - Completeness Guardrail: Cek Nama & Tanggal Acara          │   │
│   └───────────────────────────────┬────────────────────────────────┘   │
│                                   │ [Jika Lengkap]                     │
│                                   ▼                                    │
│   ┌────────────────────────────────────────────────────────────────┐   │
│   │ 2. ExecuteRagSearchAction (VectorSearchService)                │   │
│   │    - Token-based Cosine Similarity Search                      │   │
│   │    - Filter Ambang Batas: Similarity >= 0.40                   │   │
│   │    - Ambil 3 Chunk Teratas dari 8 Chunk Brand Guidelines       │   │
│   └───────────────────────────────┬────────────────────────────────┘   │
│                                   │                                    │
│                                   ▼                                    │
│   ┌────────────────────────────────────────────────────────────────┐   │
│   │ 3. GeminiApiService Dispatch (Model Gateway)                   │   │
│   │    - Model: gemini-2.5-flash (Temp: 0.2, Max Token: 2048)      │   │
│   │    - Suntik System Prompt v4.0 + Grounding + 3 Mode Nada       │   │
│   │    - Sintesis 4 Slide Layout + Instagram + WA + LinkedIn       │   │
│   └───────────────────────────────┬────────────────────────────────┘   │
│                                   │                                    │
│                                   ▼                                    │
│   ┌────────────────────────────────────────────────────────────────┐   │
│   │ 4. ValidateAiOutputAction                                      │   │
│   │    - Validasi Kontrak Skema JSON RFC 8259                      │   │
│   │    - Mekanisme Perbaikan Otomatis (Hingga 3x Retry)            │   │
│   │    - Heuristic Fallback Engine jika API/Skema Gagal            │   │
│   └───────────────────────────────┬────────────────────────────────┘   │
└───────────────────────────────────┼────────────────────────────────────┘
                                    │
                                    ▼
┌────────────────────────────────────────────────────────────────────────┐
│                 PERSISTENSI & TAHAP PERSENGKETAAN (HITL)               │
│                                                                        │
│   Basis Data SQLite:                                                   │
│   - `briefs`        : status = "completed" | "incomplete_data"         │
│   - `media_drafts`  : approval_status = "PENDING_REVIEW"               │
│   - `brand_guidelines`: 8 Vector Chunks HMSI                           │
│                                                                        │
│   [HITL Review Gate]:                                                  │
│   Draf TIDAK BOLEH dipublikasikan langsung. Lead Designer meninjau     │
│   di Review Dashboard: Approve (Rilis) atau Reject (Beri Catatan).     │
└────────────────────────────────────────────────────────────────────────┘
```

---

## 3. Realisasi Lingkup Kuantitatif Wajib ($K = 23$)

| Komponen Lingkup | Rumus Wajib | Target Minimum | Implementasi pada Briefly AI |
|---|---|:---:|---|
| **Chunk Pengetahuan RAG** | $5 + (K \bmod 4)$ | **8 Chunk** | 8 Chunk tersimpan pada `BrandGuideline.vector_metadata` dan file `brand-guidelines-8-chunks.json`. |
| **Kasus Uji Evaluasi** | $12 + (K \bmod 6)$ | **17 Kasus** | 17 kasus uji komprehensif pada `set-uji-17-kasus.json` (11 otomatis PHPUnit + 6 evaluasi manual HITL). |
| **Tools / Actions Wajib** | $2 + (K \bmod 2)$ | **3 Tools** | `ParseBriefAction`, `ExecuteRagSearchAction`, `ValidateAiOutputAction`. |
| **Mode Nada Komunikasi** | $K \bmod 3 + 1$ | **3 Mode** | `casual_energetic`, `formal_academic`, `persuasive_urgent`. |

---

## 4. Keunggulan Arsitektur: Hybrid Agentic-Workflow
Sesuai temuan evaluasi Minggu 10, Briefly AI **sengaja tidak dirancang sebagai Autonomous Agent yang sepenuhnya bebas tanpa kendali**. 

Sebaliknya, sistem memadukan:
- **Kepastian Workflow Deterministik:** Urutan fase (Parse -> Retrieve -> Synthesize -> Validate -> HITL) terkunci di kode aplikasi sehingga mudah diuji, biaya dapat diprediksi secara ketat, dan latensi terkendali.
- **Fleksibilitas Generative AI pada Titik Relevan:** Model LLM hanya dipekerjakan pada tugas ekstraksi bahasa informal dan sintesis copywriting kreatif berlandaskan sitasi brand.
- **Keamanan Lapis 5 (HITL):** Mencegah risiko salah publikasi materi promosi kampus ke ranah publik.
