# CATATAN PROSES — MINGGU 08
**Mata Kuliah:** Kapita Selekta: AI Engineering (Ganjil 2026/2027)  
**Nama / NIM / K:** Calvin Winata / 10241016 / K = 23  
**Tanggal:** 21 November 2026  
**Waktu yang saya habiskan minggu ini:** 6 jam  

---

## 1. Yang saya kerjakan minggu ini
Saya menyelesaikan Ujian Tengah Semester (UTS): mengumpulkan dokumen `04-rancangan-sistem-uts-m08.md` tepat waktu (H-1), mempresentasikan rancangan sistem Briefly AI tepat dalam 8 menit dan 8 slide, menjawab pertanyaan viva dosen pengampu dan rekan reviewer, serta menyusun lembar peer review untuk 2 rekan dari prodi berbeda (`05-peer-review-uts-m08.md`).

## 2. Tahap READ
- **Yang saya amati:** Saat sesi presentasi teman-teman sekelas, proyek yang mencoba menyelesaikan masalah terlalu luas (seperti "asisten belajar seluruh materi kuliah") kesulitan menjawab pertanyaan tentang bagaimana cara mengukur kebenaran output-nya.
- **Yang mengejutkan saya:** Dosen sangat mengapresiasi Slide 7 (Keberhasilan dan Kegagalan yang Ditemukan Sendiri). Mengakui bahwa sistem sempat gagal mengekstrak tanggal relatif *"minggu depan"* justru mendapatkan nilai kejujuran pengujian yang lebih tinggi daripada rekan yang mengklaim sistemnya tidak pernah salah.

## 3. Tahap BREAK
(Minggu 8 dialokasikan penuh untuk sesi presentasi UTS dan tanya jawab pertanggungjawaban karya).
- Saya menguji demo langsung sistem di hadapan dosen:
  - Kasus 1 (Berhasil): Brief informal workshop UI/UX diproses dalam 1,8 detik menghasilkan 4 slide berlatar Navy `#0F172A` dan sitasi Chunk 1.
  - Kasus 2 (Kegagalan yang Ditunjukkan Sendiri): Memasukkan input resep masakan, dan sistem membuktikan keberhasilan penolakan (*domain boundary defense*) dengan status `rejected_non_event`.

## 4. Tahap FIX
- Menyusun Lembar Peer Review UTS untuk Dimas (PWK) dan Sarah (Teknik Kelautan). Memberikan kritik beralasan terkait pentingnya ambang batas kemiripan vektor dan bahaya ekspor data otomatis tanpa persetujuan manusia.

## 5. Tahap BUILD
- Mengunci dokumen rancangan sistem UTS dan memperbarui catatan evaluasi.

## 6. Tantangan Wajib
- Menjawab 3 pertanyaan wajib UTS:
  1. Keputusan yang dapat dibuat berbeda: Menolak multi-agent ReAct demi deterministic workflow terarah.
  2. Input yang membuat gagal: Kasus nama acara *"Nanti"* (TC-16).
  3. Bagian yang dibuat dengan AI: Kode boilerplate skema dan fungsi cosine similarity, diverifikasi dengan 11 unit test PHPUnit.

## 7. Penggunaan AI Minggu Ini
| # | Untuk apa | Prompt (ringkas) | Yang saya UBAH dari hasilnya | Bagaimana saya verifikasi |
|---|---|---|---|---|
| 1 | Simulasi latihan tanya jawab UTS | Lampiran E Bank Soal | Melatih jawaban singkat to-the-point di bawah 1 menit | Simulasi mandiri dengan stopwatch |

- **Satu hal yang AI berikan dan ternyata KELIRU minggu ini:** AI sempat menyarankan agar saya menampilkan slide demo teknis hingga 12 slide. Saya memangkasnya menjadi tepat 8 slide sesuai aturan ketat modul Minggu 8.
- **Bagian karya minggu ini yang paling sulit saya jelaskan:** Mempertahankan alasan mengapa Briefly AI tidak perlu menggunakan model multimodal untuk generasi gambar langsung, melainkan fokus pada layout terstruktur dan copywriting.

## 8. Pemakaian dan Biaya
- Kegiatan: Demonstrasi sistem saat UTS dan pengujian kasus uji.
- Model: `gemini-2.5-flash` | Token: In: 11.200 / Out: 4.600 | Biaya: Rp 35,52 | Kumulatif: Rp 250,38

## 9. Daftar Periksa Mandiri
- [x] Dokumen rancangan sistem dikumpulkan H-1
- [x] Presentasi tepat 8 menit dan tepat 8 slide
- [x] Pertanyaan viva dijawab dengan bukti dan alasan
- [x] Sesi tanya jawab dengan dosen dan 2 rekan reviewer selesai
- [x] Dua lembar peer review dikumpulkan H+1

## 10. Yang Macet dan Rencana Minggu Depan
- Yang macet: Mempersiapkan implementasi alur RAG utuh yang terintegrasi ke basis data SQLite.
- Rencana minggu depan: Masuk ke Minggu 9, merakit RAG penuh dan mencatat baseline kinerja pertama.
