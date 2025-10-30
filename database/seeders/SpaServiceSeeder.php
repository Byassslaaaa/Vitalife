<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Spa;
use App\Models\SpaService;

class SpaServiceSeeder extends Seeder
{
    public function run(): void
    {
        $servicesPreset = [
            [
                'name' => 'Traditional Massage',
                'description' => 'Pijat tradisional untuk relaksasi dan kesehatan',
                'price' => 200000,
                'duration' => '60 menit',
                'category' => 'massage',
                'is_active' => true,
            ],
            [
                'name' => 'Aromatherapy',
                'description' => 'Terapi aromaterapi dengan essential oil pilihan',
                'price' => 300000,
                'duration' => '90 menit',
                'category' => 'therapy',
                'is_active' => true,
            ],
            [
                'name' => 'Hot Stone Massage',
                'description' => 'Pijat dengan batu panas untuk relaksasi mendalam',
                'price' => 350000,
                'duration' => '75 menit',
                'category' => 'massage',
                'is_active' => true,
            ],
            [
                'name' => 'Body Scrub & Wrap',
                'description' => 'Perawatan tubuh lengkap dengan scrub dan body wrap',
                'price' => 400000,
                'duration' => '90 menit',
                'category' => 'treatment',
                'is_active' => true,
            ]
        ];

        $spas = Spa::all();
        foreach ($spas as $spa) {
            foreach ($servicesPreset as $service) {
                SpaService::updateOrCreate(
                    ['spa_id' => $spa->id_spa, 'name' => $service['name']],
                    array_merge($service, ['spa_id' => $spa->id_spa])
                );
            }
        }
    }
}
