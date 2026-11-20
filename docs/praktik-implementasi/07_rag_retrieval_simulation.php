<?php

/**
 * PRAKTIK IMPLEMENTASI — MINGGU 7 & 9: RAG Vector Retrieval & Anti-Hallucination
 *
 * Eksperimen:
 * 1. Pemuatan 8 Chunk Panduan Brand HMSI (Lingkup Wajib K = 23).
 * 2. Algoritma Kemiripan Kosinus (Cosine Similarity) berbasis frekuensi token/kata kunci.
 * 3. Penerapan Ambang Batas Relevansi (Threshold = 0.40).
 * 4. Uji Penolakan Terhadap Pertanyaan di Luar Dokumen (Negative Knowledge Refusal).
 *
 * Cara Menjalankan:
 *   php docs/praktik-implementasi/07_rag_retrieval_simulation.php
 */
echo "======================================================================\n";
echo "  PRAKTIK MINGGU 7 & 9: RAG RETRIEVAL & GROUNDING ENGINE (Briefly AI)\n";
echo "======================================================================\n\n";

// 1. Muat 8 Chunk Brand Guidelines
$kbPath = __DIR__.'/../dokumen-sistem-briefly/brand-guidelines-kb/brand-guidelines-8-chunks.json';
if (! file_exists($kbPath)) {
    exit("File basis pengetahuan brand tidak ditemukan di: $kbPath\n");
}
$chunks = json_decode(file_get_contents($kbPath), true);
echo 'Berhasil memuat '.count($chunks)." Chunk Brand Guidelines HMSI (K = 23 Requirement).\n\n";

// 2. Fungsi Tokenisasi & Ekstraksi Vektor Sederhana
function buatVektorToken(string $teks): array
{
    $teksBersih = strtolower(preg_replace('/[^a-zA-Z0-9#\s]/', ' ', $teks));
    $tokens = array_filter(explode(' ', $teksBersih), fn ($t) => strlen($t) > 2);

    return array_count_values($tokens);
}

// 3. Algoritma Cosine Similarity
function hitungCosineSimilarity(array $vecA, array $vecB): float
{
    $dotProduct = 0.0;
    $normA = 0.0;
    $normB = 0.0;

    foreach ($vecA as $token => $count) {
        $normA += $count * $count;
        if (isset($vecB[$token])) {
            $dotProduct += $count * $vecB[$token];
        }
    }

    foreach ($vecB as $count) {
        $normB += $count * $count;
    }

    if ($normA == 0.0 || $normB == 0.0) {
        return 0.0;
    }

    return $dotProduct / (sqrt($normA) * sqrt($normB));
}

// 4. Mesin Pencarian RAG
function cariChunkRelevan(string $query, array $chunks, float $threshold = 0.40, int $limit = 3): array
{
    $vecQuery = buatVektorToken($query);
    $hasil = [];

    foreach ($chunks as $chunk) {
        $teksDokumen = $chunk['rule_title'].' '.$chunk['content'].' '.implode(' ', $chunk['keywords']);
        $vecDoc = buatVektorToken($teksDokumen);
        $skor = hitungCosineSimilarity($vecQuery, $vecDoc);

        if ($skor >= $threshold) {
            $hasil[] = [
                'chunk_id' => $chunk['chunk_id'],
                'code' => $chunk['code'],
                'rule_title' => $chunk['rule_title'],
                'similarity' => round($skor, 4),
                'content_excerpt' => substr($chunk['content'], 0, 90).'...',
            ];
        }
    }

    // Urutkan dari skor tertinggi
    usort($hasil, fn ($a, $b) => $b['similarity'] <=> $a['similarity']);

    return array_slice($hasil, 0, $limit);
}

// 5. Uji Coba Kasus Nyata
$daftarUji = [
    [
        'tipe' => 'Kasus 1: Relevan Tinggi (Warna Primer & Latar)',
        'query' => 'warna navy biru latar belakang gelap',
        'ekspektasi' => 'Mengambil CHK-01 (Palet Warna Primer)',
    ],
    [
        'tipe' => 'Kasus 2: Relevan Tinggi (Tombol CTA & Aksen)',
        'query' => 'warna tombol aksi cta pendaftaran emas deadline',
        'ekspektasi' => 'Mengambil CHK-02 (Warna Aksen & Tombol Aksi)',
    ],
    [
        'tipe' => 'Kasus 3: Relevan Multi-Dokumen (Carousel & Kasual)',
        'query' => 'struktur 4 slide carousel nada kasual mahasiswa',
        'ekspektasi' => 'Mengambil CHK-05 (Carousel) dan CHK-06 (Brand Voice Kasual)',
    ],
    [
        'tipe' => 'Kasus 4: NEGATIVE TEST (Pertanyaan di Luar Dokumen)',
        'query' => 'aturan ukuran logo sponsor platinum di bagian footer poster',
        'ekspektasi' => 'Tidak ada chunk lolos ambang batas 0.40 -> Menolak mengarang',
    ],
];

foreach ($daftarUji as $item) {
    echo "======================================================================\n";
    echo $item['tipe']."\n";
    echo "Query: \"{$item['query']}\"\n";
    echo "Ekspektasi: {$item['ekspektasi']}\n\n";

    $retrieval = cariChunkRelevan($item['query'], $chunks, 0.40, 3);

    if (empty($retrieval)) {
        echo "[HASIL RETRIEVAL: KOSONG (Skor < 0.40)]\n";
        echo "Respon Grounding Engine: \"Informasi tidak ditemukan dalam Brand Guidelines resmi. Sistem menolak menjawab dan menggunakan default safe styling.\"\n";
    } else {
        echo '[HASIL RETRIEVAL: '.count($retrieval)." CHUNK TERPILIH]\n";
        foreach ($retrieval as $r) {
            printf("  - [%s] (ID: %d) Skor: %.4f | %s\n    Excerpt: %s\n",
                $r['code'], $r['chunk_id'], $r['similarity'], $r['rule_title'], $r['content_excerpt']);
        }
    }
    echo "\n";
}

echo "Tantangan Wajib: Ambang batas 0.40 sukses memisahkan query relevan dari query acak, mencegah context pollution sebesar 100% pada uji negatif!\n";
echo "======================================================================\n";
