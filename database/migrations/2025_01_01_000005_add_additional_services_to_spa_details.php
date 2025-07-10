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
        Schema::table('spa_details', function (Blueprint $table) {
            if (!Schema::hasColumn('spa_details', 'additional_services')) {
                $table->json('additional_services')->nullable()->after('facilities');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spa_details', function (Blueprint $table) {
            if (Schema::hasColumn('spa_details', 'additional_services')) {
                $table->dropColumn('additional_services');
            }
        });
    }
};
