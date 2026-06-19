<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SystemSetting;

class HomepageSeeder extends Seeder
{
    private function getCurrencySymbol(string $code): string
    {
        $currencies = [
            'USD' => '$', 'EUR' => '€', 'GBP' => '£', 'JPY' => '¥', 'CAD' => 'C$', 
            'AUD' => 'A$', 'CHF' => 'CHF', 'CNY' => '¥', 'HKD' => 'HK$', 'INR' => '₹', 
            'BRL' => 'R$', 'RUB' => '₽', 'TRY' => '₺', 'ZAR' => 'R',
        ];
        return $currencies[$code] ?? '$';
    }

    public function run(array $appConfig = []): void
    {
        $currencyCode = $appConfig['currency'] ?? 'USD';
        $currencySymbol = $this->getCurrencySymbol($currencyCode);

        $generalSettings = [
            // --- Currency Settings from Installer ---
            'app_currency_code' => $currencyCode,
            'currency_code' => $currencyCode,
            'currency_symbol' => $currencySymbol,
            'currency_position' => 'before', // Default position
            'decimal_separator' => '.',      // Default
            'thousands_separator' => ',',    // Default
            
            // --- Timezone Setting from Installer ---
            'app_timezone' => $appConfig['timezone'] ?? 'UTC',

            // --- Toggles/Defaults (Safely OFF) ---
            'system_email_enable' => '0',
            'notify_signup_email' => '0',
            'notify_exam_email' => '0',
            'notify_payment_email' => '0',
            // ... (other default toggles from CMSSeeder should also be checked here if CMSSeeder is run before this)
        ];
        
        foreach ($generalSettings as $key => $value) {
            SystemSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'group' => 'general']
            );
        }

        $settings = [
            'frontend_show_hero'         => '1',
            'frontend_show_features'     => '1',
            'frontend_show_categories'   => '1',
            'frontend_show_exams'        => '1',
            'frontend_show_how_it_works' => '1',
            'frontend_show_audience'     => '1',
            'frontend_show_pricing'      => '1',
            'frontend_show_testimonials' => '1',
            'frontend_show_faq'          => '1',
            'frontend_show_cta'          => '1',

            'hero_badge'        => 'AI-POWERED ASSESSMENT PLATFORM',
            'hero_title'        => 'The Future of Secure Online Exams',
            'hero_subtitle'     => 'Experience AI-powered proctoring, instant grading, and detailed analytics. Secure exams for institutions, simplified for candidates.',
            'hero_cta_text'     => 'Get Started Now',
            'hero_cta_link'     => '/register',
            'hero_cta2_text'    => 'Live Demo',
            'hero_cta2_link'    => '#features',
            
            'home_stat_1_label' => 'Trusted by 10,000+ Users',
            'home_stat_3_count' => '4.9',
            'trust_badge_1'     => 'One-Time Payment',
            'trust_icon_1'      => 'fa-solid fa-shield-halved',
            'trust_badge_2'     => 'Secure Proctoring',
            'trust_icon_2'      => 'fa-solid fa-lock',
            'trust_badge_3'     => 'Instant Results',
            'trust_icon_3'      => 'fa-solid fa-bolt',

            'categories_title'       => 'Explore Exams by Category',
            'categories_subtitle'    => 'Browse through our diverse range of assessment categories designed for every skill level.',
            'categories_btn_text'    => 'View All Categories',
            'categories_btn_link'    => '/exams',
            'home_categories_list'   => '[]',

            'audience_title'    => 'Who is ZiExam AI For?',
            'audience_subtitle' => 'Our platform is designed to cater to a wide range of educational and professional needs.',
            'audience_btn_text' => 'Discover Features',
            'audience_btn_link' => '#features',

            'aud_c1_title'     => 'Coaching Institutes',
            'aud_c1_highlight' => 'Scale Your Business',
            'aud_c1_desc'      => 'Conduct large-scale mock tests with automated ranking.',
            'aud_c2_title'     => 'Schools & Colleges',
            'aud_c2_highlight' => 'Academic Integrity',
            'aud_c2_desc'      => 'Secure internal examinations with browser lockdown.',
            'aud_c3_title'     => 'Online Course Sellers',
            'aud_c3_highlight' => 'Value Addition',
            'aud_c3_desc'      => 'Bundle certification exams with your courses.',
            'aud_c4_title'     => 'SaaS Founders',
            'aud_c4_highlight' => 'Start Your SaaS',
            'aud_c4_desc'      => 'Launch your own exam platform business.',

            'features_title'    => 'Powerful Features for Modern Assessment',
            'features_subtitle' => 'Our platform offers everything you need to manage assessments efficiently.',

            'feat_p1_title'     => 'AI Exam Creation & Control',
            'feat_p1_hint_text' => 'AI',
            'feat_p1_desc'      => 'Leverage the power of Artificial Intelligence to generate questions instantly.',
            'feat_p1_i1_title'  => 'Instant Generation', 'feat_p1_i1_desc' => 'Create full exams from a single prompt.',
            'feat_p1_i2_title'  => 'Question Bank',      'feat_p1_i2_desc' => 'Manage and import questions into categorized banks.',
            'feat_p1_i3_title'  => 'Randomization',      'feat_p1_i3_desc' => 'Unique question sets for every student.',

            'feat_p2_title'     => 'Automated Evaluation',
            'feat_p2_hint_text' => 'Fast',
            'feat_p2_desc'      => 'Save hundreds of hours with instant auto-grading and deep analytics.',
            'feat_p2_i1_title'  => 'Auto Grading',   'feat_p2_i1_desc' => 'Results released immediately after submission.',
            'feat_p2_i2_title'  => 'Deep Analytics', 'feat_p2_i2_desc' => 'Question-wise analysis and strength reports.',
            'feat_p2_i3_title'  => 'Certificates',   'feat_p2_i3_desc' => 'Instant PDF certificate generation.',

            'feat_p3_title'     => 'Monetization & Access',
            'feat_p3_hint_text' => 'Revenue',
            'feat_p3_desc'      => 'Turn your knowledge into revenue with flexible payment models.',
            'feat_p3_i1_title'  => 'Paid Exams',    'feat_p3_i1_desc' => 'Sell individual exams with secure payments.',
            'feat_p3_i2_title'  => 'Subscriptions', 'feat_p3_i2_desc' => 'Create recurring revenue membership plans.',
            'feat_p3_i3_title'  => 'Coupon System', 'feat_p3_i3_desc' => 'Run marketing campaigns with discount codes.',

            'feat_p4_title'     => 'Management & Security',
            'feat_p4_hint_text' => 'Secure',
            'feat_p4_desc'      => 'Enterprise-grade security features to prevent cheating.',
            'feat_p4_i1_title'  => 'Proctoring',      'feat_p4_i1_desc' => 'Webcam and tab-switch monitoring.',
            'feat_p4_i2_title'  => 'Role Management', 'feat_p4_i2_desc' => 'Granular permissions for sub-admins.',
            'feat_p4_i3_title'  => 'Audit Log',       'feat_p4_i3_desc' => 'Track all system changes and user activity.',

            'how_it_works_title'    => 'Simple Steps to Launch',
            'how_it_works_subtitle' => 'Get your first exam live in under 10 minutes.',
            
            'hiw_s1_icon' => 'fa-solid fa-download', 'hiw_s1_title' => 'Install', 'hiw_s1_desc' => 'Setup the script on your server in minutes.',
            'hiw_s2_icon' => 'fa-solid fa-robot',    'hiw_s2_title' => 'Create',  'hiw_s2_desc' => 'Add questions manually or use AI generation.',
            'hiw_s3_icon' => 'fa-solid fa-tags',     'hiw_s3_title' => 'Price',   'hiw_s3_desc' => 'Assign plans and set up paid or free access.',
            'hiw_s4_icon' => 'fa-solid fa-chart-line', 'hiw_s4_title' => 'Analyze', 'hiw_s4_desc' => 'View results, activity, and proctoring logs.',

            'exams_title'       => 'Featured Assessments',
            'exams_count'       => '3',
            'exams_subtitle'    => 'Test your knowledge with our premium, curated examination sets.',
            'exams_bottom_text' => 'All plans include 24/7 support and free updates.',
            'exams_sub_title'    => 'Unlock Premium Access',
            'exams_sub_desc'     => 'Get unlimited access to all exams, advanced proctoring, and certification generation.',
            'exams_sub_btn_text' => 'View Plans',
            'exams_sub_btn_link' => '/pricing',

            'admin_preview_title'    => 'Control Everything from One Dashboard',
            'admin_preview_subtitle' => 'A centralized admin panel designed to manage users, exams, subscriptions, revenue, and AI usage at scale.',
            'admin_stat_1_val' => '10k+', 'admin_stat_1_lbl' => 'Users',
            'admin_stat_2_val' => '100%', 'admin_stat_2_lbl' => 'Control',
            'admin_stat_3_val' => 'Real', 'admin_stat_3_lbl' => 'Time',
            'admin_stat_4_val' => 'AI',   'admin_stat_4_lbl' => 'Usage',
            
            'cms_badge' => 'CMS INCLUDED',
            'cms_title' => 'Launch Your Website Instantly',
            'cms_desc'  => 'ZiExam AI includes a built-in CMS that lets you create dynamic pages, manage navigation menus, and edit homepage sections directly from the admin panel.',

            'pricing_title'        => 'Simple Pricing. Lifetime Ownership.',
            'pricing_subtitle'     => 'Choose the license that fits your business model. One-time purchase. No monthly fees.',
            'pricing_trust_1_text' => 'One-Time Payment', 'pricing_trust_1_icon' => 'fa-solid fa-shield-halved',
            'pricing_trust_2_text' => 'Lifetime Use',     'pricing_trust_2_icon' => 'fa-solid fa-infinity',
            'pricing_trust_3_text' => 'No Monthly Fees',  'pricing_trust_3_icon' => 'fa-solid fa-ban',
            'pricing_trust_4_text' => 'Quality Checked',  'pricing_trust_4_icon' => 'fa-solid fa-circle-check',
            'home_plans_list'      => '[]',

            'testimonials_title'    => 'Trusted by Educators Worldwide',
            'testimonials_subtitle' => 'Teams rely on our platform to build, evaluate, and scale online exams with confidence.',

            'faq_title'    => 'Frequently Asked Questions',
            'faq_subtitle' => 'Everything you need to know about the product and billing.',
            
            'faq_q1_icon' => 'fa-solid fa-server',
            'faq_q1_title' => 'Is this a SaaS ready script?',
            'faq_q1_desc'  => 'Yes. It supports multi-user roles, subscription models, and payment gateways out of the box.',
            
            'faq_q2_icon' => 'fa-solid fa-money-bill-transfer',
            'faq_q2_title' => 'Can I resell the exams?',
            'faq_q2_desc'  => 'Absolutely. You can create paid exams or bundle them into subscription plans.',
            
            'faq_q3_icon' => 'fa-brands fa-stripe',
            'faq_q3_title' => 'Can I add payment gateways?',
            'faq_q3_desc'  => 'Yes, you can integrate with major gateways like Stripe, PayPal, and others.',

            'faq_q4_icon' => 'fa-solid fa-globe',
            'faq_q4_title' => 'Can I use my own domain?',
            'faq_q4_desc'  => 'Yes, the script is designed for single-domain use, which you fully control.',

            'cta_title'     => 'Start Your Online Exam Business Today',
            'cta_subtitle'  => 'Get the most advanced AI-powered examination script on the market.',
            'cta_btn_text'  => 'Get Started Now',
            'cta_btn_link'  => '/register',
            'cta_btn2_text' => 'Live Demo',
            'cta_btn2_link' => '#',
        ];

        foreach ($settings as $key => $value) {
            SystemSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'group' => 'homepage']
            );
        }
    }
}