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
                'nama' => 'Fitness First Jogja City Mall',
                'alamat' => 'Jl. Magelang No.6, Yogyakarta',
                'services' => [
                    'Cardio Equipment',
                    'Weight Training',
                    'Group Classes'
                ],
                'fasilitas' => [
                    'Swimming Pool',
                    'Sauna',
                    'Personal Training'
                ],
                'description' => 'Gym premium dengan fasilitas lengkap dan instruktur berpengalaman.',
                'contact_person' => 'Mr. Andi Pratama',
                'contact_phone' => '0274-123456',
                'image' => 'image/gym1.jpg',
                'maps' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3952.8!2d110.367!3d-7.795!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a578b54a66b31%3A0xf92a735bf5b0b5e8!2sMagelang%20Yogyakarta!5e0!3m2!1sen!2sid!4v1620000000000!5m2!1sen!2sid',
                'is_open' => true,
            ],
            [
                'nama' => "Gold's Gym Yogyakarta",
                'alamat' => 'Jl. Solo No.47, Yogyakarta',
                'services' => [
                    'Strength Training',
                    'Cardio Workout',
                    'Functional Training'
                ],
                'fasilitas' => [
                    'Modern Equipment',
                    'Group Classes',
                    'Nutritionist Consultation'
                ],
                'description' => 'Gym internasional dengan standar kelas dunia.',
                'contact_person' => 'Ms. Sarah Gym',
                'contact_phone' => '0274-789123',
                'image' => 'image/gym2.jpg',
                'maps' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3952.7!2d110.370!3d-7.790!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a578b54a66b31%3A0xf92a735bf5b0b5e8!2sSolo%20Yogyakarta!5e0!3m2!1sen!2sid!4v1620000000000!5m2!1sen!2sid',
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
                    'facilities' => array_merge($data['services'] ?? [], $data['fasilitas'] ?? []),
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
