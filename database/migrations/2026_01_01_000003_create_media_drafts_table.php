<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('media_drafts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brief_id')->constrained('briefs')->onDelete('cascade');
            $table->foreignId('reviewed_by')->nullable()->constrained('users');
            $table->json('ai_output_json');
            $table->enum('approval_status', ['PENDING_REVIEW', 'APPROVED', 'REJECTED'])->default('PENDING_REVIEW');
            $table->text('designer_feedback')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_drafts');
    }
};
