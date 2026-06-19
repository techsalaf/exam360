<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class SocialLoginController extends Controller
{
    private function setDynamicConfig($provider)
    {
        $settings = SystemSetting::whereIn('key', [
            "social_{$provider}_client_id",
            "social_{$provider}_secret",
        ])->pluck('value', 'key');

        $clientId = $settings["social_{$provider}_client_id"] ?? null;
        $clientSecret = $settings["social_{$provider}_secret"] ?? null;

        if (!$clientId || !$clientSecret) {
            return false;
        }

        config([
            "services.{$provider}" => [
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'redirect' => route('social.callback', $provider),
            ]
        ]);

        return true;
    }

    public function redirect($provider)
    {
        if (!in_array($provider, ['google', 'facebook'])) {
            return redirect()->route('login')->with('error', __('Invalid social provider.'));
        }

        $isEnabled = SystemSetting::where('key', "social_{$provider}_enable")->value('value');
        if ($isEnabled !== '1') {
            return redirect()->route('login')->with('error', ucfirst($provider) . __(' login is currently disabled.'));
        }

        if (!$this->setDynamicConfig($provider)) {
            return redirect()->route('login')->with('error', __('Social provider credentials are missing in settings.'));
        }

        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        if (!$this->setDynamicConfig($provider)) {
            return redirect()->route('login')->with('error', __('Configuration error.'));
        }

        try {
            $socialUser = Socialite::driver($provider)->user();

            $user = User::where('email', $socialUser->getEmail())->first();

            if (!$user) {
                $user = User::create([
                    'name' => $socialUser->getName(),
                    'email' => $socialUser->getEmail(),
                    'password' => Hash::make(Str::random(16)),
                    'email_verified_at' => now(),
                    'status' => 'active',
                ]);
            }

            Auth::login($user);

            return redirect()->route('user.dashboard');

        } catch (Exception $e) {
            return redirect()->route('login')->with('error', __('Login failed. Please try again.'));
        }
    }
}