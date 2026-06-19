<?php

return [
    'save' => 'Save Changes',
    'configure' => 'Configure',
    'active' => 'Active',
    'variables' => 'Variables:',
    'test_mail' => 'Test Mail',
    'copy_url' => 'Copy URL',
    
    'general' => [
        'title' => 'Notification Center',
        'subtitle' => 'Manage triggers, logic, and third-party gateways for all communication channels.',
        'tabs' => [
            'logic' => 'Logic & Triggers',
            'sms' => 'SMS Gateway',
            'push' => 'Push Config',
            'templates' => 'Templates',
        ],
        'kpi' => [
            'email' => 'Email System',
            'sms' => 'SMS System',
            'push' => 'Push Notifs',
            'global_switch' => 'Global Switch',
        ],
        'triggers' => [
            'title' => 'Event Triggers & Routing',
            'col_event' => 'Event Trigger',
            'signup' => 'New User Registration',
            'signup_desc' => 'Triggered immediately after signup.',
            'exam' => 'Exam Completion',
            'exam_desc' => 'Results and score processing.',
            'payment' => 'Payment Successful',
            'payment_desc' => 'Invoices and plan activation.',
        ],
        'sms_gateways' => [
            'provider' => 'SMS Provider',
            'twilio' => 'Twilio',
            'vonage' => 'Vonage',
            'standard' => 'Standard',
            'international' => 'International',
            'api_creds' => 'API Credentials',
            'account_sid' => 'Account SID',
            'api_key' => 'API Key',
            'auth_token' => 'Auth Token',
            'api_secret' => 'API Secret',
            'from' => 'From Number',
            'from_desc' => 'From Number (E.164)',
            'sender_id' => 'Sender ID (Brand Name)',
            'env' => 'Environment',
            'sandbox' => 'Enable Sandbox Mode',
        ],
        'firebase' => [
            'title' => 'Firebase Cloud Messaging (FCM)',
            'server_key' => 'Server Key (Legacy)',
            'project_id' => 'Project ID',
            'app_id' => 'App ID',
            'sender_id' => 'Sender ID',
            'bucket' => 'Storage Bucket',
        ],
        'template_links' => [
            'email' => 'Email Templates',
            'sms' => 'SMS Templates',
            'push' => 'Push Templates',
        ],
    ],

    'social' => [
        'title' => 'Social Login',
        'subtitle' => 'Configure OAuth providers for one-click user registration and login.',
        'google' => 'Google Login',
        'google_desc' => 'Enable login via Google account.',
        'facebook' => 'Facebook Login',
        'facebook_desc' => 'Enable login via Facebook account.',
        'client_id' => 'Client ID',
        'client_secret' => 'Client Secret',
        'app_id' => 'App ID',
        'app_secret' => 'App Secret',
        'callback' => 'Callback URL',
        'google_help' => 'Paste this URL into your Google Developer Console configuration.',
        'facebook_help' => 'Paste this URL into your Facebook Developer settings (OAuth redirect URIs).',
    ],

    'email' => [
        'title' => 'Email Configuration',
        'subtitle' => 'Configure outgoing mail drivers and sender identity for system emails.',
        'driver_label' => 'Mail Driver',
        'drivers' => [
            'smtp' => 'SMTP',
            'smtp_desc' => 'Recommended',
            'php' => 'PHP Mail',
            'php_desc' => 'Server Default',
            'mailgun' => 'Mailgun',
            'mailgun_desc' => 'API Based',
        ],
        'smtp' => [
            'title' => 'SMTP Connection Details',
            'host' => 'Mail Host',
            'port' => 'Port',
            'username' => 'Username',
            'password' => 'Password',
            'encryption' => 'Encryption',
            'none' => 'None',
        ],
        'mailgun' => [
            'title' => 'Mailgun API Configuration',
            'domain' => 'Mailgun Domain',
            'secret' => 'Mailgun Secret (API Key)',
            'endpoint' => 'Mailgun Endpoint',
            'help' => 'Use api.eu.mailgun.net for EU regions.',
        ],
        'identity' => [
            'title' => 'Sender Identity',
            'name' => 'From Name',
            'name_desc' => 'Displayed in the recipient\'s inbox.',
            'address' => 'From Email',
            'address_desc' => 'Must be authorized by your provider.',
        ],
        'alerts' => [
            'confirm_test' => 'Send a test email to the currently logged-in user? Ensure you have Saved Changes first.',
            'sending' => 'Sending...',
            'success' => 'Success',
            'failed' => 'Connection Failed',
            'error' => 'An unexpected error occurred.',
        ]
    ],

    'templates' => [
        'email_title' => 'Email Templates',
        'email_subtitle' => 'Customize the HTML subject and body for system emails.',
        'sms_title' => 'SMS Templates',
        'sms_subtitle' => 'Configure 160-character text messages sent to users.',
        'push_title' => 'Push Notifications',
        'push_subtitle' => 'Manage mobile app alerts styling and content.',
        
        'tabs' => [
            'signup' => 'Signup Welcome',
            'exam' => 'Exam Result',
            'payment' => 'Payment Receipt',
        ],
        
        'fields' => [
            'subject' => 'Subject Line',
            'html_body' => 'HTML Body',
            'content' => 'Message Content',
            'alert_title' => 'Alert Title',
            'alert_body' => 'Alert Body',
        ],

        'defaults' => [
            'signup_sub' => 'Welcome to our platform!',
            'signup_body' => '<p>Hello {{name}},</p><p>Welcome to our platform!</p>',
            'exam_sub' => 'Exam Results Available',
            'exam_body' => '<p>Hi {{name}},</p><p>You scored <strong>{{score}}%</strong>.</p>',
            'pay_sub' => 'Payment Receipt',
            'pay_body' => '<p>We received your payment of <strong>{{amount}}</strong>.</p>',
            
            'push_signup_t' => 'Welcome!',
            'push_signup_b' => 'Thanks for joining our platform.',
            'push_exam_t' => 'Exam Results',
            'push_exam_b' => 'You scored {{score}}% on {{exam}}.',
            'push_pay_t' => 'Payment Received',
            'push_pay_b' => 'Your subscription for {{plan}} is active.',
            
            'sms_signup' => 'Welcome to Ziexam, {{name}}! Verify here: {{link}}',
            'sms_exam' => 'Congrats {{name}}! You passed {{exam}} with {{score}}%.',
            'sms_pay' => 'Payment of {{amount}} received for {{plan}}. Thanks!',
        ],

        'preview' => [
            'label' => 'Preview',
            'app_name' => 'App Name',
            'now' => 'now',
        ]
    ]
];