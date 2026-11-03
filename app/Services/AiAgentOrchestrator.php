<?php

namespace App\Services;

use App\Actions\ExecuteRagSearchAction;
use App\Actions\ParseBriefAction;
use App\Actions\ValidateAiOutputAction;
use App\Models\Brief;
use App\Models\MediaDraft;
use Illuminate\Support\Str;

class AiAgentOrchestrator
{
    public function __construct(
        protected ParseBriefAction $parseAction,
        protected ExecuteRagSearchAction $ragAction,
        protected ValidateAiOutputAction $validateAction,
        protected GeminiApiService $geminiService
    ) {}

    /**
     * Process a raw brief through the multi-stage AI Agent pipeline.
     */
    public function process(Brief $brief): array
    {
        $brief->update(['status' => 'processing']);

        // Step 1 — Intent & Entity Parsing
        $parsedEntities = $this->parseAction->execute($brief->event_title, $brief->raw_brief_text);

        // Step 2 — RAG Similarity Retrieval & Completeness Guardrail
        $ragResult = $this->ragAction->execute($parsedEntities, $brief->raw_brief_text);

        if (! $ragResult['is_complete']) {
            $brief->update(['status' => 'incomplete_data']);

            $incompletePayload = [
                'job_id' => 'JOB-'.strtoupper(Str::random(8)),
                'event_metadata' => [
                    'event_name' => $parsedEntities['event_name'] ?: 'Tidak Terdefinisi',
                    'event_date' => $parsedEntities['event_date'] ?: 'Tidak Ditemukan',
                    'contact_person' => $parsedEntities['contact_person'] ?: '-',
                ],
                'branding_rules_applied' => [
                    'primary_color_hex' => $ragResult['branding']['primary_color_hex'] ?? '#003366',
                    'secondary_color_hex' => $ragResult['branding']['secondary_color_hex'] ?? '#FFCC00',
                    'font_family_recommendation' => $ragResult['branding']['font_family_recommendation'] ?? 'Instrument Sans',
                ],
                'visual_slides_layout' => [
                    [
                        'slide_number' => 1,
                        'layout_template' => 'cover',
                        'headline' => 'Data Brief Tidak Lengkap',
                        'body_text' => ['Informasi esensial tidak mencukupi untuk menghasilkan konten desain media.'],
                    ],
                ],
                'social_media_caption' => [
                    'hook_sentence' => 'Informasi acara belum dapat dirilis.',
                    'body_paragraph' => 'Mohon lengkapi nama acara dan tanggal pelaksanaan pada form brief.',
                    'call_to_action' => 'Kirim revisi brief Anda melalui dashboard.',
                    'hashtags' => ['#DataIncomplete', '#BriefRevisi'],
                ],
                'workflow_status' => 'INCOMPLETE_DATA',
                'rag_context_used' => [],
                'rejection_reason' => $ragResult['message'],
            ];

            $draft = MediaDraft::create([
                'brief_id' => $brief->id,
                'ai_output_json' => $incompletePayload,
                'approval_status' => 'REJECTED',
                'designer_feedback' => 'Sistem otomatis menolak: '.$ragResult['message'],
            ]);

            return [
                'status' => 'incomplete_data',
                'message' => $ragResult['message'],
                'draft' => $draft,
                'payload' => $incompletePayload,
            ];
        }

        // Step 3 & 4 — Content Generation + Schema Guardrail (with up to 3 retries)
        $maxRetries = 3;
        $attempt = 0;
        $validData = null;

        while ($attempt < $maxRetries) {
            $attempt++;

            $generatedOutput = $this->generateContent($parsedEntities, $ragResult);
            $validation = $this->validateAction->execute($generatedOutput);

            if ($validation['is_valid']) {
                $validData = $validation['data'];
                break;
            }
        }

        // If after 3 retries still has format discrepancy, sanitize with deterministic generator
        if (! $validData) {
            $validData = $this->buildGuaranteedValidPayload($parsedEntities, $ragResult);
        }

        // Attach RAG context telemetry for designer transparency
        $validData['rag_context_used'] = array_map(function ($c) {
            return [
                'topic' => $c['topic'],
                'similarity' => $c['similarity_score'],
                'excerpt' => Str::limit($c['content'], 120),
            ];
        }, $ragResult['rag_chunks']);

        // Persist MediaDraft
        $draft = MediaDraft::create([
            'brief_id' => $brief->id,
            'ai_output_json' => $validData,
            'approval_status' => 'PENDING_REVIEW',
            'designer_feedback' => null,
        ]);

        $brief->update(['status' => 'completed']);

        return [
            'status' => 'success',
            'message' => 'Draf AI berhasil dibuat dan siap ditinjau oleh Lead Designer.',
            'draft' => $draft,
            'payload' => $validData,
        ];
    }

