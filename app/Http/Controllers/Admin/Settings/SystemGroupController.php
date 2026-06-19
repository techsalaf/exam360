<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\SettingsUpdater;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;

class SystemGroupController extends Controller
{
    use SettingsUpdater;

    public function index(string $group = 'general')
    {
        $settings = SystemSetting::pluck('value', 'key')->toArray();

        $isActive = ($settings['maintenance_mode'] ?? '0') === '1';
        $isBypassActive = ($settings['maintenance_bypass_admin'] ?? '0') === '1';
        
        $imagePath = $settings['maintenance_image'] ?? null;
        $hasImage = !empty($imagePath);

        return view('admin.settings.system', [
            'currentGroup' => $group,
            'settings' => $settings,
            'isActive' => $isActive,
            'isBypassActive' => $isBypassActive,
            'imagePath' => $imagePath,
            'hasImage' => $hasImage,
        ]);
    }

    public function update(Request $request)
    {
        $groupKey = $request->input('setting_group_key', 'general');

        $rules = $this->getValidationRules($groupKey);

        if ($groupKey === 'general' && $request->filled('new_password')) {
            $rules['current_password'] = 'required|current_password';
            $rules['new_password'] = 'required|string|min:8|confirmed';
        }

        $request->validate($rules);

        if ($groupKey === 'core' && $request->input('app_env') === 'production' && $request->boolean('app_debug')) {
            return back()
                ->withErrors(['app_debug' => __('Debug mode cannot be enabled in a production environment.')])
                ->withInput();
        }

        if ($groupKey === 'general' && $request->filled('new_password')) {
            $request->user()->update([
                'password' => Hash::make($request->new_password)
            ]);
            
            $request->request->remove('current_password');
            $request->request->remove('new_password');
            $request->request->remove('new_password_confirmation');
            
            session()->flash('success_password', __('Password updated successfully.'));
        }

        $tabHash = $this->getTabHash($groupKey);
        
        $booleanFields = match ($groupKey) {
            'core' => ['app_debug'],
            'maintenance' => ['maintenance_mode', 'maintenance_bypass_admin'],
            default => []
        };

        $response = $this->updateSettings($request, $groupKey, $tabHash, $booleanFields);

        SystemSetting::clearCache();
        Cache::flush();
        
        try {
            Artisan::call('view:clear');
            Artisan::call('cache:clear');
        } catch (\Exception $e) {}

        return $response;
    }

    protected function getValidationRules(string $groupKey): array
    {
        return match ($groupKey) {
            'general' => [
                'app_name' => 'required|string|max:255',
                'app_title' => 'nullable|string|max:255',
                'app_tagline' => 'nullable|string|max:255',
                'app_short_name' => 'required|string|max:10',
                'support_email' => 'nullable|email|max:255',
                'footer_text' => 'nullable|string|max:255',
            ],
            'core' => [
                'app_debug' => ['nullable', 'in:0,1'],
                'app_env' => ['required', Rule::in(['production', 'local', 'staging'])],
                'log_level' => ['required', Rule::in(['debug', 'info', 'notice', 'warning', 'error', 'critical', 'alert', 'emergency'])],
                'max_concurrent_exams' => 'required|integer|min:1',
                'session_lifetime' => 'required|integer|min:30',
            ],
            'maintenance' => [
                'maintenance_mode' => 'nullable', 
                'maintenance_bypass_admin' => 'nullable',
                'maintenance_title' => 'nullable|string|max:255',
                'maintenance_message' => 'nullable|string|max:1000',
                'maintenance_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            ],
            'roles' => [
                'default_signup_role' => 'required|integer|exists:roles,id',
            ],
            default => [],
        };
    }

    protected function getTabHash(string $groupKey): string
    {
        return match ($groupKey) {
            'general' => 'pane-general',
            'core' => 'pane-sysconfig',
            'maintenance' => 'pane-maintenance',
            'roles' => 'pane-roles',
            default => 'pane-general',
        };
    }
}