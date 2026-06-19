<?php

namespace App\Http\Controllers\Traits;

use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Helpers\PublicStorageMirror;

trait SettingsUpdater
{
    protected function updateSettings(Request $request, string $group = null, string $tabHash = null, array $booleanFields = [])
    {
        foreach ($booleanFields as $field) {
            $request->merge([
                $field => $request->has($field) ? '1' : '0'
            ]);
        }

        $deleteMap = [
            'delete_app_logo_light' => 'app_logo_light',
            'delete_app_logo_dark' => 'app_logo_dark',
            'delete_app_favicon' => 'app_favicon',
            'delete_maintenance_image' => 'maintenance_image',
            'cert_remove_bg' => 'cert_bg_image',
            'cert_remove_sig' => 'cert_sig_image',
        ];

        foreach ($deleteMap as $inputKey => $settingKey) {
            if ($request->input($inputKey) == '1') {
                $this->deleteSettingFile($settingKey);
            }
        }

        $input = $request->except(['_token', '_method']);
        $uploadedKeys = [];

        foreach ($request->files as $key => $file) {
            try {
                if ($request->hasFile($key) && $request->file($key)->isValid()) {
                    $directory = $this->resolveUploadDirectory($key);
                    $path = $request->file($key)->store($directory, 'public');

                    if ($path) {
                        $oldPath = SystemSetting::where('key', $key)->value('value');

                        if ($oldPath) {
                            if (Storage::disk('public')->exists($oldPath)) {
                                Storage::disk('public')->delete($oldPath);
                            }
                            if (class_exists(PublicStorageMirror::class)) {
                                PublicStorageMirror::delete($oldPath);
                            }
                        }

                        SystemSetting::updateOrCreate(
                            ['key' => $key],
                            [
                                'group' => $group ?? 'files',
                                'value' => $path
                            ]
                        );

                        if (class_exists(PublicStorageMirror::class)) {
                            PublicStorageMirror::sync($path);
                        }

                        $uploadedKeys[] = $key;
                    }
                }
            } catch (\Exception $e) {
                Log::error("Settings File Upload Error ({$key}): " . $e->getMessage());
            }
        }

        foreach ($input as $key => $value) {
            if (
                in_array($key, $uploadedKeys) ||
                str_starts_with($key, 'delete_') ||
                str_starts_with($key, 'remove_') ||
                str_starts_with($key, 'cert_remove_') ||
                $key === 'setting_group_key'
            ) {
                continue;
            }

            if (is_array($value)) {
                $value = json_encode($value);
            }

            $targetGroup = $group;

            if (method_exists($this, 'resolveGroup')) {
                $resolved = $this->resolveGroup($key);
                if ($resolved) {
                    $targetGroup = $resolved;
                }
            }

            SystemSetting::updateOrCreate(
                ['key' => $key],
                [
                    'group' => $targetGroup ?? 'system',
                    'value' => is_null($value) ? '' : (string) $value
                ]
            );
        }

        Cache::forget('global_settings');
        Cache::forget('global_menus');

        if (method_exists(SystemSetting::class, 'clearCache')) {
            SystemSetting::clearCache();
        }

        $redirect = redirect()->route('admin.settings.index')
            ->with('success', 'Settings updated successfully.');

        if ($tabHash) {
            return $redirect->withFragment($tabHash);
        }

        return $redirect;
    }

    private function deleteSettingFile(string $key)
    {
        $path = SystemSetting::where('key', $key)->value('value');

        if ($path) {
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            if (class_exists(PublicStorageMirror::class)) {
                PublicStorageMirror::delete($path);
            }
        }

        SystemSetting::updateOrCreate(
            ['key' => $key],
            ['value' => '', 'group' => 'files']
        );
    }

    protected function resolveUploadDirectory(string $key): string
    {
        return match ($key) {
            'app_logo_light', 'app_logo_dark' => 'logos',
            'app_favicon' => 'favicons',
            'maintenance_image' => 'maintenance',
            'cert_bg_image' => 'certificates/backgrounds',
            'cert_sig_image' => 'certificates/signatures',
            default => 'uploads',
        };
    }
}
