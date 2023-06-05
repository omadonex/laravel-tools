<?php

namespace Omadonex\LaravelTools\Acl\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Omadonex\LaravelTools\Acl\Events\UserRoleAttached;
use Omadonex\LaravelTools\Acl\Models\User;
use Omadonex\LaravelTools\Support\Models\HistoryEvent;
use Omadonex\LaravelTools\Support\Traits\HistoryServiceTrait;

class HistoryUserRoleAttachedListener
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
    public function handle(UserRoleAttached $event): void
    {
        if ($event->user->historyEnabled ?? false) {
            $this->writeToHistory($event->userId, $event->modelId, User::class, HistoryEvent::UPDATE, [], ['__common' => ['role_id' => $event->roleId]]);
        }
    }
}
