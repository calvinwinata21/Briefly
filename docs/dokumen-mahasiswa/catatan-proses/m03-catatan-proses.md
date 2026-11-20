# CATATAN PROSES — MINGGU 03
**Mata Kuliah:** Kapita Selekta: AI Engineering (Ganjil 2026/2027)  
**Nama / NIM / K:** Calvin Winata / 10241016 / K = 23  
**Tanggal:** 03 Oktober 2026  
**Waktu yang saya habiskan minggu ini:** 4,5 jam  

---

## 1. Yang saya kerjakan minggu ini
Saya mengikuti Kuis 1 (15 menit di awal sesi), menyiapkan lingkungan kerja lokal PHP/Laravel, mengonfigurasi koneksi ke Model Gateway (Google Gemini AI Studio API) secara aman melalui file `.env`, membuat file pembukuan `catatan-pemakaian.md`, dan melakukan pemanggilan model pertama dengan pencatatan latensi.

## 2. Tahap READ
- **Yang saya amati:** Pemanggilan model pertama memerlukan 8 elemen kunci: nama model, peran sistem, peran pengguna, temperature, batas token, token input, token output, dan latensi respons.
- **Yang mengejutkan saya:** Latensi jaringan berkontribusi lebih dari 80% dari total waktu respons. Pemanggilan Gemini Flash membutuhkan ~1,2 detik, sedangkan komputasi lokal PHP hanya ~40 milidetik.

## 3. Tahap BREAK
| # | Percobaan | Prediksi saya | Hasil sebenarnya | Prediksi tepat? |
|---|---|---|---|:---:|
| 1 | Hapus seluruh isi peran "system" | Model akan menjawab dengan gaya obrolan umum | Model menyapa ramah dan tidak langsung memberi data terstruktur | ya |
| 2 | Turunkan batas output token menjadi 20 | Teks output akan terpotong di tengah kalimat | Terpotong persis di tengah kata `{"event_na...` (JSON rusak) | ya |
| 3 | Ganti nama model menjadi model palsu | Muncul error HTTP 404 dari gateway | Respon error: Model not found / HTTP 404 | ya |
| 4 | Kirim input teks kosong | Model meminta pengguna memasukkan pertanyaan | Model merespon: *"Halo! Ada yang bisa saya bantu?"* | ya |
| 5 | Jalankan prompt sama di Flash vs Pro | Pro lebih pintar tapi jauh lebih lambat | Flash: 1.2s (kualitas baik). Pro: 4.8s (kualitas hampir serupa) | ya |

- **Prediksi saya yang paling MELESET:** Pada nomor 2, saya tadinya mengira model akan secara cerdas meringkas kalimatnya agar muat dalam 20 token. Ternyata model tidak "tahu" bahwa ia akan kehabisan token; komputasi token diputus paksa di tengah jalan sehingga format JSON rusak parah.
- **Apa yang salah dari cara saya berpikir:** Mengira batas token adalah "perintah untuk meringkas". Padahal batas token hanyalah rem darurat batas komputasi kompresi teks.

## 4. Tahap FIX
Tidak ada pada Minggu 3.

## 5. Tahap BUILD
- Membuat skrip `03_model_gateway_call.php` yang membaca `GEMINI_API_KEY` dari `.env`.
- Membuat file `catatan-pemakaian.md` dengan transaksi pertama Rp 9,90.
- Membandingkan 3 model: Gemini 2.5 Flash, Gemini 1.5 Pro, Gemini 1.5 Flash.

## 6. Tantangan Wajib
- Pada tugas ekstraksi brief acara kampus, Gemini 2.5 Flash menghasilkan output yang kualitasnya identik dengan Gemini 1.5 Pro, namun biaya komputasinya 94% lebih hemat (Rp 4,8 vs Rp 82,0 per panggilan).

## 7. Penggunaan AI Minggu Ini
| # | Untuk apa | Prompt (ringkas) | Yang saya UBAH dari hasilnya | Bagaimana saya verifikasi |
|---|---|---|---|---|
| 1 | Menyusun skrip cURL PHP gateway | Prompt Pack 3A | Menyensor kunci API dan membaca dari `.env` | Menjalankan di CLI dan memeriksa Git status |

- **Satu hal yang AI berikan dan ternyata KELIRU minggu ini:** Contoh skrip dari AI awalnya menaruh API key langsung di parameter URL secara hardcoded. Saya segera menghapusnya dan memindahkannya ke file `.env` yang dilindungi `.gitignore`.
- **Bagian karya minggu ini yang paling sulit saya jelaskan:** Menjelaskan mengapa API key tidak boleh di-commit meskipun repositori berstatus privat.

## 8. Pemakaian dan Biaya
- Kegiatan: Pemanggilan pertama dan pengujian 3 model.
- Model: `gemini-2.5-flash` | Token: In: 3.450 / Out: 1.200 | Biaya: Rp 9,90 | Kumulatif: Rp 9,90

## 9. Daftar Periksa Mandiri
- [x] Pemanggilan model pertama berhasil dan buktinya tersimpan
- [x] API key berada di luar file kode dan di luar repositori
- [x] Tabel uraian pemanggilan terisi lengkap
- [x] Lima percobaan BREAK dijalankan dengan prediksi lebih dulu
- [x] Perbandingan tiga model terisi biaya, latency, dan kualitas
- [x] `catatan-pemakaian.md` dibuat dan sudah berisi baris pertama
- [x] Tantangan wajib disertai bukti perbandingan

## 10. Yang Macet dan Rencana Minggu Depan
- Yang macet: Memahami cara agar model menghasilkan JSON murni tanpa markdown wrapper ```json.
- Rencana minggu depan: Masuk ke Blok B (Minggu 4), menetapkan tema proyek resmi di Lembar Tema, dan menulis prompt v1.
