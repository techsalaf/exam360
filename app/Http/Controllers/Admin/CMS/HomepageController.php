<?php

namespace App\Http\Controllers\Admin\CMS;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Plan;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Helpers\PublicStorageMirror;

class HomepageController extends Controller
{
    public function index()
    {
        $settings = SystemSetting::pluck('value', 'key')->toArray();
        $allCategories = Category::where('is_active', true)->get();
        $allPlans = Plan::where('is_active', true)->get();

        return view('admin.cms.homepage.index', compact('settings', 'allCategories', 'allPlans'));
    }

    public function designs()
    {
        $settings = SystemSetting::pluck('value', 'key')->toArray();
        $activeDesign = $settings['active_homepage_design'] ?? 'design1';

        $availableDesigns = [
            [
                'id' => 'design1',
                'name' => 'Modern SaaS (Default)',
                'image' => 'assets/img/admin/designs/design1.png',
                'desc' => 'Clean layout with hero, features, and pricing sections.'
            ],
            [
                'id' => 'design2',
                'name' => 'Professional Academic',
                'image' => 'assets/img/admin/designs/design2.png',
                'desc' => 'Focused on structured categories and exam highlights.'
            ],
            [
                'id' => 'design3',
                'name' => 'Modern Exam Discovery',
                'image' => 'assets/img/admin/designs/design3.png',
                'desc' => 'Dark-themed modern exam discovery portal with advanced filtering and statistics.'
            ]
        ];

        return view('admin.cms.homepage.designs', compact('activeDesign', 'availableDesigns'));
    }

    public function setDesign(Request $request)
    {
        $request->validate([
            'design_id' => 'required|string'
        ]);

        SystemSetting::updateOrCreate(
            ['key' => 'active_homepage_design'],
            ['value' => $request->design_id]
        );

        Cache::forget('system_settings');
        Cache::forget('global_settings');

        return redirect()->route('admin.cms.homepage.index')->with('success', 'Homepage design activated successfully.');
    }

