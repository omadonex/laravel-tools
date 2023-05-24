<?php

namespace Omadonex\LaravelTools\Support\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Omadonex\LaravelTools\Support\Events\ModelCreated;
use Omadonex\LaravelTools\Support\Models\HistoryEvent;
use Omadonex\LaravelTools\Support\Traits\HistoryServiceTrait;

class HistoryModelCreatedListener
{
    use HistoryServiceTrait;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ModelCreated $event): void
    {
        if ($event->model->hasHistory ?? false) {
            $this->writeToHistory($event->userId, $event->model->getKey(), get_class($event->model), HistoryEvent::CREATE, [], ['__common' => $event->data]);
        }
    }
}
