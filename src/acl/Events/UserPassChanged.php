<?php

namespace Omadonex\LaravelTools\Acl\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserPassChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int|string $modelId;
    public int $userId;

    /**
     * Create a new event instance.
     */
    public function __construct(int|string $modelId, int $userId)
    {
        $this->modelId = $modelId;
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
