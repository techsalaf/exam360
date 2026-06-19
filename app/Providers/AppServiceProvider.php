<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use App\Models\SystemSetting;
use App\Models\Menu;
use App\Models\Language;
use App\Models\Addon;
use App\Helpers\TranslationHelper;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        
    }

    public function boot(): void
    {
        Schema::defaultStringLength(191);

        if (!File::exists(public_path('storage'))) {
            File::makeDirectory(public_path('storage'), 0775, true);
        }

        Gate::before(function ($user, $ability) {
            return ($user->hasRole('Super Admin') || $user->id === 1) ? true : null;
        });

        Blade::directive('dynamicTrans', function ($expression) {
            return "<?php echo \App\Helpers\TranslationHelper::translateKey($expression); ?>";
        });

        Blade::directive('jsonLang', function ($expression) {
            return "<?php echo \App\Helpers\TranslationHelper::translateJson($expression); ?>";
        });

        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            return;
        }

        $this->applyRuntimeConfiguration();

        View::composer('*', function ($view) {
            if (Schema::hasTable('system_settings')) {
                $globalSettings = Cache::remember('global_settings', 86400, function () {
                    return SystemSetting::pluck('value', 'key')->toArray();
                });
                
                $view->with('gs', $globalSettings)
                     ->with('settings', $globalSettings);
            }

            if (Schema::hasTable('menus')) {
                $menus = Cache::remember('global_menus', 86400, function () {
                    return Menu::whereIn('location', [
                        'header', 
                        'footer', 
                        'footer_column_1', 
                        'footer_column_2'
                    ])->get()->keyBy('location');
                });
                
                $view->with('headerMenu', $menus['header'] ?? null)
                     ->with('footerMenu', $menus['footer'] ?? null)
                     ->with('footerCol1', $menus['footer_column_1'] ?? null)
                     ->with('footerCol2', $menus['footer_column_2'] ?? null);
            }
        });

        View::composer('admin.partials.sidebar.*', function ($view) {
            if (Schema::hasTable('addons')) {
                $globalAddons = Cache::remember('active_addons', 3600, function () {
                    return Addon::where('is_active', true)->get()->groupBy('menu_location');
                });
                $view->with('globalAddons', $globalAddons);
            } else {
                $view->with('globalAddons', collect());
            }
        });

        View::composer('admin.settings.groups.system.maintenance', function ($view) {
            $settings = SystemSetting::pluck('value', 'key')->toArray();

            $isActive = ($settings['maintenance_mode'] ?? '0') === '1';
            $isBypassActive = ($settings['maintenance_bypass_admin'] ?? '0') === '1';
            
            $imagePath = $settings['maintenance_image'] ?? null;
            $hasImage = !empty($imagePath);

            $view->with([
                'settings' => $settings,
                'isActive' => $isActive,
                'isBypassActive' => $isBypassActive,
                'imagePath' => $imagePath,
                'hasImage' => $hasImage,
            ]);
        });

        View::composer(['partials.user-topbar', 'layouts.user'], function ($view) {
            if (Auth::check()) {
                $user = Auth::user();
                if (Schema::hasTable('notifications')) {
                    $notifications = $user->notifications()->latest()->take(5)->get();
                    $unreadCount = $user->unreadNotifications()->count();

                    $view->with('topbarNotifications', $notifications)
                         ->with('unreadCount', $unreadCount);
                }
            }
        });
        
        View::composer('partials.admin-topbar', function ($view) {
            $user = Auth::user();
            
            $isAdminLangSwitcherEnabled = false;
            $adminLangs = collect();
            $currentLang = null;
            $currentLangCode = app()->getLocale();

            if (Schema::hasTable('system_settings')) {
                $rawSetting = SystemSetting::where('key', 'localization_admin_switcher')->value('value');
                $isAdminLangSwitcherEnabled = ($rawSetting !== null && in_array($rawSetting, ['1', 'on', 'true', true]));
            }
            
            if ($isAdminLangSwitcherEnabled && Schema::hasTable('languages')) {
                 $adminLangs = Cache::remember('admin_active_langs', 3600, function() {
                    return Language::where('is_active_admin', true)->get();
                });
                $currentLang = $adminLangs->firstWhere('code', $currentLangCode) ?? $adminLangs->first();
            }

            $unreadCount = 0;
            $notifications = collect();
            $showSuperAdminFeatures = $user && (Auth::check() && ($user->id === 1 || $user->hasRole('Super Admin')));

            if ($user && $showSuperAdminFeatures && Schema::hasTable('notifications')) {
                $unreadCount = $user->unreadNotifications()->count();
                $notifications = $user->notifications()->latest()->limit(5)->get();
            }

            $view->with([
                'isAdminLangSwitcherEnabled' => $isAdminLangSwitcherEnabled,
                'adminLangs' => $adminLangs,
                'currentLang' => $currentLang,
                'currentLangCode' => $currentLangCode,
                'unreadCount' => $unreadCount,
                'notifications' => $notifications,
                'showSuperAdminFeatures' => $showSuperAdminFeatures,
            ]);
        });
    }

    private function applyRuntimeConfiguration(): void
    {
        if (!Schema::hasTable('system_settings')) {
            return;
        }

        try {
            $settings = Cache::remember('global_settings', 86400, function () {
                return SystemSetting::pluck('value', 'key')->toArray();
            });
            
            $configuredDriver = $settings['mail_driver'] ?? 'log';

            if ($configuredDriver === 'smtp') {
                if (!empty($settings['mail_host']) && !empty($settings['mail_port'])) {
                    Config::set('mail.default', 'smtp');
                    Config::set('mail.mailers.smtp.host', $settings['mail_host']);
                    Config::set('mail.mailers.smtp.port', $settings['mail_port']);
                    Config::set('mail.mailers.smtp.encryption', $settings['mail_encryption'] ?? 'tls');
                    Config::set('mail.mailers.smtp.username', $settings['mail_username'] ?? '');
                    Config::set('mail.mailers.smtp.password', $settings['mail_password'] ?? '');
                } else {
                    Config::set('mail.default', 'log');
                }
            } elseif ($configuredDriver === 'mailgun') {
                Config::set('mail.default', 'mailgun');
                Config::set('services.mailgun.domain', $settings['mailgun_domain'] ?? '');
                Config::set('services.mailgun.secret', $settings['mailgun_secret'] ?? '');
                Config::set('services.mailgun.endpoint', $settings['mailgun_endpoint'] ?? 'api.mailgun.net');
            } else {
                Config::set('mail.default', 'log');
            }

            if (!empty($settings['mail_from_address'])) {
                Config::set('mail.from.address', $settings['mail_from_address']);
            }
            if (!empty($settings['mail_from_name'])) {
                Config::set('mail.from.name', $settings['mail_from_name']);
            }

            if (!empty($settings['ai_driver'])) {
                Config::set('ai.default_detector_provider', $settings['ai_driver']);
            }
            if (!empty($settings['ai_gemini_api_key'])) {
                Config::set('ai.gemini.api_key', $settings['ai_gemini_api_key']);
                Config::set('ai.gemini.enabled', true);
            }
            if (!empty($settings['ai_openai_api_key'])) {
                Config::set('ai.openai_key', $settings['ai_openai_api_key']);
            }
            
            if (($settings['ext_recaptcha_enable'] ?? '0') == '1') {
                Config::set('captcha.disable', false);
                Config::set('captcha.sitekey', $settings['ext_recaptcha_site_key'] ?? '');
                Config::set('captcha.secret', $settings['ext_recaptcha_secret'] ?? '');
            } else {
                Config::set('captcha.disable', true);
            }

            if (($settings['ext_tawk_enable'] ?? '0') == '1') {
                Config::set('services.tawk.link', $settings['ext_tawk_link'] ?? '');
                Config::set('services.tawk.active', true);
            } else {
                Config::set('services.tawk.active', false);
            }

        } catch (\Exception $e) {
            Config::set('mail.default', 'log');
        }
    }
}