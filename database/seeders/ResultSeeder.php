<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Exam;
use App\Models\Result;
use Carbon\Carbon;
use Spatie\Permission\Models\Role; // Added for Spatie

class ResultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. ✅ FIXED: Using Spatie role scope instead of database column
        $users = User::role('Student')->get(); 
        $exams = Exam::all();

        if ($users->isEmpty()) {
            $this->command->info('No students found. Creating a dummy student...');
            
            // ✅ FIXED: Separated User creation from Role assignment
            $dummyUser = User::create([
                'name' => 'John Student',
                'email' => 'student@example.com',
                'password' => bcrypt('password'),
            ]);
            
            $dummyUser->assignRole('Student');
            $users = collect([$dummyUser]);
        }

        if ($exams->isEmpty()) {
            $this->command->error('No exams found! Please seed exams first.');
            return;
        }

        $this->command->info('Seeding Results and Attempts...');

        // 2. Generate 25 Dummy Results
        for ($i = 0; $i < 25; $i++) {
            
            // Pick Random User and Exam
            $user = $users->random();
            $exam = $exams->random();
            
            // Randomize Stats
            $totalQuestions = 20; 
            $correctAnswers = rand(4, 20); 
            $totalMarks = 100;
            
            $percentage = ($correctAnswers / $totalQuestions) * 100;
            $obtainedScore = ($percentage / 100) * $totalMarks;
            $isPassed = $percentage >= 50; 

            $grade = 'F';
            if ($percentage >= 90) $grade = 'A+';
            elseif ($percentage >= 80) $grade = 'A';
            elseif ($percentage >= 70) $grade = 'B';
            elseif ($percentage >= 60) $grade = 'C';
            elseif ($percentage >= 50) $grade = 'D';

            $date = Carbon::now()->subDays(rand(0, 30))->subHours(rand(1, 12));

            $attemptId = DB::table('exam_attempts')->insertGetId([
                'user_id' => $user->id,
                'exam_id' => $exam->id,
                'status'  => 'completed',
                'score'   => $obtainedScore,
                'started_at' => $date->copy()->subMinutes(45),
                'completed_at' => $date,
                'created_at' => $date,
                'updated_at' => $date,
            ]);

            Result::create([
                'user_id'         => $user->id,
                'exam_id'         => $exam->id,
                'exam_attempt_id' => $attemptId,
                
                'total_questions' => $totalQuestions,
                'correct_answers' => $correctAnswers,
                'total_score'     => $totalMarks,
                'obtained_score'  => $obtainedScore,
                'percentage'      => $percentage,
                
                'is_passed'       => $isPassed,
                'grade'           => $grade,
                
                'created_at'      => $date,
                'updated_at'      => $date,
            ]);
        }

        $this->command->info('Successfully seeded 25 exam results!');
    }
}