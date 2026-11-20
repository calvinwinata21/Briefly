# Kisi-Kisi & Jawaban Uraian Konseptual Kuis 1 & Kuis 2
**Mata Kuliah:** Kapita Selekta: AI Engineering (Ganjil 2026/2027)  
**Komponen Penilaian:** Tes / Ujian (Kuis) — Bobot 10% Total Nilai Akhir  
**Mahasiswa:** Calvin Winata (NIM: 10241016) | Kode Peserta K = 23  

Dokumen ini memuat penguasaan konseptual mendalam atas 5 topik soal Kuis 1 (Minggu 3) dan 5 topik soal Kuis 2 (Minggu 6) sebagaimana diatur pada RPS dan instrumen penilaian.

---

# BAGIAN I: KUIS 1 (Blok A — Fondasi, Minggu 3)

### Soal 1: Membedakan AI Engineering dari Machine Learning pada Kasus Konkret
*Pertanyaan:*  
Sebuah organisasi ingin membangun sistem rekomendasi dan penyusunan materi publikasi media. Bedakan pendekatan Machine Learning murni dengan pendekatan AI Engineering pada kasus ini.

*Jawaban Berlandaskan Alasan:*
- **Pendekatan Machine Learning (ML):** Berfokus pada pelatihan model dari nol atau re-training bobot model menggunakan ribuan dataset teks/gambar historis himpunan untuk menghasilkan model spesifik baru. Output-nya berupa file bobot model (`.pt` / `.onnx`) dengan metrik loss dan F1-score. Biayanya sangat mahal dalam hal komputasi GPU, sulit diperbarui jika ada aturan brand baru, dan rawan data drift.
- **Pendekatan AI Engineering:** Berfokus pada perakitan sistem perangkat lunak yang andal di atas foundation model yang sudah tersedia (seperti Gemini API). Alih-alih melatih model, AI Engineer merancang prompt terstruktur, menghubungkan basis pengetahuan brand via RAG Vector Store, memvalidasi kepatuhan skema JSON, dan memasang guardrail Human-in-the-Loop. Sistem langsung bisa diperbarui aturannya cukup dengan mengubah dokumen rujukan tanpa melatih ulang model.

---

### Soal 2: Menghitung Perkiraan Token dan Biaya dari Deskripsi Tugas
*Pertanyaan:*  
Sebuah system prompt berukuran 760 kata, input pengguna 190 kata, dan output yang dihasilkan 380 kata (seluruhnya bahasa Indonesia). Jika tarif model adalah \$0.075 per 1M token input dan \$0.30 per 1M token output dengan kurs \$1 = Rp 16.000, hitung biaya untuk 1 transaksi dan untuk 500 transaksi.

*Jawaban & Langkah Perhitungan:*
1. **Estimasi Token Bahasa Indonesia:** (1 kata rata-rata 1.5 token atau ~3.8 karakter)
   - Token Input Prompt = $760 \times 1.5 = 1.140\text{ token}$
   - Token Input Pengguna = $190 \times 1.5 = 285\text{ token}$
   - Total Token Input = $1.140 + 285 = \mathbf{1.425\text{ token}}$
   - Token Output = $380 \times 1.5 = \mathbf{570\text{ token}}$
2. **Kalkulasi Biaya per 1 Transaksi:**
   - Biaya Input = $(1.425 / 1.000.000) \times (0.075 \times 16.000) = 0.001425 \times 1.200 = \text{Rp } 1,71$
   - Biaya Output = $(570 / 1.000.000) \times (0.30 \times 16.000) = 0.000570 \times 4.800 = \text{Rp } 2,74$
   - Biaya 1 Transaksi = $\text{Rp } 1,71 + \text{Rp } 2,74 = \mathbf{\text{Rp } 4,45}$
3. **Biaya untuk 500 Transaksi:**
   - $500 \times \text{Rp } 4,45 = \mathbf{\text{Rp } 2.225,00}$

---

### Soal 3: Mengapa Halusinasi Tidak Bisa Dihilangkan, Hanya Dikelola
*Pertanyaan:*  
Jelaskan mengapa halusinasi adalah sifat bawaan model bahasa besar dan tidak dapat dihilangkan secara permanen. Bagaimana strategi rekayasanya?

*Jawaban:*
Model bahasa besar bekerja secara probabilistik: memperkirakan token teks berikutnya yang paling mungkin muncul berdasarkan pola statistika konteks sebelumnya. Model tidak memiliki basis data kebenaran ontologis atau pemahaman semantik dunia nyata. Kalimat yang benar dan kalimat yang mengarang (halusinasi) dihasilkan melalui proses matematis yang persis sama.
Oleh karena itu, halusinasi tidak bisa "di-debug" seperti bug pemrograman biasa. Strategi AI Engineering adalah mengelolanya melalui:
1. **Mengurangi peluang:** Menyuntikkan dokumen rujukan faktual melalui RAG (Blok C).
2. **Mendeteksi dini:** Memaksa model menghasilkan field kutipan bukti (*evidence*) dan validasi skema (Blok B).
3. **Membatasi dampak:** Mencegah output berdampak langsung tanpa supervisi manusia melalui guardrail HITL (Blok D).

