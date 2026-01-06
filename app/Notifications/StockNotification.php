<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class StockNotification extends Notification
{
    use Queueable;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'type'        => 'stock',
            'title'       => $this->data['title'],
            'message'     => $this->data['message'],
            'link'        => $this->data['link'],
            'stock_type'  => $this->data['stock_type'], // gold / silver / artificial / stone / franchise
        ];
    }
}
