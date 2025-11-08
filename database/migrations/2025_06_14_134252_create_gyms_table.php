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
        Schema::create('gyms', function (Blueprint $table) {
            $table->id('id_gym');
            $table->string('nama');
            $table->text('alamat');
            $table->json('services')->nullable(); // Bisa null agar fleksibel
            $table->json('fasilitas')->nullable(); // Tambahan dari migrasi kedua
            $table->text('description')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('image');
            $table->text('maps')->nullable(); // Changed to text to accommodate iframe embed code
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gyms');
    }
};
