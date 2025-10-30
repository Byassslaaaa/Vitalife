<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Yoga;
use App\Models\YogaService;

class YogaServiceSeeder extends Seeder
{
    public function run(): void
    {
        $presets = function ($basePrice) {
            return [
                [
                    'name' => 'Hatha Yoga Class',
                    'description' => 'Kelas yoga dasar dengan fokus pada postur dan pernapasan',
                    'price' => $basePrice,
                    'duration' => '75 menit',
                    'category' => 'group_class',
                    'image' => 'image/yoga-hatha.jpg',
                    'is_active' => true,
                ],
                [
                    'name' => 'Vinyasa Flow',
                    'description' => 'Kelas yoga dinamis dengan gerakan yang mengalir',
                    'price' => $basePrice + 30000,
                    'duration' => '90 menit',
                    'category' => 'group_class',
                    'image' => 'image/yoga-vinyasa.jpg',
                    'is_active' => true,
                ],
                [
                    'name' => 'Yin Yoga',
                    'description' => 'Yoga restoratif dengan pose yang ditahan lama',
                    'price' => $basePrice + 20000,
                    'duration' => '60 menit',
                    'category' => 'restorative',
                    'image' => 'image/yoga-yin.jpg',
                    'is_active' => true,
                ],
                [
                    'name' => 'Meditation Class',
                    'description' => 'Kelas meditasi untuk ketenangan pikiran',
                    'price' => $basePrice - 50000,
                    'duration' => '45 menit',
                    'category' => 'meditation',
                    'image' => 'image/yoga-meditation-class.jpg',
                    'is_active' => true,
                ]
            ];
        };

        $yogas = Yoga::all();
        foreach ($yogas as $yoga) {
            foreach ($presets($yoga->harga) as $svc) {
                YogaService::updateOrCreate(
                    ['yoga_id' => $yoga->id_yoga, 'name' => $svc['name']],
                    array_merge($svc, ['yoga_id' => $yoga->id_yoga])
                );
            }
        }
    }
}
