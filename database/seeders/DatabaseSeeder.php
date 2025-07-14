<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::factory()->create([
            'name' => 'Admin Vitalife',
            'email' => 'admin@vitalife.com',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
        ]);

        // Create test user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        // Run all feature seeders for Yogyakarta locations
        $this->call([
            // Spa related seeders
            SpaSeeder::class,
            SpaServiceSeeder::class,

            // Gym related seeders
            GymSeeder::class,
            GymServiceSeeder::class,

            // Yoga related seeders
            YogaSeeder::class,
            YogaServiceSeeder::class,
            YogaDetailConfigSeeder::class,

            // Template seeder if exists
            DetailPageTemplateSeeder::class,
        ]);
    }
}
