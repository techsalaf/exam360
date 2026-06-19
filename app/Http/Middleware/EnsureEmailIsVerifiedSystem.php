<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\SystemSetting;

class EnsureEmailIsVerifiedSystem
{
    public function handle(Request $request, Closure $next, $redirectToRoute = null)
    {

        $setting = SystemSetting::where('key', 'system_email_enable')->value('value');
        $isEmailEnabled = $setting === '1';

        if (!$isEmailEnabled) {
            return $next($request);
        }

        if (! $request->user() ||
            ($request->user() instanceof MustVerifyEmail &&
            ! $request->user()->hasVerifiedEmail())) {
            
            return $request->expectsJson()
                    ? abort(403, 'Your email address is not verified.')
                    : Redirect::route($redirectToRoute ?: 'verification.notice');
        }

        return $next($request);
    }
}