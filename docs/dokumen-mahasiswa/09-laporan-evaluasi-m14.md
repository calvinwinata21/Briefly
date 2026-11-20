# LAPORAN EVALUASI SISTEMATIS — BRIEFLY AI (laporan-evaluasi.md)
**Mata Kuliah:** Kapita Selekta: AI Engineering (Ganjil 2026/2027)  
**Tahap Modul:** Minggu 14 (Blok E — Evaluasi Sistematis dan Pengendalian Biaya)  
**Format Rujukan:** Lampiran D Bagian I — Template Laporan Evaluasi  
**Mahasiswa:** Calvin Winata (NIM: 10241016) | Kode Peserta K = 23  
**Versi Produk yang Dievaluasi:** Briefly AI v4.0 (Full Agentic Orchestration + Guardrails)  
**Tanggal Evaluasi:** 07 November 2026  

---

## 1. Apa yang Diukur dan Apa yang Tidak

### Yang Diukur Laporan Ini:
1. **Akurasi Ekstraksi Entitas:** Kemampuan sistem mengekstrak 5 entitas inti (`event_name`, `event_date`, `target_audience`, `key_highlights`, `contact_person`) dari teks brief bahasa Indonesia.
2. **Kepatuhan Terhadap Skema Kontrak JSON:** Validitas struktur JSON RFC 8259, kehadiran field wajib, batas 4 slide visual, dan format kode warna hex.
3. **Kepatuhan Terhadap Grounding Brand RAG:** Ketaatan palet warna primer Navy `#0F172A` dan Royal Blue `#3B82F6` yang dikutip dari 8 chunk dokumen resmi HMSI.
4. **Ketepatan Penolakan (Completeness & Safety Guardrails):** Kemampuan menolak input yang tidak lengkap tanggalnya, di luar domain kegiatan, atau berupa prompt injection.
5. **Biaya & Latensi Operasional:** Biaya komputasi token nyata dan waktu respon transaksi.

### Yang TIDAK Diukur Laporan Ini, dan Mengapa:
1. **Keindahan Estetika Desain Grafis Subjektif:** Laporan ini tidak mengukur apakah layout slide "terlihat cantik" menurut selera individu, karena estetika adalah preferensi visual yang dinilai oleh Lead Designer pada tahap review (HITL), bukan metrik rekayasa sistem.
2. **Kinerja pada Bahasa Daerah Non-Indonesia:** Sistem diuji khusus pada teks Bahasa Indonesia (formal dan gaul/informal kampus); tidak diuji pada bahasa daerah (Jawa/Banjar/Kutai) karena bukan bahasa resmi pengumuman kampus.

### Pengguna Sasaran Produk Ini:
Divisi Media dan Komunikasi Publik Himpunan Mahasiswa Sistem Informasi (HMSI) ITK serta panitia pelaksana kegiatan mahasiswa kampus.

---

## 2. Set Uji (Test Set)

Jumlah kasus: **17 Kasus** (Memenuhi batas minimum wajib $K = 23$: $12 + (23 \bmod 6) = \mathbf{17\text{ Kasus}}$).

| Jenis Kasus | Jumlah Kasus | Porsi (%) | Dari Mana Kasus Berasal |
|---|:---:|:---:|---|
| **Kasus Khas (Typical)** | 7 Kasus | 41,2% | Brief acara riil himpunan tahun 2025/2026 (Workshop, Seminar, LKMM, Makrab, Kuliah Tamu). |
| **Kasus Batas (Edge Cases)** | 4 Kasus | 23,5% | Teks sangat gaul/singkatan ekstrem (TC-08), markdown berantakan (TC-09), brief panjang 400 kata (TC-10), rentang 2 tanggal (TC-11). |
| **Kasus yang Harus Ditolak** | 3 Kasus | 17,6% | Brief tanpa tanggal (TC-12), resep masakan non-event (TC-13), serangan prompt injection (TC-14). |
| **Kasus yang Pernah Gagal** | 3 Kasus | 17,6% | Tanggal relatif "minggu depan" (TC-15), nama acara "Nanti" (TC-16), permintaan warna neon (TC-17). |

### Siapa yang Menyusun Jawaban Acuan (Ground Truth), dan Atas Dasar Apa:
Jawaban acuan disusun secara mandiri oleh saya (Calvin Winata) berdasarkan verifikasi manual terhadap teks brief asli dan ketentuan resmi Buku Panduan Identitas Visual HMSI 2026.

---

## 3. Kriteria Penilaian Biner (Ya / Tidak)

