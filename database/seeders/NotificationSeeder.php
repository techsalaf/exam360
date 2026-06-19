<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use Carbon\Carbon;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        if ($users->isEmpty()) {
            return;
        }

        foreach ($users as $user) {
            
            $scenarios = [
                [
                    'title' => 'Welcome to ZiExam AI',
                    'message' => 'We are excited to have you on board! Start by exploring available exams.',
                    'type' => 'user', // Maps to 'info' color in dashboard logic
                    'action_url' => '#',
                    'icon' => 'fa-solid fa-hand-sparkles',
                    'color' => 'primary',
                    'read' => false,
                    'created_at' => Carbon::now(),
                ],
                [
                    'title' => 'Exam Result Published',
                    'message' => 'Your results for "Advanced Mathematics 101" are now available.',
                    'type' => 'system',
                    'action_url' => '#',
                    'icon' => 'fa-solid fa-square-poll-vertical',
                    'color' => 'success',
                    'read' => false,
                    'created_at' => Carbon::now()->subHours(2),
                ],
                [
                    'title' => 'Payment Successful',
                    'message' => 'We received your payment for the "Pro Subscription Plan". Transaction ID: TXN-' . rand(1000, 9999),
                    'type' => 'payment',
                    'action_url' => '#',
                    'icon' => 'fa-solid fa-receipt',
                    'color' => 'success',
                    'read' => true,
                    'created_at' => Carbon::now()->subDay(),
                ],
                [
                    'title' => 'Incomplete Profile',
                    'message' => 'Please complete your profile information to generate certificates accurately.',
                    'type' => 'user',
                    'action_url' => '#',
                    'icon' => 'fa-solid fa-user-gear',
                    'color' => 'warning',
                    'read' => false,
                    'created_at' => Carbon::now()->subDays(2),
                ],
                [
                    'title' => 'Exam Missed',
                    'message' => 'You missed the scheduled time for "Physics Weekly Quiz".',
                    'type' => 'live',
                    'action_url' => '#',
                    'icon' => 'fa-solid fa-clock-rotate-left',
                    'color' => 'danger',
                    'read' => true,
                    'created_at' => Carbon::now()->subDays(3),
                ],
                [
                    'title' => 'Certificate Earned',
                    'message' => 'Congratulations! You have earned a certificate for "English Literature".',
                    'type' => 'system',
                    'action_url' => '#',
                    'icon' => 'fa-solid fa-award',
                    'color' => 'success',
                    'read' => true,
                    'created_at' => Carbon::now()->subDays(5),
                ],
            ];

            foreach ($scenarios as $data) {
                DB::table('notifications')->insert([
                    'id'              => Str::uuid()->toString(),
                    'type'            => 'App\Notifications\SystemNotification',
                    'notifiable_type' => get_class($user),
                    'notifiable_id'   => $user->id,
                    'data'            => json_encode([
                        'title'   => $data['title'],
                        'message' => $data['message'],
                        'url'     => $data['action_url'],
                        'type'    => $data['type'],
                        'icon'    => $data['icon'],
                        'color'   => $data['color']
                    ]),
                    'read_at'         => $data['read'] ? Carbon::now() : null,
                    'created_at'      => $data['created_at'],
                    'updated_at'      => $data['created_at'],
                ]);
            }
        }
    }
}