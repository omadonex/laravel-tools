<?php

namespace Omadonex\LaravelTools\Support\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Omadonex\LaravelTools\Support\Events\ModelUpdated;
use Omadonex\LaravelTools\Support\Models\HistoryEvent;
use Omadonex\LaravelTools\Support\Traits\HistoryServiceTrait;

class HistoryModelUpdatedListener
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
    public function handle(ModelUpdated $event): void
    {
        if (defined("{$event->modelClass}::HISTORY_ENABLED") ? $event->modelClass::HISTORY_ENABLED : false) {
            $keyList = array_keys($event->newData);
            $oldData = $event->oldData;
            $newData = $event->newData;
            foreach ($keyList as $key) {
                if ($oldData[$key] == $newData[$key]) {
                    unset($oldData[$key]);
                    unset($newData[$key]);
                }
            }

            if (!empty($oldData) && !empty($newData)) {
                $this->writeToHistory($event->userId, $event->modelId, $event->modelClass, HistoryEvent::UPDATE, ['__common' => $oldData], ['__common' => $newData]);
            }
        }
    }
}
