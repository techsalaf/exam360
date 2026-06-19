<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Exam;
use App\Models\Category;
use App\Models\Question;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ExamSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Categories if none exist
        $categories = ['Aptitude Test', 'General Knowledge', 'English Literature', 'Mathematics', 'Science'];
        $catIds = [];
        
        foreach($categories as $catName) {
            $cat = Category::firstOrCreate(
                ['slug' => Str::slug($catName)],
                ['name' => $catName, 'is_active' => true]
            );
            $catIds[] = $cat->id;
        }

        // 2. Data for 15 Exams
        $examTitles = [
            'Advanced Aptitude Challenge', 'BCS Preliminary 2025', 'Bank Recruitment Test',
            'Primary Teacher Exam', 'IELTS Reading Mock', 'University Admission Physics',
            'Chemistry Organic Logic', 'Basic Computer Science', 'History of the World',
            'English Grammar Mastery', 'Logical Reasoning Alpha', 'Data Structures Basics',
            'Corporate Ethics Test', 'General Science Final', 'Defense Job Preparation'
        ];

        // 3. Loop to create Exams & Questions
        foreach ($examTitles as $index => $title) {
            
            $isPaid = rand(0, 1);
            $hasSchedule = rand(0, 10) > 7; // 30% chance to have a schedule
            
            $startDate = null;
            $endDate = null;

            if ($hasSchedule) {
                $startDate = Carbon::now()->addDays(rand(-5, 15));
                $endDate = (clone $startDate)->addHours(rand(1, 48));
            }

            $exam = Exam::create([
                'title'            => $title,
                'slug'             => Str::slug($title) . '-' . rand(100,999),
                'category_id'      => $catIds[array_rand($catIds)],
                'plan_id'          => null, 
                'banner'           => null, 
                'description'      => "This is a comprehensive assessment for $title covering key topics and concepts.",
                'duration_minutes' => rand(15, 90),
                'pass_percentage'  => rand(40, 60),
                'total_marks'      => 5, // Total marks for the exam
                'start_date'       => $startDate,
                'end_date'         => $endDate,
                'result_date'      => $endDate ? (clone $endDate)->addDays(1) : null,
                'is_active'        => rand(0, 10) > 2, 
                'is_paid'          => $isPaid,
                'price'            => $isPaid ? rand(10, 50) : null,
                'allow_retake'     => true,
            ]);

            // Create 5 Dummy Questions for each Exam
            for ($i = 1; $i <= 5; $i++) {
                Question::create([
                    'exam_id'       => $exam->id,
                    'question_text' => "Question #$i for $title: What is the correct answer?",
                    'type'          => 'mcq',
                    'options'       => [
                        'A' => 'Option One',
                        'B' => 'Option Two',
                        'C' => 'Option Three',
                        'D' => 'Option Four'
                    ],
                    'correct_answer' => 'A',
                    'explanation'    => 'This is the explanation for why A is correct.'
                    // Removed 'marks' => 1 to fix the SQL error
                ]);
            }
        }
    }
}