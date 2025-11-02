<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Add performance indexes to improve query speed
     */
    public function up(): void
    {
        // Spa Bookings Table Indexes
        Schema::table('spa_bookings', function (Blueprint $table) {
            $table->index('booking_code', 'idx_spa_booking_code');
            $table->index('customer_email', 'idx_spa_customer_email');
            $table->index(['payment_status', 'status'], 'idx_spa_payment_booking_status');
            $table->index('booking_date', 'idx_spa_booking_date');
            $table->index('created_at', 'idx_spa_created_at');
        });

        // Yoga Bookings Table Indexes
        Schema::table('yoga_bookings', function (Blueprint $table) {
            $table->index('booking_code', 'idx_yoga_booking_code');
            $table->index('customer_email', 'idx_yoga_customer_email');
            $table->index(['payment_status', 'status'], 'idx_yoga_payment_booking_status');
            $table->index('booking_date', 'idx_yoga_booking_date');
            $table->index('created_at', 'idx_yoga_created_at');
        });

        // Gym Bookings Table Indexes
        Schema::table('gym_bookings', function (Blueprint $table) {
            $table->index('booking_code', 'idx_gym_booking_code');
            $table->index('customer_email', 'idx_gym_customer_email');
            $table->index(['payment_status', 'status'], 'idx_gym_payment_booking_status');
            $table->index('created_at', 'idx_gym_created_at');
        });

        // Spa Services Table Indexes
        Schema::table('spa_services', function (Blueprint $table) {
            $table->index('is_active', 'idx_spa_services_active');
            $table->index('category', 'idx_spa_services_category');
            $table->index(['spa_id', 'is_active'], 'idx_spa_services_spa_active');
        });

        // Yoga Services Table Indexes
        Schema::table('yoga_services', function (Blueprint $table) {
            $table->index('is_active', 'idx_yoga_services_active');
            $table->index('category', 'idx_yoga_services_category');
            $table->index(['yoga_id', 'is_active'], 'idx_yoga_services_yoga_active');
        });

        // Gym Services Table Indexes
        Schema::table('gym_services', function (Blueprint $table) {
            $table->index('is_active', 'idx_gym_services_active');
            $table->index('category', 'idx_gym_services_category');
            $table->index(['gym_id', 'is_active'], 'idx_gym_services_gym_active');
        });

        // Vouchers Table Indexes
        Schema::table('vouchers', function (Blueprint $table) {
            $table->index('code', 'idx_vouchers_code');
            $table->index(['is_used', 'expired_at'], 'idx_vouchers_used_expired');
            $table->index('expired_at', 'idx_vouchers_expired_at');
        });

        // Chat Messages Table Indexes
        if (Schema::hasTable('chat_messages')) {
            Schema::table('chat_messages', function (Blueprint $table) {
                $table->index(['conversation_id', 'created_at'], 'idx_chat_messages_conv_created');
                $table->index(['is_read', 'sender_type'], 'idx_chat_messages_read_sender');
            });
        }

        // Chat Conversations Table Indexes
        if (Schema::hasTable('chat_conversations')) {
            Schema::table('chat_conversations', function (Blueprint $table) {
                $table->index(['user_id', 'status'], 'idx_chat_conversations_user_status');
                $table->index('updated_at', 'idx_chat_conversations_updated');
            });
        }

        // Users Table Indexes (if not exists)
        Schema::table('users', function (Blueprint $table) {
            // Email already has unique index
            $table->index('role', 'idx_users_role');
            $table->index('created_at', 'idx_users_created_at');
        });

        // Spas Table Indexes
        Schema::table('spas', function (Blueprint $table) {
            $table->index('is_open', 'idx_spas_open');
        });

        // Gyms Table Indexes
        Schema::table('gyms', function (Blueprint $table) {
            $table->index('is_open', 'idx_gyms_open');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Spa Bookings
        Schema::table('spa_bookings', function (Blueprint $table) {
            $table->dropIndex('idx_spa_booking_code');
            $table->dropIndex('idx_spa_customer_email');
            $table->dropIndex('idx_spa_payment_booking_status');
            $table->dropIndex('idx_spa_booking_date');
            $table->dropIndex('idx_spa_created_at');
        });

        // Yoga Bookings
        Schema::table('yoga_bookings', function (Blueprint $table) {
            $table->dropIndex('idx_yoga_booking_code');
            $table->dropIndex('idx_yoga_customer_email');
            $table->dropIndex('idx_yoga_payment_booking_status');
            $table->dropIndex('idx_yoga_booking_date');
            $table->dropIndex('idx_yoga_created_at');
        });

        // Gym Bookings
        Schema::table('gym_bookings', function (Blueprint $table) {
            $table->dropIndex('idx_gym_booking_code');
            $table->dropIndex('idx_gym_customer_email');
            $table->dropIndex('idx_gym_payment_booking_status');
            $table->dropIndex('idx_gym_created_at');
        });

        // Spa Services
        Schema::table('spa_services', function (Blueprint $table) {
            $table->dropIndex('idx_spa_services_active');
            $table->dropIndex('idx_spa_services_category');
            $table->dropIndex('idx_spa_services_spa_active');
        });

        // Yoga Services
        Schema::table('yoga_services', function (Blueprint $table) {
            $table->dropIndex('idx_yoga_services_active');
            $table->dropIndex('idx_yoga_services_category');
            $table->dropIndex('idx_yoga_services_yoga_active');
        });

        // Gym Services
        Schema::table('gym_services', function (Blueprint $table) {
            $table->dropIndex('idx_gym_services_active');
            $table->dropIndex('idx_gym_services_category');
            $table->dropIndex('idx_gym_services_gym_active');
        });

        // Vouchers
        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropIndex('idx_vouchers_code');
            $table->dropIndex('idx_vouchers_used_expired');
            $table->dropIndex('idx_vouchers_expired_at');
        });

        // Chat Messages
        if (Schema::hasTable('chat_messages')) {
            Schema::table('chat_messages', function (Blueprint $table) {
                $table->dropIndex('idx_chat_messages_conv_created');
                $table->dropIndex('idx_chat_messages_read_sender');
            });
        }

        // Chat Conversations
        if (Schema::hasTable('chat_conversations')) {
            Schema::table('chat_conversations', function (Blueprint $table) {
                $table->dropIndex('idx_chat_conversations_user_status');
                $table->dropIndex('idx_chat_conversations_updated');
            });
        }

        // Users
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_role');
            $table->dropIndex('idx_users_created_at');
        });

        // Spas
        Schema::table('spas', function (Blueprint $table) {
            $table->dropIndex('idx_spas_open');
        });

        // Gyms
        Schema::table('gyms', function (Blueprint $table) {
            $table->dropIndex('idx_gyms_open');
        });
    }
};
