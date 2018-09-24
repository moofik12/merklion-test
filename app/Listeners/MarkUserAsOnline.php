<?php

namespace App\Listeners;

use App\Events\UserJoined;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class MarkUserAsOnline
{
    /**
     * Handle the event.
     *
     * @param  UserJoined  $event
     * @return void
     */
    public function handle(UserJoined $event)
    {
        $user = $event->getUser();
        $user->is_online = true;
        $user->save();
    }
}
