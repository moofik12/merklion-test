<?php

namespace App\Listeners;

use App\Events\UserLeft;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class MarkUserAsOffline
{
    /**
     * Handle the event.
     *
     * @param  UserLeft  $event
     * @return void
     */
    public function handle(UserLeft $event)
    {
        $user = $event->getUser();
        $user->is_online = false;
        $user->save();
    }
}
