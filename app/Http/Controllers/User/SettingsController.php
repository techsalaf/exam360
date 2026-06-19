<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DateTimeZone;
use App\Models\Language;

class SettingsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $preferences = $user->preferences ?? [
            'email_notify' => true,
            'app_alert'    => true,
            'timezone'     => config('app.timezone'),
            'language'     => config('app.locale'),
        ];

        $timezones = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
        $languages = Language::where('is_active_front', true)->orderBy('name')->get();

        return view('user.settings.index', compact('user', 'preferences', 'timezones', 'languages'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'timezone' => 'required|timezone',
            'language' => 'required|exists:languages,code',
        ]);

        $user = Auth::user();

        $preferences = [
            'email_notify' => $request->boolean('email_notify'),
            'app_alert'    => $request->boolean('app_alert'),
            'timezone'     => $request->timezone,
            'language'     => $request->language,
        ];

        $user->update(['preferences' => $preferences]);

        // Fix: Force update the session locale so Topbar updates immediately
        session(['locale' => $request->language]);
        app()->setLocale($request->language);

        return back()->with('success', __('frontend.settings_saved_success'));
    }
}