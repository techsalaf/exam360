<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Helpers\PublicStorageMirror;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $categories = $query->withCount('exams')->latest()->paginate(8);

        $kpi = [
            'total'    => Category::count(),
            'active'   => Category::where('is_active', true)->count(),
            'disabled' => Category::where('is_active', false)->count(),
        ];

        return view('admin.categories.index', compact('categories', 'kpi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255|unique:categories,name',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'description' => 'nullable|string|max:500',
            'meta_text_1' => 'nullable|string|max:50',
            'meta_text_2' => 'nullable|string|max:50',
        ]);

        $data = [
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'description' => $request->description,
            'meta_text_1' => $request->meta_text_1,
            'meta_text_2' => $request->meta_text_2,
            'is_active'   => true,
        ];

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('categories', 'public');
            PublicStorageMirror::sync($path);
            $data['image'] = $path;
        }

        Category::create($data);

        return redirect()->back()->with('success', 'Category created successfully.');
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name'  => 'required|string|max:255|unique:categories,name,' . $category->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'description' => 'nullable|string|max:500',
            'meta_text_1' => 'nullable|string|max:50',
            'meta_text_2' => 'nullable|string|max:50',
        ]);

        $data = [
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'description' => $request->description,
            'meta_text_1' => $request->meta_text_1,
            'meta_text_2' => $request->meta_text_2,
        ];

        if ($request->has('delete_image') && $request->delete_image == '1') {
            if ($category->image) {
                $storagePath = public_path('storage/' . $category->image);
                if (file_exists($storagePath)) {
                    @unlink($storagePath);
                }
            }
            $data['image'] = null;
        }

        if ($request->hasFile('image')) {
            if ($category->image) {
                $storagePath = public_path('storage/' . $category->image);
                if (file_exists($storagePath)) {
                    @unlink($storagePath); 
                }
            }

            $path = $request->file('image')->store('categories', 'public');
            PublicStorageMirror::sync($path);
            $data['image'] = $path;
        }

        $category->update($data);

        return redirect()->back()->with('success', 'Category updated successfully.');
    }

    public function toggleStatus($id)
    {
        $category = Category::findOrFail($id);
        $newStatus = !$category->is_active;
        $category->update(['is_active' => $newStatus]);

        return redirect()->back()->with('success', $newStatus ? 'Category enabled.' : 'Category disabled.');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        
        if ($category->image) {
            $storagePath = public_path('storage/' . $category->image);
            if (file_exists($storagePath)) {
                @unlink($storagePath); 
            }
        }

        $category->delete();

        return redirect()->back()->with('success', 'Category deleted successfully.');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:categories,id']);

        $categories = Category::whereIn('id', $request->ids)->get();
        $count = 0;

        foreach ($categories as $category) {
            if ($category->image) {
                $storagePath = public_path('storage/' . $category->image);
                if (file_exists($storagePath)) {
                    @unlink($storagePath);
                }
            }
            $category->delete();
            $count++;
        }

        return redirect()->back()->with('success', "$count categories deleted successfully.");
    }
}