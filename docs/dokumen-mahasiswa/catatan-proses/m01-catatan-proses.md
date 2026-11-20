# CATATAN PROSES — MINGGU 01
**Mata Kuliah:** Kapita Selekta: AI Engineering (Ganjil 2026/2027)  
**Nama / NIM / K:** Calvin Winata / 10241016 / K = 23  
**Tanggal:** 19 September 2026  
**Waktu yang saya habiskan minggu ini:** 4 jam  

---

## 1. Yang saya kerjakan minggu ini
Saya mempelajari lanskap perbedaan antara Data Science, Machine Learning, Rekayasa Perangkat Lunak, dan AI Engineering. Saya menerima kode peserta K = 23, menghitung besaran lingkup wajib produk saya, dan memetakan 3 calon persoalan nyata dari bidang keilmuan Sistem Informasi.

## 2. Tahap READ
- **Yang saya amati:** Pada tabel 3 persoalan di modul, persoalan validasi kuota SKS adalah masalah deterministik murni (basis data), prediksi pengunjung pelabuhan adalah Machine Learning murni, sedangkan peringkasan 40 laporan survei adalah AI Engineering.
- **Yang mengejutkan saya:** Ketika jumlah data menjadi 40.000, tugas nomor 3 (peringkasan laporan) akan berubah strateginya dari sekadar menempelkan teks ke konteks prompt menjadi mutlak membutuhkan arsitektur RAG dan pipeline bertahap karena keterbatasan context window dan biaya token.

## 3. Tahap BREAK
| # | Percobaan | Prediksi saya | Hasil sebenarnya | Prediksi tepat? |
|---|---|---|---|:---:|
| 1 | Pertanyaan faktual sempit diajukan 3 kali di sesi baru | Output akan identik kata demi kata | Fakta inti sama, tetapi susunan kalimat pembuka berbeda di tiap sesi | ya |
| 2 | Menanyakan peristiwa yang terjadi 2 minggu lalu | Model tidak tahu atau mengarang | Model menjawab dengan informasi peristiwa tahun lalu | ya |
| 3 | Menanyakan SOP internal prodi yang tidak pernah dipublikasikan | Model akan mengaku tidak tahu | Model mengarang prosedur SOP fiktif yang terdengar sangat meyakinkan | tidak |
| 4 | Menanyakan biografi tokoh fiktif yang saya karang | Model akan mengaku tidak tahu | Model mengarang riwayat karier tokoh fiktif tersebut | tidak |

- **Prediksi saya yang paling MELESET:** Nomor 3 dan 4. Saya tadinya mengira model akan secara cerdas menolak jika data tidak ada di internet. Ternyata model justru mengarang (*hallucination*).
- **Apa yang salah dari cara saya berpikir sebelumnya:** Saya sempat mengira LLM mencari fakta di semacam "database internal". Padahal LLM hanya memperkirakan kelanjutan kata yang terdengar masuk akal secara statistika tanpa memverifikasi kebenaran. Pada nomor 4, model sama sekali *tidak tahu bahwa ia tidak tahu*.

## 4. Tahap FIX
Tidak ada pada Minggu 1.

## 5. Tahap BUILD
- **Penurunan Lingkup K = 23:**
  - Dokumen rujukan minimum : $5 + (23 \bmod 4) = 5 + 3 = \mathbf{8\text{ dokumen}}$
  - Kasus uji minimum : $12 + (23 \bmod 6) = 12 + 5 = \mathbf{17\text{ kasus}}$
  - Tool minimum : $2 + (23 \bmod 2) = 2 + 1 = \mathbf{3\text{ tool}}$
  - Mode output tambahan : K ganjil $\implies$ 3 mode nada komunikasi
- **Calon Persoalan Terpilih:** Briefly AI — Asisten Produksi Media & Desain Acara Berbasis AI Agent untuk Himpunan Mahasiswa Sistem Informasi.

## 6. Tantangan Wajib
- **Temuan AI Dipakai untuk Persoalan yang Salah:** Di kampus, ada mahasiswa yang menggunakan ChatGPT untuk menghitung IPK semester dan validasi prasyarat mata kuliah. Hal ini keliru karena perhitungan IPK adalah kalkulasi aritmatika deterministik yang semestinya dikerjakan oleh sistem informasi akademik (SIAKAD) berbasis basis data SQL. Menggunakan LLM membuka risiko salah hitung perkalian SKS.

## 7. Penggunaan AI Minggu Ini
| # | Untuk apa | Prompt (ringkas) | Yang saya UBAH dari hasilnya | Bagaimana saya verifikasi |
|---|---|---|---|---|
| 1 | Brainstorming calon persoalan SI | Prompt Pack 1A (Pemetaan persoalan) | Mengeliminasi ide chatbot umum; mempersempit ke produksi media | Diskusi dengan pengurus divisi media HMSI |

- **Satu hal yang AI berikan dan ternyata KELIRU minggu ini:** AI sempat menyarankan membuat "asisten otomatisasi tugas kuliah seluruh prodi". Saran ini melanggar kriteria kelayakan proyek karena terlalu luas dan tidak memiliki batas yang jelas.
- **Bagian karya minggu ini yang paling sulit saya jelaskan:** Menjelaskan kepada teman mengapa validasi kelulusan SKS tidak boleh menggunakan LLM.

## 8. Pemakaian dan Biaya
- Kegiatan: Brainstorming ide via antarmuka chat gratis.
- Total minggu ini: Rp 0,- | Total kumulatif: Rp 0,-

## 9. Daftar Periksa Mandiri
- [x] Kode peserta K dicatat dan seluruh angka lingkup dihitung
- [x] Tabel READ terisi lengkap dengan alasan
- [x] Empat baris BREAK punya kolom prediksi terisi sebelum percobaan
- [x] Tiga calon persoalan terisi lengkap enam unsur
- [x] Tantangan wajib terjawab
- [x] Catatan proses ditulis memakai template Lampiran C

## 10. Yang Macet dan Rencana Minggu Depan
- Yang macet: Awalnya ragu apakah persoalan media kampus cukup berbobot untuk AI Engineering.
- Rencana minggu depan: Mengukur anatomi model (token, biaya, temperature, dan uji halusinasi).
