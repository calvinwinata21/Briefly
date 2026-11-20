# LEMBAR TEMA PROYEK — KAPITA SELEKTA: AI ENGINEERING
**Semester Ganjil 2026/2027 | Bobot 2 SKS**  
**Dosen Pengampu:** Aidil Saputra Kirsan, S.ST., M.Tr.Kom.  

---

Nama            : Calvin Winata  
NIM             : 10241016  
Program studi   : Sistem Informasi  
Kode peserta K  : 23  

## Angka lingkup saya (Penurunan Wajib dari K = 23)
- Dokumen rujukan minimum : $5 + (K \bmod 4) = 5 + (23 \bmod 4) = 5 + 3 =$ **8 dokumen / chunk**
- Kasus uji minimum       : $12 + (K \bmod 6) = 12 + (23 \bmod 6) = 12 + 5 =$ **17 kasus**
- Tool minimum            : $2 + (K \bmod 2) = 2 + (23 \bmod 2) = 2 + 1 =$ **3 tool**
- Mode output tambahan    : K=23 ganjil $\implies$ Tidak wajib mode ringkas, namun produk menyediakan **3 Mode Nada Konten** ($K \bmod 3 + 1 = 3$: `casual_energetic`, `formal_academic`, `persuasive_urgent`).

---

## 1. Persoalan
**Judul kerja produk :**  
Briefly AI — Asisten Produksi Media & Desain Acara Berbasis AI Agent

**Siapa yang mengalami persoalan ini :**  
Divisi Media dan Publikasi Himpunan Mahasiswa Sistem Informasi (HMSI) serta panitia pelaksana kegiatan mahasiswa/organisasi di kampus Institut Teknologi Kalimantan (ITK).

**Bagaimana persoalan ini diselesaikan sekarang :**  
Panitia acara mengirimkan deskripsi kegiatan melalui chat WhatsApp yang tidak beraturan ke desainer grafis. Desainer harus membaca manual, mengekstrak informasi satu per satu, mengingat kode warna hex dan font brand himpunan, menyusun draf layout poster/carousel di Canva atau Photoshop, dan menulis 3 versi caption terpisah untuk Instagram, broadcast WhatsApp, dan pengumuman LinkedIn.

**Mengapa cara sekarang tidak memadai :**  
Proses tersebut memakan waktu 4–8 jam per acara. Desainer sering salah mengetik nomor kontak, keliru menerapkan palet warna resmi (warna tidak konsisten antar-acara), dan caption media sosial sering terlambat diunggah karena beban kerja desainer menumpuk menjelang tanggal kegiatan.

---

## 2. Mengapa AI Generatif
**Mengapa persoalan ini TIDAK cukup diselesaikan dengan basis data, formulir, atau seperangkat aturan biasa :**  
Panitia pelaksana kegiatan tidak pernah mengisi brief dalam formulir kaku dengan benar. Mereka sering menulis secara naratif, memakai singkatan percakapan (*"bsk lusa di sekre bwt baksos"*), dan menggabungkan informasi jam serta tanggal dalam satu kalimat. Sistem berbasis aturan reguler (*regex*) atau form kaku langsung gagal ketika panitia mengubah susunan kata. Diperlukan model bahasa alami yang mampu melakukan ekstraksi semantik toleran terhadap variasi bahasa bebas, namun tetap terikat kontrak data JSON.

**Apa yang membuat persoalan ini butuh kemampuan memahami bahasa :**  
Memahami konteks jenis kegiatan (seminar akademik vs lomba santai) agar dapat memilih nada komunikasi (*tone of voice*) yang tepat, membedakan antara tanggal pendaftaran dengan tanggal pelaksanaan acara, serta menyusun salinan publikasi persuasif multi-kanal yang menarik audiens mahasiswa.

---

## 3. Ukuran Keberhasilan
**Apa yang menjadi bukti bahwa produk ini berhasil :**  
Sistem mampu mengonversi brief teks mentah informal menjadi draf terstruktur lengkap (4 slide layout carousel visual + 3 format caption media sosial) dalam waktu kurang dari 5 detik, dengan 100% kepatuhan terhadap palet warna resmi (`#0F172A`, `#3B82F6`) dan rasio kelulusan skema data minimal 90% pada set uji 17 kasus.

**Siapa yang dapat memeriksa output-nya benar atau salah :**  
Lead Designer (Ketua Divisi Media) melalui antarmuka *Human-in-the-Loop (HITL) Review Dashboard*.

**Apa yang terjadi bila output-nya salah, dan siapa yang menanggungnya :**  
Jika AI salah mengekstrak tanggal atau narasumber, draf tidak akan langsung terbit ke publik. Lead Designer melihat kesalahan tersebut pada tahap review dan menolak draf dengan memberikan catatan revisi kepada pembuat brief. Tanggung jawab akhir berada pada Lead Designer yang menyetujui materi publikasi.

---

