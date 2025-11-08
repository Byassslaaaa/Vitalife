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
            // Add service-related fields to match spa_bookings structure
            $table->unsignedBigInteger('service_id')->nullable()->after('yoga_id');
            $table->string('service_name')->nullable()->after('service_id');
            $table->integer('service_price')->nullable()->after('service_name');

            // Add foreign key constraint
            $table->foreign('service_id')->references('id')->on('yoga_services')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('yoga_bookings', function (Blueprint $table) {
            // Drop foreign key first
            $table->dropForeign(['service_id']);

            // Drop columns
            $table->dropColumn(['service_id', 'service_name', 'service_price']);
        });
    }
};
