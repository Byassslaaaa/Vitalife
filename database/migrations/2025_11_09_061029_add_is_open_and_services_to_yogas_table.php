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
        Schema::table('yogas', function (Blueprint $table) {
            $table->boolean('is_open')->default(true)->after('maps');
            $table->json('services')->nullable()->after('is_open');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('yogas', function (Blueprint $table) {
            $table->dropColumn(['is_open', 'services']);
        });
    }
};
