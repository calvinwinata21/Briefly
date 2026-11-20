# REFLEKSI AKHIR SEMESTER (UAS) — BRIEFLY AI
**Mata Kuliah:** Kapita Selekta: AI Engineering (Ganjil 2026/2027)  
**Komponen Penilaian:** Sikap dan Profesionalisme (Bobot: 2% dari Nilai Akhir / Pelengkap Portofolio UAS)  
**Mahasiswa:** Calvin Winata (NIM: 10241016) | Kode Peserta K = 23  
**Program Studi:** Sistem Informasi — Institut Teknologi Kalimantan  

---

### 1. Keputusan rancangan apa yang paling Anda sesali, dan apa yang akan Anda lakukan berbeda?
Keputusan rancangan yang paling saya sesali terjadi pada **Minggu 5 dan 6**, ketika saya sempat mencoba menyatukan tugas ekstraksi entitas dan generasi layout visual ke dalam satu prompt raksasa (*monolithic prompt*) dengan dalih menghemat jumlah pemanggilan model.  
*Dampaknya:* Prompt raksasa tersebut sangat rapuh. Ketika panjang input bertambah sedikit saja, model mulai mengabaikan instruksi format skema JSON, melewatkan kutipan bukti (*evidence*), dan waktu respons melonjak di atas 6 detik.  
*Apa yang akan saya lakukan berbeda:* Jika memulai dari awal lagi, saya akan menerapkan **Task Decomposition** sejak hari pertama Blok B: memisahkan secara tegas antara `ParseBriefAction` (fokus pada fakta dan penolakan data bolong) dengan `GeminiApiService` (fokus pada sintesis layout berlandaskan RAG). Pemisahan tanggung jawab (*Separation of Concerns*) membuat setiap komponen jauh lebih mudah diuji, di-debug, dan dikendalikan perilakunya.

---

### 2. Kesalahan apa yang Anda buat sepanjang semester yang tidak tertangkap oleh siapa pun kecuali Anda sendiri?
Pada **Minggu 7 saat pertama kali membangun VectorSearchService**, algoritma perhitungan kemiripan kosinus saya sempat memiliki bug perhitungan norma vektor ($normA \times normB$). Rumus pembagi tidak saya beri akar kuadrat (`sqrt`), sehingga nilai kemiripan yang dihasilkan sempat melonjak di atas angka 1.0 (yang secara matematis tidak sah untuk fungsi kosinus).  
Karena sebagian besar chunk brand tetap terambil berurutan, sistem di layar antarmuka terlihat seolah-olah berjalan dengan normal dan berhasil mengambil dokumen. Teman sekelas saat sesi diskusi mengira RAG saya sudah sempurna. Namun di dalam hati saya tahu ada yang janggal karena ambang batas 0.40 meloloskan dokumen yang tidak relevan. Saya baru menyadari kesalahan matematika tersebut saat membaca kembali teori embedding di akhir pekan, lalu segera memperbaikinya dengan fungsi `sqrt($normA) * sqrt($normB)`. Pengalaman ini menyadarkan saya bahwa dalam AI Engineering, **sistem yang terlihat berjalan di permukaan belum tentu bekerja dengan benar di balik layar**.

---

### 3. Bagaimana AI membantu Anda, dan di titik mana ia menyesatkan Anda?
- **Bagaimana AI Sangat Membantu:** AI (khususnya Claude Code dan Gemini CLI) berperan luar biasa sebagai akselerator pengetikan kode *boilerplate*. AI membantu saya menyusun kelas FormRequest Laravel, fungsi regex sanitasi input yang kompleks, dan membuat struktur tabel pengujian evaluasi dalam hitungan detik. Tanpa bantuan AI, beban teknis 2 SKS ini akan sangat menyita waktu di bagian sintaksis pemrograman.
- **Di Titik Mana AI Menyesatkan:** AI berulang kali menyesatkan saya dengan memberikan solusi yang terdengar sangat meyakinkan namun secara arsitektur keliru. Pada Minggu 10, ketika saya menanyakan cara menangani alur multi-langkah, AI menyarankan agar saya membuat *Multi-Agent Swarm* dengan 4 sub-agent otonom yang saling memanggil fungsi. Saya sempat menghabiskan waktu 2 hari mencoba saran tersebut, hanya untuk mendapati bahwa sistem menjadi sangat lambat (latensi 16 detik), biaya token membengkak 4 kali lipat, dan format JSON sering rusak. AI cenderung mendorong kita ke solusi yang "terlihat canggih dan rumit", padahal solusi rekayasa terbaik untuk industri seringkali adalah alur kerja deterministik yang sederhana dan terukur.

---

### 4. Kalau produk Anda dipakai orang sungguhan mulai besok, apa yang paling membuat Anda khawatir?
Hal yang paling membuat saya khawatir adalah **rasa percaya diri palsu (*complacency*) dari para panitia dan desainer**.  
Setelah melihat sistem mampu menghasilkan 4 slide yang rapi dan caption Instagram yang terdengar keren dalam 3 detik, saya khawatir Lead Designer akan mulai malas memeriksa nomor kontak narahubung atau jam pelaksanaan acara, lalu asal menekan tombol **"APPROVE"** tanpa membaca kata demi kata.  
Jika itu terjadi, seluruh lapisan guardrails yang saya bangun dengan susah payah sepanjang semester akan runtuh di titik manusia. Model AI pada akhirnya hanyalah kalkulator kata probabilistik yang suatu saat pasti akan salah mengeja atau melewatkan satu angka telepon. Menjaga agar manusia tetap kritis dan waspada saat mengawasi sistem otomatis adalah tantangan non-teknis terbesar yang tidak bisa diselesaikan hanya dengan kode program.
