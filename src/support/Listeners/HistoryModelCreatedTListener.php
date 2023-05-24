<?php

namespace Omadonex\LaravelTools\Support\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Omadonex\LaravelTools\Support\Events\ModelCreatedT;
use Omadonex\LaravelTools\Support\Models\HistoryEvent;
use Omadonex\LaravelTools\Support\Traits\HistoryServiceTrait;

class HistoryModelCreatedTListener
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
    public function handle(ModelCreatedT $event): void
    {
        if ($event->model->hasHistory ?? false) {
            $this->writeToHistory($event->userId, $event->modelId, $event->modelClass, HistoryEvent::CREATE_T, [], ['__t' => ['__lang' => $event->lang, '__id' => $event->modelId] + $event->dataT]);
        }
    }
}
