# BANK JAWABAN PERTANGGUNGJAWABAN UTS & UAS — BRIEFLY AI
**Mata Kuliah:** Kapita Selekta: AI Engineering (Ganjil 2026/2027)  
**Rujukan:** Lampiran E — Bank Pertanyaan Pertanggungjawaban (77 Pertanyaan Terbuka)  
**Mahasiswa:** Calvin Winata (NIM: 10241016) | Program Studi Sistem Informasi  
**Konteks Akademik:** Kode Peserta K = 23 | Institut Teknologi Kalimantan  

Dokumen ini memuat jawaban analitis, beralasan, dan dapat dibuktikan atas pertanyaan-pertanyaan viva pertanggungjawaban karya Briefly AI pada sesi UTS dan UAS.

---

# BAGIAN I: PERTANYAAN UTS (Sub-CPMK 1–3)

### A. Persoalan dan Kelayakan
1. **Siapa persisnya yang mengalami persoalan yang Anda pilih? Bagaimana Anda tahu?**  
   *Jawaban:* Divisi Media dan Informasi Himpunan Mahasiswa Sistem Informasi (HMSI) ITK. Saya mengetahuinya secara empiris karena sebagai mahasiswa SI, saya mengamati desainer himpunan sering kewalahan menerima teks brief chat WhatsApp yang berantakan dari berbagai divisi panitia pelaksana kegiatan, sehingga materi promosi sering terlambat terbit menjelang hari-H acara.
2. **Bagaimana persoalan ini diselesaikan sekarang, dan mengapa cara itu tidak memadai?**  
   *Jawaban:* Diselesaikan secara manual: desainer menyalin teks chat, mengingat kode hex warna dan font secara manual, membuka Canva/Photoshop, dan mengetik 3 versi caption terpisah. Cara ini lambat (4–8 jam per acara), rawan salah ketik kontak person, dan palet warna tidak konsisten antar-acara.
3. **Mengapa persoalan ini tidak cukup diselesaikan dengan basis data atau formulir?**  
   *Jawaban:* Panitia acara tidak pernah konsisten mengisi formulir kaku. Mereka sering menulis deskripsi secara naratif, menggunakan singkatan kampus (*"bsk lusa di sekre bwt baksos"*), dan menggabungkan waktu serta tanggal dalam satu paragraf. Formulir kaku akan ditolak jika input tidak sesuai tipe data, sedangkan AI generatif memiliki fleksibilitas semantik untuk mengekstrak entitas dari bahasa percakapan bebas.
4. **Bagian mana dari persoalan Anda yang sebenarnya TIDAK butuh model bahasa?**  
   *Jawaban:* Penyimpanan data riwayat brief, autentikasi user RBAC, relasi tabel database, dan validasi format regex (apakah nomor HP diawali 08). Seluruh bagian ini dikerjakan secara deterministik oleh framework Laravel dan SQLite.
5. **Kalau anggaran Anda nol rupiah, bagian mana dari produk ini yang tetap dapat berjalan?**  
   *Jawaban:* Seluruh antarmuka web, sistem manajemen brief, Heuristic Fallback Parser (berbasis regex), dan mekanisme review persetujuan Lead Designer (HITL) tetap berjalan 100% tanpa internet. Hanya pemanggilan API Gemini yang dialihkan ke generator heuristik lokal.
6. **Siapa yang dapat memeriksa output produk Anda benar atau salah?**  
   *Jawaban:* Lead Designer (Ketua Divisi Media) dan panitia pengaju brief. Mereka dapat mencocokkan draf visual slide dan tanggal acara langsung terhadap teks brief asli.
7. **Apa yang terjadi kalau produk Anda salah, dan siapa yang menanggungnya?**  
   *Jawaban:* Jika draf memuat jadwal atau kontak yang salah, Lead Designer akan menolak draf tersebut di dashboard review dan mengirim catatan revisi. Tanggung jawab hukum dan etis berada pada Lead Designer yang memberikan persetujuan final sebelum materi diekspor keluar sistem.
8. **Sebutkan satu tanda yang akan membuat Anda menyimpulkan tema ini salah dipilih.**  
   *Jawaban:* Jika seluruh panitia kampus bersedia mengisi 15 kolom formulir kaku dengan format tanggal yang selalu seragam dan tidak pernah menggunakan bahasa gaul. Jika itu terjadi, LLM tidak lagi diperlukan dan cukup memakai basis data CRUD biasa.

