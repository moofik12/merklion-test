<?php

namespace App\Service\User;

use App\Events\UserJoined;
use App\Events\UserLeft;
use App\User;
use InvalidArgumentException;

class UserChatEventFactory
{
    private const MEMBER_ADDED = 'member_added';
    private const MEMBER_REMOVED = 'member_removed';

    /**
     * @param string $pusherEvent
     * @param User $user
     *
     * @return UserJoined|UserLeft
     */
    public function create(string $pusherEvent, User $user)
    {
        switch ($pusherEvent) {
            case self::MEMBER_ADDED:
                return new UserJoined($user);
            case self::MEMBER_REMOVED:
                return new UserLeft($user);
            default:
                throw new InvalidArgumentException('Invalid chat event');
        }
    }
}