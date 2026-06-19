<?php

return [
    'defaults' => [
        'title' => 'Localization Defaults',
        'desc' => 'Configure default language, timezone, and country for the application.',
        'regional_title' => 'Regional Settings',
        'regional_desc' => 'Set baseline defaults for new visitors.',
        'language_label' => 'Default System Language',
        'language_help' => 'Fallback language if user selection is missing.',
        'timezone_label' => 'Default Timezone',
        'country_label' => 'System Default Country',
        'countries' => [
            'US' => 'United States',
            'GB' => 'United Kingdom',
            'IN' => 'India',
            'BD' => 'Bangladesh',
        ],
        'save_btn' => 'Save Defaults',
    ],

    'switchers' => [
        'title' => 'Language Switchers',
        'desc' => 'Control where users can change languages.',
        'front_label' => 'Frontend Switcher',
        'front_help' => 'Allow visitors to change language on the public site.',
        'admin_label' => 'Admin Panel Switcher',
        'admin_help' => 'Allow staff to change language in the dashboard.',
        'update_btn' => 'Update Visibility',
    ],

    'table' => [
        'title' => 'Available Languages',
        'desc' => 'Manage system languages and their availability.',
        'add_new' => 'Add New',
        'headers' => [
            'name' => 'Name',
            'code' => 'Code',
            'rtl' => 'RTL',
            'front' => 'Frontend',
            'admin' => 'Admin',
            'actions' => 'Actions',
        ],
        'badges' => [
            'default' => 'Default',
            'active' => 'Active',
            'hidden' => 'Hidden',
            'yes' => 'Yes',
            'no' => 'No',
        ],
        'tooltips' => [
            'set_default' => 'Set as Default',
            'curr_default' => 'Current Default',
            'delete' => 'Delete Language',
        ],
    ],

    'modals' => [
        'add_title' => 'Add New Language',
        'edit_title' => 'Edit Language',
        'name_label' => 'Name',
        'name_ph' => 'e.g. French',
        'code_label' => 'Code (ISO 2)',
        'code_ph' => 'e.g. fr',
        'flag_label' => 'Flag Icon Code',
        'flag_help' => 'Used for flag-icon-css or emoji.',
        'rtl_label' => 'Right-to-Left (RTL)',
        'front_label' => 'Available on Frontend',
        'admin_label' => 'Available on Admin Panel',
        'add_btn' => 'Add Language',
        'save_btn' => 'Save Changes',
    ],

    'alerts' => [
        'delete_title' => 'Delete Language?',
        'delete_text' => 'Are you sure you want to delete :name? This will remove the translation file and cannot be undone.',
        'default_title' => 'Set as Default?',
        'default_text' => 'Make :name the primary system language? All new visitors will see this language first.',
        'yes_delete' => 'Yes, Delete',
        'yes_default' => 'Yes, Make Default',
        'cancel' => 'Cancel',
    ],
];