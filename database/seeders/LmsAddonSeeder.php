<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LmsAddonSeeder extends Seeder
{
    public function run()
    {
        $courseId = DB::table('lms_courses')->insertGetId([
            'title' => 'Mastering Laravel Development',
            'slug' => 'mastering-laravel-development',
            'description' => 'Learn how to build professional web applications using the Laravel framework from scratch.',
            'thumbnail' => null,
            'is_published' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $lessonIds = [];
        $lessons = ['Introduction to Laravel', 'Understanding Eloquent ORM', 'Deep Dive into Migrations'];

        foreach ($lessons as $index => $title) {
            $lessonIds[] = DB::table('lms_lessons')->insertGetId([
                'course_id' => $courseId,
                'title' => $title,
                'content' => 'This is the detailed content for ' . $title,
                'order' => $index + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::table('lms_contents')->insert([
            [
                'course_id' => $courseId,
                'lesson_id' => $lessonIds[0],
                'type' => 'quiz',
                'title' => 'Laravel Basics Quiz',
                'description' => 'Test your knowledge on the fundamental concepts of Laravel.',
                'rules' => json_encode(['duration' => 20, 'limit' => 2, 'deadline' => null]),
                'external_link' => null,
                'order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'course_id' => $courseId,
                'lesson_id' => $lessonIds[1],
                'type' => 'assignment',
                'title' => 'Eloquent Relationship Project',
                'description' => 'Create a small app demonstrating One-to-Many and Many-to-Many relationships.',
                'rules' => json_encode(['duration' => 0, 'limit' => 1, 'deadline' => now()->addDays(7)->format('Y-m-d H:i:s')]),
                'external_link' => null,
                'order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'course_id' => $courseId,
                'lesson_id' => null,
                'type' => 'live_class',
                'title' => 'Advanced Query Scopes Live Session',
                'description' => 'Join our live session to learn about advanced DB optimization.',
                'rules' => json_encode(['duration' => 60, 'limit' => 1, 'deadline' => now()->addDays(2)->format('Y-m-d H:i:s')]),
                'external_link' => 'https://zoom.us/test-session',
                'order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}