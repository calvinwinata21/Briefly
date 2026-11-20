<?php

/**
 * PRAKTIK IMPLEMENTASI — MINGGU 10 & 11: Agentic Orchestration & Siklus ReAct
 *
 * Eksperimen:
 * 1. Siklus ReAct (Reason -> Act -> Observe -> Evaluate).
 * 2. Penyimpanan State Tugas di Luar Percakapan (External Structured State).
 * 3. Batas Jumlah Langkah (Max Step = 5) dan Pencegahan Infinite Loop.
 * 4. Perekaman Jejak Eksekusi (Execution Trace) yang Dapat Diaudit.
 *
 * Cara Menjalankan:
 *   php docs/praktik-implementasi/08_agent_orchestrator_simulation.php
 */
echo "======================================================================\n";
echo "  PRAKTIK MINGGU 10 & 11: AGENTIC REACT CYCLE & ORCHESTRATION\n";
echo "======================================================================\n\n";

class AgentTaskState
{
    public string $jobId;

    public string $status = 'INITIALIZED';

    public int $currentStep = 0;

    public int $maxSteps = 5;

    public array $extractedEntities = [];

    public array $retrievedChunks = [];

    public ?array $generatedDraft = null;

    public array $traceLog = [];

    public bool $isComplete = false;

    public function __construct(string $jobId)
    {
        $this->jobId = $jobId;
    }

    public function recordTrace(string $phase, string $thought, string $action, $observation): void
    {
        $this->traceLog[] = [
            'step' => $this->currentStep,
            'timestamp' => microtime(true),
            'phase' => $phase,
            'thought' => $thought,
            'action' => $action,
            'observation' => $observation,
        ];
    }
}

class BrieflyAgentRunner
{
    public static function run(string $briefInput, string $selectedTone): AgentTaskState
    {
        $state = new AgentTaskState('job-'.bin2hex(random_bytes(4)));
        echo "Memulai Eksekusi Agent untuk Job ID: {$state->jobId}\n";
        echo "Selected Tone: {$selectedTone}\n";
        echo "Batas Langkah Maksimum: {$state->maxSteps}\n\n";

        while (! $state->isComplete && $state->currentStep < $state->maxSteps) {
            $state->currentStep++;
            echo "--- [LANGKAH KE-{$state->currentStep}] ---\n";

            switch ($state->currentStep) {
                case 1:
                    // REASON 1
                    $thought = 'Menerima input teks. Langkah pertama adalah mengekstraksi entitas kunci dan memvalidasi kelengkapan nama dan tanggal acara.';
                    $action = 'Call Action: ParseBriefAction';
                    // ACT 1
                    $entities = [
                        'name' => 'Workshop UI/UX Design System 2026',
                        'date' => '12 Desember 2026',
                        'target' => 'Mahasiswa SI',
                        'is_valid' => true,
                    ];
                    // OBSERVE 1
                    $state->extractedEntities = $entities;
                    $state->recordTrace('ENTITY_PARSING', $thought, $action, $entities);
                    echo "Thought : $thought\nAction  : $action\nObserve : Entitas lengkap terdeteksi ({$entities['name']}).\n\n";
                    break;

                case 2:
                    // REASON 2
                    $thought = "Entitas valid. Langkah kedua mengambil aturan brand visual dari vector database sesuai topik dan tone '{$selectedTone}'.";
                    $action = 'Call Action: ExecuteRagSearchAction';
                    // ACT 2
                    $chunks = [
                        ['chunk_id' => 1, 'code' => 'CHK-01', 'title' => 'Palet Warna Primer'],
                        ['chunk_id' => 5, 'code' => 'CHK-05', 'title' => 'Arsitektur 4 Slide Carousel'],
                        ['chunk_id' => 6, 'code' => 'CHK-06', 'title' => 'Tone Kasual Energik'],
                    ];
                    // OBSERVE 2
                    $state->retrievedChunks = $chunks;
                    $state->recordTrace('RAG_RETRIEVAL', $thought, $action, $chunks);
                    echo "Thought : $thought\nAction  : $action\nObserve : Berhasil mengambil 3 chunk brand HMSI.\n\n";
                    break;

                case 3:
                    // REASON 3
                    $thought = 'Data entitas dan brand terkumpul. Langkah ketiga menghasilkan draf konten 4 slide dan salinan multi-channel berlandaskan chunk.';
                    $action = 'Call Model Gateway: gemini-2.5-flash';
                    // ACT 3
                    $draft = [
                        'slides_count' => 4,
                        'primary_color' => '#0F172A',
                        'captions' => ['instagram', 'whatsapp', 'linkedin'],
                        'status' => 'PENDING_REVIEW',
                    ];
                    // OBSERVE 3
                    $state->generatedDraft = $draft;
                    $state->recordTrace('CONTENT_SYNTHESIS', $thought, $action, $draft);
                    echo "Thought : $thought\nAction  : $action\nObserve : Payload 4 slide berhasil disintesis.\n\n";
                    break;

                case 4:
                    // REASON 4
                    $thought = 'Draf selesai disintesis. Langkah keempat memvalidasi kontrak skema JSON dan mengunci status ke PENDING_REVIEW.';
                    $action = 'Call Action: ValidateAiOutputAction';
                    // ACT 4 & EVALUATE
                    $state->status = 'READY_FOR_HITL';
                    $state->isComplete = true; // Selesai
                    $state->recordTrace('VALIDATION_AND_STAGING', $thought, $action, ['status' => 'PENDING_REVIEW']);
                    echo "Thought : $thought\nAction  : $action\nObserve : Kontrak schema valid. Draf disimpan dengan status PENDING_REVIEW.\n\n";
                    break;

                default:
                    $state->isComplete = true;
                    break;
            }
        }

        if (! $state->isComplete && $state->currentStep >= $state->maxSteps) {
            echo "[PERINGATAN]: Batas langkah {$state->maxSteps} tercapai! Agent dihentikan paksa untuk mencegah infinite loop.\n";
            $state->status = 'STEP_LIMIT_EXHAUSTED';
        }

        return $state;
    }
}

// Jalankan Simulasi
$hasilAgent = BrieflyAgentRunner::run('Brief Workshop UI/UX', 'casual_energetic');

echo "======================================================================\n";
echo "  RINGKASAN STATUS EKSEKUSI AGENT\n";
echo "======================================================================\n";
echo "Job ID        : {$hasilAgent->jobId}\n";
echo "Total Langkah : {$hasilAgent->currentStep} dari {$hasilAgent->maxSteps}\n";
echo "Status Akhir  : {$hasilAgent->status}\n";
echo 'Audit Trace   : '.count($hasilAgent->traceLog)." langkah tersimpan dalam format terstruktur.\n";
echo "Kunci HITL    : Draf dialirkan ke Lead Designer (tidak langsung publish).\n";
echo "======================================================================\n";
