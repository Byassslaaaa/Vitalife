<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Gym;
use App\Models\GymService;

class GymServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gyms = Gym::all();

        $servicesByCategory = [
            'Personal Training' => [
                ['name' => 'Personal Training Session', 'description' => 'Sesi latihan personal 1-on-1 dengan trainer bersertifikat', 'price' => 200000, 'duration' => '60 menit'],
                ['name' => 'Small Group Training', 'description' => 'Latihan dalam grup kecil 2-4 orang', 'price' => 150000, 'duration' => '60 menit'],
                ['name' => 'Consultation & Program Design', 'description' => 'Konsultasi dan pembuatan program latihan personal', 'price' => 100000, 'duration' => '45 menit'],
            ],
            'Group Classes' => [
                ['name' => 'Zumba Class', 'description' => 'Kelas dance fitness yang energik dan menyenangkan', 'price' => 75000, 'duration' => '45 menit'],
                ['name' => 'HIIT Training', 'description' => 'High Intensity Interval Training untuk membakar kalori', 'price' => 85000, 'duration' => '30 menit'],
                ['name' => 'Yoga Class', 'description' => 'Kelas yoga untuk fleksibilitas dan ketenangan pikiran', 'price' => 80000, 'duration' => '60 menit'],
                ['name' => 'Body Combat', 'description' => 'Kelas martial arts inspired workout', 'price' => 90000, 'duration' => '45 menit'],
            ],
            'Strength Training' => [
                ['name' => 'Powerlifting Session', 'description' => 'Latihan squat, bench press, dan deadlift dengan teknik proper', 'price' => 120000, 'duration' => '90 menit'],
                ['name' => 'Olympic Lifting', 'description' => 'Latihan clean & jerk dan snatch dengan coach berpengalaman', 'price' => 150000, 'duration' => '75 menit'],
                ['name' => 'Bodybuilding Program', 'description' => 'Program khusus untuk muscle building dan shaping', 'price' => 180000, 'duration' => '75 menit'],
            ],
            'Functional Training' => [
                ['name' => 'CrossFit WOD', 'description' => 'Workout of the Day dengan variasi movement patterns', 'price' => 100000, 'duration' => '60 menit'],
                ['name' => 'TRX Suspension Training', 'description' => 'Latihan menggunakan TRX untuk strength dan stability', 'price' => 95000, 'duration' => '45 menit'],
                ['name' => 'Bootcamp Training', 'description' => 'Military-style workout yang menantang', 'price' => 110000, 'duration' => '60 menit'],
            ],
            'Recovery & Wellness' => [
                ['name' => 'Stretching Session', 'description' => 'Sesi peregangan untuk recovery dan flexibility', 'price' => 60000, 'duration' => '30 menit'],
                ['name' => 'Massage Therapy', 'description' => 'Terapi pijat untuk relaksasi otot', 'price' => 150000, 'duration' => '60 menit'],
                ['name' => 'Sauna Session', 'description' => 'Relaksasi di sauna untuk detoksifikasi', 'price' => 50000, 'duration' => '30 menit'],
            ]
        ];

        foreach ($gyms as $index => $gym) {
            $categories = array_keys($servicesByCategory);

            // Each gym gets 2-3 categories of services
            $numCategories = rand(2, 3);
            $selectedCategories = array_slice($categories, $index % count($categories), $numCategories);

            foreach ($selectedCategories as $category) {
                $services = $servicesByCategory[$category];

                // Pick 2-4 services from each category
                $numServices = rand(2, 4);
                $selectedServices = array_slice($services, 0, $numServices);

                foreach ($selectedServices as $service) {
                    GymService::create([
                        'gym_id' => $gym->id_gym,
                        'name' => $service['name'],
                        'description' => $service['description'],
                        'price' => $service['price'],
                        'duration' => $service['duration'],
                        'category' => $category,
                        'is_active' => true,
                    ]);
                }
            }
        }
    }
}
