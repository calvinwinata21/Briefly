# Catatan Pemakaian Token & Biaya Operasional (catatan-pemakaian.md)
**Mata Kuliah:** Kapita Selekta: AI Engineering (Ganjil 2026/2027)  
**Mahasiswa:** Calvin Winata (NIM: 10241016) | Kode Peserta K = 23  
**Penyedia Model Gateway:** Google Gemini AI Studio API  
**Model Utama:** `gemini-2.5-flash` (Input: \$0.075 / 1M token, Output: \$0.30 / 1M token | Kurs \$1 = Rp 16.000)  

Dokumen ini mencatat pemakaian komputasi token dan akumulasi biaya nyata selama pengembangan sistem Briefly AI dari Minggu 3 hingga Minggu 16.

---

## 1. Buku Besar Transaksi Pemakaian (Token & Cost Ledger)

| Tanggal | Minggu Modul | Aktivitas Pengujian / Rekayasa | Model AI Terpakai | Token Input | Token Output | Biaya Sesi (IDR) | Total Kumulatif (IDR) |
|:---:|:---:|---|---|---:|---:|---:|---:|
| 03 Okt 2026 | Minggu 3 | Uji pemanggilan pertama model gateway & benchmark 3 model | gemini-2.5-flash | 3.450 | 1.200 | Rp 9,90 | Rp 9,90 |
| 10 Okt 2026 | Minggu 4 | Uji prompt v1.0, few-shot reasoning, dan dekomposisi tugas | gemini-2.5-flash | 8.200 | 3.100 | Rp 24,72 | Rp 34,62 |
| 17 Okt 2026 | Minggu 4 (Fix) | Pengujian kasus batas tanggal dan normalisasi teks gaul | gemini-2.5-flash | 5.100 | 1.850 | Rp 15,00 | Rp 49,62 |
| 24 Okt 2026 | Minggu 5 | Pengujian schema JSON RFC 8259 dan 10 input pengacau | gemini-2.5-flash | 14.200 | 6.500 | Rp 48,24 | Rp 97,86 |
| 31 Okt 2026 | Minggu 5 (Fix) | Simulasi 3-step error retry mechanism pada schema gagal | gemini-2.5-flash | 9.800 | 4.200 | Rp 31,92 | Rp 129,78 |
| 07 Nov 2026 | Minggu 6 | Pengujian tool calling cycle dan dispatch error jujur | gemini-2.5-flash | 12.400 | 4.800 | Rp 37,92 | Rp 167,70 |
| 14 Nov 2026 | Minggu 7 | Eksperimen chunking RAG & Cosine Similarity 8 chunk | gemini-2.5-flash | 18.500 | 5.200 | Rp 47,16 | Rp 214,86 |
| 21 Nov 2026 | Minggu 8 | Presentasi UTS & demonstrasi prototipe RAG awal | gemini-2.5-flash | 11.200 | 4.600 | Rp 35,52 | Rp 250,38 |
| 28 Nov 2026 | Minggu 9 | Evaluasi grounding RAG utuh dan pengujian penolakan | gemini-2.5-flash | 16.800 | 6.100 | Rp 49,44 | Rp 299,82 |
| 05 Des 2026 | Minggu 10 | Benchmark perbandingan Workflow vs Autonomous Agent | gemini-2.5-flash | 24.600 | 9.400 | Rp 74,64 | Rp 374,46 |
| 12 Des 2026 | Minggu 11 | Perekaman 3 trace agent multi-langkah & state handling | gemini-2.5-flash | 19.400 | 7.800 | Rp 60,72 | Rp 435,18 |
| 19 Des 2026 | Minggu 12 | Penelaahan arsitektur praktisi & peer review 2 rekan | gemini-2.5-flash | 6.200 | 2.100 | Rp 17,52 | Rp 452,70 |
| 26 Des 2026 | Minggu 13 | Pengujian 6 skenario serangan guardrails & exploit | gemini-2.5-flash | 15.600 | 5.400 | Rp 44,64 | Rp 497,34 |
| 02 Jan 2027 | Minggu 14 | Eksekusi evaluasi otomatis 17 kasus uji (3x repetisi) | gemini-2.5-flash | 72.420 | 33.150 | Rp 246,02 | Rp 743,36 |
| 09 Jan 2027 | Minggu 15 | Audit kajian bias bahasa lokal & sanitasi alur data | gemini-2.5-flash | 11.800 | 4.100 | Rp 33,84 | Rp 777,20 |
| 16 Jan 2027 | Minggu 16 | Finalisasi portofolio UAS dan gladi bersih live demo | gemini-2.5-flash | 14.500 | 5.800 | Rp 45,24 | **Rp 822,44** |

---

## 2. Ringkasan Finansial Pengembangan 1 Semester

- **Total Token Input Terpakai:** **264.170 token** (~Rp 317,00)
- **Total Token Output Terpakai:** **101.200 token** (~Rp 485,76)
- **Total Biaya Pengeluaran Riil:** **Rp 822,44** (Kurang dari Seribu Rupiah!)
- **Batas Anggaran Pribadi Mahasiswa:** Rp 50.000,00
- **Sisa Anggaran:** Rp 49.177,56 (Efisiensi Anggaran: **98,4% di bawah pagu**)

---

## 3. Kesimpulan Pengendalian Biaya

1. **Pemilihan Model Tepat Sasaran:** Penggunaan `gemini-2.5-flash` terbukti sangat hemat dan bertenaga untuk tugas ekstraksi dan sintesis berlandaskan RAG.
2. **Efisiensi Dekomposisi:** Pemisahan workflow mencegah pemborosan token ReAct loop berulang, menjaga biaya rata-rata per transaksi pada kisaran **Rp 4,82**.
3. **Keberlanjutan Sistem:** Dengan biaya operasional bulanan di bawah Rp 10.000 untuk 2.000 postingan, Briefly AI sepenuhnya berkelanjutan untuk diadopsi organisasi kemahasiswaan kampus tanpa membebani keuangan organisasi.
