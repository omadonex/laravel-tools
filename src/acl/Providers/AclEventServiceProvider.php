<?php

namespace Omadonex\LaravelTools\Acl\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Omadonex\LaravelTools\Acl\Events\UserRoleAttached;
use Omadonex\LaravelTools\Acl\Events\UserRoleDetached;
use Omadonex\LaravelTools\Acl\Listeners\HistoryUserRoleAttachedListener;
use Omadonex\LaravelTools\Acl\Listeners\HistoryUserRoleDetachedListener;

class AclEventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        UserRoleAttached::class => [
            HistoryUserRoleAttachedListener::class,
        ],
        UserRoleDetached::class => [
            HistoryUserRoleDetachedListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
