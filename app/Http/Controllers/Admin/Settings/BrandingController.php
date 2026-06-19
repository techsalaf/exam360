<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\SettingsUpdater;
use Illuminate\Http\Request;

class BrandingController extends Controller
{
    use SettingsUpdater;

    public function update(Request $request)
    {
        $groupKey = $request->input('setting_group_key');
        $rules = $this->getValidationRules($groupKey);
        
        $request->validate($rules);

        $tabHash = $this->getTabHash($groupKey);
        
        return $this->updateSettings($request, 'branding', $tabHash);
    }

    protected function getValidationRules(?string $groupKey): array
    {
        return match ($groupKey) {
            'branding_logo' => [
                'app_logo_light' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:10240',
                'app_logo_dark'  => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:10240',
                'app_favicon'    => 'nullable|image|mimes:png,ico|max:1024',
            ],
            'branding_certificate' => [
                'cert_template_id'       => 'nullable|string',
                'cert_primary_color'     => 'required|string|max:7',
                'cert_show_logo'         => 'nullable|in:0,1',
                'cert_signature_text'    => 'nullable|string|max:100',
                'cert_markup'            => 'nullable|string',
                'cert_page_size'         => 'required|string',
                'cert_orientation'       => 'required|string',
                'cert_watermark_opacity' => 'required|numeric|min:0|max:100',
                'cert_bg_image'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240', 
                'cert_sig_image'         => 'nullable|image|mimes:png,jpeg,jpg,webp|max:5120',
            ],
            'branding_registration' => [
                'auth_register_headline'     => 'nullable|string|max:255',
                'auth_register_title'        => 'nullable|string|max:255',
                'auth_register_brand_desc'   => 'nullable|string',
                'auth_register_feature_1'    => 'nullable|string|max:255',
                'auth_register_feature_2'    => 'nullable|string|max:255',
                'auth_register_feature_3'    => 'nullable|string|max:255',
                'registration_custom_fields' => 'nullable|string',
            ],
            'branding_frontend' => [
                'frontend_show_hero'         => 'nullable|in:0,1',
                'frontend_show_features'     => 'nullable|in:0,1',
                'frontend_show_categories'   => 'nullable|in:0,1',
                'frontend_show_exams'        => 'nullable|in:0,1',
                'frontend_show_how_it_works' => 'nullable|in:0,1',
                'frontend_show_audience'     => 'nullable|in:0,1',
                'frontend_show_pricing'      => 'nullable|in:0,1',
                'frontend_show_testimonials' => 'nullable|in:0,1',
                'frontend_show_faq'          => 'nullable|in:0,1',
                'frontend_show_cta'          => 'nullable|in:0,1',
                'frontend_show_admin_preview'=> 'nullable|in:0,1',
                'frontend_show_cms_features' => 'nullable|in:0,1',
                'frontend_contact_email'     => 'nullable|email',
            ],
            'branding_styling' => [
                'custom_css' => 'nullable|string',
                'custom_js'  => 'nullable|string',
            ],
            default => [],
        };
    }

    protected function getTabHash(?string $groupKey): string
    {
        return match ($groupKey) {
            'branding_logo'         => 'pane-branding-logo',
            'branding_certificate'  => 'pane-branding-certificate',
            'branding_registration' => 'pane-branding-registration',
            'branding_frontend'     => 'pane-branding-frontend',
            'branding_styling'      => 'pane-branding-styling',
            default                 => 'pane-branding-logo',
        };
    }
}