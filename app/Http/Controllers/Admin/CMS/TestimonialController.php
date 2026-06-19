<?php

namespace App\Http\Controllers\Admin\CMS;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Stichoza\GoogleTranslate\GoogleTranslate;
use App\Helpers\PublicStorageMirror;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::orderBy('sort_order', 'asc')
            ->latest()
            ->paginate(10);

        return view('admin.cms.testimonials.index', compact('testimonials'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'role'   => 'nullable|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|max:1000',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->except('avatar');
        $data['is_active'] = true;

        $this->handleTranslations($data);

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('cms/testimonials', 'public');
            $data['avatar'] = $path;
            
            PublicStorageMirror::sync($path);
        }

        Testimonial::create($data);

        return back()->with('success', __('cms.testimonial_created'));
    }

    public function update(Request $request, $id)
    {
        $testimonial = Testimonial::findOrFail($id);

        $request->validate([
            'name'   => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|max:1000',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->except(['avatar', 'delete_avatar']);

        $this->handleTranslations($data);

        if ($request->has('delete_avatar') && $request->delete_avatar == '1') {
            $this->deleteImage($testimonial->avatar);
            $data['avatar'] = null;
        }

        if ($request->hasFile('avatar')) {
            $this->deleteImage($testimonial->avatar);
            
            $path = $request->file('avatar')->store('cms/testimonials', 'public');
            $data['avatar'] = $path;
            
            PublicStorageMirror::sync($path);
        }

        $testimonial->update($data);

        return back()->with('success', __('cms.testimonial_updated'));
    }

    public function destroy($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        
        $this->deleteImage($testimonial->avatar);
        $testimonial->delete();

        return back()->with('success', __('cms.testimonial_deleted'));
    }

    public function toggleStatus($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        $testimonial->update(['is_active' => !$testimonial->is_active]);

        return back()->with('success', __('cms.status_updated'));
    }

    private function deleteImage($path)
    {
        if (!empty($path)) {
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            PublicStorageMirror::delete($path);
        }
    }

    private function handleTranslations(&$data)
    {
        $translator = new GoogleTranslate();
        $targets = ['es', 'de', 'bn'];

        foreach (['review', 'role'] as $field) {
            if (!empty($data[$field])) {
                $payload = ['en' => $data[$field]];
                try {
                    foreach ($targets as $lang) {
                        $translator->setSource('en');
                        $translator->setTarget($lang);
                        $payload[$lang] = $translator->translate($data[$field]);
                    }
                    $data[$field] = json_encode($payload, JSON_UNESCAPED_UNICODE);
                } catch (\Exception $e) {
                }
            }
        }
    }
}