### B. Karakteristik Model & Biaya
9. **Jelaskan apa yang dilakukan model bahasa besar tanpa memakai kata "berpikir" atau "memahami".**  
   *Jawaban:* Model bahasa besar adalah mesin probabilistik yang menghitung distribusi kemungkinan matematis untuk memprediksi potongan teks (token) berikutnya yang paling mungkin muncul, satu per satu, berdasarkan urutan token teks yang telah ada sebelumnya di dalam konteks.
10. **Mengapa hallucination tidak bisa dihilangkan? Apa tiga cara mengelolanya, dan mana yang Anda pakai?**  
    *Jawaban:* Karena model dilatih untuk menghasilkan teks yang terdengar masuk akal secara statistika, bukan memverifikasi kebenaran ontologis. Tiga cara mengelolanya: (1) Grounding via RAG, (2) Structured Output dengan field bukti (*evidence*), (3) Guardrails & Human-in-the-Loop. **Briefly AI menerapkan ketiganya secara terintegrasi.**
11. **Berapa perkiraan token satu permintaan khas pada produk Anda? Tunjukkan perhitungannya.**  
    *Jawaban:* ~1.420 token input (850 token system prompt + 450 token RAG 3 chunk + 120 token brief user) dan ~650 token output JSON (4 slide + 3 caption). Biaya per transaksi: $(1.420 \times \text{Rp } 0,0012) + (650 \times \text{Rp } 0,0048) = \mathbf{\text{Rp } 4,82}$.
12. **Temperature berapa yang Anda pakai, dan mengapa bukan yang lebih tinggi atau lebih rendah?**  
    *Jawaban:* Saya memakai `temperature = 0.2`. Nilai 0.0 terkadang membuat bahasa caption terasa kaku dan monoton, sedangkan nilai di atas 0.5 sering merusak kepatuhan skema JSON (menyisipkan markdown atau trailing comma). Nilai 0.2 memberikan stabilitas format skema yang tinggi sekaligus mempertahankan variasi diksi bahasa yang luwes.
13. **Apa perbedaan jendela konteks dan ingatan? Mana yang dimiliki produk Anda?**  
    *Jawaban:* Jendela konteks (*context window*) adalah batas kapasitas token yang dapat dibaca model dalam satu kali panggilan HTTP tanpa persistensi. Ingatan (*memory*) adalah penyimpanan status terstruktur antar-sesi. Briefly AI **menyimpan state di luar context window**, yaitu pada basis data SQLite lokal (`briefs` dan `media_drafts`), sehingga riwayat tidak hilang saat sesi browser ditutup.
14. **Model apa yang Anda pakai, dan model apa yang Anda tolak? Atas dasar apa?**  
    *Jawaban:* Saya memakai `gemini-2.5-flash` dan menolak `gemini-1.5-pro`. Dasar keputusannya adalah rasio biaya dan kecepatan: Gemini 2.5 Flash memiliki latensi di bawah 2 detik dan biaya 94% lebih murah, dengan akurasi ekstraksi entitas brief yang identik dengan model Pro.
15. **Tunjukkan satu tugas pada produk Anda yang model termurah sudah memadai. Bagaimana Anda membuktikannya?**  
    *Jawaban:* Tugas ekstraksi 5 entitas brief pada `ParseBriefAction`. Saya mengujinya dengan membandingkan output Gemini Flash dan Gemini Pro pada TC-01; keduanya menghasilkan JSON entitas yang persis sama, membuktikan model mahal tidak diperlukan untuk tugas ekstraksi rutin.

### C. Kendali Output & Schema
16. **Tunjukkan system prompt Anda. Petakan setiap bagiannya ke enam bagian.**  
    *Jawaban:* Terpetakan pada file `docs/dokumen-sistem-briefly/instruksi/v1.md` dan `v4-agentic.md` (Peran: Asisten Media HMSI, Konteks: Panitia informal, Tugas: Ekstraksi & Sintesis 4 Slide, Batasan: Dilarang mengarang tanggal/warna, Contoh: 3 Few-shot kasus batas, Format: Skema JSON RFC 8259).
