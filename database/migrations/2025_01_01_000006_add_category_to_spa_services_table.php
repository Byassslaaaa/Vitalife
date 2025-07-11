<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('spa_services', function (Blueprint $table) {
            $table->string('category')->nullable()->after('duration');
        });
    }

    public function down(): void
    {
        Schema::table('spa_services', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
};