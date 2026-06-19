<?php

if (!function_exists('cms_admin_text')) {
    function cms_admin_text(array $settings, string $key, string $locale = 'en'): string
    {
        $val = $settings[$key] ?? '';
        
        if (empty($val)) {
            return '';
        }

        $json = json_decode($val, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($json)) {
            return $json[$locale] ?? '';
        }
        
        return $val;
    }
}