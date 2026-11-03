<?php

namespace Database\Seeders;

use App\Models\BrandGuideline;
use Illuminate\Database\Seeder;

class BrandGuidelineSeeder extends Seeder
{
    public function run(): void
    {
        BrandGuideline::updateOrCreate(
            ['organization_name' => 'Himpunan Mahasiswa Informatika (HMIF)'],
            [
                'primary_color_hex' => '#003366',
                'secondary_color_hex' => '#FFCC00',
                'document_path' => 'rag_documents/brand_guideline_hmif_2026.pdf',
                'vector_metadata' => [
                    'brand_voice' => 'Informatif, energik, profesional, dan ramah mahasiswa.',
                    'font_recommendation' => 'Inter, Montserrat, Instrument Sans',
                    'prohibited_elements' => 'Dilarang memotong rasio logo, dilarang memakai warna background yang bentrok dengan teks, dilarang singkatan tak resmi.',
                    'hashtag_rules' => ['#HMIFKampus', '#InformatikaJaya', '#TechEvent2026', '#MediaKreatif'],
                    'color_palette' => [
                        'primary' => '#003366',
                        'secondary' => '#FFCC00',
                        'accent' => '#00A86B',
                        'background_light' => '#F8FAFC',
                        'dark_surface' => '#0F172A',
                    ],
                    'rag_chunks' => [
                        [
                            'chunk_id' => 'chunk_1',
                            'topic' => 'Palet Warna & Identitas Visual',
                            'content' => 'Warna resmi identitas organisasi adalah Navy Blue (#003366) sebagai warna utama yang melambangkan integritas intelektual, dan Gold Yellow (#FFCC00) sebagai aksen sekunder yang melambangkan antusiasme mahasiswa.',
                            'embedding_keywords' => ['warna', 'primary_color', 'secondary_color', 'identitas', 'navy', 'gold', 'hex'],
                        ],
                        [
                            'chunk_id' => 'chunk_2',
                            'topic' => 'Struktur Tipografi & Slide Konten',
                            'content' => 'Setiap postingan slide korsel wajib memiliki minimal 3 slide dan maksimal 7 slide. Slide 1 harus cover menarik dengan headline kuat maksimal 50 karakter. Slide penutup (CTA) wajib memuat tanggal acara dan narahubung.',
                            'embedding_keywords' => ['tipografi', 'slide', 'carousel', 'headline', 'cover', 'cta', 'struktur'],
                        ],
                        [
                            'chunk_id' => 'chunk_3',
                            'topic' => 'SOP Caption Instagram & Hook',
                            'content' => 'Caption wajib diawali kalimat hook yang memicu rasa penasaran dalam 1-2 baris pertama, dilanjutkan paragraf penjelasan detail isi acara, diakhiri ajakan aksi (Call to Action) yang jelas serta 4-6 hashtag resmi organisasi.',
                            'embedding_keywords' => ['caption', 'instagram', 'hook', 'cta', 'hashtag', 'sop', 'narasi'],
                        ],
                        [
                            'chunk_id' => 'chunk_4',
                            'topic' => 'Validasi Wajib Data Acara',
                            'content' => 'Setiap publikasi event wajib mencantumkan nama acara secara eksplisit, tanggal atau rentang waktu kegiatan, dan contact person panitia. Jika data tidak lengkap, proses wajib ditolak (INCOMPLETE_DATA).',
                            'embedding_keywords' => ['validasi', 'event_name', 'event_date', 'contact_person', 'incomplete_data', 'wajib'],
                        ],
                    ],
                ],
            ]
        );
    }
}
