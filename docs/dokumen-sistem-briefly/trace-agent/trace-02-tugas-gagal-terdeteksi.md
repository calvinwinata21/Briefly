# Trace Eksekusi Agent — 02: Tugas Gagal Terdeteksi (Fail-Fast Guardrail)
**Mata Kuliah:** Kapita Selekta: AI Engineering  
**Tahap Modul:** Minggu 11 (Blok D — Tool, Memory, dan Mengatur Tugas Banyak Langkah)  
**Produk:** Briefly AI — Asisten Produksi Media & Desain Acara  
**Kasus:** TC-12 — Workshop Flutter Tanpa Tanggal Pelaksanaan  
**Status:** BLOCKED / INCOMPLETE_DATA (Dihentikan secara aman)  

---

## Log Jejak Eksekusi (Fail-Fast Termination Trace)

```
[TIMESTAMP: 2026-11-20T10:02:15.450Z] [JOB_ID: job-3a4b5c6d-23]
[USER_ROLE: requester] [SELECTED_TONE: casual_energetic]

=== LANGKAH 1: PARSING & ENTITY EXTRACTION ===
[THINK / REASON]:
Menerima brief teks mentah dari panitia. Tugas awal adalah ekstraksi entitas dan verifikasi kelengkapan minimum (apakah event_name dan event_date hadir).

[ACTION / TOOL CALL]:
Tool: App\Actions\ParseBriefAction
Arguments: {
  "raw_brief_text": "Workshop Flutter Mobile App Development bersama expert engineer. Materi mulai dari dasar widget sampai state management Bloc. Lokasi di ruang seminar. Narahubung Dani 0812333444.",
  "event_title": "Workshop Flutter Mobile App"
}

[OBSERVATION / RESULT]:
{
  "event_name": "Workshop Flutter Mobile App Development",
  "event_date": null,
  "target_audience": "Mahasiswa & Pengembang Mobile",
  "key_highlights": ["Dasar widget Flutter", "State management Bloc", "Expert engineer"],
  "contact_person": "Dani (0812333444)",
  "is_complete": false,
  "missing_fields": ["event_date"]
}

[EVALUATION]:
Entitas esensial `event_date` bernilai NULL. Materi publikasi visual (poster/carousel) TIDAK DAPAT diproduksi tanpa tanggal pelaksanaan.

=== KEPUTUSAN GUARDRAIL (EARLY TERMINATION) ===
[THINK / REASON]:
Berdasarkan aturan Lapis 1 (Completeness Guardrail), pipeline TIDAK BOLEH melanjutkan ke pemanggilan LLM mahal atau retrieval RAG bila data esensial tidak lengkap. Sistem harus menghentikan alur kerja dan menyimpan status penolakan informatif ke basis data.

[PERSISTENCE]:
Tabel `briefs` diperbarui:
- `id`: 12
- `status`: "incomplete_data"
- `validation_notes`: "Brief belum mencantumkan tanggal pelaksanaan acara. Silakan lengkapi tanggal sebelum generasi materi publikasi dapat diproses."

[TELEMETRI]:
- Tool terpanggil: ["App\\Actions\\ParseBriefAction"]
- Model LLM terpanggil: TIDAK ADA (Menghemat 100% token generasi)
- Biaya terpakai: Rp 0,-
- Waktu eksekusi: 42ms

=== KONDISI BERHENTI (TERMINATION) ===
Status akhir: BERHENTI DINI (FAIL-FAST GUARDRAIL ACTIVATED).
Pengguna menerima pesan peringatan visual di form submission untuk melengkapi tanggal acara.
```
