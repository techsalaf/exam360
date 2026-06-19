<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if Users exist, otherwise rely on default ID 1 (common setup)
        $userIds = DB::table('users')->pluck('id')->toArray();
        if (empty($userIds)) {
            $userIds = [1];
        }

        $allPayments = [];
        $gateways = ['stripe', 'paypal', 'razorpay', 'bank_transfer'];
        $statuses = ['success', 'pending', 'rejected', 'initiated'];
        $fnames = ['John', 'Jane', 'Alex', 'Sarah', 'Mike', 'Emily', 'Chris'];
        $lnames = ['Doe', 'Smith', 'Wong', 'Brown', 'Green', 'Taylor', 'Wilson'];
        $banks = ['First National Bank', 'Central Trust', 'Global Pay Solutions', 'Apex Finance'];

        $totalRecords = 25;

        for ($i = 0; $i < $totalRecords; $i++) {
            $gateway = $gateways[array_rand($gateways)];
            $status = $statuses[array_rand($statuses)];
            $amount = rand(10, 1000) + rand(0, 99) / 100;
            $charge = $amount * (rand(1, 5) / 100); // 1% to 5% charge
            $rate = 1.0; 
            $final_amount = $amount + $charge;
            $dateOffset = rand(0, 30); // Up to 30 days old

            $meta = [
                'charge' => round($charge, 2),
                'rate' => $rate,
                'final_amount' => round($final_amount, 2),
            ];

            $user_info = null;

            // Conditional data generation for Manual/Bank Transfer types
            if ($gateway === 'bank_transfer') {
                $status = ($i % 3 == 0) ? 'rejected' : (($i % 2 == 0) ? 'pending' : 'success'); // Force bank transfers to pending/success/rejected state
                
                $user_info = [
                    'first_name' => $fnames[array_rand($fnames)],
                    'last_name' => $lnames[array_rand($lnames)],
                    'bank_name' => $banks[array_rand($banks)],
                    'trx_ref' => 'REF-' . rand(100000, 999999),
                    'attachment' => $status === 'pending' || $status === 'success' ? 'screenshot_' . $i . '.png' : null,
                ];
                $meta['user_info'] = $user_info;
            }

            $allPayments[] = [
                'user_id' => $userIds[array_rand($userIds)],
                'transaction_id' => strtoupper($gateway) . '-' . Carbon::now()->timestamp . $i,
                'amount' => round($amount, 2),
                'currency' => 'USD',
                'gateway' => $gateway,
                'status' => $status,
                'gateway_response' => json_encode($meta),
                'created_at' => Carbon::now()->subDays($dateOffset)->subHours(rand(1, 23)),
                'updated_at' => Carbon::now()->subDays($dateOffset)->subHours(rand(1, 23)),
            ];
        }

        DB::table('payments')->insert($allPayments);
    }
}