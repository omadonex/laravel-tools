<?php

namespace Omadonex\LaravelTools\Support\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ModelCreatedT
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int|string $modelId;
    public string $modelClass;
    public int $userId;
    public array $dataT;
    public string $lang;

    /**
     * Create a new event instance.
     */
    public function __construct(int|string $modelId, string $modelClass, int $userId, array $dataT, string $lang)
    {
        $this->modelId = $modelId;
        $this->modelClass = $modelClass;
        $this->userId = $userId;
        $this->dataT = $dataT;
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
