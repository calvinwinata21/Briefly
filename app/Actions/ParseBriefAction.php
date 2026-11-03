<?php

namespace App\Actions;

use App\Services\GeminiApiService;

class ParseBriefAction
{
    public function __construct(
        protected GeminiApiService $geminiService
    ) {}

    /**
     * Parse raw brief text into structured intent & entities.
     */
    public function execute(string $eventTitle, string $rawBrief): array
    {
        $systemInstruction = "You are an expert event brief parser. Parse the provided raw event brief into JSON containing:
        - event_name: string
        - event_date: string (e.g. '28 Oktober 2026' or detected date)
        - target_audience: string
        - key_highlights: array of strings
        - contact_person: string (name & phone/IG or 'Panitia')";

        $userPrompt = "Title: {$eventTitle}\nRaw Brief:\n{$rawBrief}";

        $parsed = $this->geminiService->generateStructuredJson($systemInstruction, $userPrompt);

        if ($parsed && ! empty($parsed['event_name'])) {
            return [
                'event_name' => (string) $parsed['event_name'],
                'event_date' => (string) ($parsed['event_date'] ?? ''),
                'target_audience' => (string) ($parsed['target_audience'] ?? 'Mahasiswa dan Civitas Akademika'),
                'key_highlights' => is_array($parsed['key_highlights'] ?? null) ? $parsed['key_highlights'] : [],
                'contact_person' => (string) ($parsed['contact_person'] ?? ''),
            ];
        }

        // Heuristic fallback parser
        return $this->heuristicParse($eventTitle, $rawBrief);
    }

    /**
     * Smart heuristic parser for entity extraction when API key is offline or unavailable.
     */
    protected function heuristicParse(string $title, string $raw): array
    {
        // Detect dates (e.g. 24 November, 12/10/2026, 20 Oktober 2026, etc.)
        $date = '';
        if (preg_match('/(\d{1,2}\s+(?:Januari|Februari|Maret|April|Mei|Juni|Juli|Agustus|September|Oktober|November|Desember|\w+)\s+\d{4})/i', $raw, $m)) {
            $date = $m[1];
        } elseif (preg_match('/(\d{1,2}[\/\-\.]\d{1,2}[\/\-\.]\d{2,4})/i', $raw, $m)) {
            $date = $m[1];
        } elseif (preg_match('/(?:tanggal|tgl|hari|waktu|pelaksanaan)[:\s]+([^\n,\.]+)/i', $raw, $m)) {
            $date = trim($m[1]);
        }

        // Detect Contact Person (e.g. CP, WA, Hubungi, Narahubung, 08xx)
        $cp = '';
        if (preg_match('/(?:cp|wa|kontak|hubungi|narahubung|contact)[:\s]+([^\n\r]+)/i', $raw, $m)) {
            $cp = trim($m[1]);
        } elseif (preg_match('/(08\d{8,12})/i', $raw, $m)) {
            $cp = $m[1];
        }

        // Target audience
        $audience = 'Mahasiswa Umum & Seluruh Civitas Akademika Kampus';
        if (preg_match('/(?:target|peserta|sasaran|audience)[:\s]+([^\n\r]+)/i', $raw, $m)) {
            $audience = trim($m[1]);
        }

        // Extract key highlights from bullet points, dashes, or numbered lines
        $highlights = [];
        $lines = preg_split('/[\r\n]+/', $raw);
        foreach ($lines as $line) {
            $trimmed = trim($line);
            if (preg_match('/^[\-\*•\d\.]+\s*(.+)$/u', $trimmed, $m)) {
                $item = trim($m[1]);
                if (strlen($item) > 4 && strlen($item) < 120) {
                    $highlights[] = $item;
                }
            }
        }

        if (empty($highlights)) {
            $highlights = [
                'Wadah eksplorasi potensi dan pengembangan skill mahasiswa.',
                'Sesi interaktif bersama pembicara & praktisi berpengalaman.',
                'E-Sertifikat bernilai SKP dan merchandise eksklusif.',
                'Networking bersama mahasiswa se-universitas.',
            ];
        }

        return [
            'event_name' => ! empty($title) ? $title : '',
            'event_date' => $date, // empty if not found, to trigger fallback guardrail
            'target_audience' => $audience,
            'key_highlights' => array_slice($highlights, 0, 5),
            'contact_person' => $cp,
        ];
    }
}