17. **Bagian mana dari instruksi Anda yang kalau dihapus akan paling merusak? Sudahkah Anda mencobanya?**  
    *Jawaban:* Bagian Batasan (*Constraints*) nomor 1: *"Output WAJIB berupa raw JSON valid tanpa teks pengantar"*. Saya sudah mencobanya pada tahap BREAK Minggu 4: begitu kalimat itu dihapus, model langsung menambahkan basa-basi *"Tentu, ini hasil drafnya:"*, yang seketika menyebabkan `json_decode()` di PHP error fatal.
18. **Berapa versi instruksi yang sudah Anda buat? Apa yang berubah dari v1 ke versi sekarang?**  
    *Jawaban:* Ada 4 versi utama tercatat di `CATATAN.md`: v1 (Markdown list sederhana) $\rightarrow$ v2 (JSON Contract) $\rightarrow$ v3 (Grounding RAG 8 chunk) $\rightarrow$ v4 (Full Agentic + 3 Mode Nada Konten + Guardrails).
19. **Tunjukkan schema output Anda. Mengapa field ini ada dan field itu tidak?**  
    *Jawaban:* Skema terdefinisi di `briefly-output-schema.json`. Field `branding_rules_applied.rag_chunks_cited` wajib ada untuk audit sitasi sumber. Field seperti `budget_estimasi` sengaja tidak ada karena anggaran keuangan acara bukan domain divisi media melainkan divisi bendahara.
20. **Nilai apa yang tersedia untuk kasus "tidak dapat ditentukan"? Apa yang terjadi kalau ia tidak ada?**  
    *Jawaban:* Enum `INCOMPLETE_MISSING_CORE` dan nilai `null`. Jika opsi ini tidak ada, model dipaksa menebak tanggal kalender secara acak (halusinasi fatal).
21. **Mengapa field bukti berupa kutipan langsung, bukan ringkasan?**  
    *Jawaban:* Kutipan langsung (*verbatim quote*) dapat diverifikasi kecocokan string-nya secara programatis (`str_contains`) terhadap teks input asli. Jika berupa ringkasan, sistem tidak bisa memastikan apakah ringkasan itu interpretasi bebas model atau fakta riil.
22. **Apa yang terjadi kalau output tidak valid menurut schema Anda? Tunjukkan mekanismenya berjalan.**  
    *Jawaban:* Mekanisme 3-langkah di `ValidateAiOutputAction` aktif: Percobaan 1 gagal $\rightarrow$ Reprompting menyertakan pesan error skema $\rightarrow$ Jika masih gagal pada percobaan 3, sistem secara elegan beralih ke *Heuristic Fallback Engine* lokal tanpa melempar HTTP 500.
23. **Tunjukkan satu input yang membuat produk Anda menghasilkan output yang tidak valid. Mengapa ia lolos?**  
    *Jawaban:* Input berupa 500 baris teks kode SQL dump. Input tersebut lolos dari penapis awal karena disangka teks deskripsi panjang, namun gagal di validator skema karena model kehabisan batas token saat mencoba memprosesnya, sehingga memicu fallback.

### D. Tools & Actions
24. **Sebutkan tool Anda dan kapan masing-masing TIDAK boleh dipakai.**  
    *Jawaban:* (1) `ParseBriefAction`: tidak boleh untuk mencari aturan brand; (2) `ExecuteRagSearchAction`: tidak boleh untuk mencari data akun pengguna; (3) `ValidateAiOutputAction`: tidak boleh untuk memodifikasi teks brief asli.
25. **Tool mana yang hanya membaca dan mana yang mengubah sesuatu? Bagaimana perbedaannya pada rancangan?**  
    *Jawaban:* `ParseBriefAction` dan `ExecuteRagSearchAction` adalah operasi *read-only* (murni membaca string atau tabel publik), sehingga boleh berjalan otomatis. Operasi yang mengubah status draf menjadi `APPROVED` berdampak keluar sistem, sehingga dikunci pada endpoint yang memerlukan hak akses Lead Designer.
