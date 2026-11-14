<?php

namespace Tests\Feature;

use App\Models\Brief;
use App\Models\MediaDraft;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MediaProductionSystemTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        $this->seed();
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/');
        $response->assertRedirect(route('login'));
    }

    public function test_user_can_login_with_seeded_credentials(): void
    {
        $response = $this->post(route('login.post'), [
            'email' => 'designer@himpunan.or.id',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('designer.dashboard'));
        $this->assertAuthenticated();
    }

    public function test_requester_can_submit_brief_and_generate_draft(): void
    {
        $panitia = User::where('role', 'requester')->first();

        $response = $this->actingAs($panitia)->post(route('requester.store'), [
            'event_title' => 'Workshop UI/UX Design System 2026',
            'raw_brief_text' => "Acara: Workshop UI/UX Design System\nTanggal: 12 Desember 2026\nPembicara: Senior Product Designer\nNarahubung: WhatsApp 0812-3344-5566",
        ]);

        $response->assertRedirect(route('requester.history'));
        $this->assertDatabaseHas('briefs', [
            'event_title' => 'Workshop UI/UX Design System 2026',
            'status' => 'completed',
        ]);

        $brief = Brief::where('event_title', 'Workshop UI/UX Design System 2026')->first();
        $this->assertNotNull($brief->latestDraft);
        $this->assertEquals('PENDING_REVIEW', $brief->latestDraft->approval_status);

        // Verify JSON Output Contract Schema
        $json = $brief->latestDraft->ai_output_json;
        $this->assertArrayHasKey('job_id', $json);
        $this->assertArrayHasKey('event_metadata', $json);
        $this->assertArrayHasKey('branding_rules_applied', $json);
        $this->assertArrayHasKey('visual_slides_layout', $json);
        $this->assertArrayHasKey('social_media_caption', $json);
        $this->assertEquals('PENDING_REVIEW', $json['workflow_status']);
    }

    public function test_incomplete_brief_triggers_fallback_guardrail(): void
    {
        $panitia = User::where('role', 'requester')->first();

        // Brief missing date
        $response = $this->actingAs($panitia)->post(route('requester.store'), [
            'event_title' => 'Acara Tanpa Tanggal Yang Jelas',
            'raw_brief_text' => 'Deskripsi kegiatan kumpul santai bersama anggota.',
        ]);

        $this->assertDatabaseHas('briefs', [
            'event_title' => 'Acara Tanpa Tanggal Yang Jelas',
            'status' => 'incomplete_data',
        ]);
    }

    public function test_designer_can_approve_draft_hitl(): void
    {
        $designer = User::where('role', 'designer')->first();
        $draft = MediaDraft::where('approval_status', 'PENDING_REVIEW')->first();

        $response = $this->actingAs($designer)->post(route('designer.approve', $draft), [
            'designer_feedback' => 'Approved by Lead Designer.',
        ]);

        $response->assertRedirect(route('designer.detail', $draft));
        $draft->refresh();
        $this->assertEquals('APPROVED', $draft->approval_status);
        $this->assertEquals($designer->id, $draft->reviewed_by);
    }

    public function test_designer_can_export_approved_draft(): void
    {
        $designer = User::where('role', 'designer')->first();
        $draft = MediaDraft::where('approval_status', 'APPROVED')->first();

        $responseJson = $this->actingAs($designer)->get(route('designer.export', [$draft, 'json']));
        $responseJson->assertStatus(200);

        $responseMd = $this->actingAs($designer)->get(route('designer.export', [$draft, 'markdown']));
        $responseMd->assertStatus(200);
    }

    public function test_requester_can_submit_brief_with_tone_and_generate_multichannel_copy(): void
    {
        $panitia = User::where('role', 'requester')->first();

        $response = $this->actingAs($panitia)->post(route('requester.store'), [
            'event_title' => 'Webinar AI for Students 2026',
            'raw_brief_text' => "Acara: Webinar AI for Students\nTanggal: 20 November 2026\nNarahubung: 0812-9988-7766\nPembicara: AI Researcher",
            'tone' => 'formal_academic',
        ]);

        $response->assertRedirect(route('requester.history'));
        $brief = Brief::where('event_title', 'Webinar AI for Students 2026')->first();
        $this->assertEquals('formal_academic', $brief->tone);

        $caption = $brief->latestDraft->ai_output_json['social_media_caption'];
        $this->assertArrayHasKey('whatsapp_broadcast', $caption);
        $this->assertArrayHasKey('linkedin_post', $caption);
        $this->assertNotEmpty($caption['whatsapp_broadcast']);
        $this->assertNotEmpty($caption['linkedin_post']);
    }

    public function test_requester_can_revise_and_resubmit_rejected_brief(): void
    {
        $panitia = User::where('role', 'requester')->first();
        $designer = User::where('role', 'designer')->first();

        // 1. Submit initial brief
        $this->actingAs($panitia)->post(route('requester.store'), [
            'event_title' => 'Initial Hackathon 2026',
            'raw_brief_text' => "Acara: Initial Hackathon\nTanggal: 15 Desember 2026\nNarahubung: 0812-1111-2222",
        ]);

        $brief = Brief::where('event_title', 'Initial Hackathon 2026')->first();
        $initialDraft = $brief->latestDraft;

        // 2. Designer rejects draft
        $this->actingAs($designer)->post(route('designer.reject', $initialDraft), [
            'designer_feedback' => 'Tolong tambahkan target peserta spesifik.',
        ]);

        $initialDraft->refresh();
        $this->assertEquals('REJECTED', $initialDraft->approval_status);

        // 3. Requester visits revision page
        $revisionPage = $this->actingAs($panitia)->get(route('requester.revise', $brief));
        $revisionPage->assertStatus(200);

        // 4. Requester submits revision
        $resubmitResponse = $this->actingAs($panitia)->post(route('requester.revise.post', $brief), [
            'event_title' => 'Initial Hackathon 2026 (Revised)',
            'raw_brief_text' => "Acara: Initial Hackathon 2026 (Revised)\nTanggal: 15 Desember 2026\nTarget: Mahasiswa Semester 3 ke atas\nNarahubung: 0812-1111-2222",
            'tone' => 'persuasive_urgent',
        ]);

        $resubmitResponse->assertRedirect(route('requester.show', $brief));

        $brief->refresh();
        $this->assertEquals(2, $brief->mediaDrafts()->count());
        $this->assertEquals('PENDING_REVIEW', $brief->latestDraft->approval_status);
        $this->assertEquals('persuasive_urgent', $brief->tone);
    }

    public function test_admin_can_access_guidelines_and_settings(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->get(route('admin.guidelines'));
        $response->assertStatus(200);

        $responseSettings = $this->actingAs($admin)->get(route('admin.settings'));
        $responseSettings->assertStatus(200);
    }
}