---

### Soal 4: Menentukan Pengaturan Temperature Beserta Alasannya
*Pertanyaan:*  
Tentukan nilai temperature yang tepat untuk dua tugas berikut beserta alasannya:
(A) Ekstraksi tanggal dan narahubung ke format JSON.
(B) Brainstorming 10 ide tema acara dan tagline kreatif.

*Jawaban:*
- **Tugas (A): Temperature Rendah (0.0 – 0.2).**  
  *Alasan:* Ekstraksi data menuntut determinisme, kepatuhan format skema yang ketat, dan konsistensi fakta. Nilai temperature rendah membatasi model untuk hanya memilih token dengan probabilitas tertinggi, meminimalkan risiko salah ketik atau format JSON rusak.
- **Tugas (B): Temperature Tinggi (0.8 – 1.0).**  
  *Alasan:* Brainstorming menuntut variasi ide, metafora, dan keunikan sudut pandang. Temperature tinggi memperbolehkan model mengeksplorasi cabang token yang tidak terduga sehingga variasi gagasan kreatif meningkat.

---

### Soal 5: Menilai Apakah Sebuah Persoalan Layak Diselesaikan dengan LLM
*Pertanyaan:*  
Diberikan dua kasus: (1) Menghitung denda keterlambatan pengembalian buku perpustakaan berdasarkan rumus hari $\times$ Rp 1.000, dan (2) Meringkas keluhan mahasiswa dari kotak saran teks bebas menjadi poin rekomendasi perbaikan fasilitas. Nilai mana yang layak diselesaikan dengan LLM dan mana yang tidak.

*Jawaban:*
- **Kasus (1) TIDAK LAYAK diselesaikan dengan LLM.**  
  *Alasan:* Persoalan ini sepenuhnya deterministik dan pasti. Menggunakan LLM hanya menambah biaya komputasi, memperlambat latensi, dan membuka risiko salah hitung (karena LLM tidak andal dalam komputasi aritmatika presisi). Cukup diselesaikan dengan perkalian matematis di kode program biasa ($hari \times 1.000$).
- **Kasus (2) SANGAT LAYAK diselesaikan dengan LLM.**  
  *Alasan:* Masukan berupa teks bebas dengan bahasa yang ambigu, penuh nuansa emosional, dan beragam gaya penulisan. Aturan berbasis kata kunci biasa akan gagal menangkap konteks keluhan tersirat. LLM memiliki pemahaman semantik untuk mengelompokkan keluhan ke kategori yang tepat.

---
---

# BAGIAN II: KUIS 2 (Blok B — Kendali, Minggu 6)

### Soal 1: Menemukan Bagian yang Hilang pada System Prompt
*Pertanyaan:*  
Diberikan prompt: *"Kamu asisten pembuat materi publikasi. Buatlah draf caption Instagram untuk acara kampus berdasarkan brief pengguna secepatnya."* Sebutkan bagian apa saja dari 6 bagian wajib yang hilang dan jelaskan akibatnya.

*Jawaban:*
Bagian yang hilang:
1. **Konteks:** Model tidak tahu siapa audiens sasarannya (mahasiswa baru, umum, atau dosen). Akibatnya, gaya bahasa menjadi generik.
2. **Batasan (Constraints):** Tidak ada aturan tentang apa yang dilarang (misal: dilarang mengarang informasi, batas jumlah kata, batas emoji). Akibatnya, model rentan berhalusinasi dan menulis teks terlalu panjang.
3. **Contoh (Few-Shot):** Tidak ada contoh input-output yang baik dan buruk. Akibatnya, format caption berubah-ubah di setiap pemanggilan.
4. **Format Kontrak:** Format output tidak didefinisikan secara tegas (apakah teks biasa, bullet list, atau JSON). Akibatnya, luaran tidak bisa diotomasi ke sistem posting.

---

### Soal 2: Merancang Schema Output JSON untuk Satu Tugas
*Pertanyaan:*  
Rancang schema JSON untuk sistem penerimaan keluhan fasilitas laboratorium komputer kampus, lengkap dengan nilai valid dan field bukti.

