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
        Schema::table('chat_conversations', function (Blueprint $table) {
            $table->string('session_id')->nullable()->after('user_id')->index();
            $table->boolean('is_guest')->default(false)->after('session_id');
            $table->string('guest_name')->nullable()->after('is_guest');
            $table->string('guest_email')->nullable()->after('guest_name');

            // Make user_id nullable to support guest conversations
            $table->unsignedBigInteger('user_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chat_conversations', function (Blueprint $table) {
            $table->dropColumn(['session_id', 'is_guest', 'guest_name', 'guest_email']);

            // Revert user_id to not nullable
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
        });
    }
};
