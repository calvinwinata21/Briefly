# CATATAN PROSES — MINGGU 11
**Mata Kuliah:** Kapita Selekta: AI Engineering (Ganjil 2026/2027)  
**Nama / NIM / K:** Calvin Winata / 10241016 / K = 23  
**Tanggal:** 12 Desember 2026  
**Waktu yang saya habiskan minggu ini:** 6,5 jam  

---

## 1. Yang saya kerjakan minggu ini
Saya menyelesaikan implementasi orkestrasi agent multi-langkah pada Briefly AI (`08_agent_orchestrator_simulation.php`), mengintegrasikan 3 mode nada komunikasi ($K = 23 \implies \mathbf{3\text{ mode nada}}$), menyimpan state tugas di tabel SQLite `media_drafts` (di luar context window), dan mendokumentasikan 3 file trace eksekusi lengkap (`trace-01-tugas-sukses.md`, `trace-02-tugas-gagal-terdeteksi.md`, `trace-03-tugas-batas-langkah.md`).

## 2. Tahap READ
- **Yang saya amati:** Pada trace tugas yang gagal, titik di mana kegagalan *terlihat* (di langkah terakhir saat JSON schema error) hampir selalu berakar dari langkah awal (Langkah 1 saat ekstraksi tanggal menghasilkan nilai null namun tetap dipaksakan lanjut).
- **Yang mengejutkan saya:** Memisahkan state tugas ke dalam tabel basis data terstruktur memungkinkan sistem melanjutkan tugas yang sempat terhenti (misal: saat requester merevisi brief) tanpa harus mengulang proses ekstraksi dari awal.

## 3. Tahap BREAK
| # | Percobaan | Prediksi saya | Hasil sebenarnya | Prediksi tepat? |
|---|---|---|---|:---:|
| 1 | Jalankan tugas sama 3 kali, bandingkan urutan langkah | Urutan tool sama, kalimat caption variatif | Urutan tool 100% identik (Parse $\rightarrow$ RAG $\rightarrow$ Synthesis) | ya |
| 2 | Hentikan tugas di tengah jalan, lanjutkan dari state terakhir | Sistem mampu resume tanpa hilang data | Berhasil membaca state dari database dan melanjutkan | ya |
| 3 | Beri tugas yang datanya tidak lengkap (tanpa tanggal) | Sistem langsung berhenti di langkah 1 | Berhenti dengan status `incomplete_data` | ya |
| 4 | Jalankan tugas panjang, ukur pertumbuhan token | Token membengkak jika konteks menumpuk | Context window tetap stabil karena riwayat di-clear per tahap | ya |
| 5 | Tool RAG mengembalikan hasil salah tapi masuk akal | Agent mungkin tertipu jika tidak divalidasi | Skema validator Lapis 4 berhasil menangkap ketidaksesuaian warna | ya |
| 6 | Beri 2 tugas brief sekaligus dalam 1 input | Agent bingung atau menggabungkan acara | Model mencoba menggabungkan dua judul menjadi satu | ya |

- **Nomor 1:** Karena Briefly AI menggunakan arsitektur hybrid workflow, urutan eksekusi tool bersifat deterministik 100%. Ini sangat mempermudah pengujian otomatis di CI/CD.

## 4. Tahap FIX (Agent Berputar Tanpa Henti)
Kasus agent berputar pada tugas mencari 3 peraturan dianalisis:
1. *Penyebab 1:* Model tidak menandai chunk mana yang sudah pernah diambil, sehingga selalu memanggil query yang sama.
2. *Penyebab 2:* Kriteria berhenti mensyaratkan 3 peraturan, padahal dokumen yang relevan hanya ada 2.
3. *Pencegahan:* Simpan daftar ID dokumen yang sudah terambil di luar context window, dan tambahkan kondisi: jika pemanggilan kedua mengembalikan hasil identik, agent **wajib berhenti** dan melaporkan dokumen tidak bertambah.

## 5. Tahap BUILD
- Memastikan 3 tools wajib terhubung: `ParseBriefAction`, `ExecuteRagSearchAction`, `ValidateAiOutputAction`.
- Mengimplementasikan 3 trace lengkap di folder `docs/dokumen-sistem-briefly/trace-agent/`.
- Memastikan status draf selalu tersimpan sebagai `PENDING_REVIEW`.

## 6. Tantangan Wajib
- Menunjukkan tugas yang sempat gagal: Brief acara yang menyertakan tanggal dalam format hari lisan (*"malam minggu besok"*). Awalnya gagal karena model mencoba menebak kalender.
- Perbaikan: Memperbarui deskripsi `ParseBriefAction` agar secara eksplisit meminta kepastian tanggal kalender jika terdeteksi frasa waktu relatif.

## 7. Penggunaan AI Minggu Ini
| # | Untuk apa | Prompt (ringkas) | Yang saya UBAH dari hasilnya | Bagaimana saya verifikasi |
|---|---|---|---|---|
| 1 | Menyusun struktur logging trace JSON | Format telemetry ReAct | Mengeliminasi field internal yang tidak perlu | Membaca trace file di editor |

- **Satu hal yang AI berikan dan ternyata KELIRU minggu ini:** AI sempat menyarankan agar seluruh riwayat percakapan disimpan ke database dalam satu kolom teks panjang. Saya menolaknya; state disimpan dalam kolom-kolom terpisah (`event_metadata`, `branding_rules`, `visual_slides`) agar dapat di-query secara langsung dengan SQL.
- **Bagian karya minggu ini yang paling sulit saya jelaskan:** Menjelaskan perbedaan antara memory jangka pendek (session context) dan state terstruktur tugas.

## 8. Pemakaian dan Biaya
- Kegiatan: Perekaman 3 trace agent dan pengujian multi-langkah.
- Model: `gemini-2.5-flash` | Token: In: 19.400 / Out: 7.800 | Biaya: Rp 60,72 | Kumulatif: Rp 435,18

## 9. Daftar Periksa Mandiri
- [x] Trace tugas yang gagal diuraikan sampai ke langkah awal
- [x] Enam percobaan BREAK dijalankan dengan prediksi lebih dulu
- [x] Tiga kemungkinan penyebab dan pencegahan kasus FIX tertulis
- [x] Tiga trace lengkap tersimpan dan dapat diaudit
- [x] Luaran Blok D bagian 1 terselesaikan

## 10. Yang Macet dan Rencana Minggu Depan
- Yang macet: Menganalisis studi kasus arsitektur agent praktisi di dunia nyata.
- Rencana minggu depan: Masuk ke Minggu 12 (Studi Kasus Praktisi) dan melakukan Peer Review kedua.
