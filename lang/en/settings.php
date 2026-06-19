<?php

return [
    'mobile_menu' => 'Settings Menu',
    'nav_title'   => 'Settings Navigation',
    
    'groups' => [
        'system'        => 'System',
        'appearance'    => 'Appearance',
        'communication' => 'Communication',
        'billing'       => 'Billing',
        'regional'      => 'Regional',
        'visibility'    => 'Visibility',
        'automation'    => 'Automation',
        'security'      => 'Security',
    ],

    'links' => [
        'general'     => ['title' => 'General Settings', 'sub' => 'Site Identity & Timezone'],
        'config'      => ['title' => 'Core Config', 'sub' => 'Environment & Limits'],
        'roles'       => ['title' => 'Roles & Permissions', 'sub' => 'User Access Control'],
        'maintenance' => ['title' => 'Maintenance', 'sub' => 'Downtime Management'],
        
        'logo'         => ['title' => 'Logo & Favicon', 'sub' => 'Brand Assets'],
        'registration' => ['title' => 'Registration Fields', 'sub' => 'Signup Form Customization'],
        'certificates' => ['title' => 'Certificates', 'sub' => 'Templates & Design'],
        'frontend'     => ['title' => 'Frontend', 'sub' => 'Public Visibility'],
        'css'          => ['title' => 'Custom CSS', 'sub' => 'Global Overrides'],
        
        'alerts' => ['title' => 'Alerts', 'sub' => 'System Notifications'],
        'email'  => ['title' => 'Email Setup', 'sub' => 'SMTP & Drivers'],
        'social' => ['title' => 'Social Login', 'sub' => 'OAuth Providers'],
        
        'gateways' => ['title' => 'Gateways', 'sub' => 'Stripe, PayPal, etc.'],
        'currency' => ['title' => 'Currency', 'sub' => 'Symbols & Formats'],
        'tax'      => ['title' => 'Tax Rules', 'sub' => 'VAT & Sales Tax'],
        
        'language' => ['title' => 'Localization', 'sub' => 'Language & Country'],
        
        'seo'     => ['title' => 'SEO Config', 'sub' => 'Meta Tags & Analytics'],
        'sitemap' => ['title' => 'Sitemap', 'sub' => 'XML Generation'],
        
        'ai'         => ['title' => 'AI Integration', 'sub' => 'LLM Configuration'],
        'cron'       => ['title' => 'Cron Jobs', 'sub' => 'Scheduled Tasks'],
        'extensions' => ['title' => 'Extensions', 'sub' => 'Modules & Add-ons'],
        
        'gdpr'   => ['title' => 'GDPR & Cookies', 'sub' => 'Consent Management'],
        'policy' => ['title' => 'Legal Pages', 'sub' => 'Terms & Privacy'],
    ],

    'status' => [
        'operational' => 'System Operational'
    ]
];