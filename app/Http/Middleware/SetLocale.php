<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Language;
use App\Models\SystemSetting;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        if (!File::exists(storage_path('app/installed.lock'))) {
            return $next($request);
        }

        try {
            DB::connection()->getPdo();

            if (!Schema::hasTable('languages') || !Schema::hasTable('system_settings')) {
                return $next($request);
            }

            $isAdmin = $request->is('admin*');
            $sessionKey = $isAdmin ? 'admin_locale' : 'front_locale';

            if (Session::has($sessionKey)) {
                $locale = Session::get($sessionKey);
            } else {
                $defaultLang = Language::where('is_default', true)->value('code');
                $locale = SystemSetting::getValue('localization_default_language', $defaultLang ?? 'en');
            }

            $language = Language::where('code', $locale)->first();

            if ($language) {
                if ($isAdmin && !$language->is_active_admin) {
                    $locale = SystemSetting::getValue('localization_default_language', 'en');
                } elseif (!$isAdmin && !$language->is_active_front) {
                    $locale = SystemSetting::getValue('localization_default_language', 'en');
                }
            }

            App::setLocale($locale);

        } catch (\Exception $e) {
            App::setLocale('en');
        }

        return $next($request);
    }
}