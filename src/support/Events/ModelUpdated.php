<?php

namespace Omadonex\LaravelTools\Support\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ModelUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int|string $modelId;
    public string $modelClass;
    public int $userId;
    public array $oldData;
    public array $newData;
    public Model $model;

    /**
     * Create a new event instance.
     */
    public function __construct(int|string $modelId, string $modelClass, int $userId, array $oldData, array $newData, Model $model)
    {
        $this->modelId = $modelId;
        $this->modelClass = $modelClass;
        $this->userId = $userId;
        $this->oldData = $oldData;
        $this->newData = $newData;
        $this->model = $model;
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
