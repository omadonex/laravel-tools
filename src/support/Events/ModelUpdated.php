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

    public Model $model;
    public array $oldData;
    public array $newData;
    public int $userId;

    /**
     * Create a new event instance.
     */
    public function __construct(Model $model, array $oldData, array $newData, int $userId)
    {
        $this->model = $model;
        $this->oldData = $oldData;
        $this->newData = $newData;
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
