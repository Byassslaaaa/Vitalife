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
                    'Traditional Javanese Massage',
                    'Royal Heritage Treatment',
                    'Aromatherapy Massage'
                ],
                'is_open' => true,
            ],
            [
                'nama' => 'Glo Day Spa & Salon Yogyakarta',
                'alamat' => 'Jl. Janti No.166, Karangjambe, Banguntapan, Bantul',
                'noHP' => '0274-454545',
                'image' => 'image/spa2.jpg',
                'maps' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3952.6715043254846!2d110.40764067503852!3d-7.807947592227576!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a5755d5e8e707%3A0x8f5e7d6c9b8a7f6e!2sGlo%20Day%20Spa%20%26%20Salon!5e0!3m2!1sen!2sid!4v1699000000000!5m2!1sen!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>',
                'waktuBuka' => [
                    'Senin' => '10:00 - 22:00',
                    'Selasa' => '10:00 - 22:00',
                    'Rabu' => '10:00 - 22:00',
                    'Kamis' => '10:00 - 22:00',
                    'Jumat' => '10:00 - 22:00',
                    'Sabtu' => '10:00 - 22:00',
                    'Minggu' => '10:00 - 22:00',
                ],
                'services' => [
                    'Hot Stone Massage',
                    'Balinese Massage',
                    'Reflexology'
                ],
                'is_open' => true,
            ],
            [
                'nama' => 'Naavagreen Spa Yogyakarta',
                'alamat' => 'Jl. Kaliurang Km 5.2, Caturtunggal, Depok, Sleman',
                'noHP' => '0274-882323',
                'image' => 'image/spa3.jpg',
                'maps' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3953.584738473847!2d110.39472607503795!3d-7.764839692261485!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a59bcce5c5c5d%3A0x7f8e9d7c8b9a6e5f!2sNaavagreen%20Spa!5e0!3m2!1sen!2sid!4v1699000000000!5m2!1sen!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>',
                'waktuBuka' => [
                    'Senin' => '10:00 - 21:00',
                    'Selasa' => '10:00 - 21:00',
                    'Rabu' => '10:00 - 21:00',
                    'Kamis' => '10:00 - 21:00',
                    'Jumat' => '10:00 - 21:00',
                    'Sabtu' => '10:00 - 21:00',
                    'Minggu' => '10:00 - 21:00',
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
