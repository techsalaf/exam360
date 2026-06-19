<?php

return [
    'save' => 'Save Changes',
    'recommended' => 'Recommended:',
    'or' => 'or',
    'copy_url' => 'Copy URL',
    
    'ai' => [
        'title' => 'AI Configuration',
        'desc' => 'Connect to LLM providers for automated content generation and grading.',
        'primary_driver' => 'Primary AI Driver',
        'driver_desc' => 'Select the engine used for generation tasks.',
        'disabled' => 'Disabled',
        'driver_gemini' => 'Google Gemini (Recommended)',
        'driver_openai' => 'OpenAI (ChatGPT)',
        
        'gemini' => [
            'title' => 'Gemini Settings',
            'desc' => 'Configure access to Google\'s generative AI models.',
            'api_key' => 'Gemini API Key',
            'model' => 'Model Version',
        ],
        
        'openai' => [
            'title' => 'OpenAI Settings',
            'desc' => 'Configure access to ChatGPT models.',
            'api_key' => 'OpenAI API Key',
            'model' => 'Model Version',
        ],
    ],

    'cron' => [
        'title' => 'Cron Job Scheduler',
        'desc' => 'Manage scheduled background tasks for notifications and maintenance.',
        'info_title' => 'Why is this required?',
        'info_desc' => 'Cron jobs handle critical recurring tasks like exam result processing, subscription renewals, and email notifications. Without this configuration, automated features will not function.',
        'server_cmd' => 'Server Command',
        'server_cmd_desc' => 'Add this entry to your server\'s crontab (e.g., cPanel).',
        'entry_label' => 'Cron Entry (Run every minute)',
        'token_label' => 'Security Token',
        'token_ph' => 'Unique security token',
        'token_help' => 'Secures the URL for external cron services.',
        'enable_label' => 'Enable Scheduler',
        'enable_desc' => 'Process background tasks.',
        'copied' => 'Command copied to clipboard',
        'copy_fail' => 'Failed to copy command',
    ],

    'ext' => [
        'title' => 'Extension Configurations',
        'desc' => 'Manage third-party integrations, widgets, and security tools.',
        
        'google' => [
            'title' => 'Google Login',
            'desc' => 'Allow users to login with Google.',
            'client_id' => 'Client ID',
            'client_secret' => 'Client Secret',
            'callback' => 'Callback URL',
            'help' => 'Paste this URL into your Google Developer Console configuration.',
        ],

        'facebook' => [
            'title' => 'Facebook Login',
            'desc' => 'Allow users to login with Facebook.',
            'app_id' => 'App ID',
            'app_secret' => 'App Secret',
            'callback' => 'Callback URL',
            'help' => 'Paste this URL into your Facebook Developer Settings (OAuth redirect URIs).',
        ],

        'captcha' => [
            'title' => 'Custom Captcha',
            'desc' => 'Internal verification.',
            'length' => 'Code Length',
            'chars' => 'Characters',
        ],

        'recaptcha' => [
            'title' => 'Google Recaptcha v2',
            'desc' => 'Spam protection.',
            'site_key' => 'Site Key',
            'secret_key' => 'Secret Key',
        ],

        'tawk' => [
            'title' => 'Tawk.to Live Chat',
            'desc' => 'Customer support.',
            'link_label' => 'Direct Chat Link',
            'link_help' => 'Paste your Direct Chat Link from the Tawk.to dashboard.',
        ],
    ],
];