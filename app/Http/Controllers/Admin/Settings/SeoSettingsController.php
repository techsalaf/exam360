<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class SeoSettingsController extends Controller
{
    public function update(Request $request)
    {
        $groupKey = $request->input('setting_group_key');

        if ($groupKey === 'seo_config') {
            $request->validate([
                'seo_meta_title' => 'required|string|max:100',
                'seo_meta_description' => 'required|string|max:300',
                'seo_keywords' => 'nullable|string|max:500',
                'seo_google_analytics_id' => 'nullable|string|max:50',
                'seo_banner_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', 
                'seo_banner_delete' => 'nullable|in:0,1',
            ]);
            
            $this->handleSeoBanner($request);

            $fields = ['seo_meta_title', 'seo_meta_description', 'seo_keywords', 'seo_google_analytics_id'];
            
            foreach ($fields as $field) {
                SystemSetting::updateOrCreate(
                    ['key' => $field],
                    [
                        'value' => $request->input($field, ''),
                        'group' => 'seo'
                    ]
                );
            }

        } elseif ($groupKey === 'sitemap') {
            $request->validate([
                'sitemap_robots_meta' => 'nullable|string', 
            ]);

            SystemSetting::updateOrCreate(
                ['key' => 'sitemap_robots_meta'],
                [
                    'value' => $request->input('sitemap_robots_meta', ''),
                    'group' => 'seo'
                ]
            );
        } else {
            return back()->with('error', __('seo.alerts.invalid_group'))->withFragment('#pane-seo');
        }

        SystemSetting::clearCache();
        Cache::flush();
        try {
            Artisan::call('cache:clear');
        } catch (\Exception $e) {}

        $tabHash = ($groupKey === 'seo_config') ? 'pane-seo' : 'pane-sitemap';

        return redirect()->back()
            ->with('success', __('seo.alerts.updated_success'))
            ->withFragment('#' . $tabHash);
    }

    private function handleSeoBanner(Request $request)
    {
        $currentImage = SystemSetting::where('key', 'seo_banner_image')->value('value');

        if ($request->input('seo_banner_delete') == '1') {
            if ($currentImage && Storage::disk('public')->exists($currentImage)) {
                Storage::disk('public')->delete($currentImage);
            }
            
            SystemSetting::updateOrCreate(
                ['key' => 'seo_banner_image'],
                ['value' => null, 'group' => 'seo']
            );
            return; 
        }

        if ($request->hasFile('seo_banner_image')) {
            if ($currentImage && Storage::disk('public')->exists($currentImage)) {
                Storage::disk('public')->delete($currentImage);
            }

            $path = $request->file('seo_banner_image')->store('system/seo', 'public');

            SystemSetting::updateOrCreate(
                ['key' => 'seo_banner_image'],
                ['value' => $path, 'group' => 'seo']
            );
        }
    }
    
    public function generateSitemap()
    {
        try {
            $path = public_path('sitemap.xml');

            $xml = '<?xml version="1.0" encoding="UTF-8"?>';
            $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
            
            $xml .= '<url>';
            $xml .= '<loc>' . url('/') . '</loc>';
            $xml .= '<lastmod>' . Carbon::now()->toAtomString() . '</lastmod>';
            $xml .= '<changefreq>daily</changefreq>';
            $xml .= '<priority>1.0</priority>';
            $xml .= '</url>';

            $xml .= '<url>';
            $xml .= '<loc>' . url('/exams') . '</loc>';
            $xml .= '<lastmod>' . Carbon::now()->toAtomString() . '</lastmod>';
            $xml .= '<changefreq>weekly</changefreq>';
            $xml .= '<priority>0.8</priority>';
            $xml .= '</url>';

            $xml .= '</urlset>';

            File::put($path, $xml);

            SystemSetting::updateOrCreate(
                ['key' => 'sitemap_last_generated'],
                [
                    'value' => Carbon::now()->format('Y-m-d H:i:s'),
                    'group' => 'seo'
                ]
            );

            SystemSetting::clearCache();

            return redirect()->back()
                ->with('success', __('seo.alerts.sitemap_generated'))
                ->withFragment('#pane-sitemap');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('seo.alerts.sitemap_failed', ['error' => $e->getMessage()]))
                ->withFragment('#pane-sitemap');
        }
    }

    public function downloadSitemap()
    {
        $path = public_path('sitemap.xml');

        if (File::exists($path)) {
            return response()->download($path);
        }

        return redirect()->back()
            ->with('error', __('seo.alerts.sitemap_not_found'))
            ->withFragment('#pane-sitemap');
    }
}