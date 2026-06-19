<?php

return [
    // Application Page
    'app_title' => 'Application Health',
    'app_subtitle' => 'Overview of application configuration, database status, and filesystem permissions.',
    'core_config' => 'Core Configuration',
    'app_name' => 'App Name',
    'app_url' => 'App URL',
    'environment' => 'Environment',
    'debug_mode' => 'Debug Mode',
    'timezone' => 'Timezone',
    'locale' => 'Locale',
    'db_status' => 'Database Status',
    'db_connected' => 'Connected',
    'db_connection' => 'Connection Type',
    'db_size' => 'Total Size',
    'permissions' => 'Filesystem Permissions',
    'perm_hint' => 'Ensure these directories have 775 or 755 permissions for the application to function correctly.',
    'enabled' => 'Enabled',
    'disabled' => 'Disabled',
    'true' => 'True',
    'false' => 'False',

    // Server Page
    'server_title' => 'Server Environment',
    'server_subtitle' => 'Technical details about the underlying server and PHP configuration.',
    'php_config' => 'PHP Configuration',
    'memory_limit' => 'Memory Limit',
    'max_execution' => 'Max Execution',
    'upload_max' => 'Upload Max',
    'post_max' => 'Post Max Size',
    'loaded_ext' => 'Loaded Extensions',
    'installed' => 'Installed',
    'host_info' => 'Host Information',
    'ip_address' => 'IP Address',
    'protocol' => 'Protocol',
    'software' => 'Software',

    // Cache Page
    'cache_title' => 'Cache Management',
    'cache_subtitle' => 'Clear temporary data files to resolve configuration issues or update views.',
    'active_drivers' => 'Active Drivers',
    'sys_cache' => 'System Cache',
    'session_store' => 'Session Store',
    'queue_worker' => 'Queue Worker',
    'mail_system' => 'Mail System',
    'quick_actions' => 'Quick Actions',
    'btn_optimize' => 'Optimize System',
    'app_cache' => 'Application Cache',
    'app_cache_desc' => 'Clears general app data.',
    'route_cache' => 'Route Cache',
    'route_cache_desc' => 'Fixes 404/Route errors.',
    'config_cache' => 'Config Cache',
    'config_cache_desc' => 'Reloads .env settings.',
    'view_cache' => 'View / Blade Cache',
    'view_cache_desc' => 'Refreshes compiled UI.',

    // Update Page
      'update_title'         => 'Update Center',
    'update_subtitle'      => 'Manage system updates and view changelogs.',
    'btn_check_update'     => 'Check for Updates',
    'up_to_date'           => 'You are up to date!',
    'current_ver'          => 'Currently running version',
    'last_checked'         => 'Last checked:',

    // NEW KEYS TO ADD
    'manual_update_title'  => 'Manual Update',
    'manual_update_desc'   => 'Upload the latest update package provided by the developer. The system will automatically extract files and run database migrations.',
    'supported_file'       => 'Supported file',
    'max_size'             => 'Max',
    'important_label'      => 'Important',
    'update_warning'       => 'This process will overwrite core system files. Please ensure you have a full database and file backup before proceeding.',
    'btn_upload_update'    => 'Upload & Update System',
    
    // JS Alerts
    'js_confirm_update'    => 'Are you strictly sure you want to update? This action cannot be undone and will overwrite system files.',
    'js_processing'        => 'Processing...',
];