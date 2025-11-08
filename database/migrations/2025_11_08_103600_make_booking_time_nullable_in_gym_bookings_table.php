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
        Schema::table('gym_bookings', function (Blueprint $table) {
            // Make booking_time nullable since gym bookings may not require specific appointment times
            $table->time('booking_time')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gym_bookings', function (Blueprint $table) {
            // Revert booking_time to NOT NULL
            $table->time('booking_time')->nullable(false)->change();
        });
    }
};
