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
        Schema::create('yoga_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique();
            $table->unsignedBigInteger('user_id')->nullable(); // Link to user account
            $table->unsignedBigInteger('yoga_id');
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->date('booking_date');
            $table->time('booking_time');
            $table->string('class_type')->nullable();
            $table->integer('participants')->default(1);
            $table->integer('total_amount');
            $table->string('status')->default('pending'); // pending, confirmed, cancelled, completed
            $table->string('payment_status')->default('pending'); // pending, paid, failed, refunded
            $table->string('payment_method')->nullable(); // cash, transfer, midtrans
            $table->string('payment_token')->nullable();
            $table->json('payment_details')->nullable();
            $table->text('notes')->nullable();
            $table->text('special_requests')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('yoga_id')->references('id_yoga')->on('yogas')->onDelete('cascade');

            // Indexes for better performance
            $table->index('user_id');
            $table->index('yoga_id');
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
        Schema::dropIfExists('yoga_bookings');
    }
};
