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
    }

    private function runSpaSeeder()
    {
        // Create single spa entry
        $spa = \App\Models\Spa::create([
            'nama' => 'Royal Heritage Spa Yogyakarta',
            'alamat' => 'Jl. Prawirotaman II No.15, Mergangsan, Yogyakarta',
            'noHP' => '0274-373511',
            'image' => 'images/spa/royal-heritage-spa.jpg',
            'maps' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3952.9473847384738!2d110.36588347503846!3d-7.796194392226735!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a578b54a66b31%3A0xf92a735bf5b0b5e8!2sPrawirotaman%2C%20Mergangsan%2C%20Kota%20Yogyakarta!5e0!3m2!1sen!2sid!4v1620000000000!5m2!1sen!2sid',
            'waktuBuka' => json_encode([
                'Senin' => '09:00 - 21:00',
                'Selasa' => '09:00 - 21:00',
                'Rabu' => '09:00 - 21:00',
                'Kamis' => '09:00 - 21:00',
                'Jumat' => '09:00 - 21:00',
                'Sabtu' => '09:00 - 22:00',
                'Minggu' => '09:00 - 22:00',
            ]),
            'services' => json_encode([
                'Traditional Javanese Massage',
                'Royal Heritage Signature Treatment',
                'Aromatherapy Massage',
                'Hot Stone Therapy',
                'Body Scrub & Wrap',
                'Facial Treatment'
            ]),
            'is_open' => true,
        ]);

        // Create spa detail
        \App\Models\SpaDetail::create([
            'spa_id' => $spa->id_spa,
            'about_spa' => 'Royal Heritage Spa menghadirkan pengalaman spa authentic dengan sentuhan budaya Jawa yang kental.',
            'facilities' => json_encode([
                'Private Treatment Rooms',
                'Couple Room',
                'Steam Room',
                'Relaxation Lounge',
                'Traditional Joglo Architecture',
                'Garden View'
            ]),
            'contact_person_name' => 'Mbak Sari Dewi',
            'contact_person_phone' => '0274-373511',
            'gallery_images' => json_encode([
                'images/spa/royal-heritage-spa.jpg',
                'images/spa/treatment-room.jpg',
                'images/spa/relaxation-area.jpg'
            ])
        ]);

        // Create spa service
        \App\Models\SpaService::create([
            'spa_id' => $spa->id_spa,
            'name' => 'Traditional Javanese Massage',
            'description' => 'Pijat tradisional Jawa menggunakan teknik kuno dengan minyak herbal',
            'price' => 250000,
            'duration' => '90 menit',
            'is_active' => true,
        ]);
    }

    private function runGymSeeder()
    {
        // Create single gym entry
        $gym = \App\Models\Gym::create([
            'nama' => 'Fitness First Jogja City Mall',
            'alamat' => 'Jl. Magelang No.6, Yogyakarta',
            'services' => json_encode([
                'Cardio Equipment',
                'Weight Training',
                'Group Classes',
                'Personal Training',
                'Swimming Pool',
                'Sauna'
            ]),
            'image' => 'images/gym/fitness-first-jogja.jpg',
            'is_open' => true,
        ]);

        // Create gym detail
        \App\Models\GymDetail::create([
            'gym_id' => $gym->id_gym,
            'about_gym' => 'Fitness First adalah gym internasional dengan fasilitas lengkap dan kelas fitness terbaik di Yogyakarta.',
            'facilities' => json_encode([
                'Cardio Machines',
                'Free Weights',
                'Functional Training Area',
                'Group Exercise Studio',
                'Swimming Pool',
                'Sauna & Steam Room'
            ]),
            'opening_hours' => json_encode([
                'Senin' => '06:00 - 22:00',
                'Selasa' => '06:00 - 22:00',
                'Rabu' => '06:00 - 22:00',
                'Kamis' => '06:00 - 22:00',
                'Jumat' => '06:00 - 22:00',
                'Sabtu' => '07:00 - 21:00',
                'Minggu' => '07:00 - 21:00',
            ]),
            'contact_person_name' => 'Mr. Andi Pratama',
            'contact_person_phone' => '0274-123456',
            'gallery_images' => json_encode([
                'images/gym/fitness-first-jogja.jpg',
                'images/gym/cardio-area.jpg',
                'images/gym/weight-training.jpg'
            ])
        ]);

        // Create gym service
        \App\Models\GymService::create([
            'gym_id' => $gym->id_gym,
            'name' => 'Personal Training Session',
            'description' => 'Sesi latihan personal dengan instruktur berpengalaman',
            'price' => 350000,
            'duration' => '60 menit',
            'category' => 'personal_training',
            'image' => 'images/gym/services/personal-training.jpg',
            'is_active' => true,
        ]);
    }

    private function runYogaSeeder()
    {
        // Create single yoga entry
        $yoga = \App\Models\Yoga::create([
            'nama' => 'Yoga Barn Yogyakarta',
            'harga' => 150000,
            'alamat' => 'Jl. Kaliurang Km 5.2, Sleman, Yogyakarta',
            'noHP' => '0274-881234',
            'waktuBuka' => json_encode([
                'Senin' => '06:00 - 21:00',
                'Selasa' => '06:00 - 21:00',
                'Rabu' => '06:00 - 21:00',
                'Kamis' => '06:00 - 21:00',
                'Jumat' => '06:00 - 21:00',
                'Sabtu' => '06:00 - 20:00',
                'Minggu' => '07:00 - 19:00',
            ]),
            'image' => 'images/yoga/yoga-barn-yogyakarta.jpg',
            'maps' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3953.584738473847!2d110.39472607503795!3d-7.764839692261485!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a59f7bb5a8c9d%3A0x8f4e5d6a7b8c9e0f!2sKaliurang%20Yogyakarta!5e0!3m2!1sen!2sid!4v1620000000000!5m2!1sen!2sid',
        ]);

        // Create yoga detail config
        \App\Models\YogaDetailConfig::create([
            'yoga_id' => $yoga->id_yoga,
            'hero_title' => 'Welcome to Yoga Barn Yogyakarta',
            'hero_subtitle' => 'Menggabungkan tradisi yoga kuno dengan suasana alam yang tenang di Kaliurang',
            'facilities' => json_encode([
                'Air Conditioned Studio',
                'Yoga Props Available',
                'Meditation Corner',
                'Herbal Tea Corner',
                'Changing Room',
                'Free Parking'
            ]),
            'contact_person_name' => 'Ibu Sari Yoga Master',
            'contact_person_phone' => '0274-881234',
            'gallery_images' => json_encode([
                'images/yoga/yoga-barn-yogyakarta.jpg',
                'images/yoga/studio-interior.jpg',
                'images/yoga/meditation-corner.jpg'
            ]),
            'theme_color' => '#9B59B6',
            'layout_style' => 'default'
        ]);

        // Create yoga service
        \App\Models\YogaService::create([
            'yoga_id' => $yoga->id_yoga,
            'name' => 'Hatha Yoga Class',
            'description' => 'Kelas yoga dasar dengan fokus pada postur dan pernapasan',
            'price' => 150000,
            'duration' => '75 menit',
            'category' => 'group_class',
            'is_active' => true,
        ]);
    }
}
