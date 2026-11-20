# Catatan Riwayat Versi Prompt (CATATAN.md)
**Mata Kuliah:** Kapita Selekta: AI Engineering  
**Produk:** Briefly AI — Asisten Produksi Media & Desain Acara  
**Mahasiswa:** Calvin Winata (NIM: 10241016) | Kode Peserta K = 23  

Dokumen ini mencatat evolusi bertahap dari instruksi sistem (system prompt) Briefly AI sesuai siklus mingguan modul pembelajaran (Minggu 4, 5, 7, 10, 13).

---

## Tabel Riwayat Perubahan Versi

| Versi | Tanggal | Minggu Modul | Bagian yang Diubah | Alasan Perubahan | Efek yang Terlihat pada Output |
|:---:|:---:|:---:|---|---|---|
| **v1.0** | 10 Okt 2026 | Minggu 4 | Rilis awal 6 bagian dasar (Peran, Konteks, Tugas, Batasan, Few-shot, Format). Output berupa Markdown daftar. | Menetapkan basis pemahaman ekstraksi teks brief informal panitia kampus. | Ekstraksi berjalan baik pada kasus ideal, tetapi parsing otomatis gagal karena format Markdown tidak konsisten jika ada sub-butir. |
| **v1.1** | 17 Okt 2026 | Minggu 4 (Fix) | Menambahkan penegasan larangan basa-basi dan batasan "TIDAK TERCANTUM". | Menghilangkan halusinasi tanggal ketika input hanya menyebutkan hari tanpa kalender eksplisit. | Model berhenti mengarang tanggal, tetapi sesekali masih menyisipkan kalimat pengantar ramah. |
| **v2.0** | 24 Okt 2026 | Minggu 5 | Transformasi menyeluruh ke Kontrak JSON RFC 8259, penambahan field `evidence_quote` dan enum `completeness_status`. | Output harus diparsing programatis oleh framework Laravel tanpa error sintaks. | 100% output berupa objek JSON tunggal. Input yang tidak memiliki tanggal langsung terdeteksi via status `INCOMPLETE_MISSING_CORE`. |
| **v2.1** | 31 Okt 2026 | Minggu 5 (Tantangan) | Penambahan mekanisme perbaikan otomatis 3-langkah (retry on invalid JSON schema). | Mengantisipasi kegagalan ketika LLM menyertakan trailing comma atau string unescaped. | Kegagalan parsing menurun hingga 0% pada 10 variasi uji input pengacau. |
| **v3.0** | 07 Nov 2026 | Minggu 7 & 9 | Integrasi Grounding RAG 8 Chunk Brand Guidelines, kewajiban sitasi chunk sumber `[Chunk ID: X]`, dan batasan penolakan. | Desain visual dan salinan caption harus taat palet warna dan aturan resmi organisasi, bukan mengarang warna acak. | Palet warna hex (`#0F172A`, `#3B82F6`) selalu konsisten dengan chunk panduan brand. Muncul nomor sitasi chunk pada setiap slide. |
| **v4.0** | 14 Nov 2026 | Minggu 10–13 | Integrasi arsitektur Agentic, penambahan 3 mode nada komunikasi (sesuai $K=23$), guardrails 5-lapis, dan penegasan status `PENDING_REVIEW` (HITL). | Memenuhi standar agent yang terarah, aman dari prompt injection, dan wajib mendapat persetujuan Lead Designer. | Output memuat metadata telemetri orkestrator, 3 varian nada konten (`casual_energetic`, `formal_academic`, `persuasive_urgent`), dan tidak ada draf yang langsung terbit otomatis. |

---

## Analisis Perubahan Kunci (Key Learnings)

### 1. Dari Markdown ke Kontrak JSON (v1 ke v2)
Pada v1, model sering menambahkan kalimat pembuka seperti *"Tentu, ini hasil ekstraksi brief Anda:"*. Perubahan ke v2 dengan instruksi negatif tegas dan deklarasi skema JSON berhasil memaksa model menghasilkan raw JSON yang kompatibel dengan fungsi `json_decode()` di PHP backend.

### 2. Dampak Sitasi Chunk RAG (v2 ke v3)
Sebelum RAG (v2), ketika diminta menentukan warna poster, model memilih warna ungu gradasi oranye secara acak. Setelah v3 disuntikkan RAG 8 chunk brand guidelines, output selalu memilih warna primer Navy `#0F172A` dan Royal Blue `#3B82F6` dengan sitasi eksplisit `Chunk ID: 1`, membuktikan keberhasilan grounding.

### 3. Penegakan Human-in-the-Loop di Prompt dan Kode (v3 ke v4)
Instruksi v4 menegaskan bahwa luaran agent tidak boleh berstatus `APPROVED`. Namun, sesuai prinsip keamanan modul Minggu 13, aturan ini tidak hanya diletakkan pada prompt (yang rentan diabaikan), melainkan dikunci di level basis data: field `approval_status` di tabel `media_drafts` memiliki default value `PENDING_REVIEW` dan hanya controller `DesignerReviewController` yang memiliki hak mengubahnya menjadi `APPROVED`.
