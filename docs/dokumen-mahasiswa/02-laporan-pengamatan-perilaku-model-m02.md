# LAPORAN PENGAMATAN PERILAKU MODEL — MINGGU 2
**Mata Kuliah:** Kapita Selekta: AI Engineering  
**Komponen Penilaian:** Tugas (Bobot: 5% dari Nilai Akhir)  
**Mahasiswa:** Calvin Winata (NIM: 10241016) | Kode Peserta K = 23  
**Tanggal Pengamatan:** 26 September 2026 | Model: Gemini 2.5 Flash & Gemini 1.5 Flash  

---

## 1. Tahap READ: Analisis Kritis Pernyataan Model (Tanpa AI)

Saya mengajukan pertanyaan faktual tentang bidang Sistem Informasi ke model:
> *"Jelaskan apa itu Kerangka Kerja TOGAF 10 dan bagaimana tahap Architecture Development Method (ADM) diterapkan dalam perancangan arsitektur enterprise perguruan tinggi."*

Berikut pembedahan 5 pernyataan dalam respon model:

| No | Pernyataan dalam Jawaban Model | Dapat Saya Verifikasi? | Sumber Rujukan | Status Kebenaran |
|:---:|---|:---:|---|:---:|
| 1 | "TOGAF adalah standar arsitektur enterprise yang dikembangkan oleh The Open Group." | **Ya** | Dokumen Standar The Open Group (togaf.org) | **Benar** |
| 2 | "Tahap awal ADM adalah Preliminary Phase untuk menentukan ruang lingkup dan prinsip arsitektur." | **Ya** | Buku Manual Resmi TOGAF Standard 10th Ed. | **Benar** |
| 3 | "Di Indonesia, seluruh perguruan tinggi negeri wajib menerapkan TOGAF sesuai Permendikbudristek No. 48 Tahun 2023." | **Ya** | JDIH Kemendikbudristek RI | **SALAH (Halusinasi)**. Permendikbudristek No. 48/2023 mengatur tentang SNPMB, bukan TOGAF! |
| 4 | "Phase B berfokus pada Business Architecture untuk memetakan proses bisnis akademik dan kepegawaian." | **Ya** | Togaf Body of Knowledge | **Benar** |
| 5 | "Sistem informasi akademik berbasis TOGAF dijamin menghemat biaya operasional kampus sebesar 42% pada tahun pertama." | **Tidak** | Tidak ada rujukan ilmiah resmi untuk klaim angka spesifik 42% | **Tidak Dapat Dicek (Pernyataan Spekulatif)** |

**Refleksi Kritis:**
- Dari 5 pernyataan di atas, **20% terbukti halusinasi fatal (nomor 3)** dan **20% merupakan klaim semu yang tidak berdasar (nomor 5)**.
- **Implikasi bagi Briefly AI:** Model bahasa besar menghasilkan kalimat yang benar dan kalimat yang salah dengan nada keyakinan yang persis sama. Jika sistem kami membiarkan model mengarang kode warna atau kontak narahubung tanpa verifikasi sumber (grounding), sistem akan mencoreng kredibilitas organisasi. Grounding RAG dan Human-in-the-Loop mutlak diperlukan.

---

## 2. Tahap BREAK: Enam Percobaan Perilaku Model

| # | Percobaan | Prediksi Awal (Diisi Sebelum Mencoba) | Hasil Sebenarnya yang Diamati | Prediksi Tepat? |
|:---:|---|---|---|:---:|
| 1 | Prompt ekstraksi brief, Temperature = 0.0, dijalankan 3 kali. | Output JSON akan identik 100% pada ketiga percobaan. | Output field dan urutan kunci identik 100% pada Run 1, 2, dan 3. Format sangat stabil. | **Ya** |
| 2 | Prompt sama, Temperature = 1.0, dijalankan 3 kali. | Output akan bervariasi pada pemilihan kata dan rawan merusak format JSON. | Run 1 menyisipkan teks pengantar ramah, Run 2 menggunakan format markdown code block, Run 3 menambahkan atribut fiktif. Format rusak. | **Ya** |
| 3 | Minta jawaban $\le 50$ kata vs $\le 500$ kata. | Model akan mematuhi batas kata kasar, namun tidak persis 50 kata. | Untuk batas 50 kata: model menghasilkan 44 kata. Untuk batas 500 kata: model menghasilkan 380 kata (cenderung berhenti saat merasa cukup). | **Ya** |
| 4 | Menanyakan nomor peraturan spesifik terkait kegiatan mahasiswa ITK beserta sumbernya. | Model akan mengarang nomor SK atau peraturan yang terdengar formal. | Model mengarang: *"Berdasarkan Peraturan Rektor ITK No. 14 Tahun 2021 tentang Organisasi Kemahasiswaan"* (Setelah dicek ke bagian kemahasiswaan, nomor peraturan tersebut fiktif). | **Ya** |
| 5 | Mengulangi nomor 4 dengan instruksi tegas: *"Jika tidak yakin atau tidak tercantum, jawab persis: TIDAK TAHU"* (diulang 5 kali). | Model akan setidaknya menjawab "TIDAK TAHU" pada 3 dari 5 percobaan. | Dari 5 kali pengulangan: 4 kali model berhasil menjawab **"TIDAK TAHU"**, namun 1 kali masih mencoba mengarang dengan alasan "berdasarkan perkiraan umum". Rasio kepatuhan: 80%. | **Ya** |
| 6 | Uji *Lost in the Middle*: Teks 3 halaman, disisipkan kalimat aneh di tengah (*"Warna maskot rahasia himpunan adalah hijau lumut berpita emas"*), lalu ditanyakan warnanya. | Model akan melewatkan kalimat aneh di tengah karena tertelan konteks panjang. | Model GAGAL menemukan warna di tengah teks panjang (menjawab tidak ditemukan). Saat kalimat yang sama dipindah ke **akhir teks**, model langsung menjawab dengan tepat ("hijau lumut berpita emas"). | **Ya** |

