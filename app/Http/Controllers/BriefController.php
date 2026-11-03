<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBriefRequest;
use App\Models\Brief;
use App\Services\AiAgentOrchestrator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BriefController extends Controller
{
    /**
     * Display the brief submission form.
     */
    public function create(): View
    {
        return view('requester.create-brief');
    }

    /**
     * Store a new brief and trigger AI Agent Orchestration.
     */
    public function store(StoreBriefRequest $request, AiAgentOrchestrator $orchestrator): RedirectResponse
    {
        $brief = Brief::create([
            'user_id' => Auth::id() ?? 3,
            'event_title' => $request->input('event_title'),
            'raw_brief_text' => $request->input('raw_brief_text'),
            'status' => 'pending',
        ]);

        $result = $orchestrator->process($brief);

        if ($result['status'] === 'incomplete_data') {
            return redirect()->route('requester.history')
                ->with('warning', "Brief ditolak karena data tidak lengkap: {$result['message']}");
        }

        return redirect()->route('requester.history')
            ->with('success', 'Brief berhasil diproses oleh Agen AI! Draf kini diteruskan ke Lead Designer untuk peninjauan (HITL).');
    }

    /**
     * Display history of briefs for monitoring status.
     */
    public function index(): View
    {
        $user = Auth::user();

        $query = Brief::with(['mediaDrafts', 'user'])->latest();

        if ($user && $user->role === 'requester') {
            $query->where('user_id', $user->id);
        }

        $briefs = $query->paginate(10);

        return view('requester.history', compact('briefs'));
    }

    /**
     * Display specific brief details.
     */
    public function show(Brief $brief): View
    {
        $brief->load(['mediaDrafts.reviewer', 'user']);

        return view('requester.show-brief', compact('brief'));
    }
}
