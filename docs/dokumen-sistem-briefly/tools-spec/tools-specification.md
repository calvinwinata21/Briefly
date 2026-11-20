# Spesifikasi Formal Tools / Actions Agen Briefly AI
**Mata Kuliah:** Kapita Selekta: AI Engineering  
**Tahap Modul:** Minggu 6 & 11 (Blok B & D — Tool Calling & Agent Capabilities)  
**Lingkup Wajib K = 23:** $2 + (K \bmod 2) = 2 + (23 \bmod 2) = \mathbf{3\text{ Tools Wajib}}$  
**Mahasiswa:** Calvin Winata (NIM: 10241016) | Kode Peserta K = 23  

---

## 1. Daftar 3 Tools / Actions Terdaftar

| # | Nama Action Class | Kategori Operasi | Hak Akses I/O | Lokasi Penegakan Batasan |
|:---:|---|---|---|---|
| **1** | `App\Actions\ParseBriefAction` | Deterministic / LLM Parsing | Read (Raw Text) → Pure Output Array | Kode PHP (`app/Actions/ParseBriefAction.php`) |
| **2** | `App\Actions\ExecuteRagSearchAction` | Vector Similarity Retrieval | Read-Only (Tabel `brand_guidelines`) | Kode PHP & Database Query Builder |
| **3** | `App\Actions\ValidateAiOutputAction` | Contract Validation & Fallback | Pure Validation & Mutation State | Kode PHP (`app/Actions/ValidateAiOutputAction.php`) |

---

## 2. Rincian Spesifikasi Masing-Masing Tool

### Tool 1: `ParseBriefAction`

```php
namespace App\Actions;

class ParseBriefAction
{
    /**
     * Mengekstraksi entitas kunci dan memverifikasi kelengkapan brief.
     *
     * @param string $rawBriefText Teks brief mentah dari panitia.
     * @param string $eventTitle Judul awal yang diinput requester.
     * @return array{
     *     event_name: string|null,
     *     event_date: string|null,
     *     target_audience: string|null,
     *     key_highlights: array<int, string>,
     *     contact_person: string|null,
     *     is_complete: bool,
     *     missing_fields: array<int, string>
     * }
     */
    public function execute(string $rawBriefText, string $eventTitle): array;
}
```

- **Kegunaan:** Membaca teks brief bahasa Indonesia yang bebas/informal, mengekstraksi 5 entitas inti, dan memeriksa apakah kelengkapan minimal (`event_name` dan `event_date`) terpenuhi.
- **Input Parameter:**
  - `raw_brief_text` (string, required): Teks deskripsi acara bebas.
  - `event_title` (string, required): Judul acara input form.
- **Output:** Array terstruktur berisi entitas, flag `is_complete`, dan daftar field yang hilang (`missing_fields`).
- **Kapan TIDAK Boleh Dipakai:**
  - JANGAN dipakai untuk mencari aturan brand visual (gunakan `ExecuteRagSearchAction`).
  - JANGAN dipakai untuk menghasilkan slide desain atau caption publikasi (itu tugas model LLM synthesis).
- **Batas Akses Teknis (Access Boundaries):**
  - Hanya membaca string teks yang diberikan.
  - Tidak memiliki akses ke basis data, session, kredensial pengguna, atau internet eksternal.
  - Diterapkan 100% pada level kode PHP melalui fungsi reguler tanpa side-effects.

---

### Tool 2: `ExecuteRagSearchAction`

```php
namespace App\Actions;

use App\Services\VectorSearchService;

class ExecuteRagSearchAction
{
    /**
     * Mengambil chunk panduan brand paling relevan dari basis pengetahuan vektor.
     *
     * @param string $query Konteks query pencarian (nama acara + nada konten).
     * @param int $limit Jumlah maksimum chunk yang dikembalikan (default: 3).
     * @param float $minSimilarity Ambang batas kemiripan minimum (default: 0.40).
     * @return array<int, array{
     *     chunk_id: int,
     *     category: string,
     *     rule_title: string,
     *     content: string,
     *     similarity_score: float
     * }>
     */
    public function execute(string $query, int $limit = 3, float $minSimilarity = 0.40): array;
}
```

