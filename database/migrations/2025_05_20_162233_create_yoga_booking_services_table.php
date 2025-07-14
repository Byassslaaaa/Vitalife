<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateYogaBookingServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('yoga_booking_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id');
            $table->unsignedBigInteger('service_id');
            $table->string('service_name');
            $table->integer('price'); // Changed to integer for consistency
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('booking_id')->references('id')->on('yoga_bookings')->onDelete('cascade');
            $table->foreign('service_id')->references('id')->on('yoga_services')->onDelete('cascade');

            // Indexes
            $table->index('booking_id');
            $table->index('service_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('yoga_booking_services');
    }
}
