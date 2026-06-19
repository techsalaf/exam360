<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;

class AdminNotificationSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Find an Admin User
        // Adjust the 'role' column check based on your specific permission implementation
        $admin = User::role('Super Admin')->first() ?? User::first();

        if (!$admin) {
            $this->command->info('No admin user found. Skipping admin notification seeding.');
            return;
        }

        // 2. Define Admin-Specific Scenarios
        $scenarios = [
            [
                'type' => 'user',
                'data' => [
                    'type'    => 'user',
                    'title'   => 'New User Registration',
                    'message' => 'Sarah Connor (sarah@example.com) has just created an account.',
                    'url'     => route('admin.users.index'), // Dynamic route
                    'icon'    => 'fa-solid fa-user-plus',
                    'color'   => 'info'
                ],
                'created_at' => Carbon::now()->subMinutes(5),
                'read' => false
            ],
            [
                'type' => 'payment',
                'data' => [
                    'type'    => 'payment',
                    'title'   => 'Payment Received',
                    'message' => 'Received $49.00 USD from John Doe via Stripe.',
                    'url'     => route('admin.payments.index'),
                    'icon'    => 'fa-solid fa-receipt',
                    'color'   => 'success'
                ],
                'created_at' => Carbon::now()->subMinutes(25),
                'read' => false
            ],
            [
                'type' => 'live',
                'data' => [
                    'type'    => 'live',
                    'title'   => 'Critical Cheating Risk',
                    'message' => 'Session #8821 (Alex Murphy) flagged with critical behavior.',
                    'url'     => route('admin.live.index'),
                    'icon'    => 'fa-solid fa-triangle-exclamation',
                    'color'   => 'danger'
                ],
                'created_at' => Carbon::now()->subHours(1),
                'read' => false
            ],
            [
                'type' => 'ticket',
                'data' => [
                    'type'    => 'ticket',
                    'title'   => 'New Support Ticket',
                    'message' => 'Ticket #2024-99: "Unable to access exam material".',
                    'url'     => route('admin.tickets.index'),
                    'icon'    => 'fa-solid fa-headset',
                    'color'   => 'warning'
                ],
                'created_at' => Carbon::now()->subHours(3),
                'read' => false
            ],
            [
                'type' => 'payment',
                'data' => [
                    'type'    => 'payment',
                    'title'   => 'Payment Failed',
                    'message' => 'Transaction #TRX-9988 failed due to insufficient funds.',
                    'url'     => route('admin.payments.index', ['status' => 'failed']),
                    'icon'    => 'fa-solid fa-circle-xmark',
                    'color'   => 'danger'
                ],
                'created_at' => Carbon::now()->subHours(5),
                'read' => true
            ],
            [
                'type' => 'system',
                'data' => [
                    'type'    => 'system',
                    'title'   => 'System Backup Completed',
                    'message' => 'Daily database backup completed successfully (24MB).',
                    'url'     => '#',
                    'icon'    => 'fa-solid fa-server',
                    'color'   => 'secondary'
                ],
                'created_at' => Carbon::now()->subDays(1),
                'read' => true
            ],
            [
                'type' => 'user',
                'data' => [
                    'type'    => 'user',
                    'title'   => 'Email Verified',
                    'message' => 'User Michael Scott verified their email address.',
                    'url'     => route('admin.users.index'),
                    'icon'    => 'fa-solid fa-check-circle',
                    'color'   => 'info'
                ],
                'created_at' => Carbon::now()->subDays(2),
                'read' => true
            ],
            [
                'type' => 'ticket',
                'data' => [
                    'type'    => 'ticket',
                    'title'   => 'Ticket Reply',
                    'message' => 'User replied to Ticket #2024-55 regarding billing.',
                    'url'     => route('admin.tickets.index'),
                    'icon'    => 'fa-solid fa-reply',
                    'color'   => 'warning'
                ],
                'created_at' => Carbon::now()->subDays(3),
                'read' => true
            ],
        ];

        // 3. Insert into Database
        foreach ($scenarios as $notif) {
            DB::table('notifications')->insert([
                'id'              => Str::uuid()->toString(),
                'type'            => 'App\Notifications\SystemNotification', // Must match the class name used in code
                'notifiable_type' => get_class($admin), // Polymorphic Type
                'notifiable_id'   => $admin->id,        // Polymorphic ID
                'data'            => json_encode($notif['data']),
                'read_at'         => $notif['read'] ? Carbon::now() : null,
                'created_at'      => $notif['created_at'],
                'updated_at'      => $notif['created_at'],
            ]);
        }
    }
}