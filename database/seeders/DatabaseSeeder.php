<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        if (User::where('email', 'admin@vitalife.com')->doesntExist()) {
            User::factory()->create([
                'name' => 'Admin Vitalife',
                'email' => 'admin@vitalife.com',
                'password' => bcrypt('admin123'),
                'role' => 'admin',
            ]);
        }

        // Create demo user
        if (User::where('email', 'demo@vitalife.web.id')->doesntExist()) {
            User::factory()->create([
                'name' => 'Demo User',
                'email' => 'demo@vitalife.web.id',
                'password' => bcrypt('password'),
                'role' => 'user',
            ]);
        }

        // Run spa seeder manually to avoid autoload issues
        $this->runSpaSeeder();
        $this->runGymSeeder();
        $this->runYogaSeeder();
        $this->runVoucherSeeder();
    }

    private function runSpaSeeder()
    {
        // Create multiple spa entries for better data
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

        foreach ($spas as $spaData) {
            $spa = \App\Models\Spa::create($spaData);

            // Create spa detail
            \App\Models\SpaDetail::create([
                'spa_id' => $spa->id_spa,
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
            ]);

            // Create spa services
            $services = [
                [
                    'name' => 'Traditional Massage',
                    'description' => 'Pijat tradisional untuk relaksasi dan kesehatan',
                    'price' => 200000,
                    'duration' => '60 menit',
                    'category' => 'massage',
                    'image' => 'image/spa-traditional-massage.jpg',
                    'is_active' => true,
                ],
                [
                    'name' => 'Aromatherapy',
                    'description' => 'Terapi aromaterapi dengan essential oil pilihan',
                    'price' => 300000,
                    'duration' => '90 menit',
                    'category' => 'therapy',
                    'image' => 'image/spa-aromatherapy.jpg',
                    'is_active' => true,
                ],
                [
                    'name' => 'Hot Stone Massage',
                    'description' => 'Pijat dengan batu panas untuk relaksasi mendalam',
                    'price' => 350000,
                    'duration' => '75 menit',
                    'category' => 'massage',
                    'image' => 'image/spa-hot-stone.jpg',
                    'is_active' => true,
                ],
                [
                    'name' => 'Body Scrub & Wrap',
                    'description' => 'Perawatan tubuh lengkap dengan scrub dan body wrap',
                    'price' => 400000,
                    'duration' => '90 menit',
                    'category' => 'treatment',
                    'image' => 'image/spa-body-scrub.jpg',
                    'is_active' => true,
                ]
            ];

            foreach ($services as $serviceData) {
                $serviceData['spa_id'] = $spa->id_spa;
                \App\Models\SpaService::create($serviceData);
            }
        }
    }

    private function runGymSeeder()
    {
        // Create multiple gym entries
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
                'nama' => 'Gold\'s Gym Yogyakarta',
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

        foreach ($gyms as $gymData) {
            $gym = \App\Models\Gym::create($gymData);

            // Create gym detail
            \App\Models\GymDetail::create([
                'gym_id' => $gym->id_gym,
                'about_gym' => $gym->description,
                'facilities' => array_merge($gymData['services'], $gymData['fasilitas']),
                'opening_hours' => [
                    'Senin' => '06:00 - 22:00',
                    'Selasa' => '06:00 - 22:00',
                    'Rabu' => '06:00 - 22:00',
                    'Kamis' => '06:00 - 22:00',
                    'Jumat' => '06:00 - 22:00',
                    'Sabtu' => '07:00 - 21:00',
                    'Minggu' => '07:00 - 21:00',
                ],
                'contact_person_name' => $gym->contact_person,
                'contact_person_phone' => $gym->contact_phone,
                'gallery_images' => [
                    $gym->image,
                    'image/gym-interior.jpg',
                    'image/gym-equipment.jpg'
                ]
            ]);

            // Create gym services
            $services = [
                [
                    'name' => 'Personal Training Session',
                    'description' => 'Sesi latihan personal dengan instruktur berpengalaman',
                    'price' => 350000,
                    'duration' => '60 menit',
                    'category' => 'personal_training',
                    'image' => 'image/gym-personal-training.jpg',
                    'is_active' => true,
                ],
                [
                    'name' => 'Group Class',
                    'description' => 'Kelas kelompok dengan berbagai pilihan workout',
                    'price' => 100000,
                    'duration' => '45 menit',
                    'category' => 'group_class',
                    'image' => 'image/gym-group.jpg',
                    'is_active' => true,
                ],
                [
                    'name' => 'Cardio Training',
                    'description' => 'Program latihan kardio untuk kesehatan jantung',
                    'price' => 200000,
                    'duration' => '60 menit',
                    'category' => 'cardio',
                    'image' => 'image/gym-cardio.jpg',
                    'is_active' => true,
                ],
                [
                    'name' => 'Weight Training',
                    'description' => 'Program latihan beban untuk pembentukan otot',
                    'price' => 250000,
                    'duration' => '75 menit',
                    'category' => 'strength',
                    'image' => 'image/gym-weight-training.jpg',
                    'is_active' => true,
                ]
            ];

            foreach ($services as $serviceData) {
                $serviceData['gym_id'] = $gym->id_gym;
                \App\Models\GymService::create($serviceData);
            }
        }
    }

    private function runYogaSeeder()
    {
        // Create multiple yoga entries
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

        foreach ($yogas as $yogaData) {
            $yoga = \App\Models\Yoga::create($yogaData);

            // Create yoga detail config
            \App\Models\YogaDetailConfig::create([
                'yoga_id' => $yoga->id_yoga,
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
                'contact_person_name' => 'Yoga Instructor',
                'contact_person_phone' => $yoga->noHP,
                'gallery_images' => [
                    $yoga->image,
                    'image/yoga-studio.jpg',
                    'image/yoga-meditation.jpg'
                ],
                'theme_color' => '#9B59B6',
                'layout_style' => 'default'
            ]);

            // Create yoga services
            $services = [
                [
                    'name' => 'Hatha Yoga Class',
                    'description' => 'Kelas yoga dasar dengan fokus pada postur dan pernapasan',
                    'price' => $yoga->harga,
                    'duration' => '75 menit',
                    'category' => 'group_class',
                    'image' => 'image/yoga-hatha.jpg',
                    'is_active' => true,
                ],
                [
                    'name' => 'Vinyasa Flow',
                    'description' => 'Kelas yoga dinamis dengan gerakan yang mengalir',
                    'price' => $yoga->harga + 30000,
                    'duration' => '90 menit',
                    'category' => 'group_class',
                    'image' => 'image/yoga-vinyasa.jpg',
                    'is_active' => true,
                ],
                [
                    'name' => 'Yin Yoga',
                    'description' => 'Yoga restoratif dengan pose yang ditahan lama',
                    'price' => $yoga->harga + 20000,
                    'duration' => '60 menit',
                    'category' => 'restorative',
                    'image' => 'image/yoga-yin.jpg',
                    'is_active' => true,
                ],
                [
                    'name' => 'Meditation Class',
                    'description' => 'Kelas meditasi untuk ketenangan pikiran',
                    'price' => $yoga->harga - 50000,
                    'duration' => '45 menit',
                    'category' => 'meditation',
                    'image' => 'image/yoga-meditation-class.jpg',
                    'is_active' => true,
                ]
            ];

            foreach ($services as $serviceData) {
                $serviceData['yoga_id'] = $yoga->id_yoga;
                \App\Models\YogaService::create($serviceData);
            }
        }
    }

    private function runVoucherSeeder()
    {
        // Create sample vouchers
        $vouchers = [
            [
                'code' => 'WELLNESS50',
                'description' => 'Diskon 50% untuk semua layanan spa dan yoga',
                'discount_type' => 'percentage',
                'discount_percentage' => 50,
                'discount_amount' => null,
                'usage_count' => 0,
                'usage_limit' => 100,
                'is_used' => false,
                'expired_at' => now()->addMonths(3),
            ],
            [
                'code' => 'SPA30',
                'description' => 'Diskon 30% khusus untuk layanan spa',
                'discount_type' => 'percentage',
                'discount_percentage' => 30,
                'discount_amount' => null,
                'usage_count' => 0,
                'usage_limit' => 50,
                'is_used' => false,
                'expired_at' => now()->addMonths(2),
            ],
            [
                'code' => 'YOGA25',
                'description' => 'Diskon 25% untuk kelas yoga bagi pemula',
                'discount_type' => 'percentage',
                'discount_percentage' => 25,
                'discount_amount' => null,
                'usage_count' => 0,
                'usage_limit' => 75,
                'is_used' => false,
                'expired_at' => now()->addMonths(1),
            ],
            [
                'code' => 'GYM20',
                'description' => 'Diskon 20% untuk membership gym',
                'discount_type' => 'percentage',
                'discount_percentage' => 20,
                'discount_amount' => null,
                'usage_count' => 0,
                'usage_limit' => 30,
                'is_used' => false,
                'expired_at' => now()->addMonths(2),
            ],
            [
                'code' => 'SAVE100K',
                'description' => 'Potongan langsung Rp 100.000 untuk transaksi minimal Rp 500.000',
                'discount_type' => 'fixed',
                'discount_percentage' => null,
                'discount_amount' => 100000,
                'usage_count' => 0,
                'usage_limit' => 25,
                'is_used' => false,
                'expired_at' => now()->addMonths(2),
            ]
        ];

        foreach ($vouchers as $voucherData) {
            \App\Models\Voucher::create($voucherData);
        }
    }
}
