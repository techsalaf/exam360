<?php

namespace App\Http\Controllers\Admin\CMS;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Stichoza\GoogleTranslate\GoogleTranslate;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::whereIn('location', [
            'header', 
            'footer_column_1', 
            'footer_column_2'
        ])->get()->keyBy('location');

        $header     = $menus->get('header') ?? new Menu(['location' => 'header', 'items' => []]);
        $footerCol1 = $menus->get('footer_column_1') ?? new Menu(['location' => 'footer_column_1', 'items' => []]);
        $footerCol2 = $menus->get('footer_column_2') ?? new Menu(['location' => 'footer_column_2', 'items' => []]);

        return view('admin.cms.menus.index', compact('header', 'footerCol1', 'footerCol2'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'location'        => 'required|in:header,footer_column_1,footer_column_2',
            'title'           => 'nullable|string|max:100',
            'items'           => 'nullable|array',
            'items.*.label'   => 'required|string|max:50',
            'items.*.url'     => 'required|string',
        ]);

        $translator = new GoogleTranslate();
        $targetLocales = ['es', 'de', 'bn'];

        // 1. Auto-Translate Menu Title
        $titleValue = $request->title;
        if (!empty($titleValue)) {
            $titleJson = ['en' => $titleValue];
            try {
                foreach ($targetLocales as $lang) {
                    $translator->setSource('en');
                    $translator->setTarget($lang);
                    $titleJson[$lang] = $translator->translate($titleValue);
                }
                $finalTitle = json_encode($titleJson, JSON_UNESCAPED_UNICODE);
            } catch (\Exception $e) {
                $finalTitle = $titleValue;
            }
        } else {
            $finalTitle = match($request->location) {
                'header' => 'Header Menu',
                'footer_column_1' => 'Footer Links 1',
                'footer_column_2' => 'Footer Links 2',
                default => 'Menu'
            };
        }

        // 2. Process and Translate Menu Items
        $cleanItems = collect($request->items ?? [])
            ->filter(function ($item) {
                return !empty($item['label']) && !empty($item['url']);
            })
            ->map(function ($item) use ($translator, $targetLocales) {
                
                $labelValue = $item['label'];
                $labelJson = ['en' => $labelValue];

                try {
                    foreach ($targetLocales as $lang) {
                        $translator->setSource('en');
                        $translator->setTarget($lang);
                        $labelJson[$lang] = $translator->translate($labelValue);
                    }
                    $finalLabel = json_encode($labelJson, JSON_UNESCAPED_UNICODE);
                } catch (\Exception $e) {
                    $finalLabel = $labelValue;
                }

                return [
                    'label' => $finalLabel,
                    'url'   => $item['url']
                ];
            })
            ->values()
            ->all();

        Menu::updateOrCreate(
            ['location' => $request->location],
            [
                'name'  => $finalTitle,
                'items' => $cleanItems
            ]
        );

        // Clear the cache so frontend reflects changes immediately
        Cache::forget('global_menus');

        return back()->with('success', __('cms.updated') ?? 'Menu updated successfully.');
    }
}