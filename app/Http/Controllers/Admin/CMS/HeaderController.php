<?php

namespace App\Http\Controllers\Admin\CMS;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Stichoza\GoogleTranslate\GoogleTranslate;

class HeaderController extends Controller
{
    public function index()
    {
        $settings = SystemSetting::pluck('value', 'key')->toArray();
        return view('admin.cms.header.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'header_logo_height' => 'nullable|integer|min:10|max:150',
            'header_cta_text'    => 'nullable|string|max:100',
            'header_cta_link'    => 'nullable|string|max:255',
        ]);

        $allowedFields = ['header_logo_height', 'header_cta_text', 'header_cta_link'];
        $translatable = ['header_cta_text'];
        $locales = ['es', 'de', 'bn'];
        
        $translator = new GoogleTranslate();

        foreach ($allowedFields as $key) {
            if (!$request->has($key)) continue;

            $rawValue = $request->input($key);
            $value = $this->sanitizeInput($rawValue);

            if (in_array($key, $translatable) && !empty($value)) {
                $payload = ['en' => $value];
                try {
                    foreach ($locales as $lang) {
                        $translator->setSource('en');
                        $translator->setTarget($lang);
                        $payload[$lang] = $translator->translate($value);
                    }
                    $finalValue = json_encode($payload, JSON_UNESCAPED_UNICODE);
                } catch (\Exception $e) {
                    $finalValue = json_encode(['en' => $value], JSON_UNESCAPED_UNICODE);
                }
            } else {
                $finalValue = (string) $value;
            }

            SystemSetting::set($key, $finalValue, 'cms');
        }

        Cache::forget('system_settings');
        Cache::forget('global_settings');
        
        if (method_exists(SystemSetting::class, 'clearCache')) {
            SystemSetting::clearCache();
        }

        return back()->with('success', 'Header settings updated successfully.');
    }

    private function sanitizeInput($value)
    {
        if (!is_string($value)) {
            return $value;
        }

        $allowedTags = '<b><strong><i><em>';
        $cleaned = strip_tags($value, $allowedTags);
        $cleaned = preg_replace('/(<[^>]+) on\w+=".*?"/i', '$1', $cleaned);
        $cleaned = preg_replace('/javascript:/i', '', $cleaned);

        return $cleaned;
    }
}