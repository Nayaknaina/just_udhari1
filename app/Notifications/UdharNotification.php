<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class UdharNotification extends Notification
{
    use Queueable;

    public $data;

    public function __construct(array $data)
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
        'type'        => 'udhar',
        'title'       => $this->data['title'] ?? '',
        'message'     => $this->data['message'] ?? '',

        'txn_type'    => $this->data['txn_type'] ?? 'udhar',
        'item_type'   => $this->data['item_type'] ?? 'cash',

        'amount'      => $this->data['amount'] ?? 0,
        'unit'        => $this->data['unit'] ?? '₹',

        'balance'     => $this->data['balance'] ?? '',
        'balance_tag' => $this->data['balance_tag'] ?? '',

        'link'        => $this->data['link'] ?? '#',
    ];
}

}
