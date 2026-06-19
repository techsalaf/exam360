<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Coupon;
use Carbon\Carbon;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        $coupons = [
            [
                'code' => 'WELCOME50',
                'type' => 'fixed',
                'value' => 50.00,
                'usage_limit' => 100,
                'used_count' => 12,
                'min_purchase' => 100.00,
                'expires_at' => Carbon::now()->addMonths(6),
                'is_active' => true,
            ],
            [
                'code' => 'SUMMER2026',
                'type' => 'percentage',
                'value' => 20.00, // 20% off
                'usage_limit' => 500,
                'used_count' => 45,
                'min_purchase' => 0,
                'expires_at' => Carbon::now()->addMonths(3),
                'is_active' => true,
            ],
            [
                'code' => 'FLASHDEAL',
                'type' => 'fixed',
                'value' => 100.00,
                'usage_limit' => 50,
                'used_count' => 50, // Fully used
                'min_purchase' => 500.00,
                'expires_at' => Carbon::yesterday(), // Expired
                'is_active' => false,
            ],
            [
                'code' => 'STUDENT10',
                'type' => 'percentage',
                'value' => 10.00,
                'usage_limit' => null, // Unlimited
                'used_count' => 890,
                'min_purchase' => 0,
                'expires_at' => null, // Lifetime
                'is_active' => true,
            ],
        ];

        foreach ($coupons as $coupon) {
            Coupon::create($coupon);
        }
    }
}