<?php

namespace Omadonex\LaravelTools\Acl\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserRoleDetached
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int|string $modelId;
    public int $userId;
    public string $roleId;

    /**
     * Create a new event instance.
     */
    public function __construct(int|string $modelId, int $userId, string $roleId)
    {
        $this->modelId = $modelId;
        $this->userId = $userId;
        $this->roleId = $roleId;
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
