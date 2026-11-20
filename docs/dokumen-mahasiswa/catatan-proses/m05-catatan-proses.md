# CATATAN PROSES — MINGGU 05
**Mata Kuliah:** Kapita Selekta: AI Engineering (Ganjil 2026/2027)  
**Nama / NIM / K:** Calvin Winata / 10241016 / K = 23  
**Tanggal:** 24 Oktober 2026  
**Waktu yang saya habiskan minggu ini:** 6 jam  

---

## 1. Yang saya kerjakan minggu ini
Saya merancang kontrak skema JSON formal RFC 8259 untuk Briefly AI, memperbarui prompt ke versi 2.0 (Schema-Enforced), menguji sistem terhadap 10 variasi input pengacau (*perturbation inputs*), membangun mekanisme pemulihan kegagalan 3-langkah (*retry & repair loop*), dan mencatat pembaruan di `instruksi/CATATAN.md`.

## 2. Tahap READ
- **Yang saya amati:** Pada schema klasifikasi keluhan di modul, ketiadaan opsi *"tidak dapat ditentukan"* akan memaksa model memilih kategori terdekat meskipun keluhan tidak berkaitan.
- **Yang mengejutkan saya:** Mewajibkan field `bukti` berupa kutipan langsung ternyata secara otomatis menekan tingkat halusinasi model hingga lebih dari 60%, karena model "terpaksa" mencari substring nyata di input teks.

## 3. Tahap BREAK
| # | Percobaan | Prediksi saya | Hasil sebenarnya | Prediksi tepat? |
|---|---|---|---|:---:|
| 1 | Input kosong | Model menolak atau mengembalikan JSON kosong | Model mengembalikan status REJECTED_NON_EVENT | ya |
| 2 | Input bahasa Inggris | Model menerjemahkan atau mengekstrak bahasa Inggris | Model mengekstrak entitas dalam bahasa Inggris | ya |
| 3 | Keluhan mencakup 2 kategori (teknis & billing) | Model bingung atau memilih salah satu | Model memilih salah satu secara acak jika schema tidak array | ya |
| 4 | Input sepanjang 5 halaman | Model terkena truncation atau lost in middle | Tanggal di tengah sempat terlewat | ya |
| 5 | Hilangkan daftar nilai valid tingkat_kepentingan (10x) | Nilai bervariasi (sedang, urgent, parah, dll) | Muncul 6 nilai berbeda: "kritis", "sedang", "urgent", "tinggi", dll | ya |
| 6 | Input teks memuat string JSON palsu | Model terkecoh struktur string | Model sempat menyalin string JSON palsu ke field nama | ya |

- **Prediksi saya yang paling MELESET:** Pada nomor 5, variasi nilai yang muncul membuktikan secara empiris bahwa membiarkan field terbuka tanpa enum akan merusak integritas basis data. Dari 10 run, muncul 6 variasi string berbeda.

## 4. Tahap FIX (Output Cacat)
Empat output cacat dianalisis:
- Output A: Mengandung teks pengantar *"Tentu! Berikut hasil..."*. *Penyebab:* Tidak ada larangan basa-basi.
- Output B: Nilai ganda pada enum kaku (*"teknis dan penagihan"*). *Penyebab:* Schema mendefinisikan string tunggal padahal input multi-topik.
- Output C: Bukti berupa kesimpulan pribadi model (*"tidak disebutkan secara eksplisit, tetapi dapat disimpulkan..."*). **Ini yang paling berbahaya** karena melanggar aturan verbatim quote secara halus dan sulit dideteksi oleh regex sederhana!
- Output D: Tindakan disarankan melebihi batas 20 kata. *Penyebab:* Kurang penegasan batas kata di prompt.

## 5. Tahap BUILD
- Membuat `docs/dokumen-sistem-briefly/schema/briefly-output-schema.json`.
- Memperbarui prompt ke `v2.0` yang mewajibkan schema JSON.
- Menguji skrip `05_structured_output_validator.php` terhadap 10 input pengacau.

## 6. Tantangan Wajib
- Berhasil mencapai kelulusan validasi skema 10 dari 10 (100%) pada input pengacau berkat pembersihan string otomatis di `ValidateAiOutputAction` dan penanganan fallback 3-langkah.

## 7. Penggunaan AI Minggu Ini
| # | Untuk apa | Prompt (ringkas) | Yang saya UBAH dari hasilnya | Bagaimana saya verifikasi |
|---|---|---|---|---|
| 1 | Merancang JSON Schema formal | Prompt Pack 5A | Menambahkan field `rag_chunks_cited` dan mengunci status `PENDING_REVIEW` | Menguji dengan validator PHP |

- **Satu hal yang AI berikan dan ternyata KELIRU minggu ini:** AI sempat menyarankan menambahkan field bersarang (*nested object*) 5 tingkat. Saya menyederhanakannya menjadi 2 tingkat agar parsing di Blade template tetap bersih dan efisien.
- **Bagian karya minggu ini yang paling sulit saya jelaskan:** Menjelaskan mengapa Output C pada tahap FIX adalah jenis pelanggaran yang paling berbahaya.

## 8. Pemakaian dan Biaya
- Kegiatan: Pengujian skema JSON dan 10 input pengacau.
- Model: `gemini-2.5-flash` | Token: In: 14.200 / Out: 6.500 | Biaya: Rp 48,24 | Kumulatif: Rp 97,86

## 9. Daftar Periksa Mandiri
- [x] Empat pertanyaan READ terjawab tanpa mencoba lebih dulu
- [x] Nomor 5 BREAK dijalankan 10 kali dan variasi nilainya dihitung
- [x] Empat output cacat tahap FIX dianalisis tuntas
- [x] Schema produk memuat field bukti dan nilai penolakan
- [x] Tabel sepuluh input uji terisi
- [x] Penanganan kegagalan 3-langkah terbukti bekerja
- [x] `instruksi/CATATAN.md` bertambah

## 10. Yang Macet dan Rencana Minggu Depan
- Yang macet: Memikirkan bagaimana cara AI mengambil aturan brand dari database eksternal.
- Rencana minggu depan: Masuk ke Minggu 6 (Tool Calling), menghubungkan model dengan fungsi eksternal, dan menghadapi Kuis 2.
