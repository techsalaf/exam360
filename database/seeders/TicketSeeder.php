<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ticket;
use App\Models\TicketReply;
use App\Models\User;
use Faker\Factory as Faker;
use Spatie\Permission\Models\Role; // Added for Spatie

class TicketSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $students = User::role('Student')->get();
        $admin = User::role('Super Admin')->first();

        // Fallback if no admin exists (Safety check)
        if (!$admin) {
            $admin = User::first(); 
        }

        if ($students->isEmpty()) {
            $this->command->warn('No students found. Skipping ticket seeding.');
            return;
        }

        $priorities = ['low', 'medium', 'high'];
        $statuses = ['open', 'replied', 'closed'];
        $categories = ['Billing', 'Technical Issue', 'Course Content', 'General Inquiry', 'Feature Request'];

        $subjects = [
            'Billing' => [
                'Payment failed but money deducted',
                'Request for refund on recent purchase',
                'How to upgrade my membership plan?',
                'Duplicate charge on my credit card',
            ],
            'Technical Issue' => [
                'Cannot access my exam result',
                'Login issues on mobile app',
                'Certificate download returns 404',
                'Video player not loading',
            ],
            'Course Content' => [
                'Typo in Question #402',
                'Syllabus outdated for Physics module',
                'Clarification needed on Section 3',
                'Missing resources in download section',
            ],
            'General Inquiry' => [
                'Account verification pending',
                'Reset my password link invalid',
                'Can I extend my exam duration?',
            ],
            'Feature Request' => [
                'Request feature: Dark mode',
                'Please add PDF export for results',
            ]
        ];

        // Seed tickets for a subset of students
        foreach ($students->random(min($students->count(), 15)) as $user) {
            
            $ticketCount = rand(1, 4);

            for ($i = 0; $i < $ticketCount; $i++) {
                $category = $categories[array_rand($categories)];
                $status = $statuses[array_rand($statuses)];
                $priority = $priorities[array_rand($priorities)];

                // Pick a relevant subject or fallback
                $subjectList = $subjects[$category] ?? ['General Support Request'];
                $subject = $subjectList[array_rand($subjectList)];

                $createdAt = $faker->dateTimeBetween('-3 months', 'now');

                // Create the Ticket
                $ticket = Ticket::create([
                    'user_id'    => $user->id,
                    'subject'    => $subject,
                    'category'   => $category,
                    'priority'   => $priority,
                    'status'     => $status,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);

                // 1. Initial Message from User
                TicketReply::create([
                    'ticket_id'  => $ticket->id,
                    'user_id'    => $user->id,
                    'message'    => $faker->paragraph(3),
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);

                // 2. Admin Reply (if applicable)
                if (($status === 'replied' || $status === 'closed') && $admin) {
                    $replyTime = (clone $createdAt)->modify('+' . rand(1, 5) . ' hours');
                    
                    TicketReply::create([
                        'ticket_id'  => $ticket->id,
                        'user_id'    => $admin->id,
                        'message'    => "Hello {$user->name},\n\nThank you for reaching out. " . $faker->sentence(10) . "\n\nBest Regards,\nSupport Team",
                        'created_at' => $replyTime,
                        'updated_at' => $replyTime,
                    ]);
                }

                // 3. Final User Response (if closed)
                if ($status === 'closed') {
                    $closeTime = (clone $createdAt)->modify('+' . rand(6, 24) . ' hours');

                    TicketReply::create([
                        'ticket_id'  => $ticket->id,
                        'user_id'    => $user->id,
                        'message'    => "Thank you, that solved my issue. You can close this ticket.",
                        'created_at' => $closeTime,
                        'updated_at' => $closeTime,
                    ]);
                }
            }
        }
    }
}