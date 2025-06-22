<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;

class NullChannel
{
    public function send($notifiable, Notification $notification)
    {
        // No hacer nada (canal vacío)
    }
}