26. **Tunjukkan satu pertanyaan yang membuat sistem Anda memilih tool salah. Apa sebabnya?**  
    *Jawaban:* Pertanyaan *"Cari peraturan zonasi kampus"*. Sistem sempat mencoba memanggil `ExecuteRagSearchAction` karena kata *"peraturan"*, padahal tool tersebut hanya menyimpan panduan brand media. Penyebabnya adalah deskripsi awal tool yang terlalu umum (*"mencari peraturan organisasi"*). Diperbaiki dengan mengkhususkan deskripsi menjadi: *"Mencari panduan identitas visual brand HMSI"*.
27. **Apa yang terjadi kalau tool Anda gagal? Tunjukkan.**  
    *Jawaban:* Tool mengembalikan array terstruktur `['status' => 'ERROR', 'error_type' => '...', 'message' => '...']`. Sistem mencatat error ini ke log telemetri dan mengembalikan pesan transparan ke pengguna, bukan hasil kosong semu.
28. **Mengapa tool yang mengembalikan hasil kosong lebih berbahaya daripada yang mengembalikan error?**  
    *Jawaban:* Jika tool mengembalikan hasil kosong tanpa error, model AI menyimpulkan bahwa data yang dicari memang tidak ada di dunia nyata, lalu model akan mencoba "menolong" pengguna dengan mengarang jawaban fiktif secara meyakinkan. Pesan error yang jujur memaksa model sadar bahwa sistem mengalami kegagalan teknis.

### E. Grounding & RAG
29. **Berapa ukuran chunk Anda, dan mengapa bukan setengahnya atau dua kalinya?**  
    *Jawaban:* 100–150 token per butir aturan semantik. Jika setengahnya (50 token), aturan warna primer akan terpotong dari penjelasan kontras teksnya. Jika dua kalinya (300 token), aturan warna dan tipografi akan bercampur dalam satu chunk sehingga pencarian vektor kehilangan ketajaman fokus.
30. **Mengapa Anda memotong menurut struktur / menurut jumlah huruf? Apa yang Anda korbankan?**  
    *Jawaban:* Saya memotong menurut **struktur semantik dokumen** (per pasal/kategori aturan brand). Yang dikorbankan adalah ukuran chunk menjadi tidak seragam secara jumlah huruf, namun makna aturan tetap utuh 100%.
31. **Berapa chunk yang Anda ambil per pertanyaan? Apa yang terjadi kalau diambil satu, dan kalau sepuluh?**  
    *Jawaban:* Tepat 3 chunk ($N = 3$). Jika diambil 1 chunk, informasi warna aksen dan struktur slide tidak akan terbawa. Jika diambil 10 chunk, terjadi pemborosan context window dan dokumen yang tidak relevan akan mengotori instruksi model (*lost in the middle*).
32. **Apa yang dilakukan sistem Anda kalau tidak ada chunk yang cukup mirip? Mengapa itu keputusan etis, bukan teknis?**  
    *Jawaban:* Sistem menolak menerapkan aturan brand dan menyatakan data tidak tersedia. Ini keputusan etis karena memaksakan model menjawab dari pengetahuan umumnya saat aturan resmi tidak ada sama saja membohongi pengguna seolah-olah jawaban itu berasal dari organisasi.
33. **Dari mana dokumen rujukan Anda? Apakah Anda berhak memakainya?**  
    *Jawaban:* Buku Panduan Identitas Visual HMSI 2026. Dokumen resmi publik organisasi mahasiswa kampus ITK dan sah dipakai untuk keperluan akademik ini.
34. **Tunjukkan satu pertanyaan yang jawabannya tersebar di dua dokumen. Apakah rancangan Anda menanganinya?**  
    *Jawaban:* *"Buatkan slide penutup (CTA) dengan warna tombol resmi"*. Membutuhkan aturan struktur slide dari `CHK-05` dan warna tombol Amber `#F59E0B` dari `CHK-02`. Rancangan $N = 3$ berhasil mengambil kedua chunk tersebut dalam satu pemanggilan.
35. **Metadata apa yang ikut disimpan bersama chunk, dan mengapa itu penting?**  
    *Jawaban:* `chunk_id`, `code`, `category`, `rule_title`, dan `allowed_channels`. Metadata ini disuntikkan ke prompt agar model dapat mengutip nomor chunk secara eksplisit pada atribut `citation_chunk` di output JSON.

