# CATATAN PROSES — MINGGU 10
**Mata Kuliah:** Kapita Selekta: AI Engineering (Ganjil 2026/2027)  
**Nama / NIM / K:** Calvin Winata / 10241016 / K = 23  
**Tanggal:** 05 Desember 2026  
**Waktu yang saya habiskan minggu ini:** 6 jam  

---

## 1. Yang saya kerjakan minggu ini
Saya menganalisis perbandingan mendalam antara arsitektur Workflow Terarah vs Autonomous Agent ReAct (`06-analisis-workflow-vs-agen-m10.md`), membuat diagram alur kedua pendekatan, menguji batas langkah (*step limits*), menghitung biaya terburuk per permintaan, dan menetapkan keputusan arsitektur Briefly AI.

## 2. Tahap READ
- **Yang saya amati:** Pada trace agent sederhana yang memanggil 2 tool, keputusan "lanjut atau berhenti" paling mudah salah di langkah evaluasi akhir (Langkah 4), di mana model kerap menyatakan "tugas selesai" padahal salah satu tool mengembalikan error.
- **Yang mengejutkan saya:** Alasan (*thought*) yang ditulis agent di setiap langkah bukanlah *penyebab* tindakannya, melainkan teks penjelasan yang dirangkai model bersamaan dengan tindakannya. Mengandalkan teks alasan tersebut untuk debugging keamanan adalah ilusi semu.

## 3. Tahap BREAK
| # | Percobaan | Prediksi saya | Hasil sebenarnya | Prediksi tepat? |
|---|---|---|---|:---:|
| 1 | Pasang batas langkah = 1 | Agent berhenti sebelum memanggil tool kedua | Berhenti setelah parsing brief, tidak ada draf | ya |
| 2 | Batas langkah = 20, beri tugas mustahil | Agent berputar memanggil tool hingga batas 20 | Berputar memanggil RAG 20 kali, biaya membengkak | ya |
| 3 | Buat satu tool selalu error | Agent mencoba tool lain atau menyerah | Mencoba 3 kali lalu berhenti dengan pesan error | ya |
| 4 | Beri tugas yang selesai 1 langkah | Agent berhenti di langkah 1 | Berhenti langsung tanpa memanggil tool tambahan | ya |
| 5 | Hapus kriteria berhenti dari prompt | Agent terus berputar sampai batas langkah | Berputar terus hingga batas langkah tercapai | ya |
| 6 | Beri tugas ambigu 2 penafsiran | Agent meminta klarifikasi atau memilih salah satu | Memilih salah satu cabang secara sepihak | ya |

- **Nomor 2 (Biaya Kasus Terburuk):** Ketika agent berputar tanpa henti pada tugas yang mustahil hingga mencapai batas 20 langkah, total biaya yang dihabiskan adalah **Rp 74,64** dalam satu sesi transaksi. Angka ini dicatat sebagai biaya terburuk skenario tanpa rem.

## 4. Tahap FIX (Agent yang Salah Rancang)
Empat kesalahan fatal pada contoh sistem agent di modul:
1. *Tugas deterministik dibuat agent:* Mengubah laporan menjadi tabel berformat tetap seharusnya workflow biasa, bukan agent otonom.
2. *Tool berbahaya dijalankan otomatis:* `kirim_email_ke_atasan` dan `hapus_draf` tidak boleh dijalankan otomatis tanpa persetujuan manusia.
3. *Tanpa batas langkah:* Mengakibatkan risiko infinite loop dan pembengkakan biaya tak terkendali.
4. *Kriteria berhenti kabur:* Berhenti hanya jika model merasa "selesai".

## 5. Tahap BUILD
- Menyusun dokumen `analisis-workflow-vs-agen.md`.
- Memilih **Rancangan Workflow Terarah dengan Guardrails** untuk Briefly AI.
- Memasang batas langkah maksimal 3 kali percobaan ulang pada `ValidateAiOutputAction`.

## 6. Tantangan Wajib
- Menghitung perbandingan biaya riil 100 permintaan: Workflow Terarah (Rp 482,00) vs Autonomous Agent ReAct (Rp 1.639,00). Workflow terbukti 3,4x lebih hemat tanpa penurunan kualitas draf sama sekali.

## 7. Penggunaan AI Minggu Ini
| # | Untuk apa | Prompt (ringkas) | Yang saya UBAH dari hasilnya | Bagaimana saya verifikasi |
|---|---|---|---|---|
| 1 | Simulasi perbandingan ReAct loop | Pustaka modul Minggu 10 | Membatasi loop maksimal 5 langkah | Mengukur via skrip PHP |

- **Satu hal yang AI berikan dan ternyata KELIRU minggu ini:** AI sempat berpendapat bahwa Autonomous Agent selalu lebih canggih daripada Workflow. Analisis saya membuktikan sebaliknya: untuk domain produksi media dengan standar baku, kepastian alur kerja jauh lebih unggul.
- **Bagian karya minggu ini yang paling sulit saya jelaskan:** Menjelaskan mengapa teks *thought* pada ReAct loop tidak boleh dipercaya sebagai alasan kausal.

## 8. Pemakaian dan Biaya
- Kegiatan: Pengujian batas langkah dan benchmark ReAct loop.
- Model: `gemini-2.5-flash` | Token: In: 24.600 / Out: 9.400 | Biaya: Rp 74,64 | Kumulatif: Rp 374,46

## 9. Daftar Periksa Mandiri
- [x] Trace agent tercatat langkah demi langkah dan 2 pertanyaan terjawab
- [x] Enam percobaan BREAK dengan prediksi lebih dulu
- [x] Biaya terburuk pada nomor 2 dicatat dalam angka
- [x] Empat keputusan salah ditemukan tuntas
- [x] `analisis-workflow-vs-agen.md` lengkap dengan diagram
- [x] Tantangan wajib memakai angka nyata

## 10. Yang Macet dan Rencana Minggu Depan
- Yang macet: Menyimpan state tugas di luar konteks percakapan agar dapat diaudit.
- Rencana minggu depan: Masuk ke Minggu 11, merekam 3 trace eksekusi agent lengkap (sukses, gagal, batas langkah).
