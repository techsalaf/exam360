<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LoginHistory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LoginHistorySeeder extends Seeder
{
    private array $browsers = ['Chrome 120', 'Firefox 118', 'Safari 17', 'Edge 119'];
    private array $os = ['Windows 11', 'macOS Ventura', 'Android 14', 'iOS 17'];
    private array $countries = ['US', 'CA', 'GB', 'IN', 'AU', 'DE'];
    private array $cities = ['New York', 'Toronto', 'London', 'Mumbai', 'Sydney', 'Berlin'];
    private array $networkTypes = ['Residential', 'Mobile Network', 'VPN / Proxy'];
    private array $loginMethods = ['password', 'google', 'otp'];
    private array $statuses = ['success', 'failed', 'suspicious'];

    public function run(): void
    {
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->warn('Skipping LoginHistory Seeder: No Users found.');
            return;
        }
        
        $records = [];
        $totalRecords = 100;

        for ($i = 0; $i < $totalRecords; $i++) {
            $user = $users->random();
            $loginAt = Carbon::now()->subDays(rand(1, 40));
            $status = $this->statuses[array_rand($this->statuses)];
            $isSuspicious = $status === 'suspicious';

            $country = $this->countries[array_rand($this->countries)];
            $city = $this->cities[array_rand($this->cities)];

            $records[] = [
                'user_id' => $user->id,
                'ip_address' => rand(1, 255) . '.' . rand(1, 255) . '.' . rand(1, 255) . '.' . rand(1, 255),
                'login_method' => $this->loginMethods[array_rand($this->loginMethods)],
                'status' => $status,
                'city' => $city,
                'country' => $country,
                'device_type' => (rand(0, 1) ? 'Desktop' : 'Mobile'),
                'browser' => $this->browsers[array_rand($this->browsers)],
                'os' => $this->os[array_rand($this->os)],
                'session_id' => ($status === 'success' ? uniqid('sess_') : null),
                
                // Highlight VPN for suspicious attempts
                'network_type' => $isSuspicious ? 'VPN / Proxy' : $this->networkTypes[rand(0, 1)],
                
                'mfa_used' => (bool)rand(0, 1),
                'login_at' => $loginAt,
                'user_agent' => 'Mock User Agent Data ' . $i,
                'created_at' => $loginAt,
                'updated_at' => $loginAt,
            ];
        }

        // Chunking insertion for large datasets
        foreach (array_chunk($records, 500) as $chunk) {
            DB::table('login_histories')->insert($chunk);
        }
    }
}