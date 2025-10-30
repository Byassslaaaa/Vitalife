<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gym;
use App\Models\GymService;

class GymServiceSeeder extends Seeder
{
    public function run(): void
    {
        $servicePreset = [
            [
                'name' => 'Personal Training Session',
                'description' => 'Sesi latihan personal dengan instruktur berpengalaman',
                'price' => 350000,
                'duration' => '60 menit',
                'category' => 'personal_training',
                'image' => 'image/gym-personal-training.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Group Class',
                'description' => 'Kelas kelompok dengan berbagai pilihan workout',
                'price' => 100000,
                'duration' => '45 menit',
                'category' => 'group_class',
                'image' => 'image/gym-group.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Cardio Training',
                'description' => 'Program latihan kardio untuk kesehatan jantung',
                'price' => 200000,
                'duration' => '60 menit',
                'category' => 'cardio',
                'image' => 'image/gym-cardio.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Weight Training',
                'description' => 'Program latihan beban untuk pembentukan otot',
                'price' => 250000,
                'duration' => '75 menit',
                'category' => 'strength',
                'image' => 'image/gym-weight-training.jpg',
                'is_active' => true,
            ]
        ];

        $gyms = Gym::all();
        foreach ($gyms as $gym) {
            foreach ($servicePreset as $svc) {
                GymService::updateOrCreate(
                    ['gym_id' => $gym->id_gym, 'name' => $svc['name']],
                    array_merge($svc, ['gym_id' => $gym->id_gym])
                );
            }
        }
    }
}
