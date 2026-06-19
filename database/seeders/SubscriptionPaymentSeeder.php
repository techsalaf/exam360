<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Plan;
use App\Models\Payment;
use Carbon\Carbon;

class SubscriptionPaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Fetch valid data to link
        $plans = Plan::where('is_active', true)->get();
        $users = User::all();

        // Safety check
        if ($plans->isEmpty() || $users->isEmpty()) {
            $this->command->warn('Skipping Subscription Seeder: No Plans or Users found.');
            return;
        }

        $gateways = ['Stripe', 'PayPal', 'Razorpay', 'Bank_Transfer'];
        $statuses = ['success', 'pending', 'failed', 'initiated'];

        // 2. Create 25 dummy subscription records
        for ($i = 0; $i < 25; $i++) {
            
            // Pick random user and plan
            $user = $users->random();
            $plan = $plans->random();
            
            // Randomize Gateway and Status
            $gateway = $gateways[array_rand($gateways)];
            // Give higher probability to 'success' (70% chance)
            $status = (rand(1, 10) <= 7) ? 'success' : $statuses[array_rand($statuses)];

            // Determine if Monthly or Yearly purchase
            $isYearly = (bool)rand(0, 1);
            $amount = $isYearly ? $plan->price_yearly : $plan->price_monthly;

            // Calculate Dates
            $createdAt = Carbon::now()->subDays(rand(1, 60)); // Random date in last 60 days
            $startDate = null;
            $endDate = null;

            // Only generate validity dates if payment was successful
            if ($status === 'success' || $status === 'approved') {
                $startDate = $createdAt->copy();
                $endDate = $isYearly 
                    ? $startDate->copy()->addYear() 
                    : $startDate->copy()->addMonth();
            }

            // Create the record
            Payment::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id, // Linking to Plan Table
                'transaction_id' => strtoupper(substr($gateway, 0, 3)) . '-' . mt_rand(1000000000, 9999999999),
                'amount' => $amount,
                'currency' => $plan->currency ?? 'USD',
                'gateway' => $gateway,
                'status' => $status,
                'type' => 'subscription', // Important filter key
                'start_date' => $startDate,
                'end_date' => $endDate,
                'gateway_response' => json_encode(['status' => 'mock_generated', 'id' => 'mock_' . rand(100, 999)]),
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }
    }
}