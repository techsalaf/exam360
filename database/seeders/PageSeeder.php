<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;
use App\Models\PageSection;
use Illuminate\Support\Str;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'title' => 'About Us',
                'meta_description' => 'Learn about ZiExam AI and our mission to revolutionize online assessments.',
                'sections' => [
                    [
                        'type' => 'hero',
                        'content' => [
                            'heading' => 'Revolutionizing Online Assessments',
                            'subtext' => 'We provide secure, scalable, and intelligent exam solutions for institutions worldwide.'
                        ]
                    ],
                    [
                        'type' => 'text',
                        'content' => [
                            'body' => '<p><strong>ZiExam AI</strong> was founded with a single mission: to make examinations accessible, secure, and easy to manage. In an era where digital transformation is reshaping education, traditional testing methods often fall short. We bridge that gap.</p><p>Our platform leverages advanced AI to ensure integrity during exams, automated grading to save instructors time, and detailed analytics to help students understand their performance.</p>'
                        ]
                    ],
                    [
                        'type' => 'features',
                        'content' => [
                            'items' => "AI-Powered Proctoring\nInstant Result Generation\nGlobal Accessibility\nBank-Grade Security"
                        ]
                    ]
                ]
            ],
            [
                'title' => 'Terms of Service',
                'meta_description' => 'Read our terms and conditions regarding the use of our platform.',
                'sections' => [
                    [
                        'type' => 'hero',
                        'content' => [
                            'heading' => 'Terms of Service',
                            'subtext' => 'Please read these terms carefully before using our platform.'
                        ]
                    ],
                    [
                        'type' => 'text',
                        'content' => [
                            'body' => '<h4>1. Acceptance of Terms</h4><p>By accessing and using ZiExam AI, you accept and agree to be bound by the terms and provision of this agreement. In addition, when using these particular services, you shall be subject to any posted guidelines or rules applicable to such services.</p><h4>2. Provision of Services</h4><p>You agree and acknowledge that ZiExam AI is entitled to modify, improve or discontinue any of its services at its sole discretion and without notice to you even if it may result in you being prevented from accessing any information contained in it.</p><h4>3. Proprietary Rights</h4><p>You acknowledge and agree that ZiExam AI may contain proprietary and confidential information including trademarks, service marks and patents protected by intellectual property laws and international intellectual property treaties.</p>'
                        ]
                    ]
                ]
            ],
            [
                'title' => 'Privacy Policy',
                'meta_description' => 'We value your privacy. Learn how we handle your data.',
                'sections' => [
                    [
                        'type' => 'hero',
                        'content' => [
                            'heading' => 'Privacy Policy',
                            'subtext' => 'Your data security is our top priority.'
                        ]
                    ],
                    [
                        'type' => 'text',
                        'content' => [
                            'body' => '<p>At ZiExam AI, accessible from our main website, one of our main priorities is the privacy of our visitors. This Privacy Policy document contains types of information that is collected and recorded by us and how we use it.</p><ul><li>We do not sell your personal data.</li><li>We use encryption for all data transmission.</li><li>You have the right to request data deletion.</li></ul><p>If you have additional questions or require more information about our Privacy Policy, do not hesitate to contact us.</p>'
                        ]
                    ]
                ]
            ]
        ];

        foreach ($pages as $pageData) {
            $page = Page::firstOrCreate(
                ['slug' => Str::slug($pageData['title'])],
                [
                    'title' => $pageData['title'],
                    'meta_description' => $pageData['meta_description'],
                    'is_published' => true,
                ]
            );

            $page->sections()->delete();

            foreach ($pageData['sections'] as $index => $section) {
                PageSection::create([
                    'page_id' => $page->id,
                    'type' => $section['type'],
                    'content' => $section['content'],
                    'sort_order' => $index
                ]);
            }
        }
    }
}