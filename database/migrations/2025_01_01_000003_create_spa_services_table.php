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
        Schema::create('spa_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('spa_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('duration')->nullable();
            $table->integer('price')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('spa_id')->references('id_spa')->on('spas')->onDelete('cascade');
            $table->index('spa_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spa_services');
    }
};
