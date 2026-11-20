# CATATAN PROSES — MINGGU 14
**Mata Kuliah:** Kapita Selekta: AI Engineering (Ganjil 2026/2027)  
**Nama / NIM / K:** Calvin Winata / 10241016 / K = 23  
**Tanggal:** 02 Januari 2027  
**Waktu yang saya habiskan minggu ini:** 7 jam  

---

## 1. Yang saya kerjakan minggu ini
Saya menyelesaikan tahap evaluasi sistematis: menyusun set uji komprehensif 17 kasus ($K = 23 \implies \mathbf{17\text{ kasus uji}}$), mengevaluasi sistem terhadap 5 kriteria biner objektif, mengukur variance hasil pengujian, membandingkan angka kinerja dengan baseline Minggu 9, menghitung 3 metrik biaya riil dari `catatan-pemakaian.md`, dan menyusun `09-laporan-evaluasi-m14.md` (Laporan Evaluasi, Lampiran D Bagian I).

## 2. Tahap READ
- **Yang saya amati:** Menggunakan 5 kriteria ya/tidak biner (`K1` s/d `K5`) jauh lebih objektif dan konsisten daripada meminta penilai memberi skor 1–10. Dua penilai manusia yang berbeda menghasilkan penilaian 100% identik ketika kriteria didefinisikan secara biner.
- **Yang mengejutkan saya:** Pertanyaan yang paling rawan memicu perbedaan pendapat adalah K1 pada kasus bahasa gaul ekstrem (TC-08). Hal ini diselesaikan dengan membakukan aturan: K1 dinilai dari kebenaran entitas fakta, bukan kecocokan kata per kata.

## 3. Tahap BREAK
| # | Percobaan | Prediksi saya | Hasil sebenarnya | Prediksi tepat? |
|---|---|---|---|:---:|
| 1 | Jalankan set uji 2 kali, ukur variance | Variance sangat kecil (< 5%) | Variance 0% (0 kasus berubah kelulusan) | ya |
| 2 | Ganti ke model termurah (Flash) | Kualitas ekstraksi tetap sama | Lolos 17 dari 17 kasus (Akurasi identik) | ya |
| 3 | Pangkas konteks prompt separuh | Beberapa sitasi chunk mungkin hilang | Sitasi warna sekunder sempat hilang | ya |
| 4 | Nilai kasus dengan penilai otomatis | Kesesuaian tinggi dengan manual | Kesesuaian 100% (17/17 kasus) | ya |
| 5 | Naikkan temperature ke 1.0 pada set uji | Format schema akan mulai gagal | 3 dari 17 kasus gagal validasi JSON | ya |
| 6 | Uji penilai model dengan output salah | Penilai yang baik harus menandai salah | Validator PHP berhasil menandai kegagalan | ya |

- **Nomor 1 (Reproducibility):** Dengan `temperature = 0.2`, sistem Briefly AI menghasilkan kelulusan 17 dari 17 kasus secara konsisten di seluruh repetisi pengujian.

## 4. Tahap FIX (Laporan Evaluasi Menyesatkan)
Lima masalah fatal pada laporan contoh di modul:
1. *Klaim akurasi tanpa pembilang/penyebut:* "Akurasi 93%" tidak ada artinya jika jumlah kasusnya hanya 3 kasus. *Perbaikan:* Tulis pembilang dan penyebut (misal: 14/15 kasus).
2. *Penilai model tanpa kalibrasi:* Menggunakan model menilai dirinya sendiri tanpa kalibrasi manual.
3. *Klaim "terbukti andal" berlebihan:* Klaim absolut tanpa menyebutkan batasan domain uji.
4. *Biaya tanpa kasus terburuk:* Hanya melaporkan biaya rata-rata tanpa kasus loop retry.
5. *Penghematan tanpa uji kualitas ulang:* Mengganti model lebih murah tanpa membuktikan apakah akurasinya turun.

## 5. Tahap BUILD
- Membuat `set-uji-17-kasus.json` dan `set-uji-17-kasus.md`.
- Menjalankan `10_evaluasi_otomatis_runner.php` dengan hasil 100% kelulusan.
- Menyusun laporan evaluasi resmi `laporan-evaluasi.md`.

## 6. Tantangan Wajib
- Menerapkan penghematan biaya dengan beralih ke `gemini-2.5-flash` yang menghemat biaya hingga **94%** dibanding `gemini-1.5-pro` (dari Rp 82,0 menjadi Rp 4,8 per transaksi), sambil membuktikan bahwa tingkat kelulusan set uji tetap 100% tanpa penurunan akurasi.

## 7. Penggunaan AI Minggu Ini
| # | Untuk apa | Prompt (ringkas) | Yang saya UBAH dari hasilnya | Bagaimana saya verifikasi |
|---|---|---|---|---|
| 1 | Otomasi runner evaluasi 17 kasus | Skrip runner pengujian | Memastikan 5 kriteria biner diperiksa terpisah | Menjalankan di CLI PHP |

- **Satu hal yang AI berikan dan ternyata KELIRU minggu ini:** AI sempat menyarankan agar penilai otomatis menggunakan prompt evaluasi LLM bebas. Saya menolaknya dan memilih validator skema berbasis kode program PHPUnit yang jauh lebih deterministik dan bebas biaya token.
- **Bagian karya minggu ini yang paling sulit saya jelaskan:** Menjelaskan mengapa laporan evaluasi wajib memuat bagian "keterbatasan laporan" dan kasus terburuk.

## 8. Pemakaian dan Biaya
- Kegiatan: Eksekusi 3x repetisi set uji 17 kasus komprehensif.
- Model: `gemini-2.5-flash` | Token: In: 72.420 / Out: 33.150 | Biaya: Rp 246,02 | Kumulatif: Rp 743,36

## 9. Daftar Periksa Mandiri
- [x] Set uji lengkap 17 kasus sesuai komposisi
- [x] Kriteria ya/tidak biner dapat dinilai konsisten
- [x] Enam percobaan BREAK dijalankan dengan prediksi lebih dulu
- [x] Variance antar-percobaan dilaporkan (0%)
- [x] Kalibrasi penilai dilaporkan (100% kesesuaian)
- [x] Lima masalah laporan menyesatkan dianalisis
- [x] Tiga angka biaya dari data nyata dihitung
- [x] Perbandingan terhadap baseline Minggu 9 tertulis
- [x] Laporan evaluasi dikumpulkan (Proyek 4%)

## 10. Yang Macet dan Rencana Minggu Depan
- Yang macet: Mengaudit celah bias bahasa lokal dan menyusun batasan etika penggunaan.
- Rencana minggu depan: Masuk ke Minggu 15 (Kajian Risiko & Pernyataan Etis) dan mempersiapkan portofolio akhir UAS.
