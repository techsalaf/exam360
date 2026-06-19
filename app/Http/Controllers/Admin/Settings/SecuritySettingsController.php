<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SystemSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class SecuritySettingsController extends Controller
{
    public function update(Request $request): RedirectResponse
    {
        $groupKey = $request->input('setting_group_key');

        $booleans = [];

        if ($groupKey === 'security') {
            $booleans = [
                'security_2fa_enable',
                'security_verify_email',
                'security_verify_mobile',
                'security_prevent_concurrent_login',
                'security_force_ssl'
            ];
        } elseif ($groupKey === 'gdpr') {
            $booleans = ['security_gdpr_enable'];
        } elseif ($groupKey === 'policy') {
            $booleans = ['security_policy_show_footer'];
        }

        foreach ($booleans as $field) {
            // FIX: Use input(key, 0) instead of has() because of hidden fallback inputs
            $request->merge([
                $field => $request->input($field, 0)
            ]);
        }

        $rules = [];
        if ($groupKey === 'security') {
            $rules = [
                'security_2fa_enable' => 'nullable|in:0,1',
                'security_verify_email' => 'nullable|in:0,1',
                'security_verify_mobile' => 'nullable|in:0,1',
                'security_prevent_concurrent_login' => 'nullable|in:0,1',
                'security_max_login_attempts' => 'required|integer|min:1|max:100',
                'security_login_lockout_time' => 'required|integer|min:1|max:1440',
            ];
        } elseif ($groupKey === 'gdpr') {
            $rules = [
                'security_gdpr_enable' => 'nullable|in:0,1',
                'security_gdpr_message' => 'nullable|string|max:500',
            ];
        } elseif ($groupKey === 'policy') {
            $rules = [
                'security_policy_terms_url' => 'nullable|url|max:255',
                'security_policy_privacy_url' => 'nullable|url|max:255',
                'security_policy_show_footer' => 'nullable|in:0,1',
            ];
        }

        $request->validate($rules);

        $data = $request->except(['_token', 'setting_group_key']);
        
        foreach ($data as $key => $value) {
            SystemSetting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => is_null($value) ? '' : $value,
                    'group' => 'security'
                ]
            );
        }

        SystemSetting::clearCache();
        Cache::flush();
        try {
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
        } catch (\Exception $e) {}

        $fragment = match($groupKey) {
            'gdpr' => 'pane-gdpr',
            'policy' => 'pane-policy',
            default => 'pane-security'
        };

        return redirect()->back()
            ->with('success', __('security.alerts.updated_success'))
            ->withFragment('#' . $fragment);
    }
}