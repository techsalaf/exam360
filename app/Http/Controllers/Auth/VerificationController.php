<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Log;
use App\Models\SystemSetting; // Import SystemSetting

class VerificationController extends Controller
{
    protected $redirectTo = '/dashboard';

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    public function show(Request $request)
    {
        // Check if System Email is Enabled
        $isEnabled = SystemSetting::where('key', 'system_email_enable')->value('value') === '1';

        // If disabled OR user is already verified, go to dashboard
        if (!$isEnabled || $request->user()->hasVerifiedEmail()) {
            return redirect()->route('user.dashboard');
        }

        return view('auth.verify');
    }

    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('user.dashboard');
        }

        try {
            $request->user()->sendEmailVerificationNotification();
            return back()->with('resent', true);
        } catch (\Exception $e) {
            Log::error('Resend Verification Failed: ' . $e->getMessage());
            return back()->with('mail_error', 'Could not connect to email server. Please check SMTP settings.');
        }
    }

    public function verify(Request $request)
    {
        if (! hash_equals((string) $request->route('id'), (string) $request->user()->getKey())) {
            abort(403);
        }

        if (! hash_equals((string) $request->route('hash'), sha1($request->user()->getEmailForVerification()))) {
            abort(403);
        }

        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('user.dashboard')->with('verified', true);
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->route('user.dashboard')->with('verified', true);
    }
}