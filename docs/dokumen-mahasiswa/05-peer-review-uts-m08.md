# Lembar Peer Review UTS (Minggu 8)
**Mata Kuliah:** Kapita Selekta: AI Engineering (Ganjil 2026/2027)  
**Komponen Penilaian:** Tugas (Bobot: 5% dari Nilai Akhir)  
**Reviewer:** Calvin Winata (NIM: 10241016) | Sistem Informasi | Kode Peserta K = 23  

Sesuai aturan Modul Minggu 8 bagian 8.5, saya melakukan *peer review* kritis terhadap rancangan sistem dari **dua rekan mahasiswa dari program studi yang berbeda**.

---

## PEER REVIEW 1
- **Nama Rekan:** Dimas Pratama (NIM: 08241022)  
- **Program Studi:** Perencanaan Wilayah dan Kota (PWK)  
- **Judul Produk:** *ZonasiBot — Asisten Penelusur Ketentuan Zonasi Dokumen RTRW*  

### 1. Keputusan Rancangan yang Paling Kuat
*Keputusan:* Dimas memilih melakukan *chunking* berbasis pasal dan ayat hukum peraturan daerah, bukan memotong teks per jumlah huruf/karakter.  
*Alasan:* Pada dokumen hukum tata ruang seperti Perda RTRW, satu pasal memuat konteks hukum yang utuh antara peruntukan lahan (zona industri) dengan larangannya. Memotong per pasal menjamin dokumen rujukan tidak terputus di tengah klausul sanksi, sehingga retrieval sangat presisi.

### 2. Keputusan Rancangan yang Berisiko
*Keputusan:* Tidak menetapkan ambang batas minimum kemiripan (*similarity threshold*) pada alur RAG-nya, dan selalu mengambil 3 chunk teratas (*Top-3*).  
*Alasan Risiko:* Jika masyarakat menanyakan hal yang sama sekali tidak diatur dalam perda (misal: "aturan izin pembangunan pangkalan roket antariksa"), sistem tetap akan memaksakan mengambil 3 chunk terdekat dan menyuruh model menjawab. Model akan berhalusinasi mengaitkan izin roket dengan zonasi bandara. Dimas wajib memasang batas bawah similarity (misal: $\ge 0.45$) dan aturan penolakan jika tidak lolos.

### 3. Pertanyaan yang Belum Sempat Terjawab di Sesi Presentasi
> *"Bagaimana sistem Anda menangani pasal yang telah diamandemen atau dicabut oleh peraturan daerah yang lebih baru pada tahun berikutnya, jika kedua dokumen sama-sama tersimpan di basis data vektor?"*

### 4. Hal yang Ingin Saya Tiru untuk Produk Saya (Briefly AI)
Dimas menyertakan tabel pemetaan nomor pasal dan tahun peraturan secara rapi pada metadata vektornya. Saya akan meniru cara Dimas dengan memperjelas metadata `chunk_id` dan `kategori_aturan` pada basis pengetahuan Brand Guidelines Briefly AI agar penelusuran aturan warna dan tipografi menjadi lebih granular.

---

## PEER REVIEW 2
- **Nama Rekan:** Sarah Nur Azizah (NIM: 11241008)  
- **Program Studi:** Teknik Kelautan  
- **Judul Produk:** *OceanLog AI — Peringkas Laporan Logbook Survei Oseanografi Pesisir*  

### 1. Keputusan Rancangan yang Paling Kuat
*Keputusan:* Sarah mewajibkan field `verbatim_sensor_quote` pada skema output JSON-nya untuk mencatat kutipan angka suhu permukaan laut dan salinitas langsung dari file logbook mentah.  
*Alasan:* Bidang kelautan menuntut integritas data pengukuran lapangan. Kewajiban kutipan angka mentah mempermudah dosen atau peneliti memverifikasi apakah LLM mengubah koma desimal atau salah membaca satuan knot.

### 2. Keputusan Rancangan yang Berisiko
*Keputusan:* Mengizinkan sistem mengekspor data anomali gelombang secara otomatis ke email instansi stasiun maritim tanpa titik *Human-in-the-Loop (HITL)*.  
*Alasan Risiko:* Jika model salah menginterpretasikan derau sensor (*sensor noise*) sebagai peringatan gelombang ekstrem, peringatan palsu (*false alarm*) akan terkirim ke pihak luar. Mengirim email keluar adalah tindakan berdampak tinggi yang tidak dapat dibatalkan, sehingga wajib diverifikasi oleh supervisor lab oseanografi terlebih dahulu.

### 3. Pertanyaan yang Belum Sempat Terjawab di Sesi Presentasi
> *"Apa yang terjadi bila logbook survei kapal menggunakan format waktu UTC sedangkan pencatatan stasiun darat menggunakan format WITA? Apakah sistem Anda mengonversinya secara otomatis atau berisiko salah jadwal pasang surut?"*

### 4. Hal yang Ingin Saya Tiru untuk Produk Saya (Briefly AI)
Sarah memasang peringatan visual tingkat keyakinan (*confidence badge*) warna kuning pada antarmukanya ketika data sensor minim. Saya akan menerapkan indikator serupa pada dashboard review Briefly AI: jika brief panitia minim detail, beri badge peringatan *"Informasi Terbatas"* kepada Lead Designer.
