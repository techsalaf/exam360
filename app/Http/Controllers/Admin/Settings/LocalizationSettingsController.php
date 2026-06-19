<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SystemSetting;
use App\Models\Language;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class LocalizationSettingsController extends Controller
{
    public function index()
    {
        $activeAdminLanguages = Language::where('is_active_admin', true)->get();
        $allLanguages = Language::all();

        $settings = collect(SystemSetting::where('group', 'localization')->pluck('value', 'key'))
            ->map(fn ($v) => is_numeric($v) ? (int)$v : trim($v))
            ->toArray();

        return view('admin.settings.localization', compact(
            'activeAdminLanguages', 
            'allLanguages', 
            'settings'
        ));
    }

    public function update(Request $request)
    {
        $groupKey = $request->input('setting_group_key');

        if ($groupKey === 'toggles') {
            
            $front = (string) $request->input('localization_front_switcher', '0');
            $admin = (string) $request->input('localization_admin_switcher', '0');

            SystemSetting::updateOrCreate(
                ['key' => 'localization_front_switcher'],
                ['value' => $front, 'group' => 'localization']
            );

            SystemSetting::updateOrCreate(
                ['key' => 'localization_admin_switcher'],
                ['value' => $admin, 'group' => 'localization']
            );

            Cache::forget('global_settings');
            Cache::forget('admin_active_langs');

            return redirect()->back()->with(
                'success',
                __('localization.alerts.settings_updated')
            );
        }

        return back()->with('error', __('localization.alerts.invalid_group'));
    }

    public function storeLanguage(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'code' => 'required|string|size:2|unique:languages,code',
            'flag' => 'required|string|max:10',
        ]);

        $language = Language::create([
            'name'            => $request->name,
            'code'            => strtolower($request->code),
            'flag'            => strtolower($request->flag),
            'is_rtl'          => $request->input('is_rtl', 0),
            'is_active_front' => $request->input('is_active_front', 0),
            'is_active_admin' => $request->input('is_active_admin', 0),
            'is_default'      => false,
        ]);

        $path = lang_path("{$language->code}.json");
        
        if (!File::exists($path)) {
            $defaultPath = lang_path("en.json");
            
            if (File::exists($defaultPath)) {
                File::copy($defaultPath, $path);
            } else {
                File::put($path, json_encode([], JSON_PRETTY_PRINT));
            }
        }
        
        Cache::forget('admin_active_langs');

        return redirect()->back()->with('success', __('localization.alerts.lang_created'));
    }

    public function updateLanguage(Request $request, $id)
    {
        $language = Language::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:50',
            'flag' => 'required|string|max:10',
        ]);
        
        if (!$request->input('is_active_admin')) {
            $activeAdmins = Language::where('is_active_admin', 1)
                                    ->where('id', '!=', $language->id)
                                    ->count();
                                    
            if ($activeAdmins < 1) {
                return redirect()->back()->with('error', __('localization.alerts.at_least_one_admin_lang'));
            }
        }

        if ($language->is_default) {
            if (!$request->input('is_active_front') || !$request->input('is_active_admin')) {
                return redirect()->back()->with('error', __('localization.alerts.cannot_disable_default'));
            }
        }

        $language->update([
            'name'            => $request->name,
            'flag'            => $request->flag,
            'is_rtl'          => $request->input('is_rtl', 0),
            'is_active_front' => $request->input('is_active_front', 0),
            'is_active_admin' => $request->input('is_active_admin', 0),
        ]);

        Cache::forget('admin_active_langs');

        return redirect()->back()->with('success', __('localization.alerts.lang_updated'));
    }

    public function setDefaultLanguage($id)
    {
        $language = Language::findOrFail($id);

        DB::transaction(function () use ($language) {
            Language::query()->update(['is_default' => false]);

            $language->update([
                'is_default'      => true,
                'is_active_front' => true,
                'is_active_admin' => true
            ]);

            SystemSetting::updateOrCreate(
                ['key' => 'localization_default_language'],
                ['value' => $language->code, 'group' => 'localization']
            );
        });

        session(['admin_locale' => $language->code]);
        App::setLocale($language->code);

        Cache::forget('admin_active_langs');
        Cache::forget('global_settings');

        return redirect()->back()->with('success', __('localization.alerts.default_set', ['name' => $language->name]));
    }

    public function destroyLanguage($id)
    {
        $language = Language::findOrFail($id);

        if ($language->is_default) {
            return redirect()->back()->with('error', __('localization.alerts.cannot_delete_default'));
        }

        $path = lang_path("{$language->code}.json");
        
        if (File::exists($path)) {
            File::delete($path);
        }

        $language->delete();
        
        Cache::forget('admin_active_langs');

        return redirect()->back()->with('success', __('localization.alerts.lang_deleted'));
    }

    public function switchLanguage($code)
    {
        $language = Language::where('code', $code)
            ->where('is_active_admin', true)
            ->first();

        if ($language) {
            session(['admin_locale' => $code]);
            App::setLocale($code);
            return redirect()->back()->with('success', __('localization.alerts.switched', ['name' => $language->name]));
        }

        return redirect()->back()->with('error', __('localization.alerts.lang_not_found'));
    }
}