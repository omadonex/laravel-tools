<?php

namespace Omadonex\LaravelTools\Support\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ModelDeleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int|string $modelId;
    public string $modelClass;
    public array $data;
    public int $userId;

    /**
     * Create a new event instance.
     */
    public function __construct(int|string $modelId, string $modelClass, array $data, int $userId)
    {
        $this->modelId = $modelId;
        $this-> modelClass = $modelClass;
        $this->data = $data;
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
