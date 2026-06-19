<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SystemSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class AutomationSettingsController extends Controller
{
    public function update(Request $request): RedirectResponse
    {
        $groupKey = $request->input('setting_group_key');

        $this->handleBooleans($request, $groupKey);

        $request->validate($this->getValidationRules($groupKey));
        
        $data = $request->except(['_token', 'setting_group_key']);
        
        foreach ($data as $key => $value) {
            SystemSetting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => is_null($value) ? '' : $value,
                    'group' => 'automation'
                ]
            );
        }

        SystemSetting::clearCache();
        Cache::flush();
        
        try {
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
        } catch (\Exception $e) {}

        $tabHash = $this->getTabHash($groupKey);

        return redirect()->back()
            ->with('success', __('automation.alerts.settings_updated'))
            ->withFragment('#' . $tabHash);
    }

    protected function handleBooleans(Request $request, ?string $groupKey): void
    {
        $booleans = [];

        if ($groupKey === 'cron_jobs') {
            $booleans = ['automation_cron_enabled'];
        } elseif ($groupKey === 'extensions') {
            $booleans = [
                'ext_custom_captcha_enable',
                'ext_recaptcha_enable',
                'ext_tawk_enable',
                'social_google_enable',
                'social_facebook_enable',
                'ext_adsense_enable',
                'ext_otp_enable',
            ];
        }

        foreach ($booleans as $field) {
            $request->merge([
                $field => $request->input($field, 0)
            ]);
        }
    }
    
    protected function getValidationRules(?string $groupKey): array
    {
        $rules = match ($groupKey) {
            'cron_jobs' => [
                'automation_cron_enabled' => 'nullable|in:0,1',
                'automation_cron_key'     => 'required|string|min:10|max:100',
            ],
            
            'ai_integrations' => [
                'ai_driver'         => 'required|string|in:gemini,openai,disabled',
                'ai_gemini_api_key' => 'required_if:ai_driver,gemini|nullable|string|max:255',
                'ai_gemini_model'   => 'nullable|string|max:100',
                'ai_openai_api_key' => 'required_if:ai_driver,openai|nullable|string|max:255',
                'ai_openai_model'   => 'nullable|string|max:100',
            ],

            'extensions' => [
                'ext_custom_captcha_enable' => 'nullable|in:0,1',
                'ext_custom_captcha_length' => 'required_if:ext_custom_captcha_enable,1|integer|in:4,6,8',
                'ext_recaptcha_enable'      => 'nullable|in:0,1',
                'ext_recaptcha_site_key'    => 'required_if:ext_recaptcha_enable,1|nullable|string|max:255',
                'ext_recaptcha_secret'      => 'required_if:ext_recaptcha_enable,1|nullable|string|max:255',
                'ext_tawk_enable'           => 'nullable|in:0,1',
                'ext_tawk_link'             => 'required_if:ext_tawk_enable,1|nullable|url|max:255',
                'social_google_enable'      => 'nullable|in:0,1',
                'social_google_client_id'   => 'required_if:social_google_enable,1|nullable|string|max:255',
                'social_google_secret'      => 'required_if:social_google_enable,1|nullable|string|max:255',
                'social_facebook_enable'    => 'nullable|in:0,1',
                'social_facebook_client_id' => 'required_if:social_facebook_enable,1|nullable|string|max:255',
                'social_facebook_secret'    => 'required_if:social_facebook_enable,1|nullable|string|max:255',
                'ext_adsense_enable'        => 'nullable|in:0,1',
                'ext_adsense_client_id'     => 'required_if:ext_adsense_enable,1|nullable|string|max:255',
                'ext_otp_enable'            => 'nullable|in:0,1',
                'ext_otp_provider'          => 'required|string|in:email,sms',
                'ext_otp_length'            => 'required|integer|in:4,6,8',
            ],

            default => [],
        };

        if ($groupKey === 'extensions' && request()->input('ext_otp_provider') === 'sms') {
            $rules['ext_otp_sms_provider'] = 'required|string|in:twilio,vonage';
            if (request()->input('ext_otp_sms_provider') === 'twilio') {
                $rules['ext_twilio_sid'] = 'required|string|max:255';
                $rules['ext_twilio_token'] = 'required|string|max:255';
                $rules['ext_twilio_from'] = 'required|string|max:255';
            } elseif (request()->input('ext_otp_sms_provider') === 'vonage') {
                $rules['ext_vonage_key'] = 'required|string|max:255';
                $rules['ext_vonage_secret'] = 'required|string|max:255';
                $rules['ext_vonage_from'] = 'required|string|max:255';
            }
        }

        return $rules;
    }
    
    protected function getTabHash(?string $groupKey): string
    {
        return match ($groupKey) {
            'cron_jobs'       => 'cron-content',
            'ai_integrations' => 'ai-content',
            'extensions'      => 'extensions-content',
            default           => 'cron-content',
        };
    }
}