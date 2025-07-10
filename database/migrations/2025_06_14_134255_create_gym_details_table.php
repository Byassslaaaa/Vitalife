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
        Schema::create('gym_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gym_id');
            $table->json('gallery_images')->nullable(); // 5 photos
            $table->string('contact_person_name')->nullable();
            $table->string('contact_person_phone')->nullable();
            $table->text('location_maps')->nullable(); // Google Maps embed URL
            $table->json('additional_services')->nullable(); // Additional services beyond the original 3
            $table->text('about_gym')->nullable();
            $table->json('opening_hours')->nullable(); // Monday to Sunday schedule
            $table->json('facilities')->nullable();
            $table->timestamps();
            $table->foreign('gym_id')->references('id_gym')->on('gyms')->onDelete('cascade');
            $table->index('gym_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gym_details');
    }
};
