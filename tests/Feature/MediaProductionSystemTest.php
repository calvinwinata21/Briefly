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

    public function test_admin_can_access_guidelines_and_settings(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->get(route('admin.guidelines'));
        $response->assertStatus(200);

        $responseSettings = $this->actingAs($admin)->get(route('admin.settings'));
        $responseSettings->assertStatus(200);
    }
}
