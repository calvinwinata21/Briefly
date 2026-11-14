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
        Schema::table('briefs', function (Blueprint $table) {
            $table->string('tone')->nullable()->default('casual_energetic')->after('raw_brief_text');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('briefs', function (Blueprint $table) {
            $table->dropColumn('tone');
        });
    }
};
