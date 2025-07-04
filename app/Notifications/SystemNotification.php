<?php

namespace App\Notifications;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SystemNotification extends Notification
{
    protected $title;
    protected $message;
    protected $link;
    protected $type;

    public function __construct(string $title, string $message, string $link = null, string $type = null)
    {
        $this->title = $title;
        $this->message = $message;
        $this->link = $link;
        $this->type = $type;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'link' => $this->link,
            'type' => $this->type,
        ];
    }
}
