<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        $settings = SystemSetting::whereIn('key', [
            'ext_recaptcha_enable',
            'ext_recaptcha_site_key',
            'ext_custom_captcha_enable',
            'social_google_enable',
            'social_facebook_enable',
            'app_logo_dark',
            'app_logo_light',
            'ext_otp_enable',
            'ext_otp_provider'
        ])->pluck('value', 'key');

        return view('auth.login', compact('settings'));
    }

    public function login(Request $request)
    {
        $settings = SystemSetting::whereIn('key', [
            'ext_recaptcha_enable',
            'ext_recaptcha_secret',
            'ext_custom_captcha_enable'
        ])->pluck('value', 'key');

        $rules = [
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ];

        if (($settings['ext_recaptcha_enable'] ?? '0') === '1') {
            $rules['g-recaptcha-response'] = ['required', function ($attribute, $value, $fail) use ($settings) {
                $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret' => $settings['ext_recaptcha_secret'],
                    'response' => $value,
                    'remoteip' => request()->ip()
                ]);

                if (!$response->json('success')) {
                    $fail('Google reCAPTCHA verification failed. Please try again.');
                }
            }];
        } elseif (($settings['ext_custom_captcha_enable'] ?? '0') === '1') {
            $rules['captcha'] = ['required', 'captcha'];
        }

        $request->validate($rules);

        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->is_banned || $user->status === 0) {
                Auth::logout();
                throw ValidationException::withMessages([
                    'email' => 'Your account has been suspended. Please contact support.',
                ]);
            }

            $isSuperAdmin = ($user->id === 1 || $user->hasRole('Super Admin'));

            if (!$isSuperAdmin && class_exists('\Addons\OtpAuth\Models\OtpCode') && SystemSetting::where('key', 'ext_otp_enable')->value('value') == '1') {
                $settings = SystemSetting::whereIn('key', ['ext_otp_length', 'ext_otp_provider'])->pluck('value', 'key');
                $length = $settings['ext_otp_length'] ?? 6;
                $provider = $settings['ext_otp_provider'] ?? 'email';
                $otp = str_pad(random_int(0, pow(10, $length) - 1), $length, '0', STR_PAD_LEFT);
                $identifier = ($provider === 'sms') ? $user->phone : $user->email;
    
                if ($provider === 'sms' && is_null($user->phone)) {
                    Auth::logout();
                    throw ValidationException::withMessages([
                        'email' => 'Your account does not have a phone number for SMS OTP.',
                    ]);
                }
    
                try {
                    if ($provider === 'sms') {
                        $this->sendSms($user->phone, $otp);
                    } else {
                        Mail::raw("Your login OTP code is: $otp", function ($message) use ($user) {
                            $message->to($user->email)->subject('Login OTP Code');
                        });
                    }
        
                    \Addons\OtpAuth\Models\OtpCode::create([
                        'identifier' => $identifier,
                        'otp' => $otp,
                        'expires_at' => now()->addMinutes(10),
                    ]);
        
                    Auth::logout();
                    
                    $request->session()->put('otp_identifier', $identifier);
                    $request->session()->put('otp_user_id', $user->id);
                    $request->session()->put('otp_remember', $request->boolean('remember'));
        
                    return redirect()->route('otp.verify.form');

                } catch (\Exception $e) {
                    Log::error("OTP Sending Failed: " . $e->getMessage());
                    Auth::logout();
                    throw ValidationException::withMessages([
                        'email' => 'Could not send OTP. Please check your configuration.',
                    ]);
                }
            }

            if ($user->id === 1 || $user->hasAnyRole(['Super Admin', 'Instructor', 'Admin'])) {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('user.dashboard');
        }

        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    private function sendSms($phoneNumber, $otp)
    {
        $settings = SystemSetting::whereIn('key', [
            'ext_otp_sms_provider', 'ext_twilio_sid', 'ext_twilio_token', 'ext_twilio_from',
            'ext_vonage_key', 'ext_vonage_secret', 'ext_vonage_from'
        ])->pluck('value', 'key');
    
        $provider = $settings['ext_otp_sms_provider'] ?? null;
        $message = "Your verification code is: $otp";
    
        if ($provider === 'twilio' && !empty($settings['ext_twilio_sid'])) {
            $client = new \Twilio\Rest\Client($settings['ext_twilio_sid'], $settings['ext_twilio_token']);
            $client->messages->create($phoneNumber, [
                'from' => $settings['ext_twilio_from'],
                'body' => $message
            ]);
        } elseif ($provider === 'vonage' && !empty($settings['ext_vonage_key'])) {
            $basic  = new \Vonage\Client\Credentials\Basic($settings['ext_vonage_key'], $settings['ext_vonage_secret']);
            $client = new \Vonage\Client($basic);
            $client->sms()->send(
                new \Vonage\SMS\Message\SMS($phoneNumber, $settings['ext_vonage_from'], $message)
            );
        } else {
            throw new \Exception('No valid SMS provider configured or credentials missing.');
        }
    }
}