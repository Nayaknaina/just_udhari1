<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

use App\Events\VendorBillEvent;
use App\Listeners\SendVendorBillNotification;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        VendorBillEvent::class => [
            SendVendorBillNotification::class,
        ],
    ];
}

