<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Yoga;
use App\Models\YogaDetailConfig;

class YogaDetailConfigSeeder extends Seeder
{
    public function run(): void
    {
        $yogas = Yoga::all();
        foreach ($yogas as $yoga) {
            YogaDetailConfig::updateOrCreate(
                ['yoga_id' => $yoga->id_yoga],
                [
                    'hero_title' => $yoga->nama,
                    'hero_subtitle' => 'Find your inner peace and strength',
                    'gallery_images' => [$yoga->image],
                    'facilities' => [
                        [
                            'title' => 'Hatha Yoga',
                            'description' => 'Gentle yoga practice focusing on basic postures and breathing',
                            'icon' => 'fa-solid fa-person-walking'
                        ],
                        [
                            'title' => 'Meditation',
                            'description' => 'Mindfulness and meditation sessions for inner peace',
                            'icon' => 'fa-solid fa-brain'
                        ],
                        [
                            'title' => 'Breathing Exercises',
                            'description' => 'Pranayama techniques for better health and wellness',
                            'icon' => 'fa-solid fa-wind'
                        ],
                    ],
                    'show_opening_hours' => true,
                    'show_location_map' => true,
                ]
            );
        }
    }
}
