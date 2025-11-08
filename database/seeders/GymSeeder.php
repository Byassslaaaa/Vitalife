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
                    'Cardio Equipment',
                    'Weight Training',
                    'Group Classes',
                    'Spinning Class'
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
            ],
            [
                'nama' => 'Fitness First Jogja City Mall',
                'alamat' => 'Jogja City Mall Lt. 3, Jl. Magelang KM 6, Sinduadi, Mlati, Sleman',
                'services' => [
                    'Strength Training',
                    'Cardio Workout',
                    'Functional Training',
                    'Yoga Class',
                    'Zumba'
                ],
                'fasilitas' => [
                    'Swimming Pool',
                    'Modern Equipment',
                    'Group Classes',
                    'Personal Training',
                    'Nutritionist Consultation',
                    'Sauna'
                ],
                'description' => 'Fitness First merupakan gym internasional dengan standar kelas dunia. Menyediakan berbagai kelas fitness dan personal training.',
                'contact_person' => 'Customer Service',
                'contact_phone' => '0274-625777',
                'image' => 'image/gym2.jpg',
                'maps' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3953.1567374384746!2d110.36394607503795!3d-7.775347592258735!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a59bbbbbbbbbb%3A0x7f8e9d6c5b4a3e2f!2sFitness%20First%20JCM!5e0!3m2!1sen!2sid!4v1699000000000!5m2!1sen!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>',
                'is_open' => true,
            ],
            [
                'nama' => 'Spartans Gym Yogyakarta',
                'alamat' => 'Jl. Magelang No.153, Karangwaru, Tegalrejo, Yogyakarta',
                'services' => [
                    'Weight Training',
                    'Cardio Training',
                    'CrossFit',
                    'Boxing Class'
                ],
                'fasilitas' => [
                    'Professional Equipment',
                    'Personal Training',
                    'Free Consultation',
                    'AC & Sound System'
                ],
                'description' => 'Spartans Gym adalah gym lokal dengan fasilitas yang memadai dan harga terjangkau. Cocok untuk semua level, dari pemula hingga profesional.',
                'contact_person' => 'Front Desk',
                'contact_phone' => '0812-2345-6789',
                'image' => 'image/gym3.jpg',
                'maps' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3953.0889384738473!2d110.36172207503796!3d-7.7762349922590135!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a59aaaaaaaaa1%3A0x6e7f8d5c4b3a2e1f!2sSpartans%20Gym!5e0!3m2!1sen!2sid!4v1699000000000!5m2!1sen!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>',
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
