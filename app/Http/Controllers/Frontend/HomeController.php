<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Exam;
use App\Models\Plan;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class HomeController extends Controller
{
    private function getLocalizedSetting($value)
    {
        if (!is_string($value)) {
            return '';
        }

        $decoded = json_decode($value, true);

        if (!is_array($decoded)) {
            return is_string($value) ? $value : '';
        }

        $locale = App::getLocale();
        $text = $decoded[$locale] ?? $decoded['en'] ?? '';

        return is_string($text) ? $text : '';
    }

    private function processSettings(array $settings): array
    {
        $formatted = [];
        foreach ($settings as $key => $value) {
            $formatted[$key] = $this->getLocalizedSetting($value);
        }
        return $formatted;
    }

    public function index(): View
    {
        $rawSettings = DB::table('system_settings')->pluck('value', 'key')->toArray();
        $settings = $this->processSettings($rawSettings);

        $categories = collect();
        if (($rawSettings['frontend_show_categories'] ?? '1') == '1') {
            $selectedCatIds = json_decode($rawSettings['home_categories_list'] ?? '[]', true);
            $categoryQuery = Category::where('is_active', true)->withCount('exams');
            
            if (!empty($selectedCatIds) && is_array($selectedCatIds)) {
                $categories = $categoryQuery->whereIn('id', $selectedCatIds)->get();
            } else {
                $categories = $categoryQuery->latest()->take(6)->get();
            }
        }

        $featuredExams = collect();
        if (($rawSettings['frontend_show_exams'] ?? '1') == '1') {
            $examLimit = (int) ($rawSettings['exams_count'] ?? 3);
            $featuredExams = Exam::where('is_active', true)
                ->with(['category'])
                ->latest()
                ->take($examLimit)
                ->get();
        }
        
        $plans = collect();
        if (($rawSettings['frontend_show_pricing'] ?? '1') == '1') {
            $selectedPlanIds = json_decode($rawSettings['home_plans_list'] ?? '[]', true);
            $planQuery = Plan::where('is_active', true);

            if (!empty($selectedPlanIds) && is_array($selectedPlanIds)) {
                $plans = $planQuery->whereIn('id', $selectedPlanIds)->orderBy('price_monthly', 'asc')->get();
            } else {
                $plans = $planQuery->orderBy('price_monthly', 'asc')->take(3)->get();
            }
        }

        $testimonials = collect();
        if (($rawSettings['frontend_show_testimonials'] ?? '1') == '1') {
            $testimonials = Testimonial::where('is_active', true)
                ->orderBy('sort_order', 'asc')
                ->latest()
                ->get();
        }

        return view('frontend.home.index', compact(
            'settings',
            'rawSettings',
            'categories',
            'featuredExams',
            'plans',
            'testimonials'
        ));
    }

    public function exams(Request $request): View
    {
        $rawSettings = DB::table('system_settings')->pluck('value', 'key')->toArray();
        $settings = $this->processSettings($rawSettings);

        $query = Exam::where('is_active', true)->withCount('questions')->with(['category']);

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->has('category') && is_array($request->category)) {
            $categorySlugs = array_filter($request->category);
            $categoryIds = Category::whereIn('slug', $categorySlugs)->pluck('id');
            if ($categoryIds->isNotEmpty()) {
                $query->whereIn('category_id', $categoryIds);
            }
        }

        if ($request->filled('price_type')) {
            if ($request->price_type === 'free') {
                $query->where('is_paid', false);
            } elseif ($request->price_type === 'paid') {
                $query->where('is_paid', true);
            }
        }

        $sortBy = $request->input('sort_by', 'newest');
        if ($sortBy === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sortBy === 'price_desc') {
            $query->orderBy('price', 'desc');
        } else {
            $query->latest();
        }

        $exams = $query->paginate(12)->withQueryString();

        $categories = Category::where('is_active', true)
            ->withCount(['exams' => function ($q) use ($request) {
                if ($request->filled('price_type')) {
                    if ($request->price_type === 'free') {
                        $q->where('is_paid', false);
                    } elseif ($request->price_type === 'paid') {
                        $q->where('is_paid', true);
                    }
                }
            }])
            ->get();
        
        return view('frontend.pages.exam-list', compact('exams', 'categories', 'settings'));
    }

    public function cart(): View { return view('frontend.checkout.cart'); }
    public function checkout(): View { return view('frontend.checkout.checkout'); }
    public function payment(): View { return view('frontend.checkout.payment'); }
    public function success(): View { return view('frontend.checkout.success'); }
}