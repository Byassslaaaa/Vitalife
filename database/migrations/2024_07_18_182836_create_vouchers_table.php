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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('image');
            $table->text('description');
            $table->integer('discount_percentage')->default(0);
            $table->integer('usage_count')->default(0);
            $table->integer('usage_limit')->nullable();
            $table->boolean('is_used')->default(false);
            $table->timestamp('expired_at')->nullable();
            $table->string('code')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropColumn(['code', 'discount_percentage', 'usage_count', 'usage_limit', 'is_used', 'expired_at']);
        });
    }
};
