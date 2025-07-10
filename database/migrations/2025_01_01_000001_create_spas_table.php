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
        Schema::create('spas', function (Blueprint $table) {
            $table->id('id_spa');
            $table->string('nama');
            $table->text('alamat');
            $table->string('noHP');
            $table->json('waktuBuka'); // Opening hours for each day
            $table->text('maps')->nullable(); // Google Maps embed URL
            $table->json('services')->nullable(); // Store 3 services with images
            $table->string('image');
            $table->boolean('is_open')->default(true); // Keep as is_open
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spas');
    }
};
