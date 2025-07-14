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
        Schema::table('yoga_services', function (Blueprint $table) {
            $table->string('difficulty_level')->nullable()->after('category');
            $table->integer('max_participants')->nullable()->after('difficulty_level');
            $table->text('benefits')->nullable()->after('max_participants');
            $table->text('requirements')->nullable()->after('benefits');
            $table->string('image')->nullable()->after('requirements');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('yoga_services', function (Blueprint $table) {
            $table->dropColumn(['difficulty_level', 'max_participants', 'benefits', 'requirements', 'image']);
        });
    }
};
