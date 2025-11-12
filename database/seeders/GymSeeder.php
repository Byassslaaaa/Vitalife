<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gym;
use App\Models\GymDetail;

class GymSeeder extends Seeder
{
    public function run(): void
    {
        $gyms = [
            [
                'nama' => 'Celebrity Fitness Hartono Mall Yogyakarta',
                'alamat' => 'Hartono Mall Lt. 3, Jl. Ring Road Utara, Kaliwaru, Condongcatur, Depok, Sleman',
                'services' => [
                    [
                        'name' => 'Cardio Equipment',
                        'description' => 'State-of-the-art treadmills, ellipticals, and stationary bikes for cardio training',
                        'price' => 150000,
                        'image' => 'image/gym-service1.jpg'
                    ],
                    [
                        'name' => 'Weight Training',
                        'description' => 'Comprehensive weight training equipment for strength building',
                        'price' => 200000,
                        'image' => 'image/gym-service2.jpg'
                    ],
                    [
                        'name' => 'Group Classes',
                        'description' => 'Various group fitness classes including Zumba, Spinning, and Body Combat',
                        'price' => 100000,
                        'image' => 'image/gym-service3.jpg'
                    ]
                ],
                'fasilitas' => [
                    'Modern Equipment',
                    'Sauna & Steam',
                    'Personal Training',
                    'Locker Room',
                    'Free Parking'
                ],
                'description' => 'Celebrity Fitness adalah gym premium dengan fasilitas lengkap dan instruktur berpengalaman. Dilengkapi dengan peralatan fitness terbaru dari TechnoGym.',
                'contact_person' => 'Customer Service',
                'contact_phone' => '0274-625888',
                'image' => 'image/gym1.jpg',
                'maps' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3953.3289477324837!2d110.40235597503814!3d-7.7714899922581755!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a59c0c7c0c0c1%3A0x8f9e0d1c2b3a4f5e!2sCelebrity%20Fitness%20Hartono%20Mall!5e0!3m2!1sen!2sid!4v1699000000000!5m2!1sen!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>',
                'is_open' => true,
            ]
        ];

        foreach ($gyms as $data) {
            // Only pass fillable attributes to avoid mass-assignment issues
            $gym = Gym::updateOrCreate(
                ['nama' => $data['nama']],
                [
                    'nama' => $data['nama'],
                    'alamat' => $data['alamat'] ?? null,
                    'services' => $data['services'] ?? [],
                    'fasilitas' => $data['fasilitas'] ?? [],
                    'description' => $data['description'] ?? null,
                    'contact_person' => $data['contact_person'] ?? null,
                    'contact_phone' => $data['contact_phone'] ?? null,
                    'image' => $data['image'] ?? null,
                    'maps' => $data['maps'] ?? null,
                    'is_open' => $data['is_open'] ?? true,
                ]
            );

            GymDetail::updateOrCreate(
                ['gym_id' => $gym->id_gym],
                [
                    'about_gym' => $data['description'] ?? 'Gym dengan fasilitas lengkap.',
                    'facilities' => $data['fasilitas'] ?? [], // Only store simple facility strings
                    'opening_hours' => [
                        'Senin' => '06:00 - 22:00',
                        'Selasa' => '06:00 - 22:00',
                        'Rabu' => '06:00 - 22:00',
                        'Kamis' => '06:00 - 22:00',
                        'Jumat' => '06:00 - 22:00',
                        'Sabtu' => '07:00 - 21:00',
                        'Minggu' => '07:00 - 21:00',
                    ],
                    'contact_person_name' => $data['contact_person'] ?? 'Customer Service',
                    'contact_person_phone' => $data['contact_phone'] ?? null,
                    'gallery_images' => [
                        $data['image'],
                        'image/gym-interior.jpg',
                        'image/gym-equipment.jpg'
                    ]
                ]
            );
        }
    }
}