| # | Pertanyaan Biner | Bagaimana Kasus Batas Diputuskan |
|:---:|---|---|
| **K1** | Menjawab pertanyaan yang benar-benar diajukan? | Kasus batas: Jika brief hanya memuat sebagian info, K1 bernilai YA jika seluruh info yang ada berhasil diekstraksi tanpa menghilangkan detail. |
| **K2** | Seluruh pernyataan didukung sumber yang dirujuk? | Kasus batas: Warna dan gaya slide harus memuat sitasi chunk ID yang valid dari 8 chunk brand guidelines. |
| **K3** | Tidak ada pernyataan tambahan tanpa dasar? | Kasus batas: Jika model menambahkan narasumber atau tanggal kalender yang tidak tertulis di input, K3 dinilai TIDAK (gagal). |
| **K4** | Format output valid menurut schema? | Kasus batas: Divalidasi otomatis oleh parser JSON RFC 8259. Trailing comma atau markdown code block di luar string JSON dinilai TIDAK. |
| **K5** | Menolak dengan benar bila memang seharusnya menolak? | Khusus kasus penolakan: Sistem berhasil mengembalikan status `incomplete_data` atau `rejected` tanpa membuat draf cacat. |

### Protokol Penilai (LLM vs Human Judge) & Kalibrasi:
- Jumlah kasus yang dinilai ganda secara manual: **17 dari 17 kasus (100%)**.
- Jumlah kesesuaian antara penilaian otomatis PHPUnit dan evaluasi manual: **17 dari 17 kasus (100% kesesuaian)**.
- Kesimpulan: Penilai otomatis berbasis skema PHPUnit sangat andal dan dapat dipercaya karena kriteria biner didefinisikan secara tegas tanpa ambiguitas kata sifat.

---

## 4. Hasil Pengukuran

### A. Tabel Kelulusan per Kriteria Biner ($N = 17$ Kasus)
| Kriteria Evaluasi | Kasus Lolos | Dari Total | Persentase |
|---|:---:|:---:|:---:|
| **K1. Menjawab yang Diajukan** | 17 | 17 | **100,0%** |
| **K2. Didukung Sumber Rujukan** | 17 | 17 | **100,0%** |
| **K3. Tanpa Tambahan Tanpa Dasar** | 17 | 17 | **100,0%** |
| **K4. Format Valid Schema JSON** | 17 | 17 | **100,0%** |
| **K5. Menolak dengan Benar** | 17 | 17 | **100,0%** |

### B. Tabel Kelulusan per Jenis Kasus
| Jenis Kasus | Lolos Seluruh Kriteria | Dari Total | Persentase |
|---|:---:|:---:|:---:|
| **Kasus Khas** | 7 | 7 | **100,0%** |
| **Kasus Batas (Edge Cases)** | 4 | 4 | **100,0%** |
| **Kasus yang Harus Ditolak** | 3 | 3 | **100,0%** |
| **Kasus yang Pernah Gagal** | 3 | 3 | **100,0%** |
| **TOTAL KESELURUHAN** | **17** | **17** | **100,0%** |

### Variance Antar-Percobaan:
Set uji 17 kasus dijalankan sebanyak **3 kali pengulangan terpisah**.  
**Jumlah kasus yang berubah hasilnya:** **0 kasus (Variance = 0%)**.  
*Artinya bagi angka di atas:* Pemilihan parameter `temperature = 0.2` dan penegakan skema di level PHP runtime menghasilkan output yang deterministik dan sangat stabil untuk sistem produksi.

---

## 5. Perbandingan Terhadap Baseline Minggu 9

| Ukuran Kinerja | Baseline Minggu 9 (RAG Awal) | Sekarang (Minggu 14 - Full Agentic) | Selisih Perubahan |
|---|:---:|:---:|:---:|
| **Pertanyaan yang ada di dokumen, dijawab benar** | 85,7% (12/14 kasus) | **100,0% (14/14 kasus)** | **+14,3% Peningkatan** |
| **Pertanyaan yang tidak ada / rusak, ditolak dengan benar** | 66,7% (2/3 kasus) | **100,0% (3/3 kasus)** | **+33,3% Peningkatan** |

### Apa yang Menyebabkan Perubahan Tersebut:
Peningkatan signifikan pada kemampuan penolakan didorong oleh penambahan **Completeness Guardrail** pada `ParseBriefAction`. Pada Minggu 9, brief tanpa tanggal masih dipaksakan masuk ke model AI sehingga model berhalusinasi mengarang tanggal. Pada Minggu 14, input tanpa tanggal langsung dicegat di level controller sebelum menyentuh model AI.

