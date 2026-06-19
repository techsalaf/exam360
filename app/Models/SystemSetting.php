<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SystemSetting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value', 'group'];

    const CACHE_KEY = 'system_settings';

    public static function getSettings()
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            return self::all()->pluck('value', 'key')->toArray();
        });
    }
    
    public static function set(string $key, $value, string $group = 'system')
    {
        self::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group]
        );
        self::clearCache();
    }

    /**
     * Helper to get a single setting value with a fallback.
     */
    public static function getValue(string $key, $default = null)
    {
        $settings = self::getSettings();
        return $settings[$key] ?? $default;
    }

    public static function clearCache()
    {
        Cache::forget(self::CACHE_KEY);
    }
}