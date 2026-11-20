# Rancangan Alur RAG (Grounding) — Briefly AI
**Mata Kuliah:** Kapita Selekta: AI Engineering  
**Tahap Modul:** Minggu 7–9 (Blok C — Grounding: Menjawab Berdasarkan Dokumen)  
**Lingkup Wajib K = 23:** $5 + (K \bmod 4) = 5 + (23 \bmod 4) = \mathbf{8\text{ Chunk Dokumen Rujukan Minimum}}$  
**Mahasiswa:** Calvin Winata (NIM: 10241016) | Kode Peserta K = 23  

---

## 1. Katalog 8 Dokumen / Chunk Rujukan Resmi

Dokumen rujukan diturunkan dari **Buku Panduan Identitas Visual & Komunikasi Media Himpunan Mahasiswa Sistem Informasi (HMSI)**, tahun rilis 2026, status kelayakan: publik/internal organisasi, bebas dari data pribadi sensitif.

| # | ID Chunk | Kategori Aturan | Judul Aturan / Scope | Isi Pokok Aturan | Status Kelayakan |
|:---:|:---:|---|---|---|:---:|
| 1 | `CHK-01` | Identitas Warna Primer | Palet Warna Resmi Organisasi | Warna Navy `#0F172A` (latar utama gelap) dan Royal Blue `#3B82F6` (latar terang/kartu). Rasio kontras teks minimal 4.5:1 (WCAG AA). | **Lolos** (Dokumen Resmi) |
| 2 | `CHK-02` | Identitas Warna Aksen | Warna Aksen & Tombol Aksi | Warna Amber `#F59E0B` dan Emerald `#10B981` khusus tombol CTA, badge sorotan, dan deadline. Dilarang memakai warna neon/magenta. | **Lolos** (Dokumen Resmi) |
| 3 | `CHK-03` | Tipografi Heading | Standar Font Judul (Display) | Menggunakan font sans-serif modern berbobot tebal (`Plus Jakarta Sans` / `Inter`, bold 700). Huruf kapital untuk 3 kata pertama judul. | **Lolos** (Dokumen Resmi) |
| 4 | `CHK-04` | Tipografi Body | Standar Font Teks Isi & Detail | Menggunakan `Inter` / `Roboto` (regular 400, line-height 1.5). Ukuran minimal 14pt setara mobile screen untuk keterbacaan slide. | **Lolos** (Dokumen Resmi) |
| 5 | `CHK-05` | Struktur Carousel | Arsitektur 4 Slide Publikasi | Slide 1: Hook Headline; Slide 2: Latar Belakang Masalah; Slide 3: Nilai Tambah & Narasumber; Slide 4: Call to Action & Link/Kontak. | **Lolos** (Dokumen Resmi) |
| 6 | `CHK-06` | Brand Voice Kasual | Nada Komunikasi Mahasiswa | Santai, bersahabat, energetik, sapaan "Halo Rekan SI!", penggunaan maksimal 3 emoji relevan, cocok untuk lomba & malam keakraban. | **Lolos** (Dokumen Resmi) |
| 7 | `CHK-07` | Brand Voice Formal | Nada Komunikasi Akademik | Lugas, objektif, tanpa emoji berlebih, sapaan "Yth. Sivitas Akademika", cocok untuk seminar nasional, konferensi, dan workshop skripsi. | **Lolos** (Dokumen Resmi) |
| 8 | `CHK-08` | Saluran Publikasi | Standar Hashtag & Saluran Resmi | Wajib mencantumkan `#HMSIJuara #SistemInformasi #KapitaSelektaAI`. Format narahubung: `wa.me/628...` pada slide 4 dan caption WA. | **Lolos** (Dokumen Resmi) |

---

## 2. Enam Keputusan Desain RAG & Rationale

