<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;
use Illuminate\Support\Facades\DB; // <-- Import DB Facade

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Disable Foreign Key Checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 2. Clear previous records safely (Using delete() is often safer than truncate() 
        //    but truncate is fine if constraints are disabled)
        Plan::truncate(); 

        $plans = [
            // ... (Your plan data here) ...
            [
                'name' => 'Basic',
                'price_monthly' => 19.00,
                'price_yearly' => 219.00,
                'limit_monthly' => 10,
                'limit_yearly' => 120,
                'short_description' => 'Perfect for solo users and small teams just starting out.',
                'currency' => 'USD',
                'is_active' => true,
            ],
            [
                'name' => 'Pro',
                'price_monthly' => 49.00,
                'price_yearly' => 499.00,
                'limit_monthly' => 30,
                'limit_yearly' => 360,
                'short_description' => 'Ideal for growing businesses needing expanded access and features.',
                'currency' => 'USD',
                'is_active' => true,
            ],
            [
                'name' => 'Enterprise',
                'price_monthly' => 99.00,
                'price_yearly' => 999.00,
                'limit_monthly' => 60,
                'limit_yearly' => 720,
                'short_description' => 'Full access for large organizations with high volume assessment needs.',
                'currency' => 'USD',
                'is_active' => true,
            ],
            [
                'name' => 'Free Trial',
                'price_monthly' => 0.00,
                'price_yearly' => 0.00,
                'limit_monthly' => 3,
                'limit_yearly' => 3,
                'short_description' => 'Test out our platform features before committing to a paid plan.',
                'currency' => 'USD',
                'is_active' => true,
            ],
        ];

        foreach ($plans as $planData) {
            Plan::create($planData);
        }
        
        // 3. Re-enable Foreign Key Checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('Successfully seeded ' . count($plans) . ' pricing plans.');
    }
}