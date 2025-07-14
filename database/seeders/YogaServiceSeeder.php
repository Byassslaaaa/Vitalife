<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Yoga;
use App\Models\YogaService;

class YogaServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all yoga entries to add services
        $yogas = Yoga::all();

        $serviceCategories = [
            'Hatha Yoga' => [
                ['name' => 'Hatha Yoga Basic', 'description' => 'Kelas yoga dasar dengan pose-pose fundamental dan teknik pernapasan', 'price' => 150000, 'duration' => '60 menit'],
                ['name' => 'Hatha Yoga Intermediate', 'description' => 'Kelas yoga menengah dengan pose yang lebih menantang', 'price' => 200000, 'duration' => '75 menit'],
                ['name' => 'Gentle Hatha', 'description' => 'Hatha yoga lembut untuk pemula dan senior', 'price' => 125000, 'duration' => '60 menit'],
            ],
            'Vinyasa Flow' => [
                ['name' => 'Vinyasa Flow Basic', 'description' => 'Gerakan yoga yang mengalir dengan pernapasan', 'price' => 175000, 'duration' => '60 menit'],
                ['name' => 'Power Vinyasa', 'description' => 'Vinyasa dengan intensitas tinggi untuk kekuatan dan stamina', 'price' => 225000, 'duration' => '75 menit'],
                ['name' => 'Slow Flow Yoga', 'description' => 'Vinyasa dengan tempo lambat untuk mindfulness', 'price' => 185000, 'duration' => '75 menit'],
            ],
            'Yin Yoga' => [
                ['name' => 'Yin Yoga Gentle', 'description' => 'Yoga restoratif dengan pose yang lembut dan santai', 'price' => 160000, 'duration' => '75 menit'],
                ['name' => 'Yin Yang Yoga', 'description' => 'Kombinasi yin dan yang untuk keseimbangan energi', 'price' => 190000, 'duration' => '90 menit'],
                ['name' => 'Candlelight Yin', 'description' => 'Yin yoga dengan suasana lilin untuk relaksasi maksimal', 'price' => 200000, 'duration' => '90 menit'],
            ],
            'Pranayama & Meditation' => [
                ['name' => 'Pranayama Breathing', 'description' => 'Latihan teknik pernapasan untuk kontrol nafas dan energi', 'price' => 135000, 'duration' => '45 menit'],
                ['name' => 'Meditation Class', 'description' => 'Kelas meditasi untuk ketenangan pikiran dan kesadaran', 'price' => 120000, 'duration' => '45 menit'],
                ['name' => 'Sound Healing Meditation', 'description' => 'Meditasi dengan singing bowl dan sound therapy', 'price' => 180000, 'duration' => '60 menit'],
            ],
            'Specialty Classes' => [
                ['name' => 'Hot Yoga Class', 'description' => 'Yoga dalam ruangan bersuhu tinggi untuk detoksifikasi', 'price' => 250000, 'duration' => '75 menit'],
                ['name' => 'Prenatal Yoga', 'description' => 'Yoga khusus untuk ibu hamil dengan gerakan aman', 'price' => 200000, 'duration' => '60 menit'],
                ['name' => 'Kids Yoga Fun', 'description' => 'Yoga menyenangkan untuk anak-anak usia 5-12 tahun', 'price' => 100000, 'duration' => '45 menit'],
            ]
        ];

        foreach ($yogas as $index => $yoga) {
            $categories = array_keys($serviceCategories);

            // Each yoga studio gets 2-3 categories of services
            $numCategories = rand(2, 3);
            $selectedCategories = [];

            // Distribute categories based on studio positioning
            switch ($index) {
                case 0: // Yoga Barn - Focus on Hatha and Vinyasa
                    $selectedCategories = ['Hatha Yoga', 'Vinyasa Flow'];
                    break;
                case 1: // Shanti - Focus on Traditional and Meditation
                    $selectedCategories = ['Hatha Yoga', 'Pranayama & Meditation'];
                    break;
                case 2: // Surya Namaskara - Focus on Flow and Yin
                    $selectedCategories = ['Vinyasa Flow', 'Yin Yoga'];
                    break;
                case 3: // Mandala - Focus on Meditation and Specialty
                    $selectedCategories = ['Pranayama & Meditation', 'Specialty Classes', 'Yin Yoga'];
                    break;
                case 4: // Harmony - Mix of all basic styles
                    $selectedCategories = ['Hatha Yoga', 'Vinyasa Flow', 'Yin Yoga'];
                    break;
                default:
                    // For any additional studios
                    $selectedCategories = array_slice($categories, $index % count($categories), $numCategories);
                    break;
            }

            foreach ($selectedCategories as $category) {
                $services = $serviceCategories[$category];

                // Pick 2-3 services from each category
                $numServices = rand(2, 3);
                $selectedServices = array_slice($services, 0, $numServices);

                foreach ($selectedServices as $service) {
                    YogaService::create([
                        'yoga_id' => $yoga->id_yoga,
                        'name' => $service['name'],
                        'description' => $service['description'],
                        'price' => $service['price'],
                        'duration' => $service['duration'],
                        'category' => $category,
                        'is_active' => true,
                    ]);
                }
            }
        }
    }
}
