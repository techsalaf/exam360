<?php

namespace App\Http\Controllers\Admin\CMS;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use App\Helpers\PublicStorageMirror;
use Stichoza\GoogleTranslate\GoogleTranslate;

class FooterController extends Controller
{
    public function index()
    {
        $settings = SystemSetting::pluck('value', 'key')->toArray();
        return view('admin.cms.footer.index', compact('settings'));
    }

    public function update(Request $request)
    {
        if ($request->hasFile('footer_logo')) {
            $oldLogo = SystemSetting::where('key', 'footer_logo')->value('value');
            
            if ($oldLogo) {
                if (Storage::disk('public')->exists($oldLogo)) {
                    Storage::disk('public')->delete($oldLogo);
                }
                if (class_exists(PublicStorageMirror::class)) {
                    PublicStorageMirror::delete($oldLogo);
                }
            }

            $path = $request->file('footer_logo')->store('logos', 'public');
            
            SystemSetting::updateOrCreate(['key' => 'footer_logo'], ['value' => $path]);

            if (class_exists(PublicStorageMirror::class)) {
                PublicStorageMirror::sync($path);
            }
        }

        $translatableFields = [
            'footer_about_text', 
            'footer_copyright', 
            'contact_address'
        ];
        
        $targetLocales = ['es', 'de', 'bn'];
        $tr = new GoogleTranslate();
        
        $data = $request->except(['_token', 'footer_logo']);

        foreach ($data as $key => $value) {
            
            if (in_array($key, $translatableFields) && !empty($value)) {
                
                $payload = ['en' => $value];

                try {
                    foreach ($targetLocales as $lang) {
                        $tr->setSource('en');
                        $tr->setTarget($lang);
                        $payload[$lang] = $tr->translate($value);
                    }
                    $finalValue = json_encode($payload, JSON_UNESCAPED_UNICODE);
                    
                } catch (\Exception $e) {
                    $finalValue = $value;
                }
            } else {
                $finalValue = $value;
            }

            SystemSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $finalValue]
            );
        }

        Cache::forget('global_settings');
        Cache::forget('system_settings');

        return back()->with('success', __('cms.updated') ?? 'Footer settings updated successfully.');
    }
}