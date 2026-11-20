<?php

/**
 * PRAKTIK IMPLEMENTASI — MINGGU 1: Lanskap AI Engineering vs Rekayasa Biasa
 *
 * Eksperimen: Membedakan persoalan deterministik (aturan reguler/basis data)
 * dengan persoalan AI generatif (ekstraksi bahasa alami teks bebas).
 *
 * Cara Menjalankan:
 *   php docs/praktik-implementasi/01_tes_lanskap_ai.php
 */
echo "======================================================================\n";
echo "  PRAKTIK MINGGU 1: DETERMINISTIK VS AI GENERATIF (Briefly AI)\n";
echo "======================================================================\n\n";

// Skenario 1: Persoalan Deterministik (Menghitung SKS / Validasi Format NIM)
echo "--- 1. Persoalan Deterministik: Validasi Format NIM & Hitung Sisa Kuota ---\n";
function validasiNimDanHitungSks(string $nim, int $sksDiambil): array
{
    // Aturan deterministik: jika format pasti, gunakan kode program biasa, BUKAN LLM!
    $polaNim = '/^10\d{6}$/'; // Format NIM ITK: 8 digit diawali 10
    $maksSks = 24;

    $isValid = (bool) preg_match($polaNim, $nim);
    $sisaSks = max(0, $maksSks - $sksDiambil);

    return [
        'nim' => $nim,
        'format_valid' => $isValid,
        'sks_diambil' => $sksDiambil,
        'sisa_kuota' => $sisaSks,
        'metode' => 'Aturan Kode Deterministik (Regex & Math) — 0 Biaya, 100% Akurat',
    ];
}

$hasilDeterministik = validasiNimDanHitungSks('10241016', 18);
print_r($hasilDeterministik);

// Skenario 2: Persoalan AI Generatif (Mengekstrak Informasi Acara dari Teks Chat Informal)
echo "\n--- 2. Persoalan AI Generatif: Ekstraksi Bahasa Bebas Informal ---\n";
$chatInformal = 'Halo min, mau request poster dong buat Workshop UI/UX sabtu tgl 12 des 2026 jam 9 pagi di lab SI, pembicaranya mas Adit dari Tokped. Buat anak2 SI. Kontak 0812334455.';

echo "Input Teks Bebas:\n\"$chatInformal\"\n\n";

// Simulasi Heuristic Regex Parser vs AI Parser
function simulasiEkstraksiTeks(string $teks): array
{
    // Ekstraksi entitas dengan heuristik
    $hasil = [
        'nama_acara' => null,
        'tanggal' => null,
        'jam' => null,
        'kontak' => null,
    ];

    // Deteksi tanggal
    if (preg_match('/(\d{1,2}\s+[a-zA-Z]+\s+\d{4})/', $teks, $m)) {
        $hasil['tanggal'] = $m[1];
    }

    // Deteksi jam
    if (preg_match('/(jam\s+\d{1,2}(?:\.\d{2})?(?:\s*(?:pagi|siang|sore|malam|wita|wib))?)/i', $teks, $m)) {
        $hasil['jam'] = $m[1];
    }

    // Deteksi nomor kontak
    if (preg_match('/(08\d{8,11})/', $teks, $m)) {
        $hasil['kontak'] = $m[1];
    }

    // Deteksi nama acara
    if (preg_match('/(?:buat|acara)\s+([A-Za-z0-9\/\s]+?)(?=\s+(?:sabtu|minggu|senin|tgl|tanggal|jam))/i', $teks, $m)) {
        $hasil['nama_acara'] = trim($m[1]);
    }

    return $hasil;
}

$hasilEkstraksi = simulasiEkstraksiTeks($chatInformal);
echo "Hasil Ekstraksi Entitas:\n";
print_r($hasilEkstraksi);

echo "\nKESIMPULAN ANALISIS MINGGU 1:\n";
echo "1. Persoalan #1 (Validasi NIM) WAJIB diselesaikan dengan kode biasa (bukan LLM) karena sifatnya deterministik & pasti.\n";
echo "2. Persoalan #2 (Brief Informal) MEMERLUKAN pemahaman bahasa alami (AI Engineering) karena variasi bahasa manusia tidak terbatas.\n";
echo "======================================================================\n";
