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
        // Enhance chat_conversations table
        Schema::table('chat_conversations', function (Blueprint $table) {
            // Admin assignment tracking
            $table->unsignedBigInteger('admin_id')->nullable()->after('user_id');
            $table->timestamp('assigned_at')->nullable()->after('admin_id');
            $table->timestamp('waiting_since')->nullable()->after('assigned_at');

            // Transfer tracking
            $table->unsignedBigInteger('transferred_from')->nullable()->after('admin_id');
            $table->timestamp('transferred_at')->nullable()->after('transferred_from');

            // Resolution tracking
            $table->timestamp('resolved_at')->nullable()->after('status');
            $table->unsignedBigInteger('resolved_by')->nullable()->after('resolved_at');

            // Rating system
            $table->tinyInteger('rating')->nullable()->after('resolved_by')->comment('1-5 stars');
            $table->text('rating_feedback')->nullable()->after('rating');
            $table->timestamp('rated_at')->nullable()->after('rating_feedback');

            // Unread message counters
            $table->integer('unread_by_admin')->default(0)->after('last_message_at');
            $table->integer('unread_by_user')->default(0)->after('unread_by_admin');

            // Add indexes for performance
            $table->index('admin_id', 'idx_chat_admin');
            $table->index('status', 'idx_chat_status');
            $table->index(['admin_id', 'status'], 'idx_chat_admin_status');
            $table->index('rating', 'idx_chat_rating');
            $table->index('created_at', 'idx_chat_created');
        });

        // Enhance chat_messages table
        Schema::table('chat_messages', function (Blueprint $table) {
            // Message metadata
            $table->boolean('is_read')->default(false)->after('message');
            $table->timestamp('read_at')->nullable()->after('is_read');

            // Add indexes
            $table->index('chat_conversation_id', 'idx_msg_conversation');
            $table->index('sender_type', 'idx_msg_sender_type');
            $table->index(['chat_conversation_id', 'created_at'], 'idx_msg_conv_created');
            $table->index('is_read', 'idx_msg_read');
        });

        // Create canned_responses table for admin quick replies
        Schema::create('canned_responses', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100);
            $table->text('message');
            $table->string('category', 50)->nullable();
            $table->string('shortcut', 20)->nullable()->unique();
            $table->boolean('is_active')->default(true);
            $table->integer('usage_count')->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->index('category', 'idx_canned_category');
            $table->index('is_active', 'idx_canned_active');
            $table->index('shortcut', 'idx_canned_shortcut');
        });

        // Create quick_replies table for bot suggestions
        Schema::create('quick_replies', function (Blueprint $table) {
            $table->id();
            $table->string('text', 100);
            $table->string('category', 50);
            $table->string('action', 50)->nullable();
            $table->json('payload')->nullable();
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('category', 'idx_quick_category');
            $table->index('is_active', 'idx_quick_active');
        });

        // Create chat_activity_logs for admin tracking
        Schema::create('chat_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id');
            $table->unsignedBigInteger('conversation_id');
            $table->string('action', 50); // 'assigned', 'transferred', 'resolved', 'message_sent'
            $table->text('details')->nullable();
            $table->timestamp('created_at');

            $table->index('admin_id', 'idx_activity_admin');
            $table->index('conversation_id', 'idx_activity_conversation');
            $table->index(['admin_id', 'created_at'], 'idx_activity_admin_created');
        });

        // Create admin_chat_stats for performance tracking
        Schema::create('admin_chat_stats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id')->unique();
            $table->date('stat_date');
            $table->integer('total_chats')->default(0);
            $table->integer('resolved_chats')->default(0);
            $table->decimal('average_rating', 3, 2)->nullable();
            $table->integer('average_response_time')->nullable()->comment('in seconds');
            $table->integer('transferred_chats')->default(0);
            $table->timestamps();

            $table->unique(['admin_id', 'stat_date'], 'idx_admin_stats_unique');
            $table->index('stat_date', 'idx_stats_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop new tables
        Schema::dropIfExists('admin_chat_stats');
        Schema::dropIfExists('chat_activity_logs');
        Schema::dropIfExists('quick_replies');
        Schema::dropIfExists('canned_responses');

        // Revert chat_messages
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->dropIndex('idx_msg_conversation');
            $table->dropIndex('idx_msg_sender_type');
            $table->dropIndex('idx_msg_conv_created');
            $table->dropIndex('idx_msg_read');
            $table->dropColumn(['is_read', 'read_at']);
        });

        // Revert chat_conversations
        Schema::table('chat_conversations', function (Blueprint $table) {
            $table->dropIndex('idx_chat_admin');
            $table->dropIndex('idx_chat_status');
            $table->dropIndex('idx_chat_admin_status');
            $table->dropIndex('idx_chat_rating');
            $table->dropIndex('idx_chat_created');

            $table->dropColumn([
                'admin_id',
                'assigned_at',
                'waiting_since',
                'transferred_from',
                'transferred_at',
                'resolved_at',
                'resolved_by',
                'rating',
                'rating_feedback',
                'rated_at',
                'unread_by_admin',
                'unread_by_user'
            ]);
        });
    }
};