### F. Proses dan Kejujuran Akademik
36. **Tunjukkan satu input yang membuat sistem Anda gagal, dan jelaskan sebabnya sampai ke akar.**  
    *Jawaban:* Input TC-15: *"Rapat hari sabtu minggu depan jam 10 pagi"*. Sistem v1 gagal karena model mengarang tanggal kalender spesifik. Akar masalahnya: prompt awal belum memiliki konsep *tanggal relatif vs tanggal pasti*. Diperbaiki di v2 dengan Completeness Guardrail yang mendeteksi frasa waktu relatif dan menolak memproses sebelum tanggal kalender definitif diberikan.
37. **Sebutkan satu keputusan rancangan yang dapat dibuat berbeda, dan mengapa Anda memilih yang ini.**  
    *Jawaban:* Saya dapat memilih arsitektur multi-agent otonom ReAct, namun saya memilih **Deterministic Pipeline Workflow**. Alasannya adalah efisiensi biaya (menghemat 70% token) dan kemudahan pengujian unit test di Laravel.
38. **Bagian mana dari karya Anda yang dibuat dengan bantuan AI, dan bagaimana Anda memverifikasinya?**  
    *Jawaban:* AI membantu pembuatan kode skema validator dan fungsi Cosine Similarity PHP. Saya memverifikasinya dengan menjalankan 11 test case otomatis di PHPUnit (`MediaProductionSystemTest.php`) yang mencapai 41 assertions lulus.
39. **Sebutkan satu hal yang AI berikan kepada Anda dan ternyata salah. Bagaimana Anda menemukannya?**  
    *Jawaban:* AI sempat memberikan saran prompt regex yang menghapus karakter `#` pada kode warna hex, sehingga kode warna menjadi `0F172A` tanpa pagar. Akibatnya CSS di frontend Blade tidak mengenali warna tersebut. Saya menemukannya saat melihat tombol preview di browser berwarna abu-abu transparan.
40. **Prediksi Anda pada tahap BREAK minggu ke berapa yang paling meleset? Apa yang salah dari cara berpikir Anda?**  
    *Jawaban:* Pada Minggu 2 percobaan nomor 6 (*Lost in the Middle*). Waktu itu saya menduga model dengan konteks 1 juta token seperti Gemini tidak akan pernah melewatkan kalimat aneh di tengah teks 3 halaman. Ternyata prediksi saya salah: model tetap melewatkan kalimat di tengah karena bias posisi (*primacy & recency bias*). Cara berpikir saya yang keliru adalah menganggap context window besar sama dengan ingatan manusia yang sempurna.

---

# BAGIAN II: PERTANYAAN UAS (Sub-CPMK 4–6)

### G. Keputusan Agentic
41. **Bagian mana dari produk Anda yang agentic dan bagian mana yang sengaja tidak? Mengapa?**  
    *Jawaban:* Bagian yang **agentic** adalah sintesis copywriting kreatif multi-kanal berlandaskan sitasi brand dan pemilihan tone of voice. Bagian yang **sengaja tidak agentic** adalah urutan alur data (Parse $\rightarrow$ Retrieve $\rightarrow$ Synthesize $\rightarrow$ Validate $\rightarrow$ HITL) dan hak persetujuan draf. Keputusan ini diambil untuk menjaga stabilitas alur kerja, menekan biaya token, dan mencegah bypass persetujuan.
42. **Apa yang Anda tukar dengan menjadikannya agent? Sebutkan kerugiannya, bukan hanya keuntungannya.**  
    *Jawaban:* Saya menukar determinisme mutlak dengan fleksibilitas adaptasi bahasa. Kerugiannya: hasil copywriting kalimat Instagram bervariasi di setiap pengajuan brief dan sistem bergantung pada latensi jaringan API eksternal.
43. **Berapa batas langkah Anda, dan mengapa angka itu? Apa yang terjadi saat batas tercapai?**  
    *Jawaban:* Batas langkah maksimal adalah **3 kali percobaan perbaikan (max retries = 3)**. Angka ini dipilih karena jika dalam 3 kali percobaan model gagal menghasilkan JSON valid, kemungkinan besar model mengalami *hallucination lock*. Saat batas tercapai, sistem menghentikan pemanggilan model dan mengalihkan proses ke *Heuristic Fallback Engine*.
