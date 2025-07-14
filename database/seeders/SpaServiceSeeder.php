<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Spa;
use App\Models\SpaService;

class SpaServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $spas = Spa::all();

        $servicesByCategory = [
            'Traditional Massage' => [
                ['name' => 'Javanese Traditional Massage', 'description' => 'Pijat tradisional Jawa dengan teknik turun-temurun untuk relaksasi dan kesehatan', 'price' => 150000, 'duration' => '90 menit'],
                ['name' => 'Royal Lulur Package', 'description' => 'Paket lulur kerajaan dengan rempah-rempah pilihan dan masker tradisional', 'price' => 350000, 'duration' => '150 menit'],
                ['name' => 'Jamu Body Treatment', 'description' => 'Treatment menggunakan jamu tradisional untuk detoksifikasi tubuh', 'price' => 250000, 'duration' => '120 menit'],
                ['name' => 'Traditional Reflexology', 'description' => 'Pijat refleksi kaki tradisional untuk melancarkan peredaran darah', 'price' => 120000, 'duration' => '60 menit'],
            ],
            'Modern Spa Treatment' => [
                ['name' => 'Hot Stone Massage', 'description' => 'Terapi batu panas untuk relaksasi otot dan melancarkan energi', 'price' => 300000, 'duration' => '90 menit'],
                ['name' => 'Aromatherapy Massage', 'description' => 'Pijat dengan essential oil pilihan untuk ketenangan pikiran', 'price' => 275000, 'duration' => '75 menit'],
                ['name' => 'Deep Tissue Massage', 'description' => 'Pijat dalam untuk mengatasi ketegangan otot dan stress', 'price' => 325000, 'duration' => '90 menit'],
                ['name' => 'Swedish Massage', 'description' => 'Teknik pijat Swedia untuk relaksasi dan sirkulasi darah', 'price' => 280000, 'duration' => '75 menit'],
            ],
            'Body Treatment' => [
                ['name' => 'Volcanic Clay Body Wrap', 'description' => 'Body wrap dengan tanah liat vulkanik untuk detoksifikasi kulit', 'price' => 400000, 'duration' => '120 menit'],
                ['name' => 'Coffee Scrub Treatment', 'description' => 'Scrub kopi untuk mengangkat sel kulit mati dan nutrisi kulit', 'price' => 200000, 'duration' => '60 menit'],
                ['name' => 'Milk & Honey Bath', 'description' => 'Mandi susu dan madu untuk kelembutan dan nutrisi kulit', 'price' => 180000, 'duration' => '45 menit'],
                ['name' => 'Herbal Steam Bath', 'description' => 'Mandi uap dengan ramuan herbal untuk membuka pori-pori', 'price' => 150000, 'duration' => '30 menit'],
            ],
            'Facial Treatment' => [
                ['name' => 'Royal Gold Facial', 'description' => 'Facial mewah dengan gold serum anti-aging dan firming', 'price' => 500000, 'duration' => '90 menit'],
                ['name' => 'Organic Herbal Facial', 'description' => 'Facial dengan bahan organik alami untuk kulit sensitif', 'price' => 300000, 'duration' => '75 menit'],
                ['name' => 'Acne Treatment Facial', 'description' => 'Perawatan khusus untuk kulit berjerawat dan berminyak', 'price' => 250000, 'duration' => '60 menit'],
                ['name' => 'Brightening Vitamin C Facial', 'description' => 'Facial vitamin C untuk mencerahkan dan meratakan warna kulit', 'price' => 350000, 'duration' => '75 menit'],
            ],
            'Couple Package' => [
                ['name' => 'Romantic Couple Massage', 'description' => 'Pijat romantis untuk pasangan di ruang couple eksklusif', 'price' => 600000, 'duration' => '90 menit'],
                ['name' => 'Honeymoon Spa Package', 'description' => 'Paket spa bulan madu dengan treatment mewah dan romantic dinner', 'price' => 1200000, 'duration' => '4 jam'],
                ['name' => 'Couple Detox Treatment', 'description' => 'Program detox bersama pasangan dengan body wrap dan facial', 'price' => 800000, 'duration' => '150 menit'],
            ],
            'Wellness Therapy' => [
                ['name' => 'Chakra Balancing Therapy', 'description' => 'Terapi penyeimbangan chakra dengan crystal healing', 'price' => 400000, 'duration' => '90 menit'],
                ['name' => 'Sound Healing Session', 'description' => 'Terapi suara dengan singing bowl dan meditasi', 'price' => 200000, 'duration' => '60 menit'],
                ['name' => 'Reiki Healing Treatment', 'description' => 'Penyembuhan energi dengan teknik Reiki tradisional', 'price' => 300000, 'duration' => '75 menit'],
                ['name' => 'Acupuncture Therapy', 'description' => 'Terapi akupunktur untuk kesehatan dan penyembuhan', 'price' => 350000, 'duration' => '60 menit'],
            ]
        ];

        foreach ($spas as $index => $spa) {
            $categories = array_keys($servicesByCategory);

            // Each spa gets 3-4 categories of services
            $numCategories = rand(3, 4);
            $selectedCategories = [];

            // Ensure variety by selecting different categories for each spa
            for ($i = 0; $i < $numCategories; $i++) {
                $categoryIndex = ($index * 2 + $i) % count($categories);
                $selectedCategories[] = $categories[$categoryIndex];
            }

            foreach ($selectedCategories as $category) {
                $services = $servicesByCategory[$category];

                // Pick 2-3 services from each category
                $numServices = rand(2, 3);
                $selectedServices = array_slice($services, 0, $numServices);

                foreach ($selectedServices as $service) {
                    SpaService::create([
                        'spa_id' => $spa->id_spa,
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