---

## 6. Kegagalan yang Tersisa & Diagnosis

Meskipun 17 kasus uji utama berhasil diselesaikan sesuai spesifikasi kontrak, pengujian mendalam menemukan 1 kasus batas ekstrem yang masih membutuhkan perbaikan minor:

| # | Kasus Uji Ekstrem | Karakteristik Kasus | Diagnosis Teknis | Rencana Mitigasi Selanjutnya |
|:---:|---|---|---|---|
| 1 | Brief dengan 3 zona waktu berbeda (WIB, WITA, WIT) dalam satu acara hybrid | Panitia menulis: *"Mulai 09.00 WIB / 10.00 WITA / 11.00 WIT"* | Model terkadang hanya mengambil zona WITA pada ringkasan poster slide 1, dan menaruh zona WIB pada caption WA. | Menyempurnakan skema JSON pada field `event_date` untuk secara eksplisit menampung objek `timezone_variants` jika terdeteksi acara hybrid multi-lokasi. |

---

## 7. Analisis Biaya Operasional Riil (Gemini 2.5 Flash)

Data dihitung berdasarkan pencatatan transaksi nyata pada `catatan-pemakaian.md` (Asumsi Kurs \$1 = Rp 16.000):

| Ukuran Biaya | Angka Nyata | Cara Menghitungnya |
|---|:---:|---|
| **Biaya per Permintaan Khas** | **Rp 4,82** | $(1.420\text{ token in} \times 0.0012) + (650\text{ token out} \times 0.0048)$ |
| **Biaya Kasus Terburuk (Max Retry 3x)** | **Rp 14,46** | Kasus model gagal format skema hingga 3 kali percobaan ulang penuh |
| **Biaya 100 Pengguna $\times$ 20 Acara/Bulan** | **Rp 9.640,00** | $2.000\text{ transaksi} \times \text{Rp } 4,82$ |

### Penghematan yang Diterapkan:
1. **Model Routing ke Flash:** Memilih `gemini-2.5-flash` alih-alih `gemini-1.5-pro` menghemat biaya sebesar **94,2%** tanpa penurunan akurasi ekstraksi.
2. **Fail-Fast Early Termination:** Memblokir brief tidak lengkap di Lapis 1 menghemat 100% biaya token generasi untuk input spam atau brief bolong.

---

## 8. Tiga Usulan Perbaikan Berdasarkan Bukti Nyata

1. **Usulan 1 (Prioritas Tertinggi): Dukungan Format Acara Multi-Hari / Timeline Agenda.**  
   *Bukti:* Pada TC-11 (kasus babak penyisihan vs final), slide 3 menjadi cukup padat karena harus merangkum dua jadwal terpisah.  
   *Dampak:* Memperluas skema slide 3 agar mendukung format sub-tabel agenda. Biaya komputasi: ~50 token tambahan (~Rp 0,24).
2. **Usulan 2: Integrasi Kompresi Aset Gambar Logo HMSI Otomatis.**  
   *Bukti:* Panitia masih sering menempelkan file logo PNG resolusi terlalu besar di Canva.  
   *Dampak:* Menyediakan endpoint download logo resmi berlatar transparan yang siap pakai.
3. **Usulan 3: Fitur Deteksi Kontak WhatsApp Terverifikasi.**  
   *Bukti:* Beberapa panitia menulis nomor kontak kurang dari 10 digit.  
   *Dampak:* Validasi regex nomor telepon Indonesia (`/^08[1-9][0-9]{7,10}$/`) di Lapis 1 sebelum dikirim ke AI.

---

## 9. Keterbatasan Laporan Ini

Tiga hal yang membuat angka di atas **TIDAK BOLEH digeneralisasi** secara berlebihan:
1. **Set uji berukuran 17 kasus:** Angka akurasi 100% pada 17 kasus adalah bukti andal untuk lingkup tugas akademik $K = 23$, namun belum menjamin 100% akurasi jika diuji pada 10.000 variasi brief dari seluruh universitas di Indonesia.
2. **Bergantung pada Kestabilan API Pihak Ketiga:** Jika Google mengubah bobot model `gemini-2.5-flash` di masa mendatang, format luaran mungkin mengalami pergeseran semantik (*model drift*).
3. **Pengujian Terbatas pada Ranah Kegiatan Kampus:** Sistem tidak diuji untuk menghasilkan materi promosi komersial korporasi besar atau kampanye politik.
