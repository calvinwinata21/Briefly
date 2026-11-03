<?php

namespace App\Actions;

use App\Services\VectorSearchService;

class ExecuteRagSearchAction
{
    public function __construct(
        protected VectorSearchService $vectorService
    ) {}

    /**
     * Execute RAG retrieval and check completeness fallback rule.
     */
    public function execute(array $parsedEntities, string $rawBrief): array
    {
        // Check essential fields: event_name & event_date
        $eventName = trim($parsedEntities['event_name'] ?? '');
        $eventDate = trim($parsedEntities['event_date'] ?? '');

        $missingFields = [];
        if (empty($eventName) || strtolower($eventName) === 'untitled' || strlen($eventName) < 3) {
            $missingFields[] = 'event_name';
        }

        // If date is completely absent or empty
        if (empty($eventDate) || strtolower($eventDate) === 'unknown' || strtolower($eventDate) === 'null') {
            $missingFields[] = 'event_date';
        }

        if (! empty($missingFields)) {
            return [
                'is_complete' => false,
                'missing_fields' => $missingFields,
                'status' => 'INCOMPLETE_DATA',
                'message' => 'Informasi esensial ('.implode(', ', $missingFields).') tidak ditemukan pada brief kegiatan.',
                'rag_chunks' => [],
                'branding' => $this->vectorService->getActiveBranding(),
            ];
        }

        // Retrieve K=3-5 chunks matching the event details and content topic
        $searchQuery = "{$eventName} ".implode(' ', $parsedEntities['key_highlights'] ?? []).' caption visual slide brand warna';
        $chunks = $this->vectorService->searchChunks($searchQuery, 4);

        return [
            'is_complete' => true,
            'missing_fields' => [],
            'status' => 'VALIDATED',
            'rag_chunks' => $chunks,
            'branding' => $this->vectorService->getActiveBranding(),
        ];
    }
}
