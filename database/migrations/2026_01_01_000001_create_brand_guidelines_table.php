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
        Schema::create('brand_guidelines', function (Blueprint $table) {
            $table->id();
            $table->string('organization_name');
            $table->string('primary_color_hex')->default('#003366');
            $table->string('secondary_color_hex')->default('#FFCC00');
            $table->string('document_path');
            $table->json('vector_metadata')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brand_guidelines');
    }
};
