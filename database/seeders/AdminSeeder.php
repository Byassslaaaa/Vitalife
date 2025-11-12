<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin (memiliki semua akses otomatis)
        Admin::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@healife.com',
            'phone' => '081234567890',
            'password' => Hash::make('password123'),
            'role_level' => 'super_admin',
            'status' => 'active',
            'permissions' => null, // Super admin tidak perlu permissions, punya akses semua
            'notes' => 'Super Admin dengan akses penuh ke semua fitur sistem',
        ]);

        // Create Admin Spa - Khusus Kelola Spa
        Admin::create([
            'name' => 'Admin Spa',
            'email' => 'admin.spa@healife.com',
            'phone' => '081234567891',
            'password' => Hash::make('password123'),
            'role_level' => 'admin',
            'status' => 'active',
            'permissions' => [
                'spa' => true,
                'bookings' => true,
                'yoga' => false,
                'gym' => false,
                'vouchers' => false,
                'users' => false,
                'admins' => false,
                'payments' => true,
                'chat' => true,
                'feedback' => true,
                'analytics' => true,
            ],
            'notes' => 'Admin khusus mengelola Spa, booking spa, dan payment',
        ]);

        // Create Admin Yoga - Khusus Kelola Yoga
        Admin::create([
            'name' => 'Admin Yoga',
            'email' => 'admin.yoga@healife.com',
            'phone' => '081234567892',
            'password' => Hash::make('password123'),
            'role_level' => 'admin',
            'status' => 'active',
            'permissions' => [
                'spa' => false,
                'bookings' => true,
                'yoga' => true,
                'gym' => false,
                'vouchers' => false,
                'users' => false,
                'admins' => false,
                'payments' => true,
                'chat' => true,
                'feedback' => true,
                'analytics' => true,
            ],
            'notes' => 'Admin khusus mengelola Yoga, booking yoga, dan payment',
        ]);

        // Create Admin Gym - Khusus Kelola Gym
        Admin::create([
            'name' => 'Admin Gym',
            'email' => 'admin.gym@healife.com',
            'phone' => '081234567893',
            'password' => Hash::make('password123'),
            'role_level' => 'admin',
            'status' => 'active',
            'permissions' => [
                'spa' => false,
                'bookings' => true,
                'yoga' => false,
                'gym' => true,
                'vouchers' => false,
                'users' => false,
                'admins' => false,
                'payments' => true,
                'chat' => true,
                'feedback' => true,
                'analytics' => true,
            ],
            'notes' => 'Admin khusus mengelola Gym, booking gym, dan payment',
        ]);

        // Create Admin Full Services - Kelola Semua Layanan
        Admin::create([
            'name' => 'Admin All Services',
            'email' => 'admin.all@healife.com',
            'phone' => '081234567894',
            'password' => Hash::make('password123'),
            'role_level' => 'admin',
            'status' => 'active',
            'permissions' => [
                'spa' => true,
                'bookings' => true,
                'yoga' => true,
                'gym' => true,
                'vouchers' => true,
                'users' => true,
                'admins' => false, // Tidak bisa kelola admin lain
                'payments' => true,
                'chat' => true,
                'feedback' => true,
                'analytics' => true,
            ],
            'notes' => 'Admin yang mengelola semua layanan (Spa, Yoga, Gym) dan customer',
        ]);
    }
}
