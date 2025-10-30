<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Spa;
use App\Models\SpaDetail;

class SpaSeeder extends Seeder
{
    public function run(): void
    {
        $spas = [
            [
                'nama' => 'Royal Heritage Spa Yogyakarta',
                'alamat' => 'Jl. Prawirotaman II No.15, Mergangsan, Yogyakarta',
                'noHP' => '0274-373511',
                'image' => 'image/spa1.jpg',
                'maps' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3952.9473847384738!2d110.36588347503846!3d-7.796194392226735!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a578b54a66b31%3A0xf92a735bf5b0b5e8!2sPrawirotaman%2C%20Mergangsan%2C%20Kota%20Yogyakarta!5e0!3m2!1sen!2sid!4v1620000000000!5m2!1sen!2sid',
                'waktuBuka' => [
                    'Senin' => '09:00 - 21:00',
                    'Selasa' => '09:00 - 21:00',
                    'Rabu' => '09:00 - 21:00',
                    'Kamis' => '09:00 - 21:00',
                    'Jumat' => '09:00 - 21:00',
                    'Sabtu' => '09:00 - 22:00',
                    'Minggu' => '09:00 - 22:00',
                ],
                'services' => [
                    'Traditional Javanese Massage',
                    'Royal Heritage Signature Treatment',
                    'Aromatherapy Massage'
                ],
                'is_open' => true,
            ],
            [
                'nama' => 'Zen Spa & Wellness',
                'alamat' => 'Jl. Malioboro No.123, Yogyakarta',
                'noHP' => '0274-555777',
                'image' => 'image/spa2.jpg',
                'maps' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3952.5!2d110.365!3d-7.795!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a578b54a66b31%3A0xf92a735bf5b0b5e8!2sMalioboro%2C%20Yogyakarta!5e0!3m2!1sen!2sid!4v1620000000000!5m2!1sen!2sid',
                'waktuBuka' => [
                    'Senin' => '10:00 - 22:00',
                    'Selasa' => '10:00 - 22:00',
                    'Rabu' => '10:00 - 22:00',
                    'Kamis' => '10:00 - 22:00',
                    'Jumat' => '10:00 - 22:00',
                    'Sabtu' => '10:00 - 23:00',
                    'Minggu' => '10:00 - 23:00',
                ],
                'services' => [
                    'Hot Stone Massage',
                    'Balinese Massage',
                    'Reflexology'
                ],
                'is_open' => true,
            ],
            [
                'nama' => 'Serenity Spa Jogja',
                'alamat' => 'Jl. Kaliurang Km 7, Sleman, Yogyakarta',
                'noHP' => '0274-666888',
                'image' => 'image/spa3.jpg',
                'maps' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3953.0!2d110.375!3d-7.765!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a59f7bb5a8c9d%3A0x8f4e5d6a7b8c9e0f!2sKaliurang%20Yogyakarta!5e0!3m2!1sen!2sid!4v1620000000000!5m2!1sen!2sid',
                'waktuBuka' => [
                    'Senin' => '08:00 - 20:00',
                    'Selasa' => '08:00 - 20:00',
                    'Rabu' => '08:00 - 20:00',
                    'Kamis' => '08:00 - 20:00',
                    'Jumat' => '08:00 - 20:00',
                    'Sabtu' => '08:00 - 21:00',
                    'Minggu' => '08:00 - 21:00',
                ],
                'services' => [
                    'Deep Tissue Massage',
                    'Swedish Massage',
                    'Couples Massage'
                ],
                'is_open' => true,
            ]
        ];

        foreach ($spas as $data) {
            // Upsert by unique name
            $spa = Spa::updateOrCreate(
                ['nama' => $data['nama']],
                $data
            );

            // Ensure detail exists
            SpaDetail::updateOrCreate(
                ['spa_id' => $spa->id_spa],
                [
                    'about_spa' => $spa->nama . ' menghadirkan pengalaman spa terbaik dengan layanan profesional dan suasana yang menenangkan.',
                    'facilities' => [
                        'Private Treatment Rooms',
                        'Couple Room',
                        'Steam Room',
                        'Relaxation Lounge',
                        'Free Parking',
                        'Air Conditioning'
                    ],
                    'contact_person_name' => 'Customer Service',
                    'contact_person_phone' => $spa->noHP,
                    'gallery_images' => [
                        $spa->image,
                        'image/spa-interior.jpg',
                        'image/spa-room.jpg'
                    ]
                ]
            );
        }
    }
}
