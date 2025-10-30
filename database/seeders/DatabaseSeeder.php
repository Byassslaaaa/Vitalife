<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed core users (idempotent)
        if (User::where('email', 'admin@vitalife.com')->doesntExist()) {
            User::factory()->create([
                'name' => 'Admin Vitalife',
                'email' => 'admin@vitalife.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]);
        }

        if (User::where('email', 'demo@vitalife.web.id')->doesntExist()) {
            User::factory()->create([
                'name' => 'Demo User',
                'email' => 'demo@vitalife.web.id',
                'password' => Hash::make('password'),
                'role' => 'user',
            ]);
        }

        // Call dedicated seeders in the correct order
        $this->call([
            SpaSeeder::class,
            SpaServiceSeeder::class,
            GymSeeder::class,
            GymServiceSeeder::class,
            YogaSeeder::class,
            YogaServiceSeeder::class,
            YogaDetailConfigSeeder::class,
            VoucherSeeder::class,
            DetailPageTemplateSeeder::class,
        ]);
    }
}