- **Kegunaan:** Menghitung skor kemiripan kosinus berbasis frekuensi token antara teks query acara dan 8 chunk `BrandGuideline`, menyaring chunk yang melewati ambang batas $0.40$, dan mengembalikan metadata aturan desain.
- **Input Parameter:**
  - `query` (string, required): Teks topik/kategori acara dan nada komunikasi yang diinginkan.
  - `limit` (integer, optional, default: 3): Maksimum chunk yang diambil.
  - `min_similarity` (float, optional, default: 0.40): Batas bawah kosinus kemiripan.
- **Output:** Array berisi chunk teratas yang relevan beserta skor kemiripan dan isi aturan brand.
- **Kapan TIDAK Boleh Dipakai:**
  - JANGAN dipakai untuk mencari data pengguna, biodata panitia, atau riwayat brief (tabel lain selain `brand_guidelines`).
  - JANGAN dipakai untuk melakukan pencarian di mesin pencari publik (Google, Bing).
- **Batas Akses Teknis (Access Boundaries):**
  - **Read-Only Enforced:** Hanya mengeksekusi query `SELECT` pada tabel `brand_guidelines`. Tidak ada hak `INSERT`, `UPDATE`, atau `DELETE`.
  - Terbatas pada folder storage basis pengetahuan brand organisasi kampus.

---

### Tool 3: `ValidateAiOutputAction`

```php
namespace App\Actions;

class ValidateAiOutputAction
{
    /**
     * Memvalidasi kepatuhan payload JSON AI terhadap skema kontrak resmi.
     *
     * @param mixed $rawPayload Payload JSON hasil generasi model AI.
     * @return array{
     *     is_valid: bool,
     *     validated_data: array|null,
     *     validation_errors: array<int, string>,
     *     repair_attempts: int
     * }
     */
    public function execute(mixed $rawPayload): array;
}
```

- **Kegunaan:** Memeriksa apakah output JSON yang dihasilkan model memenuhi seluruh kontrak tipe data, enum, dan keberadaan field wajib. Jika terjadi kesalahan ketik kecil (misal: markdown wrapper ```json ... ```), tool membersihkan string sebelum parsing.
- **Input Parameter:**
  - `raw_payload` (string|array, required): Respon teks mentah dari model AI.
- **Output:** Array status validitas, data yang berhasil divalidasi, daftar error (jika ada), dan jumlah upaya perbaikan.
- **Kapan TIDAK Boleh Dipakai:**
  - JANGAN dipakai untuk memodifikasi konten brief acara asli.
  - JANGAN menyembunyikan error validasi fatal dengan mengganti data secara diam-diam tanpa mencatat log perbaikan.
- **Batas Akses Teknis (Access Boundaries):**
  - Beroperasi murni pada pemrosesan memori string dan array di PHP runtime.
  - Tidak memanggil model eksternal dan tidak memodifikasi status basis data secara langsung.

---

## 3. Matriks Batas Akses & Titik Human-in-the-Loop (Modul Minggu 6 & 13)

| Tindakan Sistem | Otomatis atau Butuh Persetujuan Manusia? | Rationale / Alasan Keamanan |
|---|:---:|---|
| Ekstraksi entitas brief mentah | **Otomatis** | Operasi baca internal tanpa risiko mutasi data eksternal. |
| Pencarian RAG Brand Rules | **Otomatis** | Operasi baca internal terhadap dokumen publik/resmi organisasi. |
| Generasi draf media (JSON) | **Otomatis (Status: PENDING_REVIEW)** | Tahap pembuatan draf internal; belum berdampak keluar sistem. |
| **Penerbitan / Publikasi Konten** | **WAJIB PERSETUJUAN (HITL Gate)** | **Tindakan berdampak tinggi:** publikasi ke Instagram, WA broadcast, atau LinkedIn melibatkan reputasi organisasi dan tidak dapat dibatalkan. Wajib disetujui Lead Designer. |
| **Penghapusan Riwayat Brief** | **WAJIB PERSETUJUAN (Admin)** | Mencegah hilangnya audit trail kegiatan akademik. |
