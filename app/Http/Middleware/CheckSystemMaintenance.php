<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use App\Models\SystemSetting;
use Symfony\Component\HttpFoundation\Response;

class CheckSystemMaintenance
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

            if ($request->is('assets/*') || $request->is('storage/*') || $request->is('favicon.ico')) {
                return $next($request);
            }

            if (
                $request->is('admin*') ||
                $request->is('login') ||
                $request->is('logout') ||
                $request->is('api/*')
            ) {
                return $next($request);
            }

            $settings = SystemSetting::whereIn('key', [
                'maintenance_mode',
                'maintenance_bypass_admin',
                'maintenance_title',
                'maintenance_message',
                'maintenance_image',
                'app_name',
                'app_logo_light',
                'app_logo_dark',
                'support_email'
            ])->pluck('value', 'key');

            $maintenanceOn = ($settings['maintenance_mode'] ?? '0') === '1';
            $bypassAdmin   = ($settings['maintenance_bypass_admin'] ?? '0') === '1';

            if (!$maintenanceOn) {
                return $next($request);
            }

            if ($bypassAdmin && Auth::check()) {
                $user = Auth::user();
                if ((method_exists($user, 'hasRole') && $user->hasRole('Super Admin')) || ($user->is_admin ?? false)) {
                    return $next($request);
                }
            }

            return response()
                ->view('errors.503', ['settings' => $settings], 503)
                ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');

        } catch (\Exception $e) {
            return $next($request);
        }
    }
}