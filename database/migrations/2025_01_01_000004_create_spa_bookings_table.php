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
            $table->unsignedBigInteger('user_id')->nullable(); // Link to user account
            $table->unsignedBigInteger('spa_id');
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->unsignedBigInteger('service_id');
            $table->string('service_name');
            $table->integer('service_price'); // Changed to integer for consistency
            $table->date('booking_date');
            $table->time('booking_time');
            $table->integer('duration')->default(60); // Duration in minutes
            $table->integer('total_amount');
            $table->string('status')->default('pending'); // pending, confirmed, cancelled, completed
            $table->string('payment_status')->default('pending'); // pending, paid, failed, refunded
            $table->string('payment_method')->nullable(); // cash, transfer, midtrans
            $table->string('payment_token')->nullable();
            $table->json('payment_details')->nullable();
            $table->string('therapist_preference')->nullable(); // Spa-specific: preferred therapist
            $table->text('notes')->nullable();
            $table->text('special_requests')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('spa_id')->references('id_spa')->on('spas')->onDelete('cascade');
            $table->foreign('service_id')->references('id')->on('spa_services')->onDelete('cascade');

            // Indexes for better performance
            $table->index('user_id');
            $table->index('spa_id');
            $table->index('service_id');
            $table->index('booking_code');
            $table->index('status');
            $table->index('payment_status');
            $table->index('booking_date');
            $table->index(['booking_date', 'booking_time']);
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
