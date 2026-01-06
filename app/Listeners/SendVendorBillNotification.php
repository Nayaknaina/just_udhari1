<?php

namespace App\Listeners;

use App\Events\VendorBillEvent;          // 🔥 THIS WAS MISSING
use App\Notifications\VendorBillNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendVendorBillNotification
{
    public function handle(VendorBillEvent $event)
    {
        $event->user->notify(
            new VendorBillNotification(
                $event->title,
                $event->message,
                $event->link
            )
        );
    }
}
