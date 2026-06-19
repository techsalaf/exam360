<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StudentExamSession;
use App\Models\User;
use App\Models\Exam;
use Faker\Factory as Faker;
use Carbon\Carbon;

class StudentExamSessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Ensure we have users and exams to link to
        $userIds = User::pluck('id')->toArray();
        $examIds = Exam::pluck('id')->toArray();

        if (empty($userIds) || empty($examIds)) {
            $this->command->info("Skipping StudentExamSessionSeeder: Users or Exams table is empty.");
            return;
        }

        // Define possible risk events for high-risk sessions
        $riskEvents = [
            "Tab focus lost (00:05:12)",
            "Browser window minimized (00:10:45)",
            "Copy/Paste detected (00:15:30)",
            "IP address changed abruptly (00:22:01)",
            "External application launch suspected (00:30:10)",
        ];

        // Create 20 test sessions
        for ($i = 0; $i < 20; $i++) {
            $examId = $faker->randomElement($examIds);
            
            $totalQuestions = \App\Models\Question::where('exam_id', $examId)->count();
            if ($totalQuestions === 0) $totalQuestions = 10; 

            $startTime = Carbon::now()->subMinutes($faker->numberBetween(10, 60));
            $progress = $faker->numberBetween(10, 95);
            
            $completedQuestions = round(($progress / 100) * $totalQuestions);

            $riskScore = $faker->numberBetween(0, 100);
            $status = 'ongoing';
            $eventsArray = null; // Changed $events to $eventsArray to reflect type

            // Logic for risk simulation
            if ($riskScore < 30) {
                // Low risk
            } elseif ($riskScore < 70) {
                // Medium risk
                if ($faker->boolean(50)) {
                    $eventsArray = [$riskEvents[0], $riskEvents[1]];
                }
            } else {
                // High risk
                $status = $faker->randomElement(['ongoing', 'completed']); 
                $eventsArray = $faker->randomElements($riskEvents, $faker->numberBetween(2, 4));
            }
            
            StudentExamSession::create([
                'user_id' => $faker->randomElement($userIds),
                'exam_id' => $examId,
                'status' => $status,
                'progress_percentage' => $progress,
                
                'total_questions' => $totalQuestions,
                'completed_questions' => $completedQuestions,
                
                'start_time' => $startTime,
                'last_activity_at' => Carbon::now()->subSeconds($faker->numberBetween(1, 120)),
                
                'risk_score' => $riskScore,
                // FIX: Explicitly encode the array to a JSON string
                'flagged_events' => $eventsArray ? json_encode($eventsArray) : null,
                
                'ip_address' => $faker->ipv4(),
                'user_agent' => $faker->userAgent(),
            ]);
        }
    }
}