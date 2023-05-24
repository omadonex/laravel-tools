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
    public string $lang;
    public array $dataT;
    public int $userId;

    /**
     * Create a new event instance.
     */
    public function __construct(int|string $modelId, string $modelClass, string $lang, array $dataT, int $userId)
    {
        $this->modelId = $modelId;
        $this->modelClass = $modelClass;
        $this->lang = $lang;
        $this->dataT = $dataT;
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
