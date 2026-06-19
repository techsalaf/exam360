<?php

namespace App\Http\Controllers\Admin\CMS;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\PageSection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::orderBy('updated_at', 'desc')->paginate(10);
        return view('admin.cms.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.cms.pages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'sections' => 'nullable|array',
        ]);

        try {
            DB::transaction(function () use ($request) {
                // Generate unique slug
                $slug = Str::slug($request->title);
                $originalSlug = $slug;
                $count = 1;
                while (Page::where('slug', $slug)->exists()) {
                    $slug = $originalSlug . '-' . $count++;
                }

                $page = Page::create([
                    'title' => $request->title,
                    'slug' => $slug,
                    'meta_description' => $request->meta_description,
                    'is_published' => $request->has('is_published'),
                ]);

                if ($request->has('sections')) {
                    // Reset keys to ensure sort_order is 0,1,2...
                    $sections = array_values($request->sections);
                    
                    foreach ($sections as $index => $sectionData) {
                        PageSection::create([
                            'page_id' => $page->id,
                            'type' => $sectionData['type'],
                            'content' => $sectionData['content'] ?? [], // Ensure content is not null
                            'sort_order' => $index,
                        ]);
                    }
                }
            });

            return redirect()->route('admin.cms.pages.index')->with('success', 'Page published successfully.');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function edit(Page $page)
    {
        $page->load(['sections' => function($query) {
            $query->orderBy('sort_order', 'asc');
        }]);
        
        return view('admin.cms.pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'sections' => 'nullable|array',
        ]);

        try {
            DB::transaction(function () use ($request, $page) {
                
                $page->update([
                    'title' => $request->title,
                    'meta_description' => $request->meta_description,
                    'is_published' => $request->has('is_published'),
                ]);

                // Clear old sections
                $page->sections()->delete();

                if ($request->has('sections')) {
                    // CRITICAL FIX: Reset array keys so sort_order matches visual order
                    $sections = array_values($request->sections);

                    foreach ($sections as $index => $sectionData) {
                        PageSection::create([
                            'page_id' => $page->id,
                            'type' => $sectionData['type'],
                            'content' => $sectionData['content'] ?? [],
                            'sort_order' => $index,
                        ]);
                    }
                }
            });

            return redirect()->route('admin.cms.pages.index')->with('success', 'Page updated successfully.');

        } catch (\Exception $e) {
            // Show exact error message
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function destroy(Page $page)
    {
        $page->delete();
        return back()->with('success', 'Page deleted successfully.');
    }
}