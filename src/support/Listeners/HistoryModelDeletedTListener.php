<?php

namespace Omadonex\LaravelTools\Support\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Omadonex\LaravelTools\Support\Events\ModelDeletedT;
use Omadonex\LaravelTools\Support\Models\HistoryEvent;
use Omadonex\LaravelTools\Support\Traits\HistoryServiceTrait;

class HistoryModelDeletedTListener
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
    public function handle(ModelDeletedT $event): void
    {
        if ($event->modelClass::HISTORY_ENABLED ?? false) {
            $this->writeToHistory($event->userId, $event->modelId, $event->modelClass, HistoryEvent::DELETE_T, [], []);
        }
    }
}
