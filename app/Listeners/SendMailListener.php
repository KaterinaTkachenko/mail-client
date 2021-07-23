<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use App\Events\SendMailEvent;
use App\Mail\SendMail;


class SendMailListener
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
    public function handle(SendMailEvent $event)
    {
        //Log::info('SendMailListener', $event->request );        
        \Mail::to($event->request['recepient'])->send(new SendMail($event->request) );
    }
}
