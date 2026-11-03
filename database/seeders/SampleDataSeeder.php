<?php

namespace Database\Seeders;

use App\Models\Brief;
use App\Models\MediaDraft;
use App\Models\User;
use Illuminate\Database\Seeder;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        $panitia = User::where('role', 'requester')->first();
        $designer = User::where('role', 'designer')->first();

        if (! $panitia) {
            return;
        }

        // 1. Pending Review Draft (Seminar)
        $brief1 = Brief::create([
            'user_id' => $panitia->id,
            'event_title' => 'National Tech Seminar: Future of Agentic AI 2026',
            'raw_brief_text' => "Acara: National Tech Seminar 2026\nTanggal: 28 November 2026\nWaktu: 09.00 - 15.00 WIB\nLokasi: Auditorium Utama Kampus & Zoom Webinar\nTarget Peserta: Mahasiswa Teknik Informatika & Penggiat Teknologi\n\nPoin Penting:\n- Eksplorasi Autonomous Agentic AI & Model Reasoning terkini\n- Keynote speaker praktisi AI Engineering unicorn nasional\n- Live hands-on demo pembuatan sistem RAG terintegrasi\n- Free E-Certificate (SKP) dan Goodie Bag eksklusif\n\nNarahubung: WhatsApp 0812-9876-5432 (Kak Sarah)",
            'status' => 'completed',
        ]);

        MediaDraft::create([
            'brief_id' => $brief1->id,
            'reviewed_by' => null,
            'approval_status' => 'PENDING_REVIEW',
            'designer_feedback' => null,
            'ai_output_json' => [
                'job_id' => 'JOB-TECHAI26',
                'event_metadata' => [
                    'event_name' => 'National Tech Seminar: Future of Agentic AI 2026',
                    'event_date' => '28 November 2026',
                    'contact_person' => 'WhatsApp 0812-9876-5432 (Kak Sarah)',
                ],
                'branding_rules_applied' => [
                    'primary_color_hex' => '#003366',
                    'secondary_color_hex' => '#FFCC00',
                    'font_family_recommendation' => 'Instrument Sans, Inter, sans-serif',
                ],
                'visual_slides_layout' => [
                    [
                        'slide_number' => 1,
                        'layout_template' => 'cover',
                        'headline' => 'Future of Agentic AI 2026',
                        'body_text' => [
                            'Pelaksanaan: 28 November 2026',
                            'Lokasi: Auditorium Utama & Zoom Webinar',
                            'Diselenggarakan oleh Himpunan Mahasiswa Informatika',
                        ],
                    ],
                    [
                        'slide_number' => 2,
                        'layout_template' => 'content_list',
                        'headline' => 'Apa yang Akan Kamu Pelajari?',
                        'body_text' => [
                            'Eksplorasi arsitektur autonomous agent & multi-agent reasoning.',
                            'Studi kasus nyata implementasi RAG di industri enterprise.',
                            'Hands-on live demo membangun agentic pipeline terintegrasi.',
                            'Sesi tanya jawab interaktif bersama Lead AI Engineer.',
                        ],
                    ],
                    [
                        'slide_number' => 3,
                        'layout_template' => 'quote',
                        'headline' => 'Persiapkan Karir AI Sekarang',
                        'body_text' => [
                            '"Era AI agentic bukan sekadar tren sesaat, melainkan transformasi fundamental cara kita membangun perangkat lunak."',
                            'Fasilitas: E-Sertifikat resmi (SKP), rekaman sesi, dan relasi komunitas tech.',
                        ],
                    ],
                    [
                        'slide_number' => 4,
                        'layout_template' => 'cta',
                        'headline' => 'Amankan Kursimu Sekarang!',
                        'body_text' => [
                            'Pendaftaran dibuka tanpa dipungut biaya (Gratis).',
                            'Kuota kursi luring terbatas untuk 250 peserta pertama.',
                            'Info & Pendaftaran: WhatsApp 0812-9876-5432 (Kak Sarah)',
                        ],
                    ],
                ],
                'social_media_caption' => [
                    'hook_sentence' => 'Siap hadapi era revolusi kecerdasan buatan selanjutnya? 🚀',
                    'body_paragraph' => "Jangan lewatkan 'National Tech Seminar: Future of Agentic AI 2026' pada Sabtu, 28 November 2026! Pelajari langsung bagaimana Agen AI otonom bekerja dan mengotomasi alur produksi digital bersama praktisi industri tech nasional.",
                    'call_to_action' => 'Registrasi sekarang juga melalui tautan di bio profil kami sebelum kuota terpenuhi!',
                    'hashtags' => ['#TechSeminar2026', '#AgenticAI', '#HMIFKampus', '#InformatikaJaya', '#MediaProduction'],
                ],
                'workflow_status' => 'PENDING_REVIEW',
                'rag_context_used' => [
                    [
                        'topic' => 'Palet Warna & Brand Identity',
                        'similarity' => 0.94,
                        'excerpt' => 'Warna resmi identitas organisasi adalah Navy Blue (#003366) dan Gold Yellow (#FFCC00).',
                    ],
                    [
                        'topic' => 'Struktur Tipografi & Slide Konten',
                        'similarity' => 0.91,
                        'excerpt' => 'Setiap postingan slide korsel wajib memiliki minimal 3 slide. Slide 1 harus cover menarik.',
                    ],
                    [
                        'topic' => 'SOP Caption Instagram & Hook',
                        'similarity' => 0.88,
                        'excerpt' => 'Caption wajib diawali kalimat hook yang memicu rasa penasaran dalam 1-2 baris pertama.',
                    ],
                ],
            ],
        ]);

        // 2. Approved Draft (Hackathon)
        $brief2 = Brief::create([
            'user_id' => $panitia->id,
            'event_title' => 'Campus Hackathon 2026: Code for Impact',
            'raw_brief_text' => "Event: Campus Hackathon & Competition 2026\nTanggal: 15-16 Desember 2026\nTempat: Lab Riset Multimedia Kampus\nTotal Hadiah: Rp 25.000.000\nNarahubung: 0857-1122-3344 (Budi Satria)",
            'status' => 'completed',
        ]);

        MediaDraft::create([
            'brief_id' => $brief2->id,
            'reviewed_by' => $designer?->id,
            'approval_status' => 'APPROVED',
            'designer_feedback' => 'Disetujui. Layout slide korsel rapi, warna palet sesuai dan kontras teks terbaca jelas.',
            'ai_output_json' => [
                'job_id' => 'JOB-HACK2026',
                'event_metadata' => [
                    'event_name' => 'Campus Hackathon 2026: Code for Impact',
                    'event_date' => '15-16 Desember 2026',
                    'contact_person' => '0857-1122-3344 (Budi Satria)',
                ],
                'branding_rules_applied' => [
                    'primary_color_hex' => '#003366',
                    'secondary_color_hex' => '#FFCC00',
                    'font_family_recommendation' => 'Instrument Sans, Inter, sans-serif',
                ],
                'visual_slides_layout' => [
                    [
                        'slide_number' => 1,
                        'layout_template' => 'cover',
                        'headline' => 'Campus Hackathon 2026',
                        'body_text' => ['Code for Impact — Total Hadiah Rp 25 Juta', 'Pelaksanaan: 15-16 Desember 2026'],
                    ],
                    [
                        'slide_number' => 2,
                        'layout_template' => 'content_list',
                        'headline' => 'Kategori Solusi Kompetisi',
                        'body_text' => ['Smart Campus Solution', 'Green Technology & Sustainability', 'EduTech & Inclusive Learning'],
                    ],
                    [
                        'slide_number' => 3,
                        'layout_template' => 'cta',
                        'headline' => 'Daftarkan Tim Hebatmu!',
                        'body_text' => ['Registrasi dibuka hingga 5 Desember 2026.', 'Narahubung: 0857-1122-3344 (Budi Satria)'],
                    ],
                ],
                'social_media_caption' => [
                    'hook_sentence' => 'Punya ide inovatif untuk memecahkan masalah kampus? Buktikan di sini!',
                    'body_paragraph' => 'Campus Hackathon 2026 hadir kembali dengan total hadiah Rp 25.000.000. Bentuk tim terbaikmu dan bangun prototype solutif.',
                    'call_to_action' => 'Daftar sekarang melalui tautan di bio profil!',
                    'hashtags' => ['#Hackathon2026', '#CodeForImpact', '#HMIFKampus'],
                ],
                'workflow_status' => 'APPROVED',
            ],
        ]);
    }
}
