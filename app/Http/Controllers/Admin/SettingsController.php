<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use App\Models\Language;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\Settings\PaymentSettingsController;
use Illuminate\View\View;
use DateTimeZone;

class SettingsController extends Controller
{
    public function index(RoleController $roleController): View
    {
        $settings = SystemSetting::getSettings();

        $roleData = $roleController->getRolesData();
        
        $currencies = PaymentSettingsController::getCurrencies();

        $allLanguages = Language::all();
        $activeAdminLanguages = $allLanguages->where('is_active_admin', true);
        $timezones = DateTimeZone::listIdentifiers(DateTimeZone::ALL);

        $seoSettings = SystemSetting::where('group', 'seo')->pluck('value', 'key')->toArray();
        $sitemapExists = file_exists(public_path('sitemap.xml'));
        $securitySettings = SystemSetting::where('group', 'security')->pluck('value', 'key')->toArray();
        $automationSettings = SystemSetting::where('group', 'automation')->pluck('value', 'key')->toArray();

        $viewData = array_merge(
            [
                'settings'             => $settings,
                'currencies'           => $currencies,
                'allLanguages'         => $allLanguages,
                'activeAdminLanguages' => $activeAdminLanguages,
                'timezones'            => $timezones,
                'seoSettings'          => $seoSettings,
                'sitemapExists'        => $sitemapExists,
                'securitySettings'     => $securitySettings,
                'automationSettings'   => $automationSettings,
                'extensionSettings'    => $automationSettings,
            ],
            $roleData
        );

        return view('admin.settings.index', $viewData);
    }
}