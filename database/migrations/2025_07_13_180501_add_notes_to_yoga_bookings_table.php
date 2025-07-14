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
        Schema::table('yoga_bookings', function (Blueprint $table) {
            // Check if column doesn't exist before adding
            if (!Schema::hasColumn('yoga_bookings', 'notes')) {
                $table->text('notes')->nullable()->after('payment_details');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('yoga_bookings', function (Blueprint $table) {
            if (Schema::hasColumn('yoga_bookings', 'notes')) {
                $table->dropColumn('notes');
            }
        });
    }
};
