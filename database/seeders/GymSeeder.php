<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Gym;
use App\Models\GymDetail;

class GymSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gymData = [
            [
                'nama' => 'Fitness First Jogja City Mall',
                'alamat' => 'Jl. Magelang No.6, Yogyakarta',
                'services' => [
                    'Cardio Equipment',
                    'Weight Training',
                    'Group Classes',
                    'Personal Training',
                    'Swimming Pool',
                    'Sauna'
                ],
                'image' => 'images/gym-fitness-first.jpg',
                'is_open' => true,
                'detail' => [
                    'about_gym' => 'Fitness First adalah gym internasional dengan fasilitas lengkap dan kelas fitness terbaik di Yogyakarta. Dilengkapi dengan peralatan modern dan instruktur berpengalaman.',
                    'facilities' => [
                        'Cardio Machines',
                        'Free Weights',
                        'Functional Training Area',
                        'Group Exercise Studio',
                        'Swimming Pool',
                        'Sauna & Steam Room',
                        'Locker Room',
                        'Parking Area'
                    ],
                    'opening_hours' => [
                        'Senin' => '06:00 - 22:00',
                        'Selasa' => '06:00 - 22:00',
                        'Rabu' => '06:00 - 22:00',
                        'Kamis' => '06:00 - 22:00',
                        'Jumat' => '06:00 - 22:00',
                        'Sabtu' => '07:00 - 21:00',
                        'Minggu' => '07:00 - 21:00',
                    ],
                    'contact_person_name' => 'Budi Santoso',
                    'contact_person_phone' => '0274-560123',
                    'location_maps' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3952.8739623293773!2d110.36588347503846!3d-7.801194392221735!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a578b54a66b31%3A0xf92a735bf5b0b5e8!2sJogja%20City%20Mall!5e0!3m2!1sen!2sid!4v1620000000000!5m2!1sen!2sid" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>'
                ]
            ],
            [
                'nama' => 'Celebrity Fitness Malioboro Mall',
                'alamat' => 'Jl. Malioboro No.52-58, Sosromenduran, Gedong Tengen, Yogyakarta',
                'services' => [
                    'Cardio Zone',
                    'Strength Training',
                    'Zumba Classes',
                    'Yoga Classes',
                    'Personal Training',
                    'Spinning Classes'
                ],
                'image' => 'images/gym-celebrity.jpg',
                'is_open' => true,
                'detail' => [
                    'about_gym' => 'Celebrity Fitness adalah salah satu gym chain terbesar di Indonesia yang berlokasi strategis di jantung Malioboro. Menawarkan program fitness yang variatif dengan suasana yang energik.',
                    'facilities' => [
                        'Latest Cardio Equipment',
                        'Free Weight Area',
                        'Group Exercise Studio',
                        'Spinning Studio',
                        'Stretching Area',
                        'Changing Rooms',
                        'Juice Bar',
                        'Free Parking'
                    ],
                    'opening_hours' => [
                        'Senin' => '06:00 - 23:00',
                        'Selasa' => '06:00 - 23:00',
                        'Rabu' => '06:00 - 23:00',
                        'Kamis' => '06:00 - 23:00',
                        'Jumat' => '06:00 - 23:00',
                        'Sabtu' => '07:00 - 22:00',
                        'Minggu' => '07:00 - 22:00',
                    ],
                    'contact_person_name' => 'Sari Indrawati',
                    'contact_person_phone' => '0274-561234',
                    'location_maps' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3952.8934773938677!2d110.36442707503845!3d-7.800682792222105!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a57f3aa4c8b3d%3A0x9c8b5f3a3e9d8e7c!2sMalioboro%20Mall!5e0!3m2!1sen!2sid!4v1620000000000!5m2!1sen!2sid" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>'
                ]
            ],
            [
                'nama' => 'Gold\'s Gym Yogyakarta',
                'alamat' => 'Jl. Babarsari No.2, Depok, Sleman, Yogyakarta',
                'services' => [
                    'Olympic Lifting',
                    'Powerlifting',
                    'Functional Training',
                    'HIIT Classes',
                    'Boxing Classes',
                    'Recovery Zone'
                ],
                'image' => 'images/gym-golds.jpg',
                'is_open' => true,
                'detail' => [
                    'about_gym' => 'Gold\'s Gym adalah legendary gym brand yang sudah terkenal sejak tahun 1965. Memberikan pengalaman fitness premium dengan equipment berkualitas tinggi dan community yang solid.',
                    'facilities' => [
                        'Olympic Weightlifting Platform',
                        'Heavy Duty Equipment',
                        'Functional Training Rig',
                        'Boxing Ring',
                        'Recovery Zone',
                        'Supplement Store',
                        'Personal Training Studio',
                        'Ample Parking'
                    ],
                    'opening_hours' => [
                        'Senin' => '05:00 - 22:00',
                        'Selasa' => '05:00 - 22:00',
                        'Rabu' => '05:00 - 22:00',
                        'Kamis' => '05:00 - 22:00',
                        'Jumat' => '05:00 - 22:00',
                        'Sabtu' => '06:00 - 21:00',
                        'Minggu' => '06:00 - 21:00',
                    ],
                    'contact_person_name' => 'Agus Prasetyo',
                    'contact_person_phone' => '0274-562345',
                    'location_maps' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3953.2847639384737!2d110.40472607503809!3d-7.774839692251485!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a59c79b5a7b8f%3A0x5e4f2b3c8d9a6b7c!2sBabarsari%2C%20Caturtunggal%2C%20Kec.%20Depok%2C%20Kabupaten%20Sleman%2C%20Daerah%20Istimewa%20Yogyakarta!5e0!3m2!1sen!2sid!4v1620000000000!5m2!1sen!2sid" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>'
                ]
            ],
            [
                'nama' => 'Iron Gym Yogyakarta',
                'alamat' => 'Jl. C. Simanjuntak No.70, Yogyakarta',
                'services' => [
                    'Bodybuilding',
                    'Powerlifting',
                    'Crossfit',
                    'MMA Training',
                    'Personal Training',
                    'Nutrition Consultation'
                ],
                'image' => 'images/gym-iron.jpg',
                'is_open' => true,
                'detail' => [
                    'about_gym' => 'Iron Gym adalah pilihan utama untuk serious lifters di Yogyakarta. Fokus pada strength training dengan atmosfer hardcore yang mendukung untuk mencapai target fitness maximum.',
                    'facilities' => [
                        'Heavy Duty Machines',
                        'Free Weight Section',
                        'Powerlifting Platform',
                        'Crossfit Box',
                        'MMA Training Area',
                        'Posing Room',
                        'Supplement Corner',
                        'Secure Parking'
                    ],
                    'opening_hours' => [
                        'Senin' => '05:30 - 21:30',
                        'Selasa' => '05:30 - 21:30',
                        'Rabu' => '05:30 - 21:30',
                        'Kamis' => '05:30 - 21:30',
                        'Jumat' => '05:30 - 21:30',
                        'Sabtu' => '06:00 - 21:00',
                        'Minggu' => '07:00 - 20:00',
                    ],
                    'contact_person_name' => 'Roni Setiawan',
                    'contact_person_phone' => '0274-563456',
                    'location_maps' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3952.641847383847!2d110.38472607503855!3d-7.810839392213384!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a574c4b5a8c9d%3A0x7f3e4d5a6b7c8e9f!2sJl.%20C.%20Simanjuntak%2C%20Yogyakarta!5e0!3m2!1sen!2sid!4v1620000000000!5m2!1sen!2sid" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>'
                ]
            ],
            [
                'nama' => 'FIT Station Yogyakarta',
                'alamat' => 'Jl. Kaliurang Km 5, Sleman, Yogyakarta',
                'services' => [
                    'Fitness Classes',
                    'Dance Fitness',
                    'TRX Training',
                    'Pilates',
                    'Bootcamp',
                    'Kids Fitness'
                ],
                'image' => 'images/gym-fitstation.jpg',
                'is_open' => true,
                'detail' => [
                    'about_gym' => 'FIT Station menawarkan konsep fitness yang fun dan modern dengan berbagai pilihan kelas group exercise. Cocok untuk semua level dari pemula hingga advanced.',
                    'facilities' => [
                        'Modern Group Studio',
                        'TRX Suspension Area',
                        'Dance Studio',
                        'Pilates Equipment',
                        'Kids Play Area',
                        'Healthy Cafe',
                        'Outdoor Training Space',
                        'Free WiFi'
                    ],
                    'opening_hours' => [
                        'Senin' => '06:00 - 21:00',
                        'Selasa' => '06:00 - 21:00',
                        'Rabu' => '06:00 - 21:00',
                        'Kamis' => '06:00 - 21:00',
                        'Jumat' => '06:00 - 21:00',
                        'Sabtu' => '07:00 - 20:00',
                        'Minggu' => '07:00 - 20:00',
                    ],
                    'contact_person_name' => 'Maya Kusuma',
                    'contact_person_phone' => '0274-564567',
                    'location_maps' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3953.7384738473847!2d110.39472607503795!3d-7.754839692271485!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a59f7bb5a8c9d%3A0x8f4e5d6a7b8c9e0f!2sJl.%20Kaliurang%2C%20Yogyakarta!5e0!3m2!1sen!2sid!4v1620000000000!5m2!1sen!2sid" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>'
                ]
            ]
        ];

        foreach ($gymData as $data) {
            $detailData = $data['detail'];
            unset($data['detail']);

            $gym = Gym::create($data);

            // Create gym detail
            GymDetail::create([
                'gym_id' => $gym->id_gym,
                'about_gym' => $detailData['about_gym'],
                'facilities' => $detailData['facilities'],
                'opening_hours' => $detailData['opening_hours'],
                'contact_person_name' => $detailData['contact_person_name'],
                'contact_person_phone' => $detailData['contact_person_phone'],
                'location_maps' => $detailData['location_maps'],
                'gallery_images' => [
                    $data['image'],
                    'images/gym-equipment-1.jpg',
                    'images/gym-equipment-2.jpg',
                    'images/gym-class-1.jpg',
                    'images/gym-facility-1.jpg'
                ]
            ]);
        }
    }
}
