<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SystemSetting;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;

class CMSSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'header_logo_height' => '34',
            'header_cta_text' => 'Start Free',
            'header_cta_url' => '/register',
            
            'footer_about' => 'ZiExam AI is a leading online examination platform providing secure, scalable, and intelligent assessment solutions for educational institutions and corporations.',
            'footer_copyright' => '© ' . date('Y') . ' ZiExam AI. All rights reserved.',
            
            'social_facebook' => 'https://facebook.com',
            'social_twitter' => 'https://twitter.com',
            'social_instagram' => 'https://instagram.com',
            'social_linkedin' => 'https://linkedin.com',
            'social_youtube' => 'https://youtube.com',
            
            'contact_address' => '123 Innovation Dr, Tech Valley, CA 94043',
            'contact_email' => 'support@ziexam-ai.com',
            'contact_phone' => '+1 (555) 012-3456',
            
            'footer_col1_title' => 'Useful Links',
            'footer_col2_title' => 'Legal',

            'system_email_enable' => '0',
            'system_sms_enable' => '0',
            'system_push_enable' => '0',

            'notify_signup_email' => '0',
            'notify_signup_sms' => '0',
            'notify_signup_push' => '0',

            'notify_exam_email' => '0',
            'notify_exam_sms' => '0',
            'notify_exam_push' => '0',

            'notify_payment_email' => '0',
            'notify_payment_sms' => '0',
            'notify_payment_push' => '0',

            'mail_driver' => 'smtp',
            'mail_host' => '',
            'mail_port' => '587',
            'mail_username' => '',
            'mail_password' => '',
            'mail_encryption' => 'tls',
            'mail_from_address' => '',
            'mail_from_name' => 'ZiExam AI',
        ];

        foreach ($settings as $key => $value) {
            SystemSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'group' => 'cms']
            );
        }

        DB::table('menus')->truncate();

        Menu::create([
            'name' => 'Header Menu',
            'location' => 'header',
            'items' => [
                ['label' => 'Home', 'url' => '/'],
                ['label' => 'Exams', 'url' => '/exams'],
            ]
        ]);

        Menu::create([
            'name' => 'Useful Links',
            'location' => 'footer_column_1',
            'items' => [
                ['label' => 'About Us', 'url' => '/about-us'],
                ['label' => 'Pricing Plans', 'url' => '/pricing'],
                ['label' => 'Contact Support', 'url' => '/contact'],
            ]
        ]);

        Menu::create([
            'name' => 'Legal',
            'location' => 'footer_column_2',
            'items' => [
                ['label' => 'Terms of Service', 'url' => '/terms-of-service'],
                ['label' => 'Privacy Policy', 'url' => '/privacy-policy'],
                ['label' => 'Cookie Policy', 'url' => '/cookies'],
            ]
        ]);
    }
}