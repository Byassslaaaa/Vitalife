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
            $table->string('order_id')->unique();
            
            // Match the spa_id type with spas.id_spa (unsignedBigInteger)
            $table->unsignedBigInteger('spa_id');
            
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->string('service_type');
            $table->integer('service_price');
            $table->integer('admin_fee');
            $table->integer('total_amount');
            $table->date('booking_date');
            $table->time('booking_time');
            $table->text('service_address');
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'expired'])->default('pending');
            $table->string('midtrans_transaction_id')->nullable();
            $table->timestamps();

            // Add indexes but no foreign key constraint for now
            $table->index(['spa_id', 'booking_date']);
            $table->index('payment_status');
            $table->index('order_id');
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