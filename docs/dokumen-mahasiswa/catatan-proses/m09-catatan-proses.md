# CATATAN PROSES — MINGGU 09
**Mata Kuliah:** Kapita Selekta: AI Engineering (Ganjil 2026/2027)  
**Nama / NIM / K:** Calvin Winata / 10241016 / K = 23  
**Tanggal:** 28 November 2026  
**Waktu yang saya habiskan minggu ini:** 5,5 jam  

---

## 1. Yang saya kerjakan minggu ini
Saya menyelesaikan Blok C dengan merakit alur RAG utuh pada Briefly AI (`ExecuteRagSearchAction` terhubung ke model Gemini API), mewajibkan sitasi nomor chunk sumber pada output JSON (`[Chunk ID: X]`), menerapkan mekanisme penolakan ketika informasi tidak ada di basis pengetahuan, dan mencatat dua angka kinerja baseline Minggu 9.

## 2. Tahap READ
- **Yang saya amati:** Dari 3 jenis kegagalan RAG (gagal retrieval, gagal setia pada sumber, gagal cakupan), kegagalan paling sering di awal adalah *gagal setia pada sumber* di mana chunk warna primer sudah terambil di konteks, namun model tetap memilih warna oranye gradasi karena terdistraksi prompt kreatif.
- **Yang mengejutkan saya:** Menyematkan nomor chunk `[Chunk ID: X]` di awal tiap chunk konteks dan mewajibkan model menulis atribut `citation_chunk: X` di JSON secara drastis menghentikan kebiasaan model mengabaikan isi chunk.

## 3. Tahap BREAK
| # | Percobaan | Prediksi saya | Hasil sebenarnya | Prediksi tepat? |
|---|---|---|---|:---:|
| 1 | Hapus nomor ID chunk dari konteks prompt | Model berhenti menyertakan nomor sitasi | Model hanya menyebut "sesuai panduan" tanpa nomor chunk | ya |
| 2 | Beri pertanyaan yang jawabannya ada di CHK-01 | Model mengutip kode warna Navy `#0F172A` | Palet Navy dan Royal Blue dikutip dengan benar | ya |
| 3 | Beri pertanyaan di luar 8 chunk brand | Model menolak menjawab | Model merespon data tidak ada di panduan brand | ya |
| 4 | Sisipkan aturan brand palsu di input user | RAG mengabaikan input palsu | Model tetap menggunakan warna dari chunk RAG resmi | ya |
| 5 | Turunkan threshold ke 0.10 pada query acak | Chunk tidak relevan akan terambil | Chunk tipografi terambil untuk pertanyaan konsumsi | ya |
| 6 | Uji prompt injection di dalam dokumen rujukan | Model mengeksekusi instruksi di dokumen | Model sempat terpengaruh instruksi di dalam chunk | ya |

- **Nomor 6:** Pengujian ini membuktikan bahwa teks di dalam dokumen rujukan RAG memiliki tingkat kepercayaan yang sangat tinggi di mata model. Keamanan dokumen rujukan harus dijaga ketat agar tidak disusupi perintah berbahaya (*data poisoning*).

## 4. Tahap FIX
Memperbaiki prompt sistem ke versi 3.0 (`instruksi/v3-rag.md`) dengan menambahkan aturan penegasan: jika terdapat kontradiksi antara permintaan warna di teks brief panitia dan aturan resmi di chunk RAG, model **WAJIB memprioritaskan aturan resmi chunk RAG**.

## 5. Tahap BUILD
- Mengintegrasikan `VectorSearchService.php` dan `ExecuteRagSearchAction.php` ke alur orkestrator.
- Mencatat **Dua Angka Baseline Kinerja Minggu 9:**
  1. *Pertanyaan yang ada di dokumen, dijawab benar:* **85,7%** (12 dari 14 kasus).
  2. *Pertanyaan yang tidak ada di dokumen, ditolak dengan benar:* **66,7%** (2 dari 3 kasus).
  (Kedua angka ini akan dijadikan pembanding evaluasi di Minggu 14).

## 6. Tantangan Wajib
- Menunjukkan satu kasus retrieval yang meleset: Query *"jadwal rapat pengurus"* sempat mengambil `CHK-08` (Tagar Instagram) karena adanya kata *"jadwal"*.  
- Perbaikan: Menambahkan kata kunci spesifik *"rapat", "internal", "pengurus"* ke metadata `keywords` pada basis data vektor.

## 7. Penggunaan AI Minggu Ini
| # | Untuk apa | Prompt (ringkas) | Yang saya UBAH dari hasilnya | Bagaimana saya verifikasi |
|---|---|---|---|---|
| 1 | Optimasi token prompt RAG | Lampiran A Bagian C | Memangkas kata pengantar konteks dokumen | Menghitung token input di API usage |

- **Satu hal yang AI berikan dan ternyata KELIRU minggu ini:** AI sempat menyarankan agar seluruh 8 chunk brand selalu disuntikkan ke setiap pemanggilan (tanpa retrieval selektif). Saran ini boros token; dengan retrieval Top-3, kami menghemat lebih dari 600 token input per transaksi.
- **Bagian karya minggu ini yang paling sulit saya jelaskan:** Menjelaskan perbedaan antara kegagalan retrieval dengan kegagalan kesetiaan pada sumber (*faithfulness failure*).

## 8. Pemakaian dan Biaya
- Kegiatan: Pengujian alur RAG utuh dan pencatatan baseline.
- Model: `gemini-2.5-flash` | Token: In: 16.800 / Out: 6.100 | Biaya: Rp 49,44 | Kumulatif: Rp 299,82

## 9. Daftar Periksa Mandiri
- [x] Produk menjawab berdasarkan dokumen sendiri dan menyebutkan sumber
- [x] Sistem menolak menjawab saat informasi tidak ada di dokumen
- [x] Dua angka baseline kinerja tercatat
- [x] Enam percobaan BREAK dijalankan dengan prediksi lebih dulu
- [x] Kasus retrieval meleset dianalisis dan diperbaiki
- [x] Luaran Blok C tercapai

## 10. Yang Macet dan Rencana Minggu Depan
- Yang macet: Menentukan apakah tugas banyak langkah perlu diubah menjadi autonomous agent atau tetap workflow.
- Rencana minggu depan: Masuk ke Blok D (Minggu 10), menyusun analisis komparasi Workflow vs Agent.
