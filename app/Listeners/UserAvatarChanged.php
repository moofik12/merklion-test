<?php

namespace App\Listeners;

use App\Events\AvatarChanged;

class UserAvatarChanged
{
    /**
     * Handle the event.
     *
     * @param  AvatarChanged  $event
     * @return void
     */
    public function handle(AvatarChanged $event)
    {
        $user = $event->getUser();
        $user->avatar = $event->getPath();
        $user->save();
    }
}