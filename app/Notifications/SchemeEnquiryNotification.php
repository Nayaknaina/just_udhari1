<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SchemeEnquiryNotification extends Notification
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
            'type'    => 'scheme_enquiry',

            'title'   => 'New Scheme Enquiry',

            'message' =>
                $this->data['customer_name'] .
                ' (' . $this->data['customer_phone'] . ') ne enquiry bheji',

            'scheme'  => $this->data['scheme_name'],

            'enquiry_message' => $this->data['message'],

            // optional paid info
            'utr'     => $this->data['utr'] ?? null,
            'amount'  => $this->data['amount'] ?? null,

            'link'    => $this->data['link'],
        ];
    }
}
