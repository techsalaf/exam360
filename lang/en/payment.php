<?php

return [
    'save_settings' => 'Save Settings',
    'save_gateway' => 'Save Gateway Settings',
    'enable' => 'Enable',
    
    
    'currency' => [
        'title' => 'Currency Configuration',
        'desc' => 'Configure global currency format, symbols, and display options.',
        'global_currency' => 'Global Currency',
        'global_desc' => 'Set the primary currency for all transactions.',
        'primary' => 'Primary Currency',
        'custom_opt' => '--- CUSTOM CURRENCY ---',
        'custom_code' => 'Custom Code (e.g., QAR)',
        'custom_symbol' => 'Custom Symbol (e.g., ₹)',
        'position' => 'Symbol Position',
        'pos_before' => 'Before Amount ($100)',
        'pos_after' => 'After Amount (100$)',
        'pos_before_space' => 'Before with Space ($ 100)',
        'pos_after_space' => 'After with Space (100 $)',
        'decimal_sep' => 'Decimal Separator',
        'decimal_help' => 'Character used for decimals (e.g. 10.00).',
        'thousands_sep' => 'Thousands Separator',
        'thousands_help' => 'Character used for thousands (e.g. 1,000).',
    ],

    'gateways' => [
        'stripe' => [
            'title' => 'Stripe Configuration',
            'desc' => 'Accept credit card payments via Stripe.',
            'public_key' => 'Stripe Public Key',
            'secret_key' => 'Stripe Secret Key',
            'webhook_secret' => 'Stripe Webhook Secret',
            'webhook_url' => 'Webhook URL:',
        ],
        'paypal' => [
            'title' => 'PayPal Configuration',
            'desc' => 'Accept payments via PayPal or PayPal Credit.',
            'client_id' => 'PayPal Client ID',
            'secret_key' => 'PayPal Secret Key',
            'env' => 'Environment',
            'sandbox' => 'Sandbox (Testing)',
            'live' => 'Live (Production)',
        ],
        'razorpay' => [
            'title' => 'Razorpay Configuration',
            'desc' => 'Popular payment gateway for India.',
            'key_id' => 'Razorpay Key ID',
            'key_secret' => 'Razorpay Key Secret',
        ],
        'offline' => [
            'title' => 'Offline/Bank Transfer',
            'desc' => 'Allow users to pay directly (requires manual confirmation).',
            'holder_name' => 'Account Holder Name',
            'bank_name' => 'Bank Name',
            'acc_number' => 'Account Number / IBAN',
            'swift_code' => 'IFSC / SWIFT Code',
            'instructions' => 'Additional Instructions or Regional Banks',
            'instructions_help' => 'Use this area for secondary payment methods or specific regional instructions.',
            'default_inst' => 'Example: For payments within India, use Bank A. For international payments, see details above.',
        ],
    ],

    'tax' => [
        'title' => 'Tax Configuration',
        'desc' => 'Configure VAT, GST, or Sales Tax rules applied to transactions.',
        'global_rules' => 'Global Tax Rules',
        'global_rules_desc' => 'Define how tax is calculated on checkout.',
        'name' => 'Tax Name',
        'name_help' => 'Label displayed on invoices.',
        'rate' => 'Default Tax Rate',
        'rate_help' => 'Percentage added to the subtotal.',
        'inclusive' => 'Tax Inclusive Pricing',
        'inclusive_help' => 'If enabled, product prices will include the tax amount.',
    ],
];