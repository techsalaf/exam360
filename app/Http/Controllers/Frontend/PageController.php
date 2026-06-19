<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function show($slug)
    {
        $page = Page::where('slug', $slug)
                    ->where('is_published', true)
                    ->with(['sections' => function($query) {
                        $query->orderBy('sort_order', 'asc');
                    }])
                    ->firstOrFail();

        return view('frontend.pages.dynamic', compact('page'));
    }
}