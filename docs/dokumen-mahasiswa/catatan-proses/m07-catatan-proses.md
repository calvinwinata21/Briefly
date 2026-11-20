# CATATAN PROSES — MINGGU 07
**Mata Kuliah:** Kapita Selekta: AI Engineering (Ganjil 2026/2027)  
**Nama / NIM / K:** Calvin Winata / 10241016 / K = 23  
**Tanggal:** 14 November 2026  
**Waktu yang saya habiskan minggu ini:** 6 jam  

---

## 1. Yang saya kerjakan minggu ini
Saya menyusun rancangan alur RAG (Grounding) untuk Briefly AI (`rancangan-rag.md`), mengkurasi dan memotong Buku Panduan Identitas Visual HMSI 2026 menjadi tepat 8 chunk terstruktur ($K = 23 \implies 5 + (23 \bmod 4) = 8$ chunk), membuat skrip simulasi retrieval Cosine Similarity (`07_rag_retrieval_simulation.php`), dan menyusun 10 pertanyaan uji retrieval.

## 2. Tahap READ
- **Yang saya amati:** Memotong dokumen berdasarkan struktur semantik (per pasal/topik aturan) menghasilkan chunk yang jauh lebih utuh maknanya dibandingkan memotong kaku tiap 500 huruf.
- **Yang mengejutkan saya:** Teks yang memiliki kemiripan kata tinggi belum tentu menjawab pertanyaan yang sama. Kata "biru" bisa merujuk ke warna kartu konten atau warna latar gelap. Metadata `category` sangat krusial untuk memisahkan konteksnya.

## 3. Tahap BREAK
| # | Percobaan | Prediksi saya | Hasil sebenarnya | Prediksi tepat? |
|---|---|---|---|:---:|
| 1 | Ukuran chunk sangat kecil (1 kalimat) | Konteks terpotong, arti hilang | Terambil "Rasio kontras 4.5:1", tapi tidak tahu untuk elemen apa | ya |
| 2 | Ukuran chunk sangat besar (3 halaman) | Pencarian kabur dan biaya boros | Mengambil seluruh bab warna dan tipografi sekaligus, konteks kotor | ya |
| 3 | Tanpa overlap vs dengan overlap | Overlap mencegah kalimat terpotong | Dengan overlap 15%, keterkaitan antar-aturan terjaga | ya |
| 4 | Pertanyaan memakai sinonim ("font tulisan") | Vektor tetap mampu menemukan chunk tipografi | Berhasil menemukan CHK-03 (Tipografi Display) | ya |
| 5 | Pertanyaan yang jawabannya TIDAK ADA di dokumen | Sistem tetap mengembalikan chunk skor rendah | Mengembalikan skor 0.28 (< 0.40). Terfilter oleh threshold! | ya |

- **Nomor 5 (Inti Minggu Ini):** Pertanyaan tentang "aturan ukuran logo sponsor platinum" menghasilkan skor kemiripan tertinggi 0.28. Karena ambang batas sistem disetel pada 0.40, tidak ada chunk yang lolos. Sistem secara tepat menolak menjawab dan menggunakan default safe style, alih-alih mengarang aturan logo fiktif.

## 4. Tahap FIX (Rancangan RAG Bermasalah)
Empat kesalahan fatal pada rancangan mahasiswa di modul:
1. *Chunking kaku 2.000 huruf tanpa overlap:* Memotong pasal di tengah kalimat. *Perbaikan:* Potong per pasal/butir.
2. *Metadata kosong:* Tidak mencatat nomor pasal/dokumen. *Perbaikan:* Tambahkan `chunk_id`, `rule_title`, `category`.
3. *Ambil 3 chunk tanpa batas threshold:* Mengambil chunk acak pada pertanyaan di luar dokumen. *Perbaikan:* Pasang batas bawah similarity $\ge 0.40$.
4. *Instruksi membolehkan pengetahuan umum:* *"Kalau konteks kurang, gunakan pengetahuanmu sendiri"*. **Ini yang paling berbahaya bagi pengguna!** Pengguna mengira jawabannya berasal dari perda resmi, padahal hasil karangan model. *Perbaikan:* Wajibkan menolak jika tidak ada di konteks.

## 5. Tahap BUILD
- Membuat `docs/dokumen-sistem-briefly/rancangan-rag.md` lengkap 6 bagian.
- Menyusun file basis data JSON 8 chunk brand guidelines.
- Mengimplementasikan 10 pertanyaan uji retrieval.

## 6. Tantangan Wajib
- Menjelaskan penanganan pertanyaan yang jawabannya tersebar di 2 dokumen. Memilih strategi menaikkan Top-$K$ retrieval ($K=3$) yang hanya menambah ~250 token input (~Rp 0,6) daripada multi-hop query yang memakan 3x lipat biaya pemanggilan LLM.

## 7. Penggunaan AI Minggu Ini
| # | Untuk apa | Prompt (ringkas) | Yang saya UBAH dari hasilnya | Bagaimana saya verifikasi |
|---|---|---|---|---|
| 1 | Implementasi Cosine Similarity PHP | Pustaka matematika | Menggunakan token frekuensi lokal tanpa ketergantungan library berat | Menguji dengan skrip CLI |

- **Satu hal yang AI berikan dan ternyata KELIRU minggu ini:** AI sempat menyarankan menggunakan database vektor cloud berbayar (Pinecone). Saya menolaknya karena untuk 8 chunk brand guidelines, in-memory token cosine similarity di PHP murni jauh lebih cepat (latensi <5ms) dan 100% gratis.
- **Bagian karya minggu ini yang paling sulit saya jelaskan:** Menjelaskan secara matematis mengapa pembagi fungsi kemiripan kosinus membutuhkan akar kuadrat kuadrat vektor ($L_2\text{-norm}$).

## 8. Pemakaian dan Biaya
- Kegiatan: Pengujian retrieval vektor dan pengujian threshold.
- Model: `gemini-2.5-flash` | Token: In: 18.500 / Out: 5.200 | Biaya: Rp 47,16 | Kumulatif: Rp 214,86

## 9. Daftar Periksa Mandiri
- [x] Chunking manual dilakukan dan aturannya tertulis
- [x] Sepuluh pertanyaan uji tersusun sesuai komposisi
- [x] Lima percobaan BREAK dengan nomor 5 dicatat nilai kemiripannya
- [x] Keempat kesalahan rancangan ditemukan tuntas
- [x] `rancangan-rag.md` lengkap enam bagian
- [x] Tantangan wajib terjawab dengan dua cara beserta biayanya

## 10. Yang Macet dan Rencana Minggu Depan
- Yang macet: Menyiapkan slide presentasi UTS agar pas tepat 8 slide tanpa kelebihan waktu.
- Rencana minggu depan: Presentasi Ujian Tengah Semester (Minggu 8) dan mempertahankan rancangan Briefly AI.
