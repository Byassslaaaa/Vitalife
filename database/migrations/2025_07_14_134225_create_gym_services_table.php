<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gym_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gym_id');
            $table->string('name');
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->string('duration')->nullable();
            $table->string('category');
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('gym_id')->references('id_gym')->on('gyms')->onDelete('cascade');
            $table->index('gym_id');
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gym_services');
    }
};
