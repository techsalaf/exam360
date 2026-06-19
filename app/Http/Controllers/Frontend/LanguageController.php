<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use App\Models\Language;

class LanguageController extends Controller
{
    public function switch($code)
    {
        $language = Language::where('code', $code)
            ->where('is_active_front', true)
            ->first();

        if ($language) {
            // FIX: Use 'front_locale' to match your SetLocale Middleware
            Session::put('front_locale', $code);
            App::setLocale($code);
        }

        return redirect()->back();
    }
}