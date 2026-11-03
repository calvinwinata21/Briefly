<?php

namespace App\Http\Controllers;

use App\Models\MediaDraft;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DesignerReviewController extends Controller
{
    /**
     * Display designer review dashboard with pending and reviewed drafts.
     */
    public function index(Request $request): View
    {
        $statusFilter = $request->query('status', 'all');

        $query = MediaDraft::with(['brief.user', 'reviewer'])->latest();

        if ($statusFilter !== 'all') {
            $query->where('approval_status', $statusFilter);
        }

        $drafts = $query->paginate(12);

        $stats = [
            'total' => MediaDraft::count(),
            'pending' => MediaDraft::where('approval_status', 'PENDING_REVIEW')->count(),
            'approved' => MediaDraft::where('approval_status', 'APPROVED')->count(),
            'rejected' => MediaDraft::where('approval_status', 'REJECTED')->count(),
        ];

        return view('designer.review-dashboard', compact('drafts', 'statusFilter', 'stats'));
    }

    /**
     * Display comprehensive review interface for a single media draft.
     */
    public function show(MediaDraft $draft): View
    {
        $draft->load(['brief.user', 'reviewer']);

        return view('designer.detail', compact('draft'));
    }

    /**
     * Approve draft (Human-in-the-Loop).
     */
    public function approve(Request $request, MediaDraft $draft): RedirectResponse
    {
        $feedback = $request->input('designer_feedback', 'Disetujui oleh Lead Designer sesuai Brand Guideline.');

        $payload = $draft->ai_output_json;
        $payload['workflow_status'] = 'APPROVED';

        $draft->update([
            'approval_status' => 'APPROVED',
            'reviewed_by' => Auth::id() ?? 2,
            'designer_feedback' => $feedback,
            'ai_output_json' => $payload,
        ]);

        return redirect()->route('designer.detail', $draft)
            ->with('success', 'Draf berhasil disetujui (Approved) dan siap untuk diekspor!');
    }

    /**
     * Reject draft with feedback note.
     */
    public function reject(Request $request, MediaDraft $draft): RedirectResponse
    {
        $request->validate([
            'designer_feedback' => ['required', 'string', 'min:5'],
        ]);

        $payload = $draft->ai_output_json;
        $payload['workflow_status'] = 'REJECTED';

        $draft->update([
            'approval_status' => 'REJECTED',
            'reviewed_by' => Auth::id() ?? 2,
            'designer_feedback' => $request->input('designer_feedback'),
            'ai_output_json' => $payload,
        ]);

        return redirect()->route('designer.detail', $draft)
            ->with('warning', 'Draf telah ditandai ditolak (Rejected) dengan catatan revisi.');
    }

    /**
     * Update draft content during HITL review.
     */
    public function update(Request $request, MediaDraft $draft): RedirectResponse
    {
        $request->validate([
            'hook_sentence' => ['required', 'string'],
            'body_paragraph' => ['required', 'string'],
            'call_to_action' => ['required', 'string'],
            'hashtags' => ['nullable', 'string'],
        ]);

        $payload = $draft->ai_output_json;

        // Update social caption
        $hashtags = array_values(array_filter(array_map('trim', explode(' ', str_replace(',', ' ', $request->input('hashtags', ''))))));
        $payload['social_media_caption']['hook_sentence'] = $request->input('hook_sentence');
        $payload['social_media_caption']['body_paragraph'] = $request->input('body_paragraph');
        $payload['social_media_caption']['call_to_action'] = $request->input('call_to_action');
        $payload['social_media_caption']['hashtags'] = $hashtags;

        // Update slide headlines if provided
        if ($request->has('slides') && is_array($request->input('slides'))) {
            foreach ($request->input('slides') as $index => $slideData) {
                if (isset($payload['visual_slides_layout'][$index])) {
                    $payload['visual_slides_layout'][$index]['headline'] = substr(trim($slideData['headline'] ?? ''), 0, 50);
                    if (! empty($slideData['body_text'])) {
                        $payload['visual_slides_layout'][$index]['body_text'] = array_values(array_filter(explode("\n", str_replace("\r", '', $slideData['body_text']))));
                    }
                }
            }
        }

        $draft->update([
            'ai_output_json' => $payload,
            'reviewed_by' => Auth::id() ?? 2,
        ]);

        return redirect()->route('designer.detail', $draft)
            ->with('success', 'Perubahan draf berhasil disimpan oleh Desainer!');
    }

    /**
     * Export approved draft to JSON or Markdown.
     */
    public function export(MediaDraft $draft, string $format): StreamedResponse|Response
    {
        $payload = $draft->ai_output_json;
        $jobId = $payload['job_id'] ?? 'DRAFT-'.$draft->id;

        if ($format === 'markdown') {
            $content = "# Media Production Packet — {$jobId}\n\n";
            $content .= '**Event Name:** '.($payload['event_metadata']['event_name'] ?? 'N/A')."\n";
            $content .= '**Event Date:** '.($payload['event_metadata']['event_date'] ?? 'N/A')."\n";
            $content .= '**Contact Person:** '.($payload['event_metadata']['contact_person'] ?? 'N/A')."\n";
            $content .= '**Status:** '.$draft->approval_status."\n\n";

            $content .= "## 🎨 Branding Applied\n";
            $content .= "- Primary Color: `{$payload['branding_rules_applied']['primary_color_hex']}`\n";
            $content .= "- Secondary Color: `{$payload['branding_rules_applied']['secondary_color_hex']}`\n";
            $content .= "- Font Family: `{$payload['branding_rules_applied']['font_family_recommendation']}`\n\n";

            $content .= "## 📱 Social Media Caption\n";
            $content .= "**Hook:**\n".($payload['social_media_caption']['hook_sentence'] ?? '')."\n\n";
            $content .= "**Body:**\n".($payload['social_media_caption']['body_paragraph'] ?? '')."\n\n";
            $content .= "**Call To Action:**\n".($payload['social_media_caption']['call_to_action'] ?? '')."\n\n";
            $content .= '**Hashtags:** '.implode(' ', $payload['social_media_caption']['hashtags'] ?? [])."\n\n";

            $content .= "## 🖼 Visual Slides Layout\n";
            foreach ($payload['visual_slides_layout'] ?? [] as $slide) {
                $content .= "### Slide {$slide['slide_number']} [Template: {$slide['layout_template']}]\n";
                $content .= "**Headline:** {$slide['headline']}\n";
                if (! empty($slide['body_text'])) {
                    foreach ($slide['body_text'] as $b) {
                        $content .= "- {$b}\n";
                    }
                }
                $content .= "\n";
            }

            return response($content, 200, [
                'Content-Type' => 'text/markdown; charset=UTF-8',
                'Content-Disposition' => "attachment; filename=\"briefly-{$jobId}.md\"",
            ]);
        }

        // Default JSON
        return response()->streamDownload(function () use ($payload) {
            echo json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }, "briefly-{$jobId}.json", [
            'Content-Type' => 'application/json',
        ]);
    }
}
