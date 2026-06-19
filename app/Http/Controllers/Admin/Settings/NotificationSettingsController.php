<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Traits\SettingsUpdater;

class NotificationSettingsController extends Controller
{
    use SettingsUpdater;

    protected array $toggles = [
        'system_email_enable', 
        'system_sms_enable', 
        'system_push_enable',
        'notify_signup_email', 
        'notify_signup_sms', 
        'notify_signup_push',
        'notify_exam_email', 
        'notify_exam_sms', 
        'notify_exam_push',
        'notify_payment_email', 
        'notify_payment_sms', 
        'notify_payment_push',
        'sms_sandbox_mode',
        'social_google_enable',
        'social_facebook_enable'
    ];

    public function index()
    {
        $settings = SystemSetting::pluck('value', 'key')->toArray();

        return view('admin.settings.groups.notifications.general', compact('settings'));
    }

    public function update(Request $request)
    {
        $response = $this->updateSettings(
            $request, 
            null, 
            '#pane-notifications', 
            $this->toggles
        );

        try {
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
        } catch (\Exception $e) {

        }

        return $response;
    }

    public function editTemplates($type)
    {
        if (!in_array($type, ['email', 'sms', 'push'])) {
            abort(404);
        }

        $settings = SystemSetting::pluck('value', 'key')->toArray();

        $views = [
            'email' => 'admin.settings.groups.notifications.templates.email',
            'sms'   => 'admin.settings.groups.notifications.templates.sms',
            'push'  => 'admin.settings.groups.notifications.templates.push',
        ];

        return view($views[$type], compact('settings'));
    }

    public function updateTemplates(Request $request, $type)
    {
        return $this->updateSettings($request, 'notifications');
    }

    public function sendTestEmail(Request $request)
    {
        $user = auth()->user();

        try {
            $settings = SystemSetting::pluck('value', 'key')->toArray();
            $driver = $settings['mail_driver'] ?? 'log';

            Config::set('mail.default', $driver);
            
            if ($driver === 'smtp') {
                Config::set('mail.mailers.smtp.host', $settings['mail_host'] ?? '');
                Config::set('mail.mailers.smtp.port', $settings['mail_port'] ?? 587);
                Config::set('mail.mailers.smtp.encryption', $settings['mail_encryption'] ?? 'tls');
                Config::set('mail.mailers.smtp.username', $settings['mail_username'] ?? '');
                Config::set('mail.mailers.smtp.password', $settings['mail_password'] ?? '');
            } elseif ($driver === 'mailgun') {
                Config::set('services.mailgun.domain', $settings['mailgun_domain'] ?? '');
                Config::set('services.mailgun.secret', $settings['mailgun_secret'] ?? '');
                Config::set('services.mailgun.endpoint', $settings['mailgun_endpoint'] ?? 'api.mailgun.net');
            }

            Config::set('mail.from.address', $settings['mail_from_address'] ?? config('mail.from.address'));
            Config::set('mail.from.name', $settings['mail_from_name'] ?? config('mail.from.name'));

            Mail::raw('This is a test email to verify your ZiExam AI SMTP configurations.', function($msg) use ($user) {
                $msg->to($user->email);
                $msg->subject('Test Notification - ZiExam AI');
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Test email sent successfully to ' . $user->email
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to send email: ' . $e->getMessage()
            ], 500);
        }
    }

    public function resolveGroup($key)
    {
        if (str_starts_with($key, 'sms_')) {
            return 'sms';
        }
        
        if (str_starts_with($key, 'firebase_') || str_starts_with($key, 'push_')) {
            return 'push';
        }
        
        if (str_starts_with($key, 'social_')) {
            return 'social';
        }

        return 'notifications';
    }
}