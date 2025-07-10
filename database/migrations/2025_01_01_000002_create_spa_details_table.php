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
        Schema::create('spa_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('spa_id');
            $table->string('hero_title')->nullable();
            $table->text('hero_subtitle')->nullable();
            $table->longText('description')->nullable();
            $table->longText('about_spa')->nullable();
            $table->json('gallery_images')->nullable();
            $table->json('facilities')->nullable();
            $table->longText('custom_css')->nullable();
            $table->boolean('show_facilities')->default(true);
            $table->boolean('show_opening_hours')->default(true);
            $table->boolean('show_booking_policy')->default(true);
            $table->boolean('show_location_map')->default(true);
            $table->string('booking_policy_title')->nullable();
            $table->string('booking_policy_subtitle')->nullable();
            $table->string('contact_person_name')->nullable();
            $table->string('contact_person_phone')->nullable();
            $table->timestamps();

            $table->foreign('spa_id')->references('id_spa')->on('spas')->onDelete('cascade');
            $table->unique('spa_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spa_details');
    }
};
