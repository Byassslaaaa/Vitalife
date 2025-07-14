# 🚨 Migration Fix Guide

## Problem
If you get this error when running migrations:
```
SQLSTATE[HY000]: General error: 1005 Can't create table `mainvita`.`yoga_booking_services` (errno: 150 "Foreign key constraint is incorrectly formed")
```

## Root Cause
Your database still has old migration records from previous versions that have been removed.

## ✅ Solution

### Option 1: Fresh Installation (Recommended)
```bash
# Drop all tables and recreate (WARNING: This will delete all data!)
php artisan migrate:fresh --seed
```

### Option 2: Clean Migration Records
```bash
# Check current migrations status
php artisan migrate:status

# If you see old migrations that don't exist in files, clean them:
# Connect to your database and run:
DELETE FROM migrations WHERE migration LIKE '%yoga_booking_services%';
DELETE FROM migrations WHERE migration LIKE '%add_role_to_users%';
DELETE FROM migrations WHERE migration LIKE '%create_admins%';
DELETE FROM migrations WHERE migration LIKE '%add_notes_to_yoga_bookings%';

# Then run migrations normally
php artisan migrate
```

### Option 3: Reset Migrations Table
```bash
# Backup your data first!
mysqldump -u your_username -p your_database > backup.sql

# Drop migrations table and recreate
php artisan migrate:reset
php artisan migrate --seed
```

## 📋 Current Migration List (25 files)
These are the ONLY migration files that should exist:

- ✅ 0001_01_01_000000_create_users_table.php
- ✅ 0001_01_01_000001_create_cache_table.php  
- ✅ 0001_01_01_000002_create_jobs_table.php
- ✅ 2023_05_10_add_google_id_to_users_table.php
- ✅ 2024_01_15_000003_create_detail_page_templates_table.php
- ✅ 2024_05_15_create_bookings_table.php
- ✅ 2024_05_20_create_chat_conversations_table.php
- ✅ 2024_05_20_create_chat_messages_table.php
- ✅ 2024_06_23_142128_create_yogas_table.php
- ✅ 2024_06_23_142129_create_yoga_detail_configs_table.php
- ✅ 2024_07_08_154226_create_notifications_table.php
- ✅ 2024_07_13_070625_create_feedback_table.php
- ✅ 2024_07_18_182836_create_vouchers_table.php
- ✅ 2024_07_20_045113_create_admin_requests_table.php
- ✅ 2024_07_23_142135_create_events_table.php
- ✅ 2025_01_01_000001_create_spas_table.php
- ✅ 2025_01_01_000002_create_spa_details_table.php
- ✅ 2025_01_01_000003_create_spa_services_table.php
- ✅ 2025_01_01_000004_create_spa_bookings_table.php
- ✅ 2025_01_01_000005_add_additional_services_to_spa_details.php
- ✅ 2025_01_01_000006_add_category_to_spa_services_table.php
- ✅ 2025_05_01_000000_create_yoga_bookings_table.php
- ✅ 2025_05_07_063629_create_payments_table.php
- ✅ 2025_05_07_165447_create_personal_access_tokens_table.php
- ✅ 2025_06_14_134252_create_gyms_table.php
- ✅ 2025_06_14_134255_create_gym_details_table.php
- ✅ 2025_06_15_000001_add_is_open_to_gyms_table.php
- ✅ 2025_06_23_154234_create_booking_services_table.php
- ✅ 2025_07_13_173439_add_booking_type_to_bookings_table.php
- ✅ 2025_07_13_175955_create_yoga_services_table.php
- ✅ 2025_07_13_190921_add_additional_fields_to_yoga_services_table.php
- ✅ 2025_07_14_134225_create_gym_services_table.php
- ✅ 2025_07_16_134253_create_gym_bookings_table.php

## ❌ Removed Migration Files
These files have been DELETED and should not exist:
- ❌ add_role_to_users_table.php (empty migration)
- ❌ create_admins_table.php (unused table)  
- ❌ create_yoga_booking_services_table.php (duplicate)
- ❌ add_notes_to_yoga_bookings_table.php (redundant)

## 🎯 After Fix
Run this to verify everything works:
```bash
php artisan migrate:status
# Should show 25 migrations all marked as "Ran"

php artisan tinker
App\Models\User::count();
App\Models\Spa::count(); 
App\Models\Gym::count();
App\Models\Yoga::count();
# Should return proper counts without errors
```

## 💡 Prevention
Always use `php artisan migrate:fresh --seed` for clean installations from GitHub.
