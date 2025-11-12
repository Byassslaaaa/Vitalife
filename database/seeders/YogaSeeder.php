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
                'nama' => 'Jiwa Yoga Studio Yogyakarta',
                'harga' => 100000,
                'alamat' => 'Jl. Veteran No.2B, Pandeyan, Umbulharjo, Yogyakarta',
                'noHP' => '0857-2888-5555',
                'waktuBuka' => [
                    'Senin' => '06:00 - 21:00',
                    'Selasa' => '06:00 - 21:00',
                    'Rabu' => '06:00 - 21:00',
                    'Kamis' => '06:00 - 21:00',
                    'Jumat' => '06:00 - 21:00',
                    'Sabtu' => '07:00 - 20:00',
                    'Minggu' => '07:00 - 19:00',
                ],
                'image' => 'image/yoga1.jpg',
                'maps' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3952.823473847385!2d110.37264607503843!3d-7.793189692227135!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a5783b5c5b5c5%3A0x9f0e1d2c3b4a5f6e!2sJiwa%20Yoga%20Studio!5e0!3m2!1sen!2sid!4v1699000000000!5m2!1sen!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>',
                'is_open' => true,
                'services' => [
                    [
                        'name' => 'Hatha Yoga',
                        'description' => 'Traditional yoga focusing on physical postures and breathing techniques',
                        'price' => 100000,
                        'image' => 'image/yoga-service1.jpg'
                    ],
                    [
                        'name' => 'Vinyasa Flow',
                        'description' => 'Dynamic yoga style linking movement with breath',
                        'price' => 120000,
                        'image' => 'image/yoga-service2.jpg'
                    ],
                    [
                        'name' => 'Meditation Class',
                        'description' => 'Guided meditation for mental clarity and inner peace',
                        'price' => 80000,
                        'image' => 'image/yoga-service3.jpg'
                    ]
                ],
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
