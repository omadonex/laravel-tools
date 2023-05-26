<?php

namespace Omadonex\LaravelTools\Acl\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Omadonex\LaravelTools\Acl\Models\User;

class UserRoleAttached
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;
    public string $roleId;
    public int $userId;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, string $roleId, int $userId)
    {
        $this->user = $user;
        $this->roleId = $roleId;
        $this->userId = $userId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
