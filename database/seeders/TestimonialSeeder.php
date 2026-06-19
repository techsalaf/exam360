<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Testimonial;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $testimonials = [
            [
                'name' => 'Ananya Rao',
                'role' => 'Academic Coordinator',
                'review' => 'We went from manual grading to instant evaluation overnight. The platform completely transformed how we conduct assessments.',
                'rating' => 5,
            ],
            [
                'name' => 'Daniel Moore',
                'role' => 'Training Lead',
                'review' => 'Creating exams used to take days. Now we generate structured, high-quality tests in minutes without compromising standards.',
                'rating' => 5,
            ],
            [
                'name' => 'Meera Patel',
                'role' => 'Program Manager',
                'review' => 'The analytics alone are worth it. We finally understand how students are performing across every exam topic.',
                'rating' => 4,
            ],
            [
                'name' => 'Lucas Bennett',
                'role' => 'EdTech Founder',
                'review' => 'Launching paid exams was seamless. Subscriptions, access control, and reporting—all in one place.',
                'rating' => 5,
            ],
            [
                'name' => 'Sarah Collins',
                'role' => 'Assessment Specialist',
                'review' => 'The AI evaluation for descriptive answers saved our team countless hours and improved grading consistency.',
                'rating' => 5,
            ],
            [
                'name' => 'Rohit Verma',
                'role' => 'Operations Director',
                'review' => 'It feels like a system built for scale. From roles to payments, everything just works perfectly.',
                'rating' => 5,
            ],
            [
                'name' => 'Emily Zhang',
                'role' => 'Learning Designer',
                'review' => 'Our instructors adopted it immediately. The interface is intuitive and requires almost no training.',
                'rating' => 4,
            ],
            [
                'name' => 'Michael Turner',
                'role' => 'Education Consultant',
                'review' => 'This platform didn’t just improve exams—it improved our entire workflow and student engagement.',
                'rating' => 5,
            ],
        ];

        foreach ($testimonials as $index => $item) {
            Testimonial::create([
                'name'       => $item['name'],
                'role'       => $item['role'],
                'review'     => $item['review'],
                'rating'     => $item['rating'],
                'avatar'     => null, // Images should be uploaded via Admin Panel
                'is_active'  => true,
                'sort_order' => $index,
            ]);
        }
    }
}