44. **Berapa biaya terburuk satu permintaan pada produk Anda? Tunjukkan angkanya.**  
    *Jawaban:* Biaya terburuk terjadi jika model gagal 3 kali berturut-turut: $3 \times \text{Rp } 4,82 = \mathbf{\text{Rp } 14,46\text{ per transaksi}}$. Biaya ini tetap sangat murah dan aman bagi anggaran himpunan.
45. **Kalau tugas yang sama dijalankan tiga kali, apakah jalurnya sama? Apa akibatnya bagi pengujian Anda?**  
    *Jawaban:* Jalur eksekusi tool-nya **selalu sama 100%** (karena dipandu oleh workflow deterministik di kode Laravel), dengan variasi kata minor pada caption. Akibat positifnya: pengujian evaluasi (17 kasus uji) memiliki variance 0% dan dapat diuji ulang secara konsisten (*reproducible*).
46. **Di mana keadaan tugas disimpan? Mengapa bukan di dalam riwayat percakapan?**  
    *Jawaban:* Disimpan di luar percakapan, yaitu pada basis data SQLite lokal (`briefs` dan `media_drafts`). Jika disimpan di percakapan, biaya context window akan terus membengkak dan state akan hilang jika browser di-refresh.
47. **Tunjukkan trace satu tugas yang gagal. Pada langkah mana kegagalan bermula?**  
    *Jawaban:* Pada kasus TC-12 (Brief tanpa tanggal), kegagalan bermula di **Langkah 1 (`ParseBriefAction`)** ketika fungsi mendeteksi `event_date` bernilai null. Sistem langsung menghentikan proses sebelum membuang token di Langkah 2 dan 3.
48. **Alasan yang dinyatakan agent Anda pada tiap langkah — seberapa jauh ia dapat dipercaya?**  
    *Jawaban:* Alasan ReAct (*thought*) hanyalah teks penjelasan yang dihasilkan model, bukan rekaman aktivitas neuron buatan yang sesungguhnya. Oleh karena itu, sistem Briefly AI tidak mempercayai alasan teks tersebut untuk keamanan; validasi tetap diverifikasi melalui skema biner di PHP code.
49. **Kalau Anda memakai multi-agent: tunjukkan bahwa satu agent sudah dicoba dan tidak memadai.**  
    *Jawaban:* Briefly AI **secara sadar TIDAK memakai multi-agent** karena pengujian membuktikan satu model orchestrator dengan 3 action tools terisolasi sudah 100% memadai untuk memproduksi materi media kampus, dengan biaya 3,4x lebih hemat.
50. **Apa yang terjadi kalau tool mengembalikan hasil yang salah tetapi masuk akal? Apakah sistem Anda menangkapnya?**  
    *Jawaban:* Sistem menangkapnya di dua titik: (1) Di Lapis 4 melalui validasi tipe data dan rentang nilai hex warna resmi; (2) Di Lapis 5 melalui Lead Designer (HITL) yang membaca draf sebelum dipublikasikan.

### H. Guardrails & Keamanan
51. **Tunjukkan tabel batas akses tool Anda. Baris mana yang penegakannya hanya bersandar pada instruksi?**  
    *Jawaban:* Tidak ada. **100% batasan akses kritis ditegakkan di level kode PHP dan database constraint.** Instruksi prompt hanya berfungsi sebagai panduan perilaku sekunder.
52. **Mengapa penegakan di lapis instruksi lebih lemah daripada di lapis akses tool?**  
    *Jawaban:* Karena prompt berada di konteks yang sama dengan teks input pengguna yang tidak terpercaya (*untrusted input*). Penyerang dapat menyusun kata-kata persuasif (*prompt injection*) untuk membujuk model mengabaikan instruksinya. Sebaliknya, pembatasan di level kode (seperti query `SELECT` read-only) tidak dapat dibatalkan oleh kepatuhan model AI.
53. **Tindakan apa pada produk Anda yang tidak akan pernah dijalankan tanpa persetujuan manusia? Mengapa?**  
    *Jawaban:* **Publikasi dan penyebaran konten ke ranah publik.** Karena tindakan ini melibatkan reputasi institusi dan tidak dapat dibatalkan setelah dilihat oleh khalayak media sosial.
