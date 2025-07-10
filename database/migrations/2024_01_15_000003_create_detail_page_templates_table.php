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
        Schema::create('detail_page_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // 'spa' or 'yoga'
            $table->text('description')->nullable();
            $table->json('config_data'); // Template configuration
            $table->string('preview_image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['type', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_page_templates');
    }
};
