# Trace Eksekusi Agent — 03: Penanganan Batas Langkah (Step Limit & Safe Fallback)
**Mata Kuliah:** Kapita Selekta: AI Engineering  
**Tahap Modul:** Minggu 11 (Blok D — Tool, Memory, dan Mengatur Tugas Banyak Langkah)  
**Produk:** Briefly AI — Asisten Produksi Media & Desain Acara  
**Kasus:** Simulasi Kegagalan Model Menghasilkan JSON Rusak (Syntax Error / Truncated)  
**Status:** STEP_LIMIT_REACHED -> GRACEFUL HEURISTIC FALLBACK ACTIVATED  

---

## Log Jejak Eksekusi (Max Retry & Fallback Trace)

```
[TIMESTAMP: 2026-11-20T11:45:22.010Z] [JOB_ID: job-9x8y7z6w-23]
[USER_ROLE: requester] [SELECTED_TONE: formal_academic]

=== LANGKAH 1: PARSING BRIEF & RAG RETRIEVAL (BERHASIL) ===
- ParseBriefAction: OK (Event: "Simposium Riset SI 2027", Date: "14 Feb 2027")
- ExecuteRagSearchAction: OK (Retrieved Chunk 1, 3, 7)
- Menyiapkan payload untuk LLM Synthesis.

=== LANGKAH 2: GENERASI KONTEN (PERCOBAAN 1) ===
[ACTION / MODEL DISPATCH]:
Model: gemini-2.5-flash
Output: String JSON terpotong di tengah jalan karena gangguan jaringan penyedia model:
`{"job_id": "job-9x8y7z6w", "event_metadata": {"name": "Simposium Riset SI 2027", "date": "14 Feb 2027", "target": "Dosen & Mahasiswa"` ... [TRUNCATED]

[VALIDASI KONTRAK]:
Tool: App\Actions\ValidateAiOutputAction
Hasil: INVALID JSON (SyntaxError: Unexpected end of JSON input).
Status Percobaan: 1 / 3 GAGAL.

=== LANGKAH 3: REPROMPTING TERARAH (PERCOBAAN 2) ===
[THINK / REASON]:
Percobaan 1 menghasilkan JSON tidak lengkap. Kirim ulang prompt dengan penegasan:
"Output sebelumnya terpotong. Hasilkan JSON LENGKAP dengan batas 4 slide dan penutup brace }."

[ACTION / MODEL DISPATCH]:
Model: gemini-2.5-flash (Retry 1)
Output: Model mengembalikan teks diawali kalimat pengantar:
`Tentu, berikut JSON yang Anda minta: { "job_id": ... , "visual_slides_layout": [ ... trailing comma , ] }`

[VALIDASI KONTRAK]:
Tool: App\Actions\ValidateAiOutputAction
Hasil: INVALID SCHEMA (Trailing comma pada array slide).
Status Percobaan: 2 / 3 GAGAL.

=== LANGKAH 4: REPROMPTING KETAT TERAKHIR (PERCOBAAN 3) ===
[THINK / REASON]:
Percobaan 2 gagal karena sintaks trailing comma. Kirim satu kali lagi dengan instruksi zero-temperature:
"HANYA hasilkan raw JSON valid RFC 8259 tanpa trailing comma."

[ACTION / MODEL DISPATCH]:
Model: gemini-2.5-flash (Retry 2)
Output: Model timeout / tidak merespon dalam 10 detik.

[VALIDASI KONTRAK]:
Batas percobaan (Max Retries = 3) TERCAPAI.

=== AKTIVASI HEURISTIC FALLBACK (SAFE RECOVERY) ===
[THINK / REASON]:
Batas langkah pemanggilan AI tercapai. Sesuai prinsip keandalan AI Engineering:
1. JANGAN biarkan sistem macet dalam perulangan tak berujung (infinite loop).
2. JANGAN lemparkan crash HTTP 500 ke pengguna.
3. Alihkan ke mesin cadangan deterministik: Heuristic Fallback Engine.

[ACTION / FALLBACK DISPATCH]:
Heuristic Fallback Engine menyusun draf darurat dari entitas brief yang sudah tersimpan:
- Mengambil template slide standar 4 langkah HMSI.
- Mengisi warna default resmi: Navy `#0F172A` dan Royal Blue `#3B82F6` (dari Chunk 1).
- Menyusun teks caption standar Instagram dan WhatsApp broadcast.
- Menyematkan flag audit: `telemetry.fallback_activated = true`.

[PERSISTENCE]:
Tabel `media_drafts` tersimpan dengan:
- `approval_status`: "PENDING_REVIEW"
- `ai_output_json.is_fallback`: true
- `designer_feedback_warning`: "PERINGATAN: Draf ini disusun melalui Heuristic Fallback karena model AI gagal memenuhi kontrak format setelah 3 kali percobaan."

=== KONDISI BERHENTI (TERMINATION) ===
Status akhir: SELESAI MELALUI CADANGAN AMAN (FALLBACK COMPLETED).
Lead Designer dapat melihat draf dan melakukan revisi manual di dashboard review tanpa panitia kehilangan draf acaranya.
```
