<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SystemNotification extends Notification
{
    use Queueable;

    public $type;
    public $data;

    public function __construct(string $type, array $data)
    {
        $this->type = $type; // 'user', 'payment', 'ticket', 'live'
        $this->data = $data;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'type'    => $this->type,
            'title'   => $this->data['title'],
            'message' => $this->data['message'],
            'url'     => $this->data['url'] ?? '#',
            'icon'    => $this->getIcon(),
            'color'   => $this->getColor(),
        ];
    }

    private function getIcon()
    {
        return match($this->type) {
            'user'    => 'fa-solid fa-user-plus',
            'payment' => 'fa-solid fa-credit-card',
            'ticket'  => 'fa-solid fa-headset',
            'live'    => 'fa-solid fa-tower-broadcast',
            default   => 'fa-solid fa-bell'
        };
    }

    private function getColor()
    {
        return match($this->type) {
            'user'    => 'info',
            'payment' => 'success',
            'ticket'  => 'warning',
            'live'    => 'danger',
            default   => 'primary'
        };
    }
}