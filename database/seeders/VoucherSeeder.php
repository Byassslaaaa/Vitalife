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
