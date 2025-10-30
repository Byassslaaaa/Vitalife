<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DetailPageTemplate;

class DetailPageTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'name' => 'Spa Classic',
                'type' => 'spa',
                'description' => 'Template klasik untuk halaman detail spa',
                'config_data' => [
                    'layout' => 'classic',
                    'theme' => 'light',
                    'sections' => ['hero', 'about', 'services', 'facilities', 'gallery', 'contact']
                ],
                'preview_image' => 'image/templates/spa-classic.png',
                'is_active' => true,
            ],
            [
                'name' => 'Yoga Serenity',
                'type' => 'yoga',
                'description' => 'Template tenang untuk kelas yoga',
                'config_data' => [
                    'layout' => 'serenity',
                    'theme' => 'purple',
                    'sections' => ['hero', 'classes', 'benefits', 'gallery', 'contact']
                ],
                'preview_image' => 'image/templates/yoga-serenity.png',
                'is_active' => true,
            ]
        ];

        foreach ($templates as $tpl) {
            DetailPageTemplate::updateOrCreate(
                ['name' => $tpl['name']],
                $tpl
            );
        }
    }
}