| # | Keputusan Arsitektur | Nilai yang Dipilih | Rationale / Alasan Rekayasa |
|:---:|---|---|---|
| **1** | **Ukuran Chunk (Chunk Size)** | 100–150 token per aturan spesifik (Topic-based chunking) | Memotong per butir aturan semantik (warna, tipografi, nada suara) menjaga keutuhan konteks aturan tanpa tercampur aturan lain. |
| **2** | **Overlap Antar-Chunk** | 15% overlap semantik pada metadata kategori | Memastikan relasi antara aturan warna primer (`CHK-01`) dan aksen (`CHK-02`) tidak terputus saat pencarian gabungan. |
| **3** | **Jumlah Chunk Terambil ($N$)** | Tepat 3 chunk teratas ($N=3$) | Menjaga keseimbangan antara kelengkapan panduan (warna, slide, nada) dan efisiensi context window (hanya ~450 token RAG context). |
| **4** | **Batas Kemiripan (Threshold)** | $\text{Cosine Similarity} \ge 0.40$ | Menolak chunk acak yang tidak berhubungan dengan brief, mencegah context pollution. |
| **5** | **Metadata yang Disertakan** | `chunk_id`, `category`, `rule_title`, `verbatim_excerpt` | Memberikan konteks terstruktur pada prompt sehingga model dapat mengutip nomor chunk secara presisi pada output JSON. |
| **6** | **Perilaku Bila Tidak Ditemukan** | Tolak mengarang; gunakan default fallback aman & laporkan | Jika query pengguna di luar domain HMSI (misal: "desain poster partai politik"), sistem menolak menerapkan brand HMSI dan menyatakan rules tidak ditemukan. |

---

## 3. Format Konteks RAG yang Disuntikkan ke Prompt

```markdown
--- KONTEKS PANDUAN BRAND TERVERIFIKASI (RAG GROUNDING) ---
[DOKUMEN RUJUKAN: Buku Panduan Identitas Visual HMSI 2026]

[Chunk ID: 1] - Kategori: Identitas Warna Primer
Aturan: Palet Warna Resmi Organisasi
Isi: Warna Navy (#0F172A) digunakan sebagai latar utama gelap dan Royal Blue (#3B82F6) sebagai elemen pendukung. Kontras teks minimal 4.5:1.

[Chunk ID: 5] - Kategori: Struktur Carousel
Aturan: Arsitektur 4 Slide Publikasi
Isi: Carousel publikasi wajib terdiri atas 4 slide: Slide 1 (Hook), Slide 2 (Masalah/Konteks), Slide 3 (Solusi/Narasumber), Slide 4 (CTA & Kontak).

[Chunk ID: 6] - Kategori: Brand Voice Kasual
Aturan: Nada Komunikasi Mahasiswa (casual_energetic)
Isi: Nada komunikasi santai dan berenergi. Gunakan sapaan akrab "Halo Rekan SI!", kalimat persuasif aktif, dan maksimal 3 emoji relevan.
-----------------------------------------------------------
```

---

## 4. Sepuluh Pertanyaan Uji Retrieval RAG

Sesuai ketentuan Modul Minggu 7 bagian 7.2:
- 6 Kasus informasi ada pada 1 chunk
- 2 Kasus informasi tersebar di 2 chunk
- 2 Kasus informasi TIDAK ADA pada dokumen (Negative Testing)

