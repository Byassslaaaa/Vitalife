<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Yoga;

class YogaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $yogaData = [
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
                'image' => 'images/yoga-barn.jpg',
                'maps' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3953.584738473847!2d110.39472607503795!3d-7.764839692261485!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a59f7bb5a8c9d%3A0x8f4e5d6a7b8c9e0f!2sKaliurang%20Yogyakarta!5e0!3m2!1sen!2sid!4v1620000000000!5m2!1sen!2sid',
            ],
            [
                'nama' => 'Shanti Yoga Studio Yogyakarta',
                'harga' => 175000,
                'alamat' => 'Jl. Gejayan Kompleks Colombo No.C4, Depok, Sleman, Yogyakarta',
                'noHP' => '0274-882345',
                'waktuBuka' => [
                    'Senin' => '05:30 - 20:30',
                    'Selasa' => '05:30 - 20:30',
                    'Rabu' => '05:30 - 20:30',
                    'Kamis' => '05:30 - 20:30',
                    'Jumat' => '05:30 - 20:30',
                    'Sabtu' => '06:00 - 20:00',
                    'Minggu' => '06:00 - 19:00',
                ],
                'image' => 'images/yoga-shanti.jpg',
                'maps' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3953.1847384738473!2d110.40472607503809!3d-7.784839692241485!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a59c79b5a7b8f%3A0x5e4f2b3c8d9a6b7c!2sGejayan%20Yogyakarta!5e0!3m2!1sen!2sid!4v1620000000000!5m2!1sen!2sid',
            ],
            [
                'nama' => 'Surya Namaskara Yoga Center',
                'harga' => 125000,
                'alamat' => 'Jl. Imogiri Timur Km 7, Bantul, Yogyakarta',
                'noHP' => '0274-883456',
                'waktuBuka' => [
                    'Senin' => '06:00 - 21:00',
                    'Selasa' => '06:00 - 21:00',
                    'Rabu' => '06:00 - 21:00',
                    'Kamis' => '06:00 - 21:00',
                    'Jumat' => '06:00 - 21:00',
                    'Sabtu' => '06:00 - 20:00',
                    'Minggu' => '07:00 - 19:00',
                ],
                'image' => 'images/yoga-surya.jpg',
                'maps' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3952.2847384738473!2d110.39472607503855!3d-7.824839692193384!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a574c4b5a8c9d%3A0x7f3e4d5a6b7c8e9f!2sImogiri%20Bantul%20Yogyakarta!5e0!3m2!1sen!2sid!4v1620000000000!5m2!1sen!2sid',
            ],
            [
                'nama' => 'Mandala Yoga & Meditation',
                'harga' => 200000,
                'alamat' => 'Jl. Prawirotaman III No.629, Mergangsan, Yogyakarta',
                'noHP' => '0274-884567',
                'waktuBuka' => [
                    'Senin' => '05:00 - 22:00',
                    'Selasa' => '05:00 - 22:00',
                    'Rabu' => '05:00 - 22:00',
                    'Kamis' => '05:00 - 22:00',
                    'Jumat' => '05:00 - 22:00',
                    'Sabtu' => '05:00 - 21:00',
                    'Minggu' => '06:00 - 20:00',
                ],
                'image' => 'images/yoga-mandala.jpg',
                'maps' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3952.9473847384738!2d110.36588347503846!3d-7.796194392226735!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a578b54a66b31%3A0xf92a735bf5b0b5e8!2sPrawirotaman%20Yogyakarta!5e0!3m2!1sen!2sid!4v1620000000000!5m2!1sen!2sid',
            ],
            [
                'nama' => 'Harmony Yoga Studio',
                'harga' => 165000,
                'alamat' => 'Jl. Magelang Km 3.5, Mlati, Sleman, Yogyakarta',
                'noHP' => '0274-885678',
                'waktuBuka' => [
                    'Senin' => '06:00 - 20:00',
                    'Selasa' => '06:00 - 20:00',
                    'Rabu' => '06:00 - 20:00',
                    'Kamis' => '06:00 - 20:00',
                    'Jumat' => '06:00 - 20:00',
                    'Sabtu' => '07:00 - 19:00',
                    'Minggu' => '07:00 - 19:00',
                ],
                'image' => 'images/yoga-harmony.jpg',
                'maps' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3953.0847384738473!2d110.36472607503825!3d-7.791839692231485!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a59e8cb6a9c0d%3A0xa1b2c3d4e5f6789a!2sMagelang%20Yogyakarta!5e0!3m2!1sen!2sid!4v1620000000000!5m2!1sen!2sid',
            ]
        ];

        foreach ($yogaData as $data) {
            Yoga::create($data);
        }
    }
}
