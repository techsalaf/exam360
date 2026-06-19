<?php

namespace App\Helpers;

use Illuminate\Support\Facades\App;

class TranslationHelper
{
    // The key here is the core logic that both directives rely on.
    public static function translateKey($key, $settings = [])
    {
        $value = $settings[$key] ?? '';
        if (empty($value)) return '';
        return self::decode($value);
    }

    public static function translateJson($input)
    {
        if (empty($input)) return '';
        return self::decode($input);
    }

    private static function decode($input)
    {
        // 1. If input is already an array, use it directly (from Eloquent casting)
        if (is_array($input)) {
            $data = $input;
        } else {
            // 2. Otherwise try to decode the JSON string
            $data = json_decode($input, true);
        }

        // 3. Validate we have a proper translation array
        if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
            $locale = App::getLocale();
            
            // Return current locale, fallback to English, fallback to first item
            return $data[$locale] ?? $data['en'] ?? reset($data);
        }

        // 4. If not JSON, return original string
        return $input;
    }
}