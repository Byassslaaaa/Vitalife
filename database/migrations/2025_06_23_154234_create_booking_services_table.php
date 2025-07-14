<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id');
            $table->unsignedBigInteger('service_id');
            $table->string('service_name');
            $table->string('service_type'); // 'spa', 'yoga', 'gym'
            $table->integer('price'); // Changed to integer for consistency
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');

            // Indexes
            $table->index('booking_id');
            $table->index('service_id');
            $table->index('service_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_services');
    }
};
