<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Voucher;
use Illuminate\Support\Carbon;

class VoucherSeeder extends Seeder
{
    public function run(): void
    {
        $vouchers = [
            [
                'code' => 'WELLNESS50',
                'description' => 'Diskon 50% untuk semua layanan spa dan yoga',
                'discount_type' => 'percentage',
                'discount_percentage' => 50,
                'discount_amount' => 0,
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
                'discount_amount' => 0,
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
                'discount_amount' => 0,
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
                'discount_amount' => 0,
                'usage_count' => 0,
                'usage_limit' => 30,
                'is_used' => false,
                'expired_at' => now()->addMonths(2),
            ],
            [
                'code' => 'SAVE100K',
                'description' => 'Potongan langsung Rp 100.000 untuk transaksi minimal Rp 500.000',
                'discount_type' => 'fixed',
                'discount_percentage' => 0,
                'discount_amount' => 100000,
                'usage_count' => 0,
                'usage_limit' => 25,
                'is_used' => false,
                'expired_at' => now()->addMonths(2),
            ]
        ];

        foreach ($vouchers as $data) {
            Voucher::updateOrCreate(
                ['code' => $data['code']],
                $data
            );
        }
    }
}
