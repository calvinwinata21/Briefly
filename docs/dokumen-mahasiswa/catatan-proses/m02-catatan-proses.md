# CATATAN PROSES — MINGGU 02
**Mata Kuliah:** Kapita Selekta: AI Engineering (Ganjil 2026/2027)  
**Nama / NIM / K:** Calvin Winata / 10241016 / K = 23  
**Tanggal:** 26 September 2026  
**Waktu yang saya habiskan minggu ini:** 5 jam  

---

## 1. Yang saya kerjakan minggu ini
Saya mengamati anatomi model bahasa besar: mengukur rasio token teks bahasa Indonesia, membandingkan dampak temperature 0.0 vs 1.0, menguji fenomena *Lost in the Middle*, menghitung proyeksi biaya, dan mengumpulkan Laporan Pengamatan Perilaku Model (Tugas 5%).

## 2. Tahap READ
- **Yang saya amati:** Dari 5 pernyataan yang dihasilkan model tentang standar arsitektur TOGAF, 20% merupakan halusinasi pasal perundang-undangan dan 20% spekulasi tanpa bukti ilmiah.
- **Yang mengejutkan saya:** Model mengarang nomor peraturan menteri dengan gaya bahasa yang sangat formal dan percaya diri tinggi, sehingga jika pembaca tidak melakukan verifikasi silang ke JDIH, pembaca akan mudah tertipu.

## 3. Tahap BREAK
| # | Percobaan | Prediksi saya | Hasil sebenarnya | Prediksi tepat? |
|---|---|---|---|:---:|
| 1 | Prompt sama, temperature 0, 3 kali | Output identik 100% | Format field dan urutan kunci identik | ya |
| 2 | Prompt sama, temperature 1, 3 kali | Output bervariasi kata dan strukturnya | Run 1 teks santai, Run 2 markdown, Run 3 format rusak | ya |
| 3 | Jawaban $\le 50$ kata vs $\le 500$ kata | Batas kata kasar terpenuhi | 44 kata untuk batas 50; 380 kata untuk batas 500 | ya |
| 4 | Tanya SK rektor fiktif bidang SI | Model akan mengarang nomor SK | Model mengarang "Peraturan Rektor No 14/2021" | ya |
| 5 | Tambah instruksi: "Jika tidak yakin, jawab TIDAK TAHU" (5x) | Menjawab tidak tahu minimal 3x | 4 kali menjawab "TIDAK TAHU", 1 kali masih menebak | ya |
| 6 | Uji *Lost in the Middle* (kalimat aneh di tengah teks 3 hal) | Kalimat di tengah terlewat | Di tengah: TIDAK TERDETEKSI. Di akhir: TERDETEKSI | ya |

- **Prediksi saya yang paling MELESET:** Pada nomor 5, saya kira instruksi "jawab TIDAK TAHU" akan 100% dipatuhi di setiap pemanggilan. Ternyata pada run ke-3 model tetap mencoba berspekulasi. Kepatuhan model pada prompt teks bersifat stokastik, bukan mutlak.
- **Apa yang salah dari cara saya berpikir:** Menganggap bahwa kalimat instruksi negatif pada prompt bertindak seperti blok `if-else` pemrograman deterministik. Padahal instruksi teks hanya memberi bobot probabilitas.

## 4. Tahap FIX
Tidak ada pada Minggu 2.

## 5. Tahap BUILD
- Memilih calon terkuat: Briefly AI (Asisten Produksi Media & Desain Acara).
- Menghitung anggaran: 1.420 token input + 650 token output = Rp 4,82 per transaksi. Proyeksi 100 pengguna $\times$ 20 acara = Rp 9.648 / bulan.
- Batas anggaran pribadi semester ini: Rp 50.000 (sangat aman).

## 6. Tantangan Wajib
- Berhasil merancang prompt penolakan ketidaktahuan yang konsisten 5 dari 5 menjawab "TIDAK TAHU" pada pertanyaan fiktif setelah menambahkan larangan berspekulasi.

## 7. Penggunaan AI Minggu Ini
| # | Untuk apa | Prompt (ringkas) | Yang saya UBAH dari hasilnya | Bagaimana saya verifikasi |
|---|---|---|---|---|
| 1 | Menghitung token dan rumus biaya | Prompt Pack 2C | Mengonversi tarif dollar ke kurs Rp 16.000 | Menghitung manual dengan kalkulator |
| 2 | Uji variasi temperature | Prompt Pack 2B | Menyesuaikan kasus ke brief acara kampus | Mengamati respon konsol playground |

- **Satu hal yang AI berikan dan ternyata KELIRU minggu ini:** AI sempat menyatakan bahwa temperature 0.0 menjamin output 100% deterministik seperti fungsi matematika murni. Faktanya, pada model multimodal, sedikit variasi penanganan floating-point komputasi GPU tetap dapat menghasilkan perbedaan token kecil.
- **Bagian karya minggu ini yang paling sulit saya jelaskan:** Menjelaskan mengapa biaya token output 4 kali lipat lebih mahal daripada token input.

## 8. Pemakaian dan Biaya
- Kegiatan: Pengujian konsol AI Studio (Tier Gratis).
- Total minggu ini: Rp 0,- | Total kumulatif: Rp 0,-

## 9. Daftar Periksa Mandiri
- [x] Tabel uraian jawaban terisi $\ge 5$ baris
- [x] Enam percobaan BREAK dijalankan dengan prediksi terisi lebih dulu
- [x] Nomor 5 diulang lima kali dan hasilnya dihitung
- [x] Perhitungan biaya ditampilkan langkahnya
- [x] Tantangan wajib dilaporkan apa adanya
- [x] Laporan pengamatan perilaku model dikumpulkan (Tugas 5%)

## 10. Yang Macet dan Rencana Minggu Depan
- Yang macet: Memahami cara kerja Model Gateway untuk mengabstraksi pemanggilan API.
- Rencana minggu depan: Menyiapkan kredensial API secara aman di `.env` dan menghadapi Kuis 1.
