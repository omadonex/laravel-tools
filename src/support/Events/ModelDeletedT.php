<?php

namespace Omadonex\LaravelTools\Support\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ModelDeletedT
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int|string $modelId;
    public string $modelClass;
    public int $userId;
    public array $data;
    public string $lang;

    /**
     * Create a new event instance.
     */
    public function __construct(int|string $modelId, string $modelClass, int $userId, array $data, string $lang)
    {
        $this->modelId = $modelId;
        $this->modelClass = $modelClass;
        $this->userId = $userId;
        $this->data = $data;
        $this->lang = $lang;
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
