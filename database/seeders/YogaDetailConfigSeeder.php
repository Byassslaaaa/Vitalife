<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Yoga;
use App\Models\YogaDetailConfig;

class YogaDetailConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $yogas = Yoga::all();

        foreach ($yogas as $index => $yoga) {
            $configData = [
                [
                    'booking_policy_subtitle' => 'FIND YOUR INNER PEACE',
                    'contact_person_name' => 'Mbak Dewi Sari',
                    'contact_person_phone' => '0274-881234',
                    'facilities' => [
                        'Premium Yoga Mats',
                        'Props & Blocks',
                        'Meditation Cushions',
                        'Sound System',
                        'Air Conditioning',
                        'Changing Rooms',
                        'Tea Corner',
                        'Free Parking'
                    ],
                    'gallery_images' => [
                        'images/yoga-barn.jpg',
                        'images/yoga-class-1.jpg',
                        'images/yoga-meditation.jpg',
                        'images/yoga-outdoor.jpg',
                        'images/yoga-sunset.jpg'
                    ]
                ],
                [
                    'booking_policy_subtitle' => 'EMBRACE THE SERENITY',
                    'contact_person_name' => 'Mas Agung Yoga',
                    'contact_person_phone' => '0274-882345',
                    'facilities' => [
                        'Eco-Friendly Mats',
                        'Bolsters & Straps',
                        'Essential Oil Diffuser',
                        'Natural Lighting',
                        'Bamboo Flooring',
                        'Herbal Tea Service',
                        'Meditation Garden',
                        'Bicycle Parking'
                    ],
                    'gallery_images' => [
                        'images/yoga-shanti.jpg',
                        'images/yoga-nature.jpg',
                        'images/yoga-peaceful.jpg',
                        'images/yoga-bamboo.jpg',
                        'images/yoga-garden.jpg'
                    ]
                ],
                [
                    'booking_policy_subtitle' => 'AWAKEN YOUR SPIRIT',
                    'contact_person_name' => 'Ibu Sari Asih',
                    'contact_person_phone' => '0274-883456',
                    'facilities' => [
                        'Traditional Yoga Props',
                        'Meditation Bells',
                        'Incense & Candles',
                        'Natural Ventilation',
                        'Wooden Decking',
                        'Prayer Flags',
                        'Spiritual Library',
                        'Sacred Space'
                    ],
                    'gallery_images' => [
                        'images/yoga-surya.jpg',
                        'images/yoga-sunrise.jpg',
                        'images/yoga-spiritual.jpg',
                        'images/yoga-traditional.jpg',
                        'images/yoga-peaceful.jpg'
                    ]
                ],
                [
                    'booking_policy_subtitle' => 'DISCOVER INNER HARMONY',
                    'contact_person_name' => 'Pak Made Dharma',
                    'contact_person_phone' => '0274-884567',
                    'facilities' => [
                        'Sacred Geometry Design',
                        'Crystal Healing Corner',
                        'Mandala Art Decor',
                        'Sound Healing Bowls',
                        'Aromatherapy Station',
                        'Meditation Cave',
                        'Labyrinth Walking Path',
                        'Energy Healing Space'
                    ],
                    'gallery_images' => [
                        'images/yoga-mandala.jpg',
                        'images/yoga-sacred.jpg',
                        'images/yoga-crystal.jpg',
                        'images/yoga-healing.jpg',
                        'images/yoga-energy.jpg'
                    ]
                ],
                [
                    'booking_policy_subtitle' => 'BALANCE MIND BODY SOUL',
                    'contact_person_name' => 'Mbak Maya Indira',
                    'contact_person_phone' => '0274-885678',
                    'facilities' => [
                        'Multi-Level Classes',
                        'Beginner Friendly',
                        'Advanced Workshops',
                        'Teacher Training',
                        'Wellness Library',
                        'Community Garden',
                        'Healthy Cafe',
                        'Workshop Studio'
                    ],
                    'gallery_images' => [
                        'images/yoga-harmony.jpg',
                        'images/yoga-community.jpg',
                        'images/yoga-workshop.jpg',
                        'images/yoga-training.jpg',
                        'images/yoga-wellness.jpg'
                    ]
                ]
            ];

            YogaDetailConfig::create([
                'yoga_id' => $yoga->id_yoga,
                'booking_policy_subtitle' => $configData[$index]['booking_policy_subtitle'],
                'contact_person_name' => $configData[$index]['contact_person_name'],
                'contact_person_phone' => $configData[$index]['contact_person_phone'],
                'facilities' => $configData[$index]['facilities'],
                'gallery_images' => $configData[$index]['gallery_images'],
                'show_opening_hours' => true,
                'show_location_map' => true,
            ]);
        }
    }
}
