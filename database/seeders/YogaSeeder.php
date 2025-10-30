<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Yoga;
use App\Models\YogaDetailConfig;

class YogaSeeder extends Seeder
{
    public function run(): void
    {
        $yogas = [
            [
                'nama' => 'Yoga Barn Yogyakarta',
                'harga' => 150000,
                'alamat' => 'Jl. Kaliurang Km 5.2, Sleman, Yogyakarta',
                'noHP' => '0274-881234',
                'waktuBuka' => [
                    'Senin' => '06:00 - 21:00',
                    'Selasa' => '06:00 - 21:00',
                    'Rabu' => '06:00 - 21:00',
                    'Kamis' => '06:00 - 21:00',
                    'Jumat' => '06:00 - 21:00',
                    'Sabtu' => '06:00 - 20:00',
                    'Minggu' => '07:00 - 19:00',
                ],
                'image' => 'image/yoga1.jpg',
                'maps' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3953.584738473847!2d110.39472607503795!3d-7.764839692261485!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a59f7bb5a8c9d%3A0x8f4e5d6a7b8c9e0f!2sKaliurang%20Yogyakarta!5e0!3m2!1sen!2sid!4v1620000000000!5m2!1sen!2sid',
            ],
            [
                'nama' => 'Zen Yoga Studio',
                'harga' => 120000,
                'alamat' => 'Jl. Malioboro No.89, Yogyakarta',
                'noHP' => '0274-445566',
                'waktuBuka' => [
                    'Senin' => '07:00 - 20:00',
                    'Selasa' => '07:00 - 20:00',
                    'Rabu' => '07:00 - 20:00',
                    'Kamis' => '07:00 - 20:00',
                    'Jumat' => '07:00 - 20:00',
                    'Sabtu' => '07:00 - 19:00',
                    'Minggu' => '08:00 - 18:00',
                ],
                'image' => 'image/yoga2.jpg',
                'maps' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3952.5!2d110.365!3d-7.795!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a578b54a66b31%3A0xf92a735bf5b0b5e8!2sMalioboro%2C%20Yogyakarta!5e0!3m2!1sen!2sid!4v1620000000000!5m2!1sen!2sid',
            ],
            [
                'nama' => 'Peaceful Yoga Center',
                'harga' => 180000,
                'alamat' => 'Jl. Wates Km 3, Yogyakarta',
                'noHP' => '0274-777888',
                'waktuBuka' => [
                    'Senin' => '06:30 - 21:30',
                    'Selasa' => '06:30 - 21:30',
                    'Rabu' => '06:30 - 21:30',
                    'Kamis' => '06:30 - 21:30',
                    'Jumat' => '06:30 - 21:30',
                    'Sabtu' => '07:00 - 20:00',
                    'Minggu' => '07:00 - 20:00',
                ],
                'image' => 'image/yoga3.jpg',
                'maps' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3952.9!2d110.350!3d -7.800!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a578b54a66b31%3A0xf92a735bf5b0b5e8!2sWates%20Yogyakarta!5e0!3m2!1sen!2sid!4v1620000000000!5m2!1sen!2sid',
            ]
        ];

        foreach ($yogas as $data) {
            $yoga = Yoga::updateOrCreate(
                ['nama' => $data['nama']],
                $data
            );

            YogaDetailConfig::updateOrCreate(
                ['yoga_id' => $yoga->id_yoga],
                [
                    'hero_title' => 'Welcome to ' . $yoga->nama,
                    'hero_subtitle' => 'Menggabungkan tradisi yoga kuno dengan suasana yang tenang dan damai',
                    'facilities' => [
                        'Air Conditioned Studio',
                        'Yoga Props Available',
                        'Meditation Corner',
                        'Herbal Tea Corner',
                        'Changing Room',
                        'Free Parking'
                    ],
                    'gallery_images' => [
                        $yoga->image,
                        'image/yoga-studio.jpg',
                        'image/yoga-meditation.jpg'
                    ],
                    'show_opening_hours' => true,
                    'show_location_map' => true,
                ]
            );
        }
    }
}
