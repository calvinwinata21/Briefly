# CATATAN PROSES — MINGGU 06
**Mata Kuliah:** Kapita Selekta: AI Engineering (Ganjil 2026/2027)  
**Nama / NIM / K:** Calvin Winata / 10241016 / K = 23  
**Tanggal:** 07 November 2026  
**Waktu yang saya habiskan minggu ini:** 6 jam  

---

## 1. Yang saya kerjakan minggu ini
Saya mengikuti Kuis 2 (15 menit di awal sesi), mendefinisikan spesifikasi formal 3 action tools wajib ($K = 23 \implies 2 + (23 \bmod 2) = 3$ tools), mengimplementasikan `tools-specification.md`, membangun skrip simulasi pemanggilan tool dengan penanganan error jujur (`06_tool_calling_simulation.php`), dan mengumpulkan Luaran Blok B (Tugas 10%).

## 2. Tahap READ
- **Yang saya amati:** Model AI tidak pernah mengeksekusi kode program secara langsung. Model hanya menghasilkan string teks permintaan pemanggilan tool (nama fungsi dan parameter JSON); sistem backend kitalah yang mengeksekusinya di server.
- **Yang mengejutkan saya:** Jawaban akhir model terkadang menyelipkan fakta tambahan yang tidak ada di hasil yang dikembalikan tool. Pengecekan silang kata demi kata terhadap output tool wajib dilakukan sistem.

## 3. Tahap BREAK
| # | Percobaan | Prediksi saya | Hasil sebenarnya | Prediksi tepat? |
|---|---|---|---|:---:|
| 1 | Ubah deskripsi tool jadi 1 kata ("mencari") | Model salah pilih tool pada query ambigu | Model memanggil tool secara acak | ya |
| 2 | Sediakan 2 tool yang fungsinya tumpang tindih | Model bingung dan memanggil keduanya | Model memanggil tool pertama yang terdaftar | ya |
| 3 | Buat tool selalu mengembalikan error jujur | Model melaporkan error ke pengguna | Model merespon: *"Sistem gagal mencari data..."* | ya |
| 4 | Buat tool mengembalikan hasil kosong tanpa error | Model mengira data memang tidak ada | Model mulai MENGARANG fakta fiktif! | ya |
| 5 | Pertanyaan yang tidak butuh tool sama sekali | Model langsung menjawab tanpa panggil tool | Model menjawab tanpa memicu tool dispatch | ya |
| 6 | Pertanyaan butuh 2 tool berurutan | Model memanggil tool 1 lalu tool 2 | Berhasil merangkai ParseBrief lalu RAG search | ya |

- **Banding Nomor 3 dan 4 (Paling Kritis):** Nomor 3 memunculkan error eksplisit sehingga kegagalan transparan dan dapat ditangani. Nomor 4 adalah **kegagalan paling berbahaya** karena disembunyikan; model mengira datanya memang kosong di dunia nyata, lalu mulai berhalusinasi mengarang warna dan aturan brand secara liar!

## 4. Tahap FIX (Deskripsi Tool Menyesatkan)
Ketiga deskripsi contoh di modul (`cari_data`, `cari_peraturan`, `hitung`) tumpang tindih. Saya menulis ulang ketiganya:
1. `cari_data` $\rightarrow$ *"Mencari data statistik demografi penduduk. JANGAN gunakan untuk mencari teks peraturan zonasi."*
2. `cari_peraturan` $\rightarrow$ *"Mencari klausul hukum dan ketentuan zonasi RTRW. JANGAN gunakan untuk angka statistik penduduk."*
3. `hitung` $\rightarrow$ *"Melakukan perhitungan luas area dalam satuan meter persegi."*

## 5. Tahap BUILD
- Merancang 3 tools Briefly AI: `ParseBriefAction`, `ExecuteRagSearchAction`, `ValidateAiOutputAction`.
- Menetapkan batas akses tertulis: tool RAG dipaksa *read-only* pada tabel brand guidelines.
- Menguji skrip simulasi pemanggilan tool dan verifikasi penanganan error jujur.

## 6. Tantangan Wajib
- Menunjukkan pertanyaan yang sempat salah memilih tool: *"Tampilkan riwayat pengajuan brief acara bulan lalu"*. Awalnya model memanggil `ExecuteRagSearchAction` karena kata *"brief"*.
- Perbaikan: Mengubah deskripsi RAG tool menjadi: *"Mencari panduan identitas visual brand HMSI (warna, font, tone). JANGAN gunakan untuk mencari riwayat brief atau data pengajuan panitia."*  
- Hasil: Model 100% tepat tidak memanggil tool brand untuk riwayat brief.

## 7. Penggunaan AI Minggu Ini
| # | Untuk apa | Prompt (ringkas) | Yang saya UBAH dari hasilnya | Bagaimana saya verifikasi |
|---|---|---|---|---|
| 1 | Merancang deskripsi tool | Prompt Pack 6A | Menambahkan kalimat batasan "KAPAN TIDAK BOLEH DIPAKAI" | Menguji 8 pertanyaan variasi |

- **Satu hal yang AI berikan dan ternyata KELIRU minggu ini:** AI sempat menyarankan menambahkan tool `kirim_ke_instagram` yang berjalan otomatis setelah validasi. Saya menolaknya karena tindakan ekspor/publikasi keluar sistem wajib melewati persetujuan Lead Designer (HITL).
- **Bagian karya minggu ini yang paling sulit saya jelaskan:** Menjelaskan mengapa mengembalikan hasil kosong tanpa error jauh lebih berbahaya daripada mengembalikan pesan error.

## 8. Pemakaian dan Biaya
- Kegiatan: Pengujian tool calling dan evaluasi deskripsi.
- Model: `gemini-2.5-flash` | Token: In: 12.400 / Out: 4.800 | Biaya: Rp 37,92 | Kumulatif: Rp 167,70

## 9. Daftar Periksa Mandiri
- [x] Catatan satu siklus tool calling terisi lengkap
- [x] Enam percobaan BREAK dengan prediksi lebih dulu
- [x] Analisis perbandingan nomor 3 dan 4 tertulis
- [x] Ketiga deskripsi tool tahap FIX ditulis ulang
- [x] Produk memanggil $\ge 1$ tool dan hasilnya dipakai
- [x] Tiap tool punya batas akses tertulis
- [x] Tantangan wajib disertai bukti sebelum-sesudah
- [x] Luaran Blok B dikumpulkan (Tugas 10%)

## 10. Yang Macet dan Rencana Minggu Depan
- Yang macet: Mengumpulkan dan memotong dokumen rujukan brand HMSI menjadi 8 chunk yang sah.
- Rencana minggu depan: Masuk ke Blok C (Minggu 7), merancang arsitektur RAG, dan mempersiapkan presentasi UTS (Minggu 8).
