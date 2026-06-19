<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminBulkNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function via($notifiable)
    {
        $channels = [];

        if (in_array('email', $this->data['channels'])) {
            $channels[] = 'mail';
        }

        // Add SMS logic here later if needed (e.g., 'vonage', 'twilio')

        return $channels;
    }

    public function toMail($notifiable)
    {
        $content = $this->data['body'];
        
        $content = str_replace('@{{name}}', $notifiable->name, $content);
        $content = str_replace('@{{email}}', $notifiable->email, $content);
        $content = str_replace('@{{site_name}}', config('app.name'), $content);

        return (new MailMessage)
            ->subject($this->data['subject'])
            ->markdown('emails.admin.bulk', ['content' => $content]);
    }
}