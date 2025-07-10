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
        Schema::create('spa_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique();
            $table->unsignedBigInteger('spa_id');
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->unsignedBigInteger('service_id');
            $table->string('service_name');
            $table->decimal('service_price', 10, 2);
            $table->string('status'); // pending, confirmed, completed, cancelled
            $table->string('payment_status'); // pending, paid, failed
            $table->string('payment_token')->nullable();
            $table->datetime('booking_date')->nullable();
            $table->time('booking_time')->nullable(); // Spa-specific: appointment time
            $table->string('therapist_preference')->nullable(); // Spa-specific: preferred therapist
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('spa_id')->references('id_spa')->on('spas')->onDelete('cascade');
            $table->foreign('service_id')->references('id')->on('spa_services')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spa_bookings');
    }
};
