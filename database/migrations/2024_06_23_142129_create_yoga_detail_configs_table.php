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
        Schema::create('yoga_detail_configs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('yoga_id');
            $table->string('hero_title')->nullable();
            $table->string('hero_subtitle', 500)->nullable();
            $table->json('gallery_images')->nullable();
            $table->json('facilities')->nullable();
            $table->string('booking_policy_title')->nullable()->default('BOOKING POLICY');
            $table->string('booking_policy_subtitle')->nullable()->default('YOUR WELLNESS PLANS');
            $table->string('contact_person_name')->nullable();
            $table->string('contact_person_phone')->nullable();
            $table->boolean('show_opening_hours')->default(true);
            $table->boolean('show_location_map')->default(true);
            $table->boolean('show_facilities')->default(true);
            $table->boolean('show_booking_policy')->default(true);
            $table->boolean('show_class_types')->default(true);
            $table->text('custom_css')->nullable();
            $table->string('theme_color', 7)->default('#10B981'); // Default green color
            $table->enum('layout_style', ['default', 'modern', 'minimal'])->default('default');
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('yoga_id')->references('id_yoga')->on('yogas')->onDelete('cascade');

            // Unique constraint to ensure one config per yoga
            $table->unique('yoga_id');

            // Index for better performance
            $table->index('yoga_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('yoga_detail_configs');
    }
};
