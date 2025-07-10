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
        Schema::create('gym_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique();
            $table->unsignedBigInteger('gym_id');
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->unsignedBigInteger('service_id');
            $table->string('service_name');
            $table->decimal('service_price', 10, 2);
            $table->string('status');
            $table->string('payment_status');
            $table->string('payment_token')->nullable();
            $table->datetime('booking_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('gym_id')->references('id_gym')->on('gyms')->onDelete('cascade');
            $table->foreign('service_id')->references('id')->on('gym_services')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gym_bookings');
    }
};
