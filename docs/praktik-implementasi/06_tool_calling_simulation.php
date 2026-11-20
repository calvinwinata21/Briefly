<?php

/**
 * PRAKTIK IMPLEMENTASI — MINGGU 6: Tool Calling & Deskripsi Batas Akses
 *
 * Eksperimen:
 * 1. Siklus pemanggilan tool: Prompt -> Tool Request -> Tool Execution -> Final Response.
 * 2. Menguji pengaruh deskripsi tool terhadap ketepatan pemilihan tool oleh model.
 * 3. Penanganan error jujur (Honest Error vs Hidden Failure).
 *
 * Cara Menjalankan:
 *   php docs/praktik-implementasi/06_tool_calling_simulation.php
 */
echo "======================================================================\n";
echo "  PRAKTIK MINGGU 6: TOOL CALLING & PENANGANAN ERROR JUJUR (Briefly AI)\n";
echo "======================================================================\n\n";

// Definisi 3 Tool Terdaftar
$toolsCatalog = [
    'ParseBriefAction' => [
        'description' => 'Mengekstraksi entitas kunci (nama acara, tanggal, audiens, kontak) dari teks brief mentah. JANGAN gunakan untuk mencari aturan warna atau brand.',
        'parameters' => ['raw_brief_text', 'event_title'],
        'access' => 'Read-only on input string',
    ],
    'ExecuteRagSearchAction' => [
        'description' => 'Mencari chunk panduan brand HMSI (palet warna, tipografi, nada suara) berdasarkan kemiripan kosinus. JANGAN gunakan untuk mencari data peserta atau akun admin.',
        'parameters' => ['query', 'limit', 'min_similarity'],
        'access' => 'Read-only on brand_guidelines table',
    ],
    'ValidateAiOutputAction' => [
        'description' => 'Memvalidasi kepatuhan payload JSON terhadap skema kontrak resmi Briefly AI. JANGAN gunakan untuk mengubah teks brief pengguna.',
        'parameters' => ['raw_payload'],
        'access' => 'Pure validation in-memory',
    ],
];

// Simulasi Dispatcher Tool Calling
function dispatcherTool(string $toolName, array $params, array $catalog): array
{
    if (! isset($catalog[$toolName])) {
        // Error Jujur 1: Tool tidak terdaftar
        return [
            'status' => 'ERROR',
            'error_type' => 'TOOL_NOT_FOUND',
            'message' => "Tool '{$toolName}' tidak dikenal dalam sistem. Tool yang tersedia: ".implode(', ', array_keys($catalog)),
        ];
    }

    // Validasi parameter wajib
    foreach ($catalog[$toolName]['parameters'] as $param) {
        if (! isset($params[$param])) {
            // Error Jujur 2: Parameter hilang
            return [
                'status' => 'ERROR',
                'error_type' => 'MISSING_PARAMETER',
                'message' => "Parameter '{$param}' wajib diisi untuk memanggil tool '{$toolName}'.",
            ];
        }
    }

    // Eksekusi simulasi
    if ($toolName === 'ExecuteRagSearchAction') {
        if (empty(trim($params['query']))) {
            return [
                'status' => 'ERROR',
                'error_type' => 'EMPTY_QUERY',
                'message' => 'Query pencarian RAG tidak boleh kosong.',
            ];
        }

        return [
            'status' => 'SUCCESS',
            'data' => [
                ['chunk_id' => 1, 'title' => 'Palet Warna Primer', 'similarity' => 0.85],
                ['chunk_id' => 6, 'title' => 'Brand Voice Kasual', 'similarity' => 0.78],
            ],
        ];
    }

    return ['status' => 'SUCCESS', 'message' => "Tool {$toolName} berhasil dieksekusi."];
}

// 1. Uji Pemanggilan Sukses
echo "--- 1. Uji Pemanggilan Tool Normal (Sukses) ---\n";
$reqNormal = dispatcherTool('ExecuteRagSearchAction', ['query' => 'UI/UX Workshop', 'limit' => 2, 'min_similarity' => 0.4], $toolsCatalog);
print_r($reqNormal);

// 2. Uji Penanganan Error Jujur vs Error Disembunyikan (Modul 6.3)
echo "\n--- 2. Perbandingan: Error Jujur vs Error Disembunyikan ---\n";
echo "Skenario: Model memanggil ExecuteRagSearchAction dengan query kosong.\n";

$errorJujur = dispatcherTool('ExecuteRagSearchAction', ['query' => '', 'limit' => 2, 'min_similarity' => 0.4], $toolsCatalog);
echo "\n[A] Respon Jujur (Implementasi Briefly AI):\n";
print_r($errorJujur);
echo "Efek: Model AI mengetahui bahwa query-nya kosong dan dapat memperbaikinya secara sadar.\n";

echo "\n[B] Respon Disembunyikan (Praktik Buruk yang Dilarang):\n";
$errorDisembunyikan = ['status' => 'SUCCESS', 'data' => []]; // Hasil kosong tanpa pesan error
print_r($errorDisembunyikan);
echo "Bahaya: Model menyimpulkan bahwa data brand memang TIDAK ADA di organisasi, lalu mulai MENGARANG warna sendiri secara liar!\n\n";

// 3. Uji Pemilihan Tool Berdasarkan Deskripsi
echo "--- 3. Uji Ketepatan Pemilihan Tool pada 8 Pertanyaan (Minggu 6) ---\n";
$soalUji = [
    1 => ['q' => 'Saya ingin tahu warna navy resmi HMSI', 'target' => 'ExecuteRagSearchAction'],
    2 => ['q' => 'Ekstrak tanggal dan narasumber dari teks WA ini', 'target' => 'ParseBriefAction'],
    3 => ['q' => 'Periksa apakah JSON ini sudah memenuhi 4 slide layout', 'target' => 'ValidateAiOutputAction'],
    4 => ['q' => 'Berapa jumlah penduduk Kota Balikpapan?', 'target' => 'NONE (Di luar cakupan)'],
    5 => ['q' => 'Cari aturan hashtag Instagram dan validasi output', 'target' => 'MULTI-TOOL (RAG -> Validate)'],
    6 => ['q' => 'Apa resep masakan rendang Padang?', 'target' => 'NONE (Ditolak)'],
    7 => ['q' => 'Ambil data mahasiswa dari basis data akademik', 'target' => 'NONE (Ditolak: batas akses tool)'],
    8 => ['q' => 'Ekstrak kontak person dan cari warna tombol CTA', 'target' => 'MULTI-TOOL (Parse -> RAG)'],
];

foreach ($soalUji as $no => $item) {
    printf("%d. Pertanyaan: \"%-50s\" -> Tool: %s\n", $no, $item['q'], $item['target']);
}

echo "\nKesimpulan: Deskripsi tool yang memuat batasan 'KAPAN TIDAK BOLEH DIPAKAI' mencegah model salah memilih tool pada pertanyaan jebakan.\n";
echo "======================================================================\n";
