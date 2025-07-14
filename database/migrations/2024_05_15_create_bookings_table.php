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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique(); // Changed from order_id to booking_code for consistency

            // Service IDs - nullable because booking can be for spa, yoga, or gym
            $table->unsignedBigInteger('spa_id')->nullable();
            $table->unsignedBigInteger('yoga_id')->nullable();
            $table->unsignedBigInteger('gym_id')->nullable();

            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->string('customer_address')->nullable(); // For terapis bookings
            $table->string('service_type')->nullable();
            $table->integer('service_price')->nullable();
            $table->integer('admin_fee')->default(0);
            $table->integer('total_amount');
            $table->date('booking_date');
            $table->time('booking_time');
            $table->text('service_address')->nullable(); // Spa/Yoga/Gym address
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'expired'])->default('pending');
            $table->string('payment_token')->nullable(); // Midtrans snap token
            $table->string('midtrans_transaction_id')->nullable();
            $table->timestamps();

            // Add indexes for better performance
            $table->index(['spa_id', 'booking_date']);
            $table->index(['yoga_id', 'booking_date']);
            $table->index(['gym_id', 'booking_date']);
            $table->index('payment_status');
            $table->index('booking_code');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
