<?php

return [

    'openai_key' => env('OPENAI_API_KEY'),
    'default_model' => env('AI_MODEL', 'gpt-3.5-turbo'),
    'max_tokens' => 2000,
    
    'default_detector_provider' => env('AI_RISK_PROVIDER', 'gemini'),

    'gemini' => [
        'api_key' => env('GEMINI_API_KEY'),
        'model'   => env('GEMINI_MODEL', 'gemini-1.5-flash'),
        'enabled' => (bool) env('GEMINI_API_KEY'), 
    ],

    'risk_thresholds' => [
        'warning' => 30,
        'critical' => 70,
    ]
];