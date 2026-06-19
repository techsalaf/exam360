<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Error Page Language Lines
    |--------------------------------------------------------------------------
    */

    'defaults' => [
        'app_name' => 'ZiExam AI',
    ],

    '403' => [
        'code' => '403',
        'title' => 'Access Denied',
        'message' => 'You do not have the necessary permissions to view this resource. If you believe this is an error, please contact your system administrator.',
        'go_back' => 'Go Back',
        'home' => 'Return Home',
    ],

    '404' => [
        'code' => '404',
        'title' => 'Page Not Found',
        'message' => 'We couldn\'t find the requested assessment or page. It might have been moved, removed, or the URL is incorrect.',
        'home' => 'Go to Homepage',
    ],

    '500' => [
        'code' => '500',
        'title' => 'Internal Server Error',
        'message' => 'Something went wrong on our end. We apologize for the inconvenience.',
        'subtext' => 'Our technical team has been notified and is working to resolve this issue.',
        'return' => 'Return to Safety',
    ],

    '503' => [
        'default_title' => 'Under Maintenance',
        'default_message' => 'We are currently performing scheduled maintenance to improve our services. We will be back shortly.',
        'contact_support' => 'Contact Support',
        'check_status' => 'Check Status',
        'image_alt' => 'Maintenance Mode Illustration',
    ],

];