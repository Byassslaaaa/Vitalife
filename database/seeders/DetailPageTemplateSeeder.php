<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DetailPageTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $spaTemplates = [
            [
                'name' => 'Classic Spa',
                'type' => 'spa',
                'description' => 'Traditional spa layout with elegant design',
                'config_data' => json_encode([
                    'hero_title' => null,
                    'hero_subtitle' => null,
                    'facilities' => [
                        [
                            'title' => 'Traditional Massage',
                            'description' => 'Traditional massage to relieve tension and stress from your body',
                            'icon' => 'fa-solid fa-spa'
                        ],
                        [
                            'title' => 'Reflexology',
                            'description' => 'Stimulate foot reflex points to boost health and wellness',
                            'icon' => 'fa-solid fa-heart'
                        ],
                        [
                            'title' => 'Thai Massage',
                            'description' => 'Thai massage with pressure and stretch techniques',
                            'icon' => 'fa-solid fa-leaf'
                        ]
                    ],
                    'booking_policy_title' => 'BOOKING POLICY',
                    'booking_policy_subtitle' => 'YOUR WELLNESS PLANS',
                    'show_opening_hours' => true,
                    'show_location_map' => true,
                    'show_facilities' => true,
                    'show_booking_policy' => true,
                    'theme_color' => '#3B82F6',
                    'layout_style' => 'default'
                ]),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Modern Spa',
                'type' => 'spa',
                'description' => 'Contemporary spa design with clean lines',
                'config_data' => json_encode([
                    'hero_title' => null,
                    'hero_subtitle' => null,
                    'facilities' => [
                        [
                            'title' => 'Aromatherapy',
                            'description' => 'Essential oil treatments for mind and body relaxation',
                            'icon' => 'fa-solid fa-seedling'
                        ],
                        [
                            'title' => 'Hot Stone Therapy',
                            'description' => 'Heated stone massage for deep muscle relaxation',
                            'icon' => 'fa-solid fa-fire'
                        ],
                        [
                            'title' => 'Facial Treatment',
                            'description' => 'Professional facial care for glowing skin',
                            'icon' => 'fa-solid fa-star'
                        ]
                    ],
                    'booking_policy_title' => 'RESERVATION POLICY',
                    'booking_policy_subtitle' => 'YOUR RELAXATION JOURNEY',
                    'show_opening_hours' => true,
                    'show_location_map' => true,
                    'show_facilities' => true,
                    'show_booking_policy' => true,
                    'theme_color' => '#8B5CF6',
                    'layout_style' => 'modern'
                ]),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        $yogaTemplates = [
            [
                'name' => 'Traditional Yoga',
                'type' => 'yoga',
                'description' => 'Classic yoga studio layout with peaceful design',
                'config_data' => json_encode([
                    'hero_title' => null,
                    'hero_subtitle' => null,
                    'facilities' => [
                        [
                            'title' => 'Hatha Yoga',
                            'description' => 'Gentle yoga practice focusing on basic postures',
                            'icon' => 'fa-solid fa-person-walking'
                        ],
                        [
                            'title' => 'Meditation',
                            'description' => 'Mindfulness and meditation sessions for inner peace',
                            'icon' => 'fa-solid fa-brain'
                        ],
                        [
                            'title' => 'Breathing Exercises',
                            'description' => 'Pranayama techniques for better health',
                            'icon' => 'fa-solid fa-wind'
                        ]
                    ],
                    'booking_policy_title' => 'CLASS POLICY',
                    'booking_policy_subtitle' => 'YOUR YOGA JOURNEY',
                    'show_opening_hours' => true,
                    'show_location_map' => true,
                    'show_facilities' => true,
                    'show_booking_policy' => true,
                    'show_class_types' => true,
                    'theme_color' => '#10B981',
                    'layout_style' => 'default'
                ]),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Power Yoga',
                'type' => 'yoga',
                'description' => 'Dynamic yoga studio for intensive practice',
                'config_data' => json_encode([
                    'hero_title' => null,
                    'hero_subtitle' => null,
                    'facilities' => [
                        [
                            'title' => 'Vinyasa Flow',
                            'description' => 'Dynamic flowing sequences linking breath and movement',
                            'icon' => 'fa-solid fa-bolt'
                        ],
                        [
                            'title' => 'Power Yoga',
                            'description' => 'Intense yoga practice for strength and flexibility',
                            'icon' => 'fa-solid fa-dumbbell'
                        ],
                        [
                            'title' => 'Hot Yoga',
                            'description' => 'Yoga practice in heated room for deeper stretches',
                            'icon' => 'fa-solid fa-fire'
                        ]
                    ],
                    'booking_policy_title' => 'STUDIO POLICY',
                    'booking_policy_subtitle' => 'YOUR FITNESS TRANSFORMATION',
                    'show_opening_hours' => true,
                    'show_location_map' => true,
                    'show_facilities' => true,
                    'show_booking_policy' => true,
                    'show_class_types' => true,
                    'theme_color' => '#F59E0B',
                    'layout_style' => 'modern'
                ]),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('detail_page_templates')->insert(array_merge($spaTemplates, $yogaTemplates));
    }
}
