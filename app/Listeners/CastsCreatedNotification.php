<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\CastsCreated;
use App\Notifications\CastsEmailNotification;

class CastsCreatedNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(CastsCreated $event)
    {   
        \Notification::route('mail', 'jacob.atam@gmail.com')
        ->notify(new CastsEmailNotification($event));
    }
}