## 4. Dokumen Rujukan (8 Chunk Panduan Brand HMSI)
| # | Judul Dokumen / Chunk | Penerbit / Asal | Tahun | Halaman / Scope | Status Kelayakan |
|---|---|---|:---:|---|:---:|
| 1 | Palet Warna Primer (`CHK-01`) | Divisi Media HMSI | 2026 | Hal. 4 (Warna Navy `#0F172A` & Blue `#3B82F6`) | **Lolos** (Dokumen Resmi) |
| 2 | Warna Aksen & CTA (`CHK-02`) | Divisi Media HMSI | 2026 | Hal. 6 (Warna Amber `#F59E0B` & Emerald `#10B981`) | **Lolos** (Dokumen Resmi) |
| 3 | Tipografi Display / Heading (`CHK-03`) | Divisi Media HMSI | 2026 | Hal. 8 (Plus Jakarta Sans Bold 700) | **Lolos** (Dokumen Resmi) |
| 4 | Tipografi Body Text (`CHK-04`) | Divisi Media HMSI | 2026 | Hal. 9 (Inter Regular 400, max 35 kata/slide) | **Lolos** (Dokumen Resmi) |
| 5 | Arsitektur 4 Slide Carousel (`CHK-05`) | Divisi Media HMSI | 2026 | Hal. 12 (Hook, Problem, Offering, CTA) | **Lolos** (Dokumen Resmi) |
| 6 | Brand Voice Kasual Energik (`CHK-06`) | Divisi Media HMSI | 2026 | Hal. 15 (Sapaan "Halo Rekan SI!", max 3 emoji) | **Lolos** (Dokumen Resmi) |
| 7 | Brand Voice Formal Akademik (`CHK-07`) | Divisi Media HMSI | 2026 | Hal. 17 (Bahasa baku, "Yth. Sivitas Akademika") | **Lolos** (Dokumen Resmi) |
| 8 | Kanal & Tagar Resmi (`CHK-08`) | Divisi Media HMSI | 2026 | Hal. 20 (Tagar wajib, format wa.me/) | **Lolos** (Dokumen Resmi) |

**Konfirmasi:**
- [x] Seluruh dokumen sah saya pakai (Dokumen internal organisasi yang telah disetujui publikasinya untuk kegiatan mahasiswa).
- [x] Tidak ada data pribadi orang sungguhan di dalamnya (Menggunakan data sintetis/dummy kampus).
- [x] Sedikitnya enam pertanyaan uji saya terjawab oleh kumpulan ini (Tercakup pada 17 kasus uji).

---

## 5. Uji Lima Kriteria Penolakan
1. **AI tidak dipaksakan pada persoalan deterministik :** LOLOS. Penyimpanan data, relasi user, dan autentikasi ditangani oleh Laravel & SQLite; AI hanya dipakai pada ekstraksi bahasa bebas dan sintesis materi kreatif.
2. **Output dapat dibuktikan benar atau salah :** LOLOS. Kebenaran tanggal, nama acara, dan palet warna dapat dicek langsung terhadap brief asli dan dokumen brand guidelines.
3. **Sumber pengetahuan tersedia dan sah :** LOLOS. Menggunakan Buku Panduan Identitas Visual HMSI 2026 yang dapat diakses publik.
4. **Tidak bergantung pada data pribadi nyata :** LOLOS. Kontak narahubung pada set uji menggunakan nomor dummy (`0812-3344-5566`).
5. **Lingkupnya muat dikerjakan seorang diri 12 minggu :** LOLOS. Fokus sempit pada otomasi draf media organisasi kemahasiswaan dengan arsitektur monolitik Laravel.

---

## 6. Batas yang Saya Tetapkan Sendiri
**Produk ini TIDAK dimaksudkan untuk :**  
Menghasilkan aset desain bitmap/vektor final secara otomatis tanpa supervisi, atau memposting langsung ke API Instagram tanpa persetujuan manusia.

**Keputusan apa yang TIDAK boleh diserahkan kepada produk ini :**  
Persetujuan kelayakan rilis materi publikasi ke publik dan penetapan kebijakan identitas brand organisasi baru.

---

## 7. Rencana Lapisan
| Blok | Yang Akan Saya Tambahkan | Risiko Terbesar |
|---|---|---|
| **B — Kendali (Mgg 4–6)** | JSON Schema kontrak data, few-shot prompt, 3 action tools | Model menghasilkan JSON tidak valid (syntax error / trailing comma). |
| **C — Grounding (Mgg 7–9)** | Vector Search Service berbasis Cosine Similarity untuk 8 chunk brand | Retrieval mengambil chunk yang salah karena kata kunci tidak cocok. |
| **D — Agentic (Mgg 10–13)** | Multi-step pipeline, 3 mode nada komunikasi, Guardrails 5-lapis, HITL review | Loop pemanggilan berulang (infinite retry) jika API error. |
| **E — Kematangan (Mgg 14–16)**| Set uji 17 kasus, analisis biaya operasional, kajian etika & bias data | Bias representasi bahasa lokal dan ketergantungan pada model komersial. |

---

Tanda tangan mahasiswa : **Calvin Winata**  
Tanggal : **10 Oktober 2026**  
Catatan dosen : **Tema disetujui. Lingkup K = 23 (8 chunk, 17 kasus uji, 3 tool) sudah sesuai.**  
Vonis : **DITERIMA**  
