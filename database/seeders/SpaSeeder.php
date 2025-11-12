<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Spa;
use App\Models\SpaDetail;
use Illuminate\Support\Facades\Cache;

class SpaSeeder extends Seeder
{
    public function run(): void
    {
        $spas = [
            [
                'nama' => 'Martha Tilaar Salon Day Spa Yogyakarta',
                'alamat' => 'Jl. Laksda Adisucipto No.32-34, Demangan, Depok, Sleman',
                'noHP' => '0274-485888',
                'image' => 'image/spa1.jpg',
                'maps' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3953.040485614967!2d110.38697607503788!3d-7.7769089922597135!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a59bdd7b8c5f5%3A0x3e3f9c8a0d6d6f9a!2sMartha%20Tilaar%20Salon%20Day%20Spa!5e0!3m2!1sen!2sid!4v1699000000000!5m2!1sen!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>',
                'waktuBuka' => [
                    'Senin' => '09:00 - 21:00',
                    'Selasa' => '09:00 - 21:00',
                    'Rabu' => '09:00 - 21:00',
                    'Kamis' => '09:00 - 21:00',
                    'Jumat' => '09:00 - 21:00',
                    'Sabtu' => '09:00 - 21:00',
                    'Minggu' => '09:00 - 21:00',
                ],
                'services' => [
                    [
                        'name' => 'Traditional Javanese Massage',
                        'description' => 'Experience authentic Javanese massage technique to relieve muscle tension and improve circulation',
                        'price' => 180000,
                        'image' => 'image/spa-service1.jpg'
                    ],
                    [
                        'name' => 'Royal Heritage Treatment',
                        'description' => 'Luxurious treatment inspired by royal heritage with premium ingredients',
                        'price' => 250000,
                        'image' => 'image/spa-service2.jpg'
                    ],
                    [
                        'name' => 'Aromatherapy Massage',
                        'description' => 'Relaxing massage with essential oils for body and mind wellness',
                        'price' => 200000,
                        'image' => 'image/spa-service3.jpg'
                    ]
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

        // Clear spa cache after seeding
        Cache::forget('spas.all');
    }
}
