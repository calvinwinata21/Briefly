# CATATAN PROSES — MINGGU 16
**Mata Kuliah:** Kapita Selekta: AI Engineering (Ganjil 2026/2027)  
**Nama / NIM / K:** Calvin Winata / 10241016 / K = 23  
**Tanggal:** 16 Januari 2027  
**Waktu yang saya habiskan minggu ini:** 6 jam  

---

## 1. Yang saya kerjakan minggu ini
Saya menyelesaikan Ujian Akhir Semester (UAS): mengumpulkan seluruh berkas portofolio komprehensif (`10241016-portofolio.zip`), melakukan demonstrasi langsung (*live demo*) selama 6 menit di hadapan dosen pengampu (Aidil Saputra Kirsan, S.ST., M.Tr.Kom.) pada 2 kasus yang dipilih acak oleh dosen dari set uji 17 kasus, memaparkan hasil evaluasi sistematis dan kajian risiko selama 5 menit, menjawab pertanyaan viva pertanggungjawaban selama 9 menit, dan menuliskan lembar `11-refleksi-akhir-uas-m16.md`.

## 2. Tahap READ
- **Yang saya amati:** Pada sesi UAS, dosen tidak menguji tampilan CSS antarmuka, melainkan langsung memilih dua kasus dari set uji saya: satu kasus ideal (TC-01) dan satu kasus yang memang dilaporkan gagal/ditolak (TC-12).
- **Yang mengejutkan saya:** Ketika sistem gagal mengekstrak dan menolak memproses kasus TC-12 (karena tanggal tidak ada), dosen justru memberikan apresiasi penuh karena perilaku sistem di panggung demo persis sama dengan apa yang saya laporkan di dokumen evaluasi. Kejujuran laporan evaluasi dinilai jauh lebih tinggi daripada klaim palsu.

## 3. Tahap BREAK
(Minggu 16 dialokasikan penuh untuk sesi demonstrasi UAS langsung dan pertanggungjawaban karya portofolio):
- **Kasus Demo 1 (Dipilih Dosen):** TC-01 — Workshop UI/UX Design System 2026.
  - *Hasil Demo:* Sistem memproses brief dalam 1,84 detik, mengekstrak 5 entitas, mengambil Chunk 1, 5, 6, dan menghasilkan 4 slide layout berlatar Dark Navy `#0F172A` serta caption 3 kanal. Status draf terkunci `PENDING_REVIEW`.
- **Kasus Demo 2 (Dipilih Dosen):** TC-13 — Resep Masakan Bolu Pandan (Kasus Penolakan).
  - *Hasil Demo:* Sistem berhasil mendeteksi bahwa input bukan merupakan acara kampus, dan menghentikan alur tanpa membuat draf sampah di basis data (`rejected_non_event`).

## 4. Tahap FIX
- Memastikan seluruh dokumen di folder `dokumen-mahasiswa`, `dokumen-sistem-briefly`, dan `praktik-implementasi` tersinkronisasi dan dapat diaudit secara transparan.

## 5. Tahap BUILD
- Mengompilasi portofolio akhir lengkap:
  1. `README.md` repositori
  2. `rancangan-sistem.md` (UTS)
  3. Direktori `instruksi/` (v1, v2, v3, v4, CATATAN.md)
  4. Direktori `schema/` (briefly-output-schema.json)
  5. Direktori `tools-spec/` (tools-specification.md)
  6. Direktori `brand-guidelines-kb/` (8 chunk brand guidelines HMSI)
  7. Direktori `set-uji/` (17 kasus uji komprehensif)
  8. Direktori `trace-agent/` (3 trace audit eksekusi)
  9. `laporan-evaluasi.md` (Minggu 14)
  10. `kajian-risiko.md` (Minggu 15)
  11. `guardrails.md` (Minggu 13)
  12. `catatan-pemakaian.md` (Minggu 3–16)
  13. `catatan-proses/` (Minggu 01–16 lengkap)
  14. `refleksi.md` (1 halaman refleksi UAS)

## 6. Tantangan Wajib
- Menjawab 4 pertanyaan wajib UAS dengan tenang, beralasan, dan mengakui batas pengetahuan secara jujur (terekam pada `12-bank-pertanyaan-dan-jawaban-pertanggungjawaban.md`).

## 7. Penggunaan AI Minggu Ini
| # | Untuk apa | Prompt (ringkas) | Yang saya UBAH dari hasilnya | Bagaimana saya verifikasi |
|---|---|---|---|---|
| 1 | Pengecekan konsistensi portofolio | Verifikasi checklist Lampiran H | Menyusun urutan folder agar rapi dan mudah dinilai | Mengecek kelengkapan file manual |

- **Satu hal yang AI berikan dan ternyata KELIRU minggu ini:** AI sempat menyarankan membuat klaim bahwa sistem ini siap diproduksi massal untuk seluruh universitas di Indonesia. Saya menolaknya dan tetap menyatakan secara jujur keterbatasan sistem bahwa evaluasi dilakukan pada 17 kasus uji spesifik lingkungan kampus ITK.
- **Bagian karya minggu ini yang paling sulit saya jelaskan:** Menjelaskan kepada dosen bagaimana saya membuktikan bahwa penilai otomatis PHPUnit bebas dari bias model.

## 8. Pemakaian dan Biaya
- Kegiatan: Gladi bersih dan eksekusi demonstrasi UAS.
- Model: `gemini-2.5-flash` | Token: In: 14.500 / Out: 5.800 | Biaya: Rp 45,24 | Total Akhir 1 Semester: **Rp 822,44**

## 9. Daftar Periksa Mandiri
- [x] Portofolio lengkap dikumpulkan H-2
- [x] Demonstrasi langsung berjalan 6 menit sesuai laporan
- [x] Pemaparan evaluasi dan risiko selesai 5 menit
- [x] Sesi tanya jawab viva 9 menit terjawab dengan bukti
- [x] Lembar refleksi 1 halaman selesai
- [x] Seluruh komponen penilaian Proyek (30%) dan UAS (10%) tuntas

## 10. Yang Macet dan Rencana Minggu Depan
- Semester perkuliahan Kapita Selekta: AI Engineering resmi berakhir dengan sukses dan seluruh luaran telah terverifikasi secara fungsional maupun akademis!
