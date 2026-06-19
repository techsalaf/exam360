<?php

return [
    'save' => 'পরিবর্তন সংরক্ষণ করুন',
    'configure' => 'কনফিগার করুন',
    'active' => 'সক্রিয়',
    'variables' => 'ভেরিয়েবলস:',
    'test_mail' => 'টেস্ট মেইল',
    'copy_url' => 'URL কপি করুন',
    
    'general' => [
        'title' => 'নোটিফিকেশন সেন্টার',
        'subtitle' => 'সকল কমিউনিকেশন চ্যানেলের জন্য ট্রিগার, লজিক এবং গেটওয়ে ম্যানেজ করুন।',
        'tabs' => [
            'logic' => 'লজিক এবং ট্রিগার',
            'sms' => 'SMS গেটওয়ে',
            'push' => 'পুশ কনফিগারেশন',
            'templates' => 'টেমপ্লেট',
        ],
        'kpi' => [
            'email' => 'ইমেল সিস্টেম',
            'sms' => 'SMS সিস্টেম',
            'push' => 'পুশ নোটিফিকেশন',
            'global_switch' => 'গ্লোবাল সুইচ',
        ],
        'triggers' => [
            'title' => 'ইভেন্ট ট্রিগার এবং রাউটিং',
            'col_event' => 'ইভেন্ট ট্রিগার',
            'signup' => 'নতুন ব্যবহারকারী রেজিস্ট্রেশন',
            'signup_desc' => 'সাইন আপ করার সাথে সাথেই ট্রিগার হয়।',
            'exam' => 'পরীক্ষা সম্পন্ন',
            'exam_desc' => 'ফলাফল এবং স্কোর প্রসেসিং।',
            'payment' => 'পেমেন্ট সফল',
            'payment_desc' => 'ইনভয়েস এবং প্ল্যান অ্যাক্টিভেশন।',
        ],
        'sms_gateways' => [
            'provider' => 'SMS প্রোভাইডার',
            'twilio' => 'Twilio',
            'vonage' => 'Vonage',
            'standard' => 'স্ট্যান্ডার্ড',
            'international' => 'ইন্টারন্যাশনাল',
            'api_creds' => 'API ক্রেডেনশিয়ালস',
            'account_sid' => 'Account SID',
            'api_key' => 'API Key',
            'auth_token' => 'Auth Token',
            'api_secret' => 'API Secret',
            'from' => 'প্রেরক নম্বর',
            'from_desc' => 'প্রেরক নম্বর (E.164)',
            'sender_id' => 'Sender ID (ব্র্যান্ড নেম)',
            'env' => 'এনভায়রনমেন্ট',
            'sandbox' => 'স্যান্ডবক্স মোড চালু করুন',
        ],
        'firebase' => [
            'title' => 'Firebase ক্লাউড মেসেজিং (FCM)',
            'server_key' => 'সার্ভার কী (Legacy)',
            'project_id' => 'Project ID',
            'app_id' => 'App ID',
            'sender_id' => 'Sender ID',
            'bucket' => 'Storage Bucket',
        ],
        'template_links' => [
            'email' => 'ইমেল টেমপ্লেট',
            'sms' => 'SMS টেমপ্লেট',
            'push' => 'পুশ টেমপ্লেট',
        ],
    ],

    'social' => [
        'title' => 'সোশ্যাল লগইন',
        'subtitle' => 'এক ক্লিকে রেজিস্ট্রেশন ও লগইন এর জন্য OAuth কনফিগার করুন।',
        'google' => 'গুগল লগইন',
        'google_desc' => 'গুগল অ্যাকাউন্টের মাধ্যমে লগইন চালু করুন।',
        'facebook' => 'ফেসবুক লগইন',
        'facebook_desc' => 'ফেসবুক অ্যাকাউন্টের মাধ্যমে লগইন চালু করুন।',
        'client_id' => 'Client ID',
        'client_secret' => 'Client Secret',
        'app_id' => 'App ID',
        'app_secret' => 'App Secret',
        'callback' => 'কলব্যাক URL',
        'google_help' => 'এই URL টি আপনার Google Developer Console কনফিগারেশনে পেস্ট করুন।',
        'facebook_help' => 'এই URL টি আপনার Facebook Developer সেটিংসে পেস্ট করুন।',
    ],

    'email' => [
        'title' => 'ইমেল কনফিগারেশন',
        'subtitle' => 'আউটগোয়িং মেইল ড্রাইভার এবং প্রেরকের পরিচয় কনফিগার করুন।',
        'driver_label' => 'মেইল ড্রাইভার',
        'drivers' => [
            'smtp' => 'SMTP',
            'smtp_desc' => 'সুপারিশকৃত',
            'php' => 'PHP মেইল',
            'php_desc' => 'সার্ভার ডিফল্ট',
            'mailgun' => 'Mailgun',
            'mailgun_desc' => 'API ভিত্তিক',
        ],
        'smtp' => [
            'title' => 'SMTP কানেকশন ডিটেইলস',
            'host' => 'মেইল হোস্ট',
            'port' => 'পোর্ট',
            'username' => 'ইউজারনেম',
            'password' => 'পাসওয়ার্ড',
            'encryption' => 'এনক্রিপশন',
            'none' => 'নেই',
        ],
        'mailgun' => [
            'title' => 'Mailgun API কনফিগারেশন',
            'domain' => 'Mailgun ডোমেইন',
            'secret' => 'Mailgun সিক্রেট (API Key)',
            'endpoint' => 'Mailgun এন্ডপয়েন্ট',
            'help' => 'EU অঞ্চলের জন্য api.eu.mailgun.net ব্যবহার করুন।',
        ],
        'identity' => [
            'title' => 'প্রেরকের পরিচয়',
            'name' => 'প্রেরকের নাম',
            'name_desc' => 'প্রাপকের ইনবক্সে প্রদর্শিত হবে।',
            'address' => 'প্রেরকের ইমেল',
            'address_desc' => 'আপনার প্রোভাইডার দ্বারা অনুমোদিত হতে হবে।',
        ],
        'alerts' => [
            'confirm_test' => 'বর্তমান ব্যবহারকারীকে একটি টেস্ট ইমেল পাঠাবেন? নিশ্চিত করুন যে আপনি পরিবর্তনগুলি সেভ করেছেন।',
            'sending' => 'পাঠানো হচ্ছে...',
            'success' => 'সফল',
            'failed' => 'কানেকশন ব্যর্থ',
            'error' => 'একটি অপ্রত্যাশিত ত্রুটি ঘটেছে।',
        ]
    ],

    'templates' => [
        'email_title' => 'ইমেল টেমপ্লেট',
        'email_subtitle' => 'সিস্টেম ইমেলের জন্য HTML সাবজেক্ট এবং বডি কাস্টমাইজ করুন।',
        'sms_title' => 'SMS টেমপ্লেট',
        'sms_subtitle' => '১৬০ অক্ষরের টেক্সট মেসেজ কনফিগার করুন।',
        'push_title' => 'পুশ নোটিফিকেশন',
        'push_subtitle' => 'মোবাইল অ্যাপ অ্যালার্ট এবং কনটেন্ট ম্যানেজ করুন।',
        
        'tabs' => [
            'signup' => 'সাইনআপ স্বাগতম',
            'exam' => 'পরীক্ষার ফলাফল',
            'payment' => 'পেমেন্ট রসিদ',
        ],
        
        'fields' => [
            'subject' => 'সাবজেক্ট',
            'html_body' => 'HTML বডি',
            'content' => 'মেসেজ কনটেন্ট',
            'alert_title' => 'অ্যালার্ট টাইটেল',
            'alert_body' => 'অ্যালার্ট বডি',
        ],

        'defaults' => [
            'signup_sub' => 'আমাদের প্ল্যাটফর্মে স্বাগতম!',
            'signup_body' => '<p>হ্যালো {{name}},</p><p>আমাদের প্ল্যাটফর্মে স্বাগতম!</p>',
            'exam_sub' => 'পরীক্ষার ফলাফল প্রকাশিত হয়েছে',
            'exam_body' => '<p>হাই {{name}},</p><p>আপনি <strong>{{score}}%</strong> স্কোর করেছেন।</p>',
            'pay_sub' => 'পেমেন্ট রসিদ',
            'pay_body' => '<p>আমরা আপনার <strong>{{amount}}</strong> পেমেন্ট পেয়েছি।</p>',
            
            'push_signup_t' => 'স্বাগতম!',
            'push_signup_b' => 'আমাদের সাথে যোগ দেওয়ার জন্য ধন্যবাদ।',
            'push_exam_t' => 'পরীক্ষার ফলাফল',
            'push_exam_b' => 'আপনি {{exam}}-এ {{score}}% পেয়েছেন।',
            'push_pay_t' => 'পেমেন্ট রিসিভড',
            'push_pay_b' => 'আপনার {{plan}} সাবস্ক্রিপশন সক্রিয় হয়েছে।',
            
            'sms_signup' => 'Ziexam-এ স্বাগতম, {{name}}! ভেরিফাই করুন: {{link}}',
            'sms_exam' => 'অভিনন্দন {{name}}! আপনি {{score}}% নিয়ে {{exam}} পাস করেছেন।',
            'sms_pay' => '{{plan}}-এর জন্য {{amount}} পেমেন্ট গৃহীত হয়েছে। ধন্যবাদ!',
        ],

        'preview' => [
            'label' => 'প্রিভিউ',
            'app_name' => 'অ্যাপ নেম',
            'now' => 'এখন',
        ]
    ]
];