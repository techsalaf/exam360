<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\SystemSetting;
use App\Notifications\AdminBulkNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

class UserNotificationController extends Controller
{
    public function create()
    {
        $smsEnabled = SystemSetting::where('key', 'system_sms_enable')->value('value') === '1';
        $emailEnabled = SystemSetting::where('key', 'system_email_enable')->value('value') !== '0';

        return view('admin.users.notifications.create', compact('smsEnabled', 'emailEnabled'));
    }

    public function searchRecipients(Request $request)
    {
        $term = trim($request->get('q'));
        $filter = $request->get('filter', 'active');

        $query = User::query()
            ->select(['id', 'name', 'email', 'username', 'is_banned', 'email_verified_at', 'created_at']);

        if (!empty($term)) {
            $query->where(function ($q) use ($term) {
                $q->where('name', 'LIKE', "%{$term}%")
                  ->orWhere('email', 'LIKE', "%{$term}%")
                  ->orWhere('username', 'LIKE', "%{$term}%");
            });
        }

        switch ($filter) {
            case 'banned':
                $query->where('is_banned', true);
                break;
            case 'unverified':
                $query->whereNull('email_verified_at');
                break;
            default:
                $query->where('is_banned', false);
                break;
        }

        $users = $query->latest()->limit(50)->get();

        $results = $users->map(function ($user) {
            $nameParts = explode(' ', trim($user->name));
            $first = $nameParts[0] ?? '';
            $last = count($nameParts) > 1 ? end($nameParts) : substr($first, 1, 1);

            return [
                'id' => $user->id,
                'name' => $user->name,
                'first_name' => $first,
                'last_name' => $last,
                'email' => $user->email,
                'username' => $user->username ?? ''
            ];
        });

        return response()->json($results);
    }

    public function send(Request $request)
    {
        $request->validate([
            'audience' => 'required|in:all,specific',
            'users' => 'required_if:audience,specific|array',
            'channels' => 'required|array|min:1',
            'email_subject' => [
                function ($attribute, $value, $fail) use ($request) {
                    if (in_array('email', $request->input('channels', [])) && empty($value)) {
                        $fail('The email subject is required.');
                    }
                },
                'nullable', 'string', 'max:255'
            ],
            'email_body' => [
                function ($attribute, $value, $fail) use ($request) {
                    if (in_array('email', $request->input('channels', [])) && empty($value)) {
                        $fail('The email message body is required.');
                    }
                },
                'nullable', 'string'
            ],
            'sms_message' => [
                function ($attribute, $value, $fail) use ($request) {
                    if (in_array('sms', $request->input('channels', [])) && empty($value)) {
                        $fail('The SMS message content is required.');
                    }
                },
                'nullable', 'string', 'max:160'
            ],
        ]);

        try {
            $selectedChannels = $request->input('channels', []);
            
            $settings = SystemSetting::pluck('value', 'key');

            if (in_array('email', $selectedChannels)) {
                
                // 1. Check Sender Identity (The Fix for your error)
                $fromAddress = $settings['mail_from_address'] ?? config('mail.from.address');
                $fromName = $settings['mail_from_name'] ?? config('mail.from.name');

                if (empty($fromAddress)) {
                    return back()->withInput()->with('error', 'Email Campaign Failed: "From Email" address is missing. Please configure it in Settings > Notifications > Email.');
                }

                // 2. Configure Driver
                $driver = $settings['mail_driver'] ?? 'smtp';
                
                if ($driver === 'smtp' && empty($settings['mail_host'])) {
                     return back()->withInput()->with('error', 'Email Campaign Failed: SMTP Host is not configured.');
                }

                Config::set('mail.default', $driver);
                
                if ($driver === 'smtp') {
                    Config::set('mail.mailers.smtp.host', $settings['mail_host'] ?? config('mail.mailers.smtp.host'));
                    Config::set('mail.mailers.smtp.port', $settings['mail_port'] ?? config('mail.mailers.smtp.port'));
                    Config::set('mail.mailers.smtp.encryption', $settings['mail_encryption'] ?? config('mail.mailers.smtp.encryption'));
                    Config::set('mail.mailers.smtp.username', $settings['mail_username'] ?? config('mail.mailers.smtp.username'));
                    Config::set('mail.mailers.smtp.password', $settings['mail_password'] ?? config('mail.mailers.smtp.password'));
                } elseif ($driver === 'mailgun') {
                    Config::set('services.mailgun.domain', $settings['mailgun_domain'] ?? config('services.mailgun.domain'));
                    Config::set('services.mailgun.secret', $settings['mailgun_secret'] ?? config('services.mailgun.secret'));
                    Config::set('services.mailgun.endpoint', $settings['mailgun_endpoint'] ?? config('services.mailgun.endpoint'));
                }

                Config::set('mail.from.address', $fromAddress);
                Config::set('mail.from.name', $fromName);
            }

            if (in_array('sms', $selectedChannels)) {
                $smsDriver = $settings['sms_driver'] ?? 'twilio';
                
                if (empty($settings['sms_api_key'])) {
                    return back()->withInput()->with('error', 'SMS Campaign Failed: SMS Provider settings are missing.');
                }
            }

            $notificationData = [
                'channels' => $selectedChannels,
                'subject'  => $request->email_subject,
                'body'     => $request->email_body,
                'sms'      => $request->sms_message,
            ];

            $query = User::query();

            if ($request->audience === 'all') {
                $query->where('is_banned', false);
            } else {
                $query->whereIn('id', $request->input('users', []));
            }

            $totalRecipients = $query->count();

            if ($totalRecipients === 0) {
                return back()->with('error', 'No valid recipients found based on your selection.');
            }

            $query->chunk(100, function ($users) use ($notificationData) {
                Notification::send($users, new AdminBulkNotification($notificationData));
            });

            return redirect()->route('admin.users.index')
                ->with('success', "Notification campaign queued successfully for {$totalRecipients} users.");

        } catch (\Exception $e) {
            Log::error('Bulk Notification Error: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }
}