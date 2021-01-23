<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\FilmerEmailNotification;


class FlimerCreated
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
    public function handle($event)
    {
        try {
            \Notification::route('mail', $event->casts['email'])
            ->notify(new FilmerEmailNotification($event));
        } catch (\Throwable $th) {
            Log::Error($th);
        }
       
    }
}
