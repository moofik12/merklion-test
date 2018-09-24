<?php

namespace App\Events;

use App\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AvatarChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var User
     */
    private $user;

    /**
     * @var string
     */
    private $path;

    /**
     * AvatarChanged constructor.
     * @param User $user
     * @param string $path
     */
    public function __construct(User $user, string $path)
    {
        $this->user = $user;
        $this->path = $path;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return PresenceChannel
     */
    public function broadcastOn()
    {
        return new PresenceChannel('chat.members');
    }

    /**
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'user' => $this->user,
            'path' => $this->path
        ];
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }
}