| # | Tipe Kasus Uji | Pertanyaan / Kebutuhan Brief | Chunk Target yang Seharusnya Terambil | Ekspektasi Perilaku Sistem |
|:---:|---|---|:---:|---|
| 1 | Ada (Single) | Apa kode hex warna latar gelap resmi organisasi? | `CHK-01` | Mengembalikan `#0F172A` dan sitasi `[Chunk 1]`. |
| 2 | Ada (Single) | Apa warna tombol aksi (CTA) yang disarankan? | `CHK-02` | Mengembalikan `#F59E0B` dan sitasi `[Chunk 2]`. |
| 3 | Ada (Single) | Font apa yang dipakai untuk headline poster? | `CHK-03` | Mengembalikan `Plus Jakarta Sans` / `Inter` bold. |
| 4 | Ada (Single) | Berapa ukuran font minimal untuk teks body slide? | `CHK-04` | Mengembalikan 14pt dan sitasi `[Chunk 4]`. |
| 5 | Ada (Single) | Bagaimana urutan tipe slide dalam format carousel? | `CHK-05` | Mengembalikan 4 urutan: Hook, Problem, Offering, CTA. |
| 6 | Ada (Single) | Apa hashtag wajib yang harus dipasang di Instagram? | `CHK-08` | Mengembalikan `#HMSIJuara #SistemInformasi #KapitaSelektaAI`. |
| 7 | **Tersebar (Multi)** | Desain slide penutup (CTA) dengan warna tombol resmi. | `CHK-02` + `CHK-05` | Menggabungkan struktur Slide 4 dari `CHK-05` dengan warna `#F59E0B` dari `CHK-02`. |
| 8 | **Tersebar (Multi)** | Buatkan caption promosi lomba dengan nada kasual dan hashtag resmi. | `CHK-06` + `CHK-08` | Menggabungkan gaya bahasa "Halo Rekan SI!" (`CHK-06`) dengan daftar hashtag resmi (`CHK-08`). |
| 9 | **Tidak Ada** | Berapa ukuran logo sponsor platinum di bagian footer poster? | **None (< 0.40)** | Sistem menyatakan: *"Aturan ukuran logo sponsor tidak tercantum dalam Brand Guidelines"*. |
| 10 | **Tidak Ada** | Apakah boleh menggunakan maskot karakter kartun kucing 3D? | **None (< 0.40)** | Sistem menyatakan: *"Penggunaan maskot 3D tidak diatur dalam dokumen resmi"*, dilarang mengarang izin maskot. |

---

## 5. Tantangan Wajib: Penanganan Jawaban Tersebar di 2 Dokumen

Pada pertanyaan nomor 7 dan 8, jawaban memerlukan penggabungan informasi dari 2 chunk terpisah. Jika hanya mengambil 1 chunk teratas, sistem akan kehilangan informasi warna atau informasi struktur slide.

### Dua Strategi Mengatasinya & Analisis Biaya:

1. **Strategi A: Peningkatan Top-$K$ Retrieval ($K=3$ sampai $K=4$) [Dipilih oleh Briefly AI]**
   - *Cara Kerja:* Alih-alih mengambil 1 chunk teratas, retriever mengambil 3 chunk teratas yang memiliki skor kemiripan di atas ambang batas 0.40, lalu menggabungkannya ke dalam satu blok konteks prompt.
   - *Biaya Token:* Menambah sekitar 250 token input pada prompt (~Rp 0,6 per pemanggilan model).
   - *Kelebihan:* Sangat andal, latensi cepat (tanpa pemanggilan API tambahan), dan tidak memerlukan model orkestrasi kedua.

2. **Strategi B: Multi-Hop Query Decomposition (Dua Langkah Pemanggilan)**
   - *Cara Kerja:* Agent pertama memecah query menjadi sub-pertanyaan 1 ("Cari struktur slide") dan sub-pertanyaan 2 ("Cari warna tombol"), melakukan 2 kali retrieval terpisah, lalu menggabungkan hasilnya pada pemanggilan ketiga.
   - *Biaya Token:* Membutuhkan 3 kali pemanggilan LLM terpisah, menghabiskan ~1.800 token total (biaya ~3x lipat dibanding Strategi A, sekitar Rp 2,8 per transaksi).
   - *Kesimpulan:* Strategi A jauh lebih superior untuk produk Briefly AI karena basis pengetahuannya ringkas (8 chunk), sehingga menaikkan $K=3$ sudah mencakup 100% kebutuhan informasi lintas-chunk tanpa pembengkakan biaya.