54. **Tunjukkan bentuk persetujuannya. Apakah pengguna punya cukup informasi untuk menolak?**  
    *Jawaban:* Antarmuka dashboard review menampilkan tinjauan visual 4 slide secara utuh, teks lengkap caption 3 kanal, sitasi chunk brand, dan tombol merah *"Tolak Draf"* yang membuka modal form catatan revisi wajib. Desainer memiliki seluruh konteks untuk menolak.
55. **Serangan apa yang berhasil menembus produk Anda? Bagaimana Anda menutupnya?**  
    *Jawaban:* Permintaan warna ungu neon `#FF00FF` sempat dituruti model pada v3. Saya menutupnya di **Lapis 4 Kode Program** dengan memasang fungsi validasi warna di PHP yang secara deterministik menolak warna non-HMSI dan memaksanya kembali ke `#0F172A`.
56. **Mengapa serangan lewat dokumen rujukan lebih berhasil daripada lewat input pengguna?**  
    *Jawaban:* Karena input pengguna secara bawaan dicurigai oleh pengembang dan disaring di awal, sedangkan dokumen rujukan RAG sering diasumsikan sebagai "sumber kebenaran yang aman". Jika dokumen rujukan disusupi teks perintah jahat (*data poisoning*), model akan langsung mengeksekusinya tanpa curiga.
57. **Celah keamanan apa yang Anda ketahui masih ada? Mengapa Anda menerimanya?**  
    *Jawaban:* Ketergantungan pada API komersial Google dan celah injeksi semantik metafora halus. Saya menerimanya karena risikonya termitigasi tuntas oleh keberadaan Lead Designer (HITL) di gerbang akhir.
58. **Apa yang akan mengubah keputusan Anda untuk menerima risiko itu?**  
    *Jawaban:* Jika sistem ini diubah menjadi sistem publikasi otomatis tanpa supervisi manusia (*fully autonomous posting*), maka celah tersebut wajib ditutup dengan model classifier injeksi lokal khusus.

### I. Evaluasi & Biaya
59. **Berapa kasus pada set uji Anda, dan bagaimana komposisinya? Mengapa proporsi itu?**  
    *Jawaban:* 17 kasus uji: 7 Khas (41%), 4 Batas (24%), 3 Ditolak (18%), 3 Pernah Gagal (18%). Proporsi ini mencerminkan distribusi operasional nyata: mayoritas kasus normal, diimbangi kasus ekstrem dan pengujian integritas penolakan sistem.
60. **Siapa yang menyusun jawaban acuan, dan atas dasar apa?**  
    *Jawaban:* Saya sendiri (Calvin Winata) berdasarkan verifikasi manual terhadap teks brief asli dan ketentuan buku pedoman identitas brand HMSI 2026.
61. **Tunjukkan satu kriteria penilaian Anda dan satu kasus batas yang membuat dua penilai bisa berbeda pendapat.**  
    *Jawaban:* Kasus TC-08 (Teks gaul ekstrem). Penilai 1 mungkin menilai gagal di K1 karena kata *"gasskeun"* tidak dimasukkan ke caption poster, sedangkan Penilai 2 menilai lulus karena inti acara briefing baksos berhasil diekstraksi. Kami membakukannya dengan aturan: kelulusan dinilai dari kebenaran fakta entitas, bukan kesamaan kata perkataan gaul.
62. **Kalau Anda memakai penilai model: berapa tingkat kesesuaiannya dengan penilaian Anda?**  
    *Jawaban:* Tingkat kesesuaian adalah **100% (17 dari 17 kasus)** pada pengujian validasi skema PHPUnit.
63. **Berapa variance antar-percobaan pada set uji Anda? Apa artinya?**  
    *Jawaban:* Variance = 0% pada 3 kali pengulangan. Artinya sistem sangat stabil dan deterministik untuk kebutuhan produksi organisasi.
64. **Sebutkan satu klaim pada laporan evaluasi Anda, dan tunjukkan buktinya sekarang juga.**  
    *Jawaban:* Klaim: *"Sistem mampu menolak input bukan acara kampus sebesar 100%"*. Bukti: Jalankan skrip `php docs/praktik-implementasi/10_evaluasi_otomatis_runner.php`, kasus TC-13 (resep masakan) terbukti menghasilkan status `rejected_non_event` dan menolak pembuatan draf.