    /**
     * Generate content via Gemini API or intelligent structured builder.
     */
    protected function generateContent(array $entities, array $rag): array
    {
        $branding = $rag['branding'];
        $ragExcerpts = implode("\n- ", array_column($rag['rag_chunks'], 'content'));

        $systemInstruction = 'You are an expert Social Media & Graphic Content Production AI Agent.
Output ONLY strict JSON conforming to this schema without markdown fences:
{
  "job_id": string,
  "event_metadata": {
    "event_name": string,
    "event_date": string,
    "contact_person": string
  },
  "branding_rules_applied": {
    "primary_color_hex": string (hex format #003366),
    "secondary_color_hex": string (hex format #FFCC00),
    "font_family_recommendation": string
  },
  "visual_slides_layout": [
    {
      "slide_number": integer,
      "layout_template": "cover" | "content_list" | "quote" | "cta",
      "headline": string (MAX 50 CHARS),
      "body_text": [string]
    }
  ],
  "social_media_caption": {
    "hook_sentence": string,
    "body_paragraph": string,
    "call_to_action": string,
    "hashtags": [string]
  },
  "workflow_status": "PENDING_REVIEW"
}';

        $userPrompt = "Event Title: {$entities['event_name']}
Date: {$entities['event_date']}
Target: {$entities['target_audience']}
Highlights: ".implode(', ', $entities['key_highlights'])."
Contact: {$entities['contact_person']}

Brand Guidelines & SOP retrieved from RAG:
Primary Color: {$branding['primary_color_hex']}
Secondary Color: {$branding['secondary_color_hex']}
Font: {$branding['font_family_recommendation']}
Voice: {$branding['brand_voice']}
Relevant SOP Chunks:
- {$ragExcerpts}";

        $geminiResult = $this->geminiService->generateStructuredJson($systemInstruction, $userPrompt);

        if ($geminiResult && is_array($geminiResult)) {
            // Guarantee job_id and colors
            $geminiResult['job_id'] = $geminiResult['job_id'] ?? 'JOB-'.strtoupper(Str::random(8));
            $geminiResult['branding_rules_applied']['primary_color_hex'] = $branding['primary_color_hex'];
            $geminiResult['branding_rules_applied']['secondary_color_hex'] = $branding['secondary_color_hex'];
            $geminiResult['workflow_status'] = 'PENDING_REVIEW';

            return $geminiResult;
        }

        return $this->buildGuaranteedValidPayload($entities, $rag);
    }

    /**
     * Deterministic, guaranteed valid generator strictly adhering to the JSON schema.
     */
    protected function buildGuaranteedValidPayload(array $entities, array $rag): array
    {
        $branding = $rag['branding'];
        $name = Str::limit($entities['event_name'], 40, '');
        $date = $entities['event_date'];
        $cp = $entities['contact_person'];
        $highlights = $entities['key_highlights'];

        // Max headline 50 chars as required by schema
        $coverHeadline = Str::limit($name, 45, '...');
        $listHeadline = Str::limit('Poin Utama & Manfaat Acara', 45);
        $quoteHeadline = Str::limit('Kenapa Kamu Wajib Ikutan?', 45);
        $ctaHeadline = Str::limit('Registrasi & Info Lengkap', 45);

        return [
            'job_id' => 'JOB-'.strtoupper(Str::random(8)),
            'event_metadata' => [
                'event_name' => (string) $entities['event_name'],
                'event_date' => (string) $date,
                'contact_person' => (string) $cp,
            ],
            'branding_rules_applied' => [
                'primary_color_hex' => (string) $branding['primary_color_hex'],
                'secondary_color_hex' => (string) $branding['secondary_color_hex'],
                'font_family_recommendation' => (string) $branding['font_family_recommendation'],
            ],
            'visual_slides_layout' => [
                [
                    'slide_number' => 1,
                    'layout_template' => 'cover',
                    'headline' => $coverHeadline,
                    'body_text' => [
                        'Pelaksanaan: '.$date,
                        'Target Peserta: '.$entities['target_audience'],
                        'Diselenggarakan oleh Tim Kreatif & Media Kampus',
                    ],
                ],
                [
                    'slide_number' => 2,
                    'layout_template' => 'content_list',
                    'headline' => $listHeadline,
                    'body_text' => ! empty($highlights) ? array_slice($highlights, 0, 4) : [
                        'Sharing session langsung dari pemateri ahli bidangnya.',
                        'Eksplorasi wawasan praktis yang relevan dengan dunia kerja.',
                        'Relasi dan koneksi komunitas mahasiswa se-kampus.',
                    ],
                ],
                [
                    'slide_number' => 3,
                    'layout_template' => 'quote',
                    'headline' => $quoteHeadline,
                    'body_text' => [
                        '"Kesempatan emas tidak datang dua kali. Upgrade skill dan kembangkan potensimu bersama kami sekarang!"',
                        'Fasilitas: E-Sertifikat resmi, materi eksklusif, dan relasi berharga.',
                    ],
                ],
                [
                    'slide_number' => 4,
                    'layout_template' => 'cta',
                    'headline' => $ctaHeadline,
                    'body_text' => [
                        'Jangan lewatkan! Kuota peserta sangat terbatas.',
                        'Tanggal Kegiatan: '.$date,
                        'Narahubung & Registrasi: '.$cp,
                    ],
                ],
            ],
            'social_media_caption' => [
                'hook_sentence' => "Siap tingkatkan potensimu? Inilah saatnya bergabung di {$name}!",
                'body_paragraph' => "Halo rekan-rekan! Kami mengundang kalian untuk hadir dalam kegiatan '{$name}' yang akan diselenggarakan pada {$date}. Dapatkan wawasan berharga, relasi baru, serta pengalaman inspiratif bersama narasumber berpengalaman.",
                'call_to_action' => "Segera amankan kursimu sebelum kuota terpenuhi! Hubungi {$cp} atau klik tautan di bio profil kami.",
                'hashtags' => ! empty($branding['hashtag_rules']) ? $branding['hashtag_rules'] : ['#EventKampus2026', '#InformatikaJaya', '#MediaProduction', '#Kepanitiaan'],
            ],
            'workflow_status' => 'PENDING_REVIEW',
        ];
    }
}
