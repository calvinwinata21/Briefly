<?php

namespace App\Services;

use App\Models\BrandGuideline;

class VectorSearchService
{
    /**
     * Perform vector/similarity search on brand guidelines and SOP rules.
     */
    public function searchChunks(string $query, int $topK = 4): array
    {
        $guidelines = BrandGuideline::latest()->first();

        if (! $guidelines || empty($guidelines->vector_metadata['rag_chunks'])) {
            return $this->getDefaultChunks();
        }

        $chunks = $guidelines->vector_metadata['rag_chunks'];
        $queryWords = $this->tokenize($query);

        $scoredChunks = [];
        foreach ($chunks as $chunk) {
            $chunkText = ($chunk['topic'] ?? '').' '.($chunk['content'] ?? '').' '.implode(' ', $chunk['embedding_keywords'] ?? []);
            $chunkWords = $this->tokenize($chunkText);

            // Compute similarity based on word overlap and keyword weighting
            $score = 0;
            foreach ($queryWords as $word) {
                if (in_array($word, $chunkWords)) {
                    $score += 1.5;
                }
                if (in_array($word, $chunk['embedding_keywords'] ?? [])) {
                    $score += 3.0;
                }
            }

            // Normalization
            $score = round(min(1.0, 0.45 + ($score * 0.1)), 2);

            $scoredChunks[] = [
                'chunk_id' => $chunk['chunk_id'] ?? 'chunk_'.uniqid(),
                'topic' => $chunk['topic'] ?? 'Aturan Brand',
                'content' => $chunk['content'] ?? '',
                'similarity_score' => $score,
            ];
        }

        // Sort by similarity descending
        usort($scoredChunks, fn ($a, $b) => $b['similarity_score'] <=> $a['similarity_score']);

        return array_slice($scoredChunks, 0, $topK);
    }

    /**
     * Get active brand colors and font rules.
     */
    public function getActiveBranding(): array
    {
        $guidelines = BrandGuideline::latest()->first();

        if ($guidelines) {
            return [
                'organization_name' => $guidelines->organization_name,
                'primary_color_hex' => $guidelines->primary_color_hex,
                'secondary_color_hex' => $guidelines->secondary_color_hex,
                'font_family_recommendation' => $guidelines->vector_metadata['font_recommendation'] ?? 'Instrument Sans, Inter, sans-serif',
                'brand_voice' => $guidelines->vector_metadata['brand_voice'] ?? 'Informatif, energik, profesional.',
                'hashtag_rules' => $guidelines->vector_metadata['hashtag_rules'] ?? ['#MediaKampus', '#EventUpdate'],
            ];
        }

        return [
            'organization_name' => 'Organisasi Kampus',
            'primary_color_hex' => '#003366',
            'secondary_color_hex' => '#FFCC00',
            'font_family_recommendation' => 'Instrument Sans, Inter, sans-serif',
            'brand_voice' => 'Informatif dan ramah mahasiswa.',
            'hashtag_rules' => ['#EventKampus', '#InfoAcara'],
        ];
    }

    /**
     * Fallback default chunks if DB has none.
     */
    protected function getDefaultChunks(): array
    {
        return [
            [
                'chunk_id' => 'chunk_default_1',
                'topic' => 'Palet Warna & Brand Identity',
                'content' => 'Gunakan warna Primary Navy (#003366) dan Secondary Gold (#FFCC00) sebagai aksen visual utama.',
                'similarity_score' => 0.95,
            ],
            [
                'chunk_id' => 'chunk_default_2',
                'topic' => 'Struktur Slide Visual',
                'content' => 'Slide 1 cover atraktif (headline < 50 karakter), slide isi sistematis, dan slide penutup berisi call to action & tanggal.',
                'similarity_score' => 0.88,
            ],
            [
                'chunk_id' => 'chunk_default_3',
                'topic' => 'SOP Caption Instagram',
                'content' => 'Gunakan hook memikat di awal, elaborasi manfaat kegiatan di isi, dan sertakan kontak narahubung serta CTA pendaftaran.',
                'similarity_score' => 0.85,
            ],
        ];
    }

    /**
     * Helper to tokenize text into lowercase clean words.
     */
    protected function tokenize(string $text): array
    {
        $clean = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', mb_strtolower($text));
        $words = preg_split('/\s+/', $clean, -1, PREG_SPLIT_NO_EMPTY);

        return array_unique($words);
    }
}
