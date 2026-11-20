# CATATAN PROSES — MINGGU 15
**Mata Kuliah:** Kapita Selekta: AI Engineering (Ganjil 2026/2027)  
**Nama / NIM / K:** Calvin Winata / 10241016 / K = 23  
**Tanggal:** 09 Januari 2027  
**Waktu yang saya habiskan minggu ini:** 6 jam  

---

## 1. Yang saya kerjakan minggu ini
Saya menyelesaikan kajian risiko keamanan komprehensif, mengaudit tiga pintu masuk bias (dari model, dokumen rujukan, dan rancangan saya sendiri), melakukan pelacakan alur data (*data trace*), menyusun pernyataan etis resmi (`10-kajian-risiko-dan-pernyataan-etis-m15.md`), dan menuliskan paragraf wajib *"Kapan produk saya tidak boleh dipercaya"*.

## 2. Tahap READ
- **Yang saya amati:** Pelacakan alur data membuktikan bahwa data yang masuk ke sistem Briefly AI hanyalah deskripsi publik kegiatan mahasiswa, dan tidak ada data sensitif (NIK, rekam medis, atau kata sandi) yang dikirim ke API Google Gemini.
- **Yang mengejutkan saya:** Bias yang paling berbahaya dalam sistem saya bukan berasal dari model AI, melainkan dari **rancangan saya sendiri** yang mengunci format carousel selalu tepat 4 slide. Keputusan teknis ini memaksa acara sederhana yang hanya butuh 1 slide untuk dimekarkan menjadi 4 slide.

## 3. Tahap BREAK
| # | Percobaan | Prediksi saya | Hasil sebenarnya | Prediksi tepat? |
|---|---|---|---|:---:|
| 1 | Uji brief bahasa Indonesia vs Inggris | Keduanya dipahami, diksi Indo lebih pas | Bahasa Indonesia lebih tepat gayanya untuk event HMSI | ya |
| 2 | Uji istilah daerah lokal (*bubuhan SI*) | Model sempat bingung dengan kata daerah | Diartikan kelompok mahasiswa setelah diberi few-shot | ya |
| 3 | Uji kasus di luar kategori (berita duka cita) | Sistem memaksakan masuk ke 4 slide promo | Terbukti dipaksa masuk ke slide CTA (sangat tidak etis) | ya |
| 4 | Minta sistem menulis materi kampanye politik | Sistem menolak karena di luar domain | Ditolak oleh domain boundary guardrail | ya |
| 5 | Masukkan data sensitif NIK 16 digit | Sistem mendeteksi dan menolak input | Diblokir oleh sanitasi regex di form request | ya |
| 6 | Audit riwayat Git atas kunci API (`git log -S`) | Tidak ada API key yang pernah ter-commit | 0 temuan: repositori bersih 100% | ya |

- **Nomor 3 (Pelajaran Etika Terbesar):** Ketika memasukkan teks berita duka cita anggota himpunan, sistem mencoba membuat slide 4 berisi "Daftar Sekarang dan Hubungi Kontak!". Ini membuktikan bahwa tanpa filter domain tegas, sistem AI akan menghasilkan keluaran yang sangat tidak pantas dan merusak tatanan moral organisasi. Sistem kini memblokir teks non-acara promosi.

## 4. Tahap FIX (Pernyataan Etis yang Kosong)
Empat kalimat klise pernyataan etis di modul ("memperhatikan etika", "berkomitmen adil", dll) diubah menjadi pernyataan yang **bisa dicek secara objektif**:
1. *"Sistem ini wajib mempertahankan rasio kontras teks minimal 4.5:1 (WCAG AA) agar dapat dibaca oleh tunanetra ringan."*
2. *"Sistem tidak pernah mempublikasikan konten secara otomatis tanpa tanda tangan digital Lead Designer di dashboard review."*
3. *"Sistem tidak memproses, menyimpan, atau mengirim data identitas pribadi penduduk (NIK) ke server cloud."*

## 5. Tahap BUILD
- Menyusun dokumen `kajian-risiko.md` (Lampiran D Bagian II).
- Menetapkan daftar risiko yang secara sadar diterima tanpa mitigasi.
- Menulis pernyataan etis resmi organisasi.

## 6. Tantangan Wajib
- Menulis satu paragraf lugas berjudul *"Kapan produk saya tidak boleh dipercaya"*, ditujukan kepada mahasiswa awam dengan penegasan: jangan mempercayai draf AI secara buta jika jadwal acara masih tentatif atau memuat perubahan lisan yang belum final.

## 7. Penggunaan AI Minggu Ini
| # | Untuk apa | Prompt (ringkas) | Yang saya UBAH dari hasilnya | Bagaimana saya verifikasi |
|---|---|---|---|---|
| 1 | Audit ragam bias representasi bahasa | Prompt Pack 15A | Mengarahkan ke konteks kampus di Kalimantan Timur | Diskusi bersama rekan himpunan |

- **Satu hal yang AI berikan dan ternyata KELIRU minggu ini:** AI sempat menyarankan agar sistem memberikan disclaimer kecil di footer web yang tersembunyi. Saya menolaknya; transparansi bahwa sistem ini ditenagai AI wajib ditampilkan secara mencolok di bagian atas form brief dan dashboard review.
- **Bagian karya minggu ini yang paling sulit saya jelaskan:** Menjelaskan mengapa pengembang sistem bertanggung jawab penuh atas rekomendasi salah yang dihasilkan AI.

## 8. Pemakaian dan Biaya
- Kegiatan: Audit bias bahasa dan sanitasi alur data.
- Model: `gemini-2.5-flash` | Token: In: 11.800 / Out: 4.100 | Biaya: Rp 33,84 | Kumulatif: Rp 777,20

## 9. Daftar Periksa Mandiri
- [x] Tabel alur data lengkap enam baris
- [x] Enam percobaan BREAK dijalankan dengan prediksi lebih dulu
- [x] Audit repositori Git atas kredensial bersih 100%
- [x] Kajian bias memuat contoh konkret dari produk sendiri
- [x] Pernyataan etis dapat dicek oleh pihak ketiga
- [x] Daftar risiko yang diterima tertulis beserta alasannya
- [x] Luaran Blok E dikumpulkan (Proyek 15%)

## 10. Yang Macet dan Rencana Minggu Depan
- Yang macet: Mengompilasi seluruh artefak portofolio semester ke dalam satu paket arsip rapi.
- Rencana minggu depan: Minggu 16 (Ujian Akhir Semester), gladi bersih demo produk langsung di depan dosen, dan refleksi akhir.
