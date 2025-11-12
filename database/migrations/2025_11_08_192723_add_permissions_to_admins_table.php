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
        Schema::table('admins', function (Blueprint $table) {
            // Permissions untuk manage fitur
            $table->json('permissions')->nullable()->after('role_level');
            // Format: {"spa": true, "yoga": true, "gym": false, "bookings": true, "vouchers": false, "users": true}

            // Untuk tracking
            $table->text('notes')->nullable()->after('permissions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn(['permissions', 'notes']);
        });
    }
};
