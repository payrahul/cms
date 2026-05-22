<?php

namespace App\Listeners;

use App\Events\UrlHitEvent;
Use Illuminate\Support\Facades\Log;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UrlHitListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        Log::info('Listener executed');
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        //
    }
}
