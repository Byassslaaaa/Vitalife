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
        Schema::create('yoga_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('yoga_id');
            $table->string('name');
            $table->text('description');
            $table->integer('price');
            $table->string('duration')->nullable();
            $table->string('category');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('yoga_id')->references('id_yoga')->on('yogas')->onDelete('cascade');

            // Indexes
            $table->index('yoga_id');
            $table->index('category');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('yoga_services');
    }
};
