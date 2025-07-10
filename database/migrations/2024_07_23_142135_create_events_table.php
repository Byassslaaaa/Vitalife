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
        Schema::create('events', function (Blueprint $table) {
            $table->id('id_event');
            $table->string('nama');
            $table->text('deskripsi');
            $table->date('tanggal');
            $table->integer('harga');
            $table->text('alamat');
            $table->string('noHP');
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
        Schema::table('event', function (Blueprint $table) {
            $table->dropColumn('gambar');
        });
    }
};
