<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Aptitude Test',
                'description' => 'Prepare for competitive exams with our aptitude mock tests.',
                'icon' => 'fa-brain',
                'meta_text_1' => 'Timed Tests',
                'meta_text_2' => 'Ranking',
                'is_active' => true,
            ],
            [
                'name' => 'General Knowledge',
                'description' => 'Enhance your GK with daily quizzes and current affairs.',
                'icon' => 'fa-globe',
                'meta_text_1' => 'Daily Quiz',
                'meta_text_2' => 'Global',
                'is_active' => true,
            ],
            [
                'name' => 'Mathematics',
                'description' => 'Solve complex problems and master mathematical concepts.',
                'icon' => 'fa-calculator',
                'meta_text_1' => 'Problem Solving',
                'meta_text_2' => 'Formulas',
                'is_active' => true,
            ],
            [
                'name' => 'Computer Science',
                'description' => 'Learn programming logic, algorithms, and system design.',
                'icon' => 'fa-laptop-code',
                'meta_text_1' => 'Coding',
                'meta_text_2' => 'Tech Skills',
                'is_active' => true,
            ],
            [
                'name' => 'English Literature',
                'description' => 'Explore classic literature, grammar, and vocabulary.',
                'icon' => 'fa-book-open',
                'meta_text_1' => 'Grammar',
                'meta_text_2' => 'Vocabulary',
                'is_active' => true,
            ],
            [
                'name' => 'Physics',
                'description' => 'Understand the laws of nature with physics simulations.',
                'icon' => 'fa-atom',
                'meta_text_1' => 'Theory',
                'meta_text_2' => 'Labs',
                'is_active' => true,
            ],
            [
                'name' => 'Chemistry',
                'description' => 'Dive into reactions, periodic tables, and organic chemistry.',
                'icon' => 'fa-flask',
                'meta_text_1' => 'Reactions',
                'meta_text_2' => 'Lab Safe',
                'is_active' => true,
            ],
            [
                'name' => 'Logical Reasoning',
                'description' => 'Sharpen your mind with puzzles and logic games.',
                'icon' => 'fa-puzzle-piece',
                'meta_text_1' => 'Puzzles',
                'meta_text_2' => 'IQ Test',
                'is_active' => false,
            ],
            [
                'name' => 'Web Development',
                'description' => 'Full stack development quizzes from HTML to Laravel.',
                'icon' => 'fa-code',
                'meta_text_1' => 'Frontend',
                'meta_text_2' => 'Backend',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $index => $cat) {
            Category::create([
                'name' => $cat['name'],
                'slug' => Str::slug($cat['name']),
                'description' => $cat['description'],
                'icon' => $cat['icon'],
                'meta_text_1' => $cat['meta_text_1'],
                'meta_text_2' => $cat['meta_text_2'],
                'is_active' => $cat['is_active'],
                'sort_order' => $index,
            ]);
        }
    }
}