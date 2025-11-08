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
        Schema::table('spa_bookings', function (Blueprint $table) {
            // Change booking_time from time to dateTime to store full datetime
            $table->dateTime('booking_time')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spa_bookings', function (Blueprint $table) {
            // Revert back to time type
            $table->time('booking_time')->change();
        });
    }
};