    public function updateThumbnail(Request $request)
    {
        $request->validate([
            'design_id' => 'required',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        if ($request->hasFile('thumbnail')) {
            $designId = $request->design_id;
            $dbKey = 'thumb_' . $designId;

            $oldPath = SystemSetting::where('key', $dbKey)->value('value');
            if ($oldPath) {
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                    PublicStorageMirror::delete($oldPath);
                }
            }

            $path = $request->file('thumbnail')->store('cms/thumbnails', 'public');
            SystemSetting::updateOrCreate(['key' => $dbKey], ['value' => $path]);
            PublicStorageMirror::sync($path);
        }

        return back()->with('success', 'Design thumbnail updated successfully.');
    }

    public function update(Request $request)
    {
        $translatable = [
            'hero_badge', 'hero_title', 'hero_subtitle', 'hero_cta_text', 'hero_cta2_text',
            'home_stat_1_label', 'trust_badge_1', 'trust_badge_2', 'trust_badge_3',
            'categories_title', 'categories_subtitle', 'categories_bottom_text', 'categories_btn_text',
            'audience_title', 'audience_subtitle', 'audience_bottom_text', 'audience_btn_text',
            'aud_c1_title', 'aud_c1_highlight', 'aud_c1_desc',
            'aud_c2_title', 'aud_c2_highlight', 'aud_c2_desc',
            'aud_c3_title', 'aud_c3_highlight', 'aud_c3_desc',
            'aud_c4_title', 'aud_c4_highlight', 'aud_c4_desc',
            'aud_c5_title', 'aud_c5_highlight', 'aud_c5_desc',
            'features_title', 'features_subtitle',
            'feat_p1_title', 'feat_p1_desc', 'feat_p1_hint_text',
            'feat_p1_i1_title', 'feat_p1_i1_desc', 'feat_p1_i2_title', 'feat_p1_i2_desc', 'feat_p1_i3_title', 'feat_p1_i3_desc',
            'feat_p2_title', 'feat_p2_desc', 'feat_p2_hint_text',
            'feat_p2_i1_title', 'feat_p2_i1_desc', 'feat_p2_i2_title', 'feat_p2_i2_desc', 'feat_p2_i3_title', 'feat_p2_i3_desc',
            'feat_p3_title', 'feat_p3_desc', 'feat_p3_hint_text',
            'feat_p3_i1_title', 'feat_p3_i1_desc', 'feat_p3_i2_title', 'feat_p3_i2_desc', 'feat_p3_i3_title', 'feat_p3_i3_desc',
            'feat_p4_title', 'feat_p4_desc', 'feat_p4_hint_text',
            'feat_p4_i1_title', 'feat_p4_i1_desc', 'feat_p4_i2_title', 'feat_p4_i2_desc', 'feat_p4_i3_title', 'feat_p4_i3_desc',
            'how_it_works_title', 'how_it_works_subtitle',
            'hiw_s1_title', 'hiw_s1_desc', 'hiw_s2_title', 'hiw_s2_desc',
            'hiw_s3_title', 'hiw_s3_desc', 'hiw_s4_title', 'hiw_s4_desc',
            'exams_title', 'exams_subtitle', 'exams_bottom_text', 
            'exams_sub_title', 'exams_sub_desc', 'exams_sub_btn_text',
            'admin_preview_title', 'admin_preview_subtitle',
            'admin_stat_1_lbl', 'admin_stat_2_lbl', 'admin_stat_3_lbl', 'admin_stat_4_lbl',
            'admin_feat_1_title', 'admin_feat_1_desc',
            'admin_feat_2_title', 'admin_feat_2_desc',
            'admin_feat_3_title', 'admin_feat_3_desc',
            'admin_feat_4_title', 'admin_feat_4_desc',
            'admin_check_1', 'admin_check_2', 'admin_check_3',
            'cms_badge', 'cms_title', 'cms_desc',
            'cms_feat_1_title', 'cms_feat_1_desc',
            'cms_feat_2_title', 'cms_feat_2_desc',
            'cms_feat_3_title', 'cms_feat_3_desc',
            'cms_feat_4_title', 'cms_feat_4_desc',
            'pricing_title', 'pricing_subtitle',
            'pricing_trust_1', 'pricing_trust_2', 'pricing_trust_3', 'pricing_trust_4',
            'testimonials_title', 'testimonials_subtitle',
            'faq_title', 'faq_subtitle',
            'faq_q1_title', 'faq_q1_desc',
            'faq_q2_title', 'faq_q2_desc',
            'faq_q3_title', 'faq_q3_desc',
            'faq_q4_title', 'faq_q4_desc',
            'cta_title', 'cta_subtitle', 'cta_btn_text', 'cta_btn2_text',
        ];

        $exclude = [
            '_token',
            'hero_image_file', 'delete_hero_image',
            'admin_preview_image_file', 'delete_admin_preview_image',
            'cms_section_image_file', 'delete_cms_section_image',
            'cms_image_1_file', 'delete_cms_image_1',
            'cms_image_2_file', 'delete_cms_image_2',
            'avatar_1_file', 'delete_avatar_1', 
            'avatar_2_file', 'delete_avatar_2',
            'avatar_3_file', 'delete_avatar_3', 
            'avatar_4_file', 'delete_avatar_4',
            'aud_c1_image_file', 'delete_aud_c1_image',
            'aud_c2_image_file', 'delete_aud_c2_image',
            'aud_c3_image_file', 'delete_aud_c3_image',
            'aud_c4_image_file', 'delete_aud_c4_image',
            'aud_c5_image_file', 'delete_aud_c5_image',
            'selected_categories', 'selected_plans',
            'home_pricing_popular_id',
        ];

        $data = $request->except($exclude);

        foreach ($data as $key => $value) {
            if ($key === 'exams_count' || $key === 'home_stat_3_count' || (Str::startsWith($key, 'admin_stat_') && Str::endsWith($key, '_val'))) {
                 $finalValue = $value;
            }
            elseif (Str::endsWith($key, '_icon')) {
                $finalValue = $value;
            }
            elseif (in_array($key, $translatable) && is_array($value)) {
                $finalValue = json_encode($value, JSON_UNESCAPED_UNICODE);
            } 
            else {
                $finalValue = $value;
            }

            if ($finalValue === null || trim($finalValue) === '') {
                SystemSetting::where('key', $key)->delete();
            } else {
                SystemSetting::updateOrCreate(['key' => $key], ['value' => $finalValue]);
            }
        }

        $cats = $request->has('selected_categories') ? json_encode($request->selected_categories) : '[]';
        SystemSetting::updateOrCreate(['key' => 'home_categories_list'], ['value' => $cats]);

        $plans = $request->has('selected_plans') ? json_encode($request->selected_plans) : '[]';
        SystemSetting::updateOrCreate(['key' => 'home_plans_list'], ['value' => $plans]);

        $popularId = $request->input('home_pricing_popular_id') ?? null;
        SystemSetting::updateOrCreate(['key' => 'home_pricing_popular_id'], ['value' => $popularId]);

        $this->handleImage($request, 'hero_image_file', 'home_hero_image', 'delete_hero_image');
        $this->handleImage($request, 'admin_preview_image_file', 'admin_preview_image', 'delete_admin_preview_image');
        $this->handleImage($request, 'cms_section_image_file', 'cms_section_image', 'delete_cms_section_image');
        $this->handleImage($request, 'cms_image_1_file', 'cms_image_1', 'delete_cms_image_1');
        $this->handleImage($request, 'cms_image_2_file', 'cms_image_2', 'delete_cms_image_2');

        for ($i = 1; $i <= 4; $i++) {
            $this->handleImage($request, "avatar_{$i}_file", "avatar_{$i}", "delete_avatar_{$i}");
        }

        for ($i = 1; $i <= 5; $i++) {
            $this->handleImage($request, "aud_c{$i}_image_file", "aud_c{$i}_image", "delete_aud_c{$i}_image");
        }
        
        Cache::forget('system_settings');
        Cache::forget('global_settings');

        return back()->with('success', 'Homepage settings updated successfully.');
    }

    private function handleImage($request, $inputName, $dbKey, $deleteInputName)
    {
        if ($request->hasFile($inputName)) {
            $oldPath = SystemSetting::where('key', $dbKey)->value('value');
            if ($oldPath) {
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                    PublicStorageMirror::delete($oldPath);
                }
            }
            $path = $request->file($inputName)->store('cms/homepage', 'public');
            SystemSetting::updateOrCreate(['key' => $dbKey], ['value' => $path]);
            PublicStorageMirror::sync($path);
        }

        if ($request->filled($deleteInputName) && $request->input($deleteInputName) == '1') {
            $old = SystemSetting::where('key', $dbKey)->value('value');
            if ($old) {
                if (Storage::disk('public')->exists($old)) {
                    Storage::disk('public')->delete($old);
                    PublicStorageMirror::delete($old);
                }
            }
            SystemSetting::updateOrCreate(['key' => $dbKey], ['value' => null]);
        }
    }
}