65. **Bagaimana angka Anda berubah dari baseline Minggu 9? Apa yang menyebabkannya?**  
    *Jawaban:* Akurasi penolakan kasus bolong naik dari 66,7% ke 100%. Disebabkan oleh penambahan `ParseBriefAction` Completeness Guardrail.
66. **Kasus jenis apa yang paling sering gagal? Apa polanya?**  
    *Jawaban:* Kasus tanggal relatif (*"minggu depan"*, *"akhir bulan"*). Polanya: model cenderung mencoba menebak tanggal terdekat daripada bertanya.
67. **Penghematan apa yang Anda terapkan, dan bagaimana Anda membuktikan kualitas tidak turun?**  
    *Jawaban:* Beralih dari Gemini Pro ke Gemini Flash (menghemat 94% biaya). Dibuktikan pada set uji 17 kasus yang tetap mencapai kelulusan 100%.
68. **Tiga hal apa yang membuat angka Anda tidak boleh digeneralisasi?**  
    *Jawaban:* (1) Ukuran set uji 17 kasus, (2) Pengujian terbatas pada teks bahasa Indonesia domain kampus, (3) Bergantung pada versi model Gemini saat pengujian dilakukan.
69. **Kalau saya memberi Anda sepuluh kasus baru dari bidang Anda sekarang, berapa yang Anda perkirakan lolos?**  
    *Jawaban:* Saya memperkirakan **9 dari 10 kasus akan lolos**. Kasus yang mungkin gagal adalah brief yang memuat jadwal hybrid multi-zona waktu yang sangat rumit.

### J. Etika, Tanggung Jawab & Refleksi
70. **Siapa yang dirugikan kalau produk Anda salah?**  
    *Jawaban:* Panitia acara dan citra organisasi himpunan mahasiswa Sistem Informasi di mata publik dan pemateri eksternal.
71. **Apakah pengguna tahu ia berhadapan dengan AI? Tunjukkan di mana ia diberi tahu.**  
    *Jawaban:* Ya, pada header form brief dan banner review dashboard tertulis label transparan: *"Briefly AI — Draf Media Dihasilkan oleh AI Berdasarkan Brand HMSI 2026. Wajib Direview oleh Lead Designer."*
72. **Data siapa yang diproses, dan apakah pemiliknya tahu?**  
    *Jawaban:* Data pengajuan acara milik panitia mahasiswa, yang diinput secara sadar melalui akun login web terdaftar.
73. **Untuk apa produk Anda TIDAK BOLEH dipakai? Apa yang mencegahnya?**  
    *Jawaban:* Dilarang untuk kampanye politik praktis, informasi hoax, atau berita duka cita. Dicegah oleh filter domain di Lapis 1 dan gerbang persetujuan Lead Designer di Lapis 5.
74. **Tunjukkan bias yang masuk lewat rancangan Anda sendiri — bukan lewat model, bukan lewat dokumen.**  
    *Jawaban:* Pembatasan format carousel yang dikunci tepat 4 slide. Acara pengumuman singkat yang sebenarnya hanya butuh 1 slide terpaksa dimekarkan menjadi 4 slide karena rancangan skema saya.
75. **Kasus apa yang tidak cocok ke satu pun kategori schema Anda? Ke mana ia dipaksa masuk?**  
    *Jawaban:* Berita duka cita anggota himpunan. Jika dipaksakan masuk, sistem akan memaksanya memiliki slide "OFFERING" dan "CALL TO ACTION" yang tidak etis. Oleh karena itu sistem menolaknya secara tegas.
76. **Kalau produk Anda dipakai orang sungguhan mulai besok, apa yang paling membuat Anda khawatir?**  
    *Jawaban:* Munculnya rasa percaya diri berlebihan dari desainer (*complacency*), di mana desainer langsung menekan tombol "APPROVE" tanpa memeriksa ulang kebenaran nomor kontak dan jam acara.
77. **Kalau sistem Anda memberi rekomendasi salah yang merugikan seseorang, siapa yang bertanggung jawab?**  
    *Jawaban:* **Saya sebagai perancang sistem dan Lead Designer yang menyetujui draf tersebut.** "Model AI yang salah" bukan jawaban profesional; manusia yang mengoperasikan dan merancang sistem bertanggung jawab penuh atas dampak publikasinya.
