# Praktik & Implementasi Pembelajaran — Briefly AI
### Repositori Skrip Eksperimen & Pembuktian Modul Kapita Selekta: AI Engineering
**Nama:** Calvin Winata (NIM: 10241016) | Kode Peserta K = 23  
**Program Studi:** Sistem Informasi — Institut Teknologi Kalimantan  

Folder ini memuat kumpulan skrip mandiri (runnable standalone scripts) yang mengimplementasikan dan membuktikan konsep-konsep inti setiap blok pembelajaran pada produk **Briefly AI**.

---

## 📂 Daftar Skrip Eksperimen

| File Skrip | Minggu Modul | Topik Pokok Bahasan | Yang Dibuktikan |
|---|:---:|---|---|
| [`01_tes_lanskap_ai.php`](01_tes_lanskap_ai.php) | Minggu 1 | Lanskap: AI Engineering vs Aturan Deterministik | Bukti kapan regex/kode biasa cukup (validasi NIM) dan kapan AI generatif esensial (ekstraksi brief teks bebas). |
| [`02_token_cost_temperature_exp.php`](02_token_cost_temperature_exp.php) | Minggu 2 | Token, Biaya, Temperature & Uji Halusinasi | Perhitungan biaya token Bahasa Indonesia, perbandingan temperature 0.0 vs 1.0, dan penolakan 5 pertanyaan tanpa dasar. |
| [`03_model_gateway_call.php`](03_model_gateway_call.php) | Minggu 3 | Model Gateway & Penanganan Kredensial Aman | Membaca API key dari `.env` secara aman, mengukur latensi pemanggilan, dan mekanisme graceful offline fallback. |
| [`04_prompt_engineering_reasoning.php`](04_prompt_engineering_reasoning.php) | Minggu 4 | Prompt Engineering & Task Decomposition | Perbandingan Zero-shot vs Few-shot pada teks gaul/singkatan, serta memecah tugas besar menjadi 3 sub-tugas terukur. |
| [`05_structured_output_validator.php`](05_structured_output_validator.php) | Minggu 5 | Structured Output & Schema Validator | Validasi kepatuhan skema JSON RFC 8259 terhadap 10 variasi input pengacau (perturbation inputs). |
| [`06_tool_calling_simulation.php`](06_tool_calling_simulation.php) | Minggu 6 | Tool Calling & Penanganan Error Jujur | Siklus pemanggilan tool, pengaruh deskripsi batasan tool, dan perbandingan error jujur vs error disembunyikan. |
| [`07_rag_retrieval_simulation.php`](07_rag_retrieval_simulation.php) | Minggu 7 & 9 | RAG Retrieval & Cosine Similarity Engine | Pemuatan 8 chunk brand guidelines HMSI, cosine similarity token, filter ambang batas 0.40, dan penolakan query luar domain. |
| [`08_agent_orchestrator_simulation.php`](08_agent_orchestrator_simulation.php) | Minggu 10 & 11 | Agentic ReAct Cycle & State Management | Siklus Reason-Act-Observe-Evaluate, batas langkah (max 5 steps), audit trace terstruktur, dan penyerahan ke HITL. |
| [`09_guardrails_testing_suite.php`](09_guardrails_testing_suite.php) | Minggu 13 | Guardrails 5-Lapis & Uji Serangan Keamanan | Pengujian ketahanan sistem terhadap 6 skenario serangan (direct prompt injection, jailbreak, eksploitasi tool, bypass approval). |
| [`10_evaluasi_otomatis_runner.php`](10_evaluasi_otomatis_runner.php) | Minggu 14 | Automated Evaluation Runner 17 Kasus Uji | Evaluasi 17 kasus uji komprehensif terhadap 5 kriteria biner (K1-K5), rekapitulasi statistik, dan proyeksi biaya. |

---

## 🚀 Cara Menjalankan Skrip

Seluruh skrip ditulis dalam bahasa PHP murni (PHP 8.2+) dan dapat dijalankan langsung melalui terminal / command prompt dari direktori akar proyek:

```bash
# Menjalankan salah satu skrip eksperimen
php docs/praktik-implementasi/01_tes_lanskap_ai.php
php docs/praktik-implementasi/02_token_cost_temperature_exp.php
php docs/praktik-implementasi/05_structured_output_validator.php
php docs/praktik-implementasi/07_rag_retrieval_simulation.php
php docs/praktik-implementasi/09_guardrails_testing_suite.php
php docs/praktik-implementasi/10_evaluasi_otomatis_runner.php
```
