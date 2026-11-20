# CATATAN PROSES — MINGGU 12
**Mata Kuliah:** Kapita Selekta: AI Engineering (Ganjil 2026/2027)  
**Nama / NIM / K:** Calvin Winata / 10241016 / K = 23  
**Tanggal:** 19 Desember 2026  
**Waktu yang saya habiskan minggu ini:** 4,5 jam  

---

## 1. Yang saya kerjakan minggu ini
Minggu ini tidak ada tahap BUILD untuk produk kode. Seluruh minggu saya manfaatkan untuk membedah studi kasus arsitektur agent praktisi di dunia nyata, melakukan analisis titik rawan kegagalan, menyusun Lembar Peer Review kedua untuk 2 rekan dari prodi berbeda (`07-peer-review-m12.md`), dan menuliskan bantahan kritis atas saran pengubahan produk saya.

## 2. Tahap READ
- **Yang saya amati:** Pada arsitektur agent komersial di industri, pemanggilan model besar otonom hampir selalu dibatasi oleh lapisan-lapisan deterministik (*deterministic wrappers*) dan cache respons untuk mengendalikan biaya.
- **Yang mengejutkan saya:** Di sistem nyata, lebih dari 70% pemanggilan model adalah pemanggilan tugas rutin kecil (klasifikasi dan ekstraksi) menggunakan model murah (seperti Gemini Flash atau model 8B), bukan model raksasa.

## 3. Tahap BREAK
Membedah 5 cara membuat sistem agent gagal:
1. *Serangan Injeksi Prompt Tidak Langsung via Dokumen:* Memasukkan instruksi jahat di file PDF rujukan yang dipercaya sistem.
2. *Manipulasi Status Persetujuan:* Mencoba memanipulasi parameter payload agar status langsung berubah menjadi `APPROVED`.
3. *Lonjakan Biaya via Infinite ReAct Loop:* Memberikan tugas yang tidak memiliki kriteria selesai yang jelas.
4. *Kegagalan Jaringan API Eksternal:* Mematikan koneksi internet saat agent sedang mengeksekusi langkah ke-3.
5. *Eksploitasi Batas Akses Tool:* Meminta model memanggil fungsi internal sistem file server.

## 4. Tahap FIX (Peer Review Kedua)
- Me-review karya Rio Ferdinand (Bisnis Digital — ReviewSense) dan Anisa Rahmawati (Teknik Industri — WorkGuide AI).
- Memberikan masukan terkait bahaya tool penerbitan voucher kompensasi otomatis tanpa persetujuan finansial dan risiko multi-agent yang memperlambat latensi hingga 14 detik.

## 5. Tahap BUILD
(Sesuai petunjuk modul Minggu 12, tahap BUILD produk ditiadakan; waktu dipakai untuk analisis dan penguatan arsitektur).

## 6. Tantangan Wajib
- Menuliskan bantahan rasional terhadap kritik rekan yang menyarankan agar jumlah slide carousel dibuat dinamis (3 sampai 8 slide). Saya mempertahankan batasan tetap 4 slide demi retensi keterbacaan audiens ponsel, stabilitas rendering UI, dan pengendalian biaya token output.

## 7. Penggunaan AI Minggu Ini
| # | Untuk apa | Prompt (ringkas) | Yang saya UBAH dari hasilnya | Bagaimana saya verifikasi |
|---|---|---|---|---|
| 1 | Analisis titik rawan arsitektur praktisi | Prompt Pack 12A | Mengarahkan analisis ke risiko kebocoran data kampus | Menelaah artikel arsitektur engineering blog |

- **Satu hal yang AI berikan dan ternyata KELIRU minggu ini:** AI sempat menyatakan bahwa sistem agent di industri tidak memerlukan pengawasan manusia jika akurasinya di atas 95%. Faktanya, untuk tindakan legal dan publikasi korporat, *Human-in-the-Loop* adalah kewajiban tata kelola risiko yang tidak bisa ditawar.
- **Bagian karya minggu ini yang paling sulit saya jelaskan:** Menjelaskan mengapa arsitektur multi-agent seringkali merupakan bentuk *over-engineering* untuk kebutuhan tugas satu semester.

## 8. Pemakaian dan Biaya
- Kegiatan: Analisis arsitektur dan diskusi peer review.
- Model: `gemini-2.5-flash` | Token: In: 6.200 / Out: 2.100 | Biaya: Rp 17,52 | Kumulatif: Rp 452,70

## 9. Daftar Periksa Mandiri
- [x] Enam pertanyaan analisis terjawab di kelas
- [x] Lima cara membuat sistem gagal dipetakan
- [x] Tiga kritik kuat memenuhi syarat
- [x] Dua lembar peer review dikumpulkan dan dibagikan ke rekan
- [x] Tantangan wajib (bantahan rasional) terselesaikan

## 10. Yang Macet dan Rencana Minggu Depan
- Yang macet: Membangun mekanisme penangkapan serangan injeksi prompt di level kode.
- Rencana minggu depan: Masuk ke Minggu 13 (Guardrails & Human-in-the-Loop), menyusun `guardrails.md`, dan menguji 6 skenario serangan.
