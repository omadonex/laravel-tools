<?php

namespace Omadonex\LaravelTools\Acl\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Omadonex\LaravelTools\Acl\Events\UserRoleDetached;
use Omadonex\LaravelTools\Support\Models\HistoryEvent;
use Omadonex\LaravelTools\Support\Traits\HistoryServiceTrait;

class HistoryUserRoleDetachedListener
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
    public function handle(UserRoleDetached $event): void
    {
        if ($event->model->historyEnabled ?? false) {
            $this->writeToHistory($event->userId, $event->user->getKey(), get_class($event->user), HistoryEvent::UPDATE, ['__common' => ['role_id' => $event->roleId]], []);
        }
    }
}
