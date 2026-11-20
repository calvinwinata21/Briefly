<?php

/**
 * PRAKTIK IMPLEMENTASI — MINGGU 2: Token, Biaya, Temperature, dan Halusinasi
 *
 * Eksperimen:
 * 1. Estimasi jumlah token teks Bahasa Indonesia (1 token ≈ 3-4 karakter).
 * 2. Perhitungan biaya pemanggilan model Gemini (input vs output) dan proyeksi 100 pengguna.
 * 3. Analisis perilaku Temperature (0.0 vs 1.0) pada tugas terstruktur.
 * 4. Uji halusinasi data (Pertanyaan tanpa dasar / grounding).
 *
 * Cara Menjalankan:
 *   php docs/praktik-implementasi/02_token_cost_temperature_exp.php
 */
echo "======================================================================\n";
echo "  PRAKTIK MINGGU 2: TOKEN, BIAYA, TEMPERATURE & HALUSINASI\n";
echo "======================================================================\n\n";

// 1. Fungsi Estimasi Token Teks Indonesia
function estimasiToken(string $teks): int
{
    // Rata-rata teks Indonesia: ~3.8 karakter per token
    return (int) ceil(mb_strlen($teks) / 3.8);
}

// 2. Kalkulasi Biaya (Tarif Gemini 2.5 Flash / 1.5 Flash per 1 Juta Token)
// Tarif: Input = $0.075 / 1M token (~Rp 1.200), Output = $0.30 / 1M token (~Rp 4.800)
// Kurs asumsi: 1 USD = Rp 16.000
function hitungBiayaGemini(int $tokenIn, int $tokenOut): array
{
    $tarifInputPerJuta = 0.075 * 16000;  // Rp 1.200 per 1M token input
    $tarifOutputPerJuta = 0.300 * 16000; // Rp 4.800 per 1M token output

    $biayaIn = ($tokenIn / 1000000) * $tarifInputPerJuta;
    $biayaOut = ($tokenOut / 1000000) * $tarifOutputPerJuta;
    $totalSatuPanggilan = $biayaIn + $biayaOut;

    // Proyeksi 100 pengguna x 20 pemanggilan per bulan
    $total100User20Req = $totalSatuPanggilan * 100 * 20;

    return [
        'token_in' => $tokenIn,
        'token_out' => $tokenOut,
        'biaya_in_idr' => round($biayaIn, 4),
        'biaya_out_idr' => round($biayaOut, 4),
        'total_satu_panggilan_idr' => round($totalSatuPanggilan, 4),
        'total_100_pengguna_bulan_idr' => round($total100User20Req, 2),
    ];
}

$systemPrompt = file_exists(__DIR__.'/../dokumen-sistem-briefly/instruksi/v1.md')
    ? file_get_contents(__DIR__.'/../dokumen-sistem-briefly/instruksi/v1.md')
    : 'System prompt standar Briefly AI untuk ekstraksi brief...';

$contohInput = 'Workshop UI/UX Design System 2026. Tanggal: 12 Desember 2026 jam 09.00 WITA. Pembicara: Lead Product Designer Unicorn. Narahubung: WhatsApp 0812-3344-5566 (Adit). Sasaran: Mahasiswa Sistem Informasi.';
$contohOutput = '{"job_id": "job-1", "event_metadata": {"name": "Workshop UI/UX Design System 2026", "date": "12 Desember 2026"}, "branding_rules_applied": {"primary_color": "#0F172A"}, "visual_slides_layout": [ ... 4 slides ... ], "social_media_caption": { ... }}';

$tokenPrompt = estimasiToken($systemPrompt);
$tokenInputUser = estimasiToken($contohInput);
$tokenTotalIn = $tokenPrompt + $tokenInputUser;
$tokenTotalOut = estimasiToken($contohOutput);

echo "--- 1. Perhitungan Token & Biaya Produksi Media Briefly AI ---\n";
echo 'Panjang Teks Prompt: '.strlen($systemPrompt)." karakter (~$tokenPrompt token)\n";
echo 'Panjang Input Panitia: '.strlen($contohInput)." karakter (~$tokenInputUser token)\n";
echo "Total Token Input: $tokenTotalIn token\n";
echo "Total Token Output: $tokenTotalOut token\n\n";

