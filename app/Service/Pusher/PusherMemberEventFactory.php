<?php

namespace App\Service\Pusher;

use App\Events\MemberAdded;
use App\Events\MemberRemoved;
use App\User;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use InvalidArgumentException;

class PusherMemberEventFactory
{
    private const MEMBER_ADDED = 'member_added';
    private const MEMBER_REMOVED = 'member_removed';

    /**
     * @param string $pusherEvent
     * @param User $user
     *
     * @return ShouldBroadcast
     */
    public function create(string $pusherEvent, User $user)
    {
        switch ($pusherEvent) {
            case self::MEMBER_ADDED:
                return new MemberAdded($user);
            case self::MEMBER_REMOVED:
                return new MemberRemoved($user);
            default:
                throw new InvalidArgumentException('Invalid pusher event');
        }
    }
}