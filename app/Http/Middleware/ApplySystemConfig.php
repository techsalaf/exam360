<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use App\Models\SystemSetting;

class ApplySystemConfig
{
    public function handle(Request $request, Closure $next)
    {
        if (!File::exists(storage_path('app/installed.lock'))) {
            return $next($request);
        }

        try {
            DB::connection()->getPdo();

            if (!Schema::hasTable('system_settings')) {
                return $next($request);
            }

            $settings = SystemSetting::whereIn('key', [
                'app_debug',
                'log_level',
            ])->pluck('value', 'key');

            if (!app()->configurationIsCached() && isset($settings['app_debug'])) {
                Config::set('app.debug', $settings['app_debug'] === '1');
            }

            if (isset($settings['log_level']) && $settings['log_level'] !== '') {
                Config::set('logging.level', $settings['log_level']);
            }

        } catch (\Exception $e) {
            return $next($request);
        }

        return $next($request);
    }
}