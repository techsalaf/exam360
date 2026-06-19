<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\SystemSetting;
use App\Notifications\SystemNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        $settings = SystemSetting::whereIn('key', [
            'ext_recaptcha_enable',
            'ext_recaptcha_site_key',
            'ext_custom_captcha_enable',
            'security_policy_terms_url',
            'security_policy_privacy_url',
            'social_google_enable',
            'social_facebook_enable',
            'app_logo_dark',
            'app_logo_light',
            'ext_otp_enable',
            'ext_otp_provider',
            'registration_custom_fields'
        ])->pluck('value', 'key');

        return view('auth.register', compact('settings'));
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $user = $this->create($request->all());

        if (class_exists('\Addons\OtpAuth\Models\OtpCode') && SystemSetting::where('key', 'ext_otp_enable')->value('value') == '1') {
            $settings = SystemSetting::whereIn('key', ['ext_otp_length', 'ext_otp_provider'])->pluck('value', 'key');
            $length = $settings['ext_otp_length'] ?? 6;
            $provider = $settings['ext_otp_provider'] ?? 'email';
            $otp = str_pad(random_int(0, pow(10, $length) - 1), $length, '0', STR_PAD_LEFT);
            $identifier = ($provider === 'sms') ? $user->phone : $user->email;
    
            try {
                if ($provider === 'sms') {
                    $this->sendSms($user->phone, $otp);
                } else {
                    Mail::raw("Your registration OTP code is: $otp", function ($message) use ($user) {
                        $message->to($user->email)->subject('Registration OTP Code');
                    });
                }
    
                \Addons\OtpAuth\Models\OtpCode::create([
                    'identifier' => $identifier,
                    'otp' => $otp,
                    'expires_at' => now()->addMinutes(10),
                ]);
    
                session()->put('otp_identifier', $identifier);
                session()->put('otp_user_id', $user->id);
                return redirect()->route('otp.verify.form');
    
            } catch (\Exception $e) {
                Log::error("OTP Sending Failed: " . $e->getMessage());
                $user->delete();
                return back()->withInput()->withErrors('Could not send OTP. Please check your phone/email and try again.');
            }
        }

        Auth::login($user);

        $settings = SystemSetting::whereIn('key', [
            'system_email_enable',
            'notify_signup_email'
        ])->pluck('value', 'key');

        $emailEnabled = $settings['system_email_enable'] ?? '0';
        $signupEmailEnabled = $settings['notify_signup_email'] ?? '0';

        if ($emailEnabled == '1' && $signupEmailEnabled == '1') {
            try {
                event(new Registered($user));
            } catch (\Exception $e) {
                Log::error('Registration Email Failed: ' . $e->getMessage());
                session()->flash('mail_error', 'Account created successfully, but verification email could not be sent.');
            }
        }

        return redirect()->route('user.dashboard');
    }

    protected function validator(array $data)
    {
        $settings = SystemSetting::whereIn('key', [
            'ext_recaptcha_enable',
            'ext_recaptcha_secret',
            'ext_custom_captcha_enable',
            'ext_otp_enable',
            'ext_otp_provider',
            'registration_custom_fields'
        ])->pluck('value', 'key');

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];

        // Handle Dynamic Registration Fields Validation
        if (!empty($settings['registration_custom_fields'])) {
            $customFields = json_decode($settings['registration_custom_fields'], true);
            foreach ($customFields as $field) {
                $fieldKey = 'custom_' . str_replace(' ', '_', strtolower($field['label']));
                $fieldRules = [];
                
                if ($field['required'] === '1') {
                    $fieldRules[] = 'required';
                } else {
                    $fieldRules[] = 'nullable';
                }

                if ($field['type'] === 'attachment') {
                    $fieldRules[] = 'file';
                    $fieldRules[] = 'max:5120'; // 5MB limit
                } else {
                    $fieldRules[] = 'string';
                }

                $rules[$fieldKey] = $fieldRules;
            }
        }

        if (($settings['ext_otp_enable'] ?? '0') === '1' && ($settings['ext_otp_provider'] ?? '') === 'sms') {
            $rules['phone'] = ['required', 'string', 'max:20', 'unique:users,phone'];
        }

        if (($settings['ext_recaptcha_enable'] ?? '0') === '1' && !empty($settings['ext_recaptcha_secret'])) {
            $rules['g-recaptcha-response'] = [
                'required',
                function ($attribute, $value, $fail) use ($settings) {
                    try {
                        $response = Http::asForm()->post(
                            'https://www.google.com/recaptcha/api/siteverify',
                            [
                                'secret' => $settings['ext_recaptcha_secret'],
                                'response' => $value,
                                'remoteip' => request()->ip()
                            ]
                        );

                        if (!$response->json('success')) {
                            $fail('Google reCAPTCHA verification failed.');
                        }
                    } catch (\Exception $e) {
                        Log::error('reCAPTCHA request failed: ' . $e->getMessage());
                        $fail('Could not verify reCAPTCHA. Please try again later.');
                    }
                }
            ];
        } elseif (($settings['ext_custom_captcha_enable'] ?? '0') === '1') {
            $rules['captcha'] = ['required', 'captcha'];
        }

        return Validator::make($data, $rules);
    }

    protected function create(array $data)
    {
        $settings = SystemSetting::where('key', 'registration_custom_fields')->value('value');
        $customData = [];

        if (!empty($settings)) {
            $customFields = json_decode($settings, true);
            foreach ($customFields as $field) {
                $fieldKey = 'custom_' . str_replace(' ', '_', strtolower($field['label']));
                if (request()->hasFile($fieldKey)) {
                    $customData[$field['label']] = request()->file($fieldKey)->store('user_attachments', 'public');
                } else {
                    $customData[$field['label']] = $data[$fieldKey] ?? null;
                }
            }
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
            'custom_fields' => $customData, // Assumes json column in users table
        ]);

        $defaultRoleId = SystemSetting::where('key', 'default_signup_role')->value('value');

        if ($defaultRoleId) {
            $role = Role::find($defaultRoleId);
        } else {
            $role = Role::where('name', 'Student')->first();
        }

        if ($role && $role->name !== 'Super Admin') {
            $user->assignRole($role);
        }

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        try {
            $admins = User::role('Super Admin')->get();

            if ($admins->isNotEmpty()) {
                Notification::send($admins, new SystemNotification('user', [
                    'title'   => 'New User Registered',
                    'message' => "{$user->name} ({$user->email}) has just created an account.",
                    'url'     => route('admin.users.show', $user->id),
                    'icon'    => 'fa-solid fa-user-plus',
                    'color'   => 'info'
                ]));
            }
        } catch (\Exception $e) {
            Log::error('Admin Notification Failed: ' . $e->getMessage());
        }

        return $user;
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