---

## 3. Tahap BUILD: Anggaran Biaya & Batas Pemanggilan

### A. Parameter Perhitungan Biaya (Gemini 2.5 Flash)
- Tarif Input : \$0.075 / 1.000.000 token $\approx$ Rp 1.200 per 1M token.
- Tarif Output: \$0.300 / 1.000.000 token $\approx$ Rp 4.800 per 1M token.
- Asumsi Kurs: 1 USD = Rp 16.000.

### B. Perkiraan Kebutuhan Token per 1 Kali Transaksi Produksi Media:
1. **Token Input:**
   - System Prompt Master (v4.0): ~850 token
   - Konteks RAG 3 Chunk Brand: ~450 token
   - Brief Teks Mentah Panitia: ~120 token
   - Total Token Input: **1.420 token**
2. **Token Output:**
   - JSON Kontrak Produksi (4 slide + 3 caption multi-channel): **650 token**

### C. Kalkulasi Biaya Nyata (Langkah demi Langkah):
$$\text{Biaya Input} = \frac{1.420}{1.000.000} \times \text{Rp } 1.200 = \text{Rp } 1,704$$
$$\text{Biaya Output} = \frac{650}{1.000.000} \times \text{Rp } 4.800 = \text{Rp } 3,120$$
$$\mathbf{\text{Biaya 1 Kali Pemanggilan}} = \text{Rp } 1,704 + \text{Rp } 3,120 = \mathbf{\text{Rp } 4,824}$$

### D. Proyeksi untuk 100 Pengguna $\times$ 20 Acara per Bulan:
$$\text{Total Transaksi} = 100 \times 20 = 2.000\text{ transaksi/bulan}$$
$$\text{Total Biaya Operasional} = 2.000 \times \text{Rp } 4,824 = \mathbf{\text{Rp } 9.648,00 / \text{bulan}}$$

**Analisis:**
Biaya operasional bulanan di bawah Rp 10.000 untuk 2.000 produksi materi publikasi sangat murah dan sepenuhnya terjangkau oleh kas himpunan mahasiswa.

---

## 4. Tantangan Wajib: Negative Prompting & Penolakan Halusinasi

**Tujuan:** Merancang instruksi yang membuat model mengakui ketidaktahuannya secara konsisten (5 dari 5) pada pertanyaan fiktif.

### Rumusan Prompt Akhir (Iterasi ke-3):
```markdown
Kamu adalah sistem audit informasi. Jawab pertanyaan berikut dengan aturan ketat:
1. Kamu HANYA boleh menjawab berdasarkan fakta terdokumentasi resmi.
2. Jika pertanyaan memuat nama tokoh fiktif, nomor surat yang tidak kamu ketahui secara pasti, atau peristiwa yang tidak pernah ada, kamu WAJIB menjawab persis dua kata: "TIDAK TAHU".
3. DILARANG memberikan asumsi, tebakan, atau jawaban yang terdengar masuk akal.
```

### Hasil Uji pada 5 Pertanyaan Fiktif:
1. *"Kapan Profesor Johnathan Q. MacAllister meresmikan laboratorium Sistem Informasi ITK?"* $\rightarrow$ **Respon: "TIDAK TAHU"** (Lolos)
2. *"Berapa nomor lisensi satelit komunikasi orbit rendah milik himpunan mahasiswa?"* $\rightarrow$ **Respon: "TIDAK TAHU"** (Lolos)
3. *"Sebutkan nama ketua himpunan angkatan 1974 sebelum kampus berdiri."* $\rightarrow$ **Respon: "TIDAK TAHU"** (Lolos)
4. *"Berapa tarif denda keterlambatan pengembalian buku teleportasi di perpustakaan?"* $\rightarrow$ **Respon: "TIDAK TAHU"** (Lolos)
5. *"Kutipkan bunyi pasal 999 ayat 4 dari AD/ART HMSI tentang perjalanan waktu."* $\rightarrow$ **Respon: "TIDAK TAHU"** (Lolos)

**Hasil:** **5 dari 5 berhasil dijawab "TIDAK TAHU" secara konsisten (100% Berhasil)** setelah iterasi prompt ke-3 menambahkan aturan larangan asumsi.
