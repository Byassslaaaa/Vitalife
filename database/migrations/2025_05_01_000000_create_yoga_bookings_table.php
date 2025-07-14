<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateYogaBookingsTable extends Migration
{
    public function up()
    {
        Schema::create('yoga_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique();
            $table->unsignedBigInteger('yoga_id');
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->date('booking_date');
            $table->time('booking_time');
            $table->string('class_type');
            $table->integer('total_amount');
            $table->string('status')->default('pending');
            $table->string('payment_status')->default('pending');
            $table->string('payment_token')->nullable();
            $table->text('payment_details')->nullable();
            $table->text('notes')->nullable(); // Added for consistency
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('yoga_id')->references('id_yoga')->on('yogas')->onDelete('cascade');

            // Indexes for better performance
            $table->index('yoga_id');
            $table->index('booking_code');
            $table->index('status');
            $table->index('payment_status');
            $table->index('booking_date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('yoga_bookings');
    }
}