*Jawaban Kontrak Skema:*
```json
{
  "$schema": "https://json-schema.org/draft/2020-12/schema",
  "type": "object",
  "required": ["kategori", "tingkat_urgensi", "bukti_kutipan", "dapat_ditentukan"],
  "properties": {
    "kategori": {
      "type": "string",
      "enum": ["hardware_rusak", "jaringan_internet", "software_lisensi", "kebersihan_ruang", "lainnya"]
    },
    "tingkat_urgensi": {
      "type": "string",
      "enum": ["rendah", "sedang", "tinggi", "darurat"]
    },
    "bukti_kutipan": {
      "type": "string",
      "description": "Kutipan langsung kata-per-kata dari teks keluhan pengguna sebagai dasar diagnosis"
    },
    "tindakan_disarankan": {
      "type": "string",
      "maxLength": 100
    },
    "dapat_ditentukan": {
      "type": "boolean"
    }
  }
}
```

---

### Soal 3: Mengapa Field Bukti dan Nilai "Tidak Bisa Ditentukan" Membuat Sistem Lebih Andal
*Pertanyaan:*  
Jelaskan mengapa field `bukti_kutipan` dan pilihan nilai `"tidak_dapat_ditentukan"` wajib ada pada perancangan skema data AI Engineering.

*Jawaban:*
1. **Field `bukti_kutipan` (Verbatim Evidence):** Memaksa model mendasarkan keputusannya pada teks nyata di input, bukan sekadar intuisi semu. Jika model mengklaim urgensi tinggi, ia wajib menunjukkan kalimat mana yang membuktikannya. Hal ini memudahkan verifikasi manusia dan audit trail.
2. **Nilai `"tidak_dapat_ditentukan"`:** Memberikan opsi keluar yang aman bagi model ketika data input tidak memadai. Tanpa opsi ini, model dipaksa memilih salah satu kategori yang ada, yang berujung pada tebakan acak (halusinasi klasifikasi). Mengetahui bahwa sistem tidak tahu jauh lebih aman daripada sistem yang pura-pura tahu.

---

### Soal 4: Menilai Dua Deskripsi Tool yang Menyebabkan Pemilihan Salah
*Pertanyaan:*  
Bandingkan dua deskripsi tool berikut:  
Tool A: *"mencari informasi di database"*  
Tool B: *"mencari data"*  
Jelaskan mengapa deskripsi tersebut akan membuat agent salah memilih tool dan bagaimana cara memperbaikinya.

*Jawaban:*
- **Masalah:** Kedua deskripsi terlalu umum, memiliki fungsi semantik yang tumpang tindih (*overlap*), dan tidak menyebutkan **kapan tool TIDAK boleh digunakan**. Model bahasa tidak memiliki petunjuk pembeda yang jelas, sehingga pemilihan tool menjadi acak.
- **Perbaikan yang Tepat:**
  - Tool A (Diperbaiki): *"Mencari teks peraturan dan panduan brand HMSI berdasarkan kata kunci. Mengembalikan maksimal 3 chunk aturan desain. JANGAN gunakan untuk mencari data akun pengguna atau transaksi keuangan."*
  - Tool B (Diperbaiki): *"Mencari riwayat pengajuan brief acara berdasarkan nama kegiatan atau tanggal. JANGAN gunakan untuk mencari aturan palet warna atau dokumen PDF."*

---

### Soal 5: Menentukan Tool yang Boleh Otomatis vs Butuh Persetujuan Manusia
*Pertanyaan:*  
Dari tiga tool berikut: (1) `ekstrak_teks_brief`, (2) `cari_aturan_brand`, (3) `publikasikan_postingan_instagram`, tentukan mana yang boleh berjalan otomatis dan mana yang wajib persetujuan manusia beserta alasannya.

*Jawaban:*
- **Tool (1) `ekstrak_teks_brief`:** **Boleh Otomatis.**  
  *Alasan:* Operasi baca murni pada memori lokal, tidak mengubah data eksternal, dan tidak memiliki konsekuensi yang tidak dapat dibatalkan jika salah.
- **Tool (2) `cari_aturan_brand`:** **Boleh Otomatis.**  
  *Alasan:* Operasi *read-only* pada tabel dokumen publik organisasi, beroperasi di lingkungan internal sistem.
- **Tool (3) `publikasikan_postingan_instagram`:** **WAJIB PERSETUJUAN MANUSIA (HITL Gate).**  
  *Alasan:* Memenuhi tiga kriteria tindakan berdampak tinggi:
  1. **Tidak dapat dibatalkan:** Postingan yang sudah terbit dilihat oleh ribuan pengikut media sosial dalam hitungan detik.
  2. **Terlihat pihak luar:** Mengatasnamakan organisasi resmi kampus.
  3. **Berdampak pada reputasi:** Kesalahan informasi tanggal atau ejaan nama narasumber merugikan kredibilitas institusi.
