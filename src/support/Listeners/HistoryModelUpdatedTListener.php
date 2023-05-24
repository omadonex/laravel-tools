<?php

namespace Omadonex\LaravelTools\Support\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Omadonex\LaravelTools\Support\Events\ModelUpdatedT;
use Omadonex\LaravelTools\Support\Models\HistoryEvent;
use Omadonex\LaravelTools\Support\Traits\HistoryServiceTrait;

class HistoryModelUpdatedTListener
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
    public function handle(ModelUpdatedT $event): void
    {
        if ($event->model->hasHistory ?? false) {
            $keyList = array_keys($event->newDataT);
            $oldDataT = $event->oldDataT;
            $newDataT = $event->newDataT;
            foreach ($keyList as $key) {
                if ($oldDataT[$key] == $newDataT[$key]) {
                    unset($oldDataT[$key]);
                    unset($newDataT[$key]);
                }
            }

            if (!empty($oldDataT) && !empty($newDataT)) {
                $this->writeToHistory($event->userId, $event->modelId, $event-> modelClass, HistoryEvent::UPDATE_T, ['__t' => ['__lang' => $event->lang, '__id' => $event->modelId] + $oldDataT], ['__t' => ['__lang' => $event->lang, '__id' => $event->modelId] + $newDataT]);
            }
        }
    }
}