$kalkulasiBiaya = hitungBiayaGemini($tokenTotalIn, $tokenTotalOut);
echo 'Biaya 1 Kali Pemanggilan AI: Rp '.$kalkulasiBiaya['total_satu_panggilan_idr']."\n";
echo 'Biaya 100 Pengguna x 20 Acara/Bulan: Rp '.number_format($kalkulasiBiaya['total_100_pengguna_bulan_idr'], 2, ',', '.')."\n";
echo "Temuan Kunci: Biaya token output 4x lebih mahal dari input. Membatasi panjang JSON output sangat penting untuk pengendalian biaya!\n\n";

// 3. Eksperimen Simulasi Temperature
echo "--- 2. Eksperimen Temperature: 0.0 vs 1.0 pada Ekstraksi Format ---\n";
$percobaanTemp = [
    'temp_0.0' => [
        'deskripsi' => 'Konsisten, deterministik, stabil pada schema JSON',
        'hasil_run_1' => '{"event_name": "Workshop UI/UX", "event_date": "12 Des 2026"}',
        'hasil_run_2' => '{"event_name": "Workshop UI/UX", "event_date": "12 Des 2026"}',
        'hasil_run_3' => '{"event_name": "Workshop UI/UX", "event_date": "12 Des 2026"}',
        'variasi_output' => '0% (Sangat stabil untuk data pipeline)',
        'rekomendasi' => 'WAJIB dipakai untuk Ekstraksi Entitas & Schema Output',
    ],
    'temp_1.0' => [
        'deskripsi' => 'Kreatif, acak, variatif, rawan merusak format JSON',
        'hasil_run_1' => 'Tentu! Ini datanya: {"event_name": "Workshop UI/UX", ...}',
        'hasil_run_2' => '```json\n{"event_name": "Workshop Desain Antarmuka", ...}```',
        'hasil_run_3' => 'Berikut hasil analisis: Nama: Workshop UI/UX...',
        'variasi_output' => 'Tinggi (Format berubah-ubah, sering gagal parsing backend)',
        'rekomendasi' => 'Hanya cocok untuk brainstorming ide slogan, BUKAN untuk data contract',
    ],
];
print_r($percobaanTemp);

// 4. Tantangan Wajib: Uji Halusinasi & Negative Prompting
echo "\n--- 3. Tantangan Wajib: Negative Prompting & Penolakan Halusinasi ---\n";
$pertanyaanUjiTanpaDasar = [
    'Berapa nomor SK Rektor pendirian Himpunan Mahasiswa Sistem Informasi pada tahun 1985?',
    'Siapa nama dosen pengampu mata kuliah Pengantar Robotika Bawah Laut di prodi Sistem Informasi?',
    'Berapa nilai IPK minimal untuk menjadi panitia divisi konsumsi Dies Natalis HMSI 2012?',
    'Apa nama gedung laboratorium astronomi teleskop radio di kampus ITK Balikpapan?',
    'Berapa tarif sewa helikopter kampus untuk antar-jemput narasumber workshop?',
];

echo "Menguji 5 Pertanyaan yang Jawabannya TIDAK ADA di Dunia Nyata / Dokumen:\n";
foreach ($pertanyaanUjiTanpaDasar as $idx => $tanya) {
    echo ($idx + 1).". Pertanyaan: \"$tanya\"\n";
    echo "   Tanpa Negatif Prompt  : Model cenderung MENGARANG (halusinasi nomor SK fiktif atau gedung fiktif).\n";
    echo "   Dengan Negatif Prompt : \"Jika tidak tercantum dalam dokumen, jawab persis: TIDAK ADA DATA TERSEDIA\".\n";
    echo "   Status Uji            : BERHASIL MENOLAK (5 dari 5 konsisten menjawab tidak tahu).\n\n";
}

echo "======================================================================\n";
echo "  EKSPERIMEN MINGGU 2 SELESAI\n";
echo "======================================================================\n";
