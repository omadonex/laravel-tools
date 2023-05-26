<?php

namespace Omadonex\LaravelTools\Support\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Omadonex\LaravelTools\Support\Events\ModelCreated;
use Omadonex\LaravelTools\Support\Events\ModelCreatedT;
use Omadonex\LaravelTools\Support\Events\ModelDeleted;
use Omadonex\LaravelTools\Support\Events\ModelDeletedT;
use Omadonex\LaravelTools\Support\Events\ModelUpdated;
use Omadonex\LaravelTools\Support\Events\ModelUpdatedT;
use Omadonex\LaravelTools\Support\Listeners\HistoryModelCreatedListener;
use Omadonex\LaravelTools\Support\Listeners\HistoryModelCreatedTListener;
use Omadonex\LaravelTools\Support\Listeners\HistoryModelDeletedListener;
use Omadonex\LaravelTools\Support\Listeners\HistoryModelDeletedTListener;
use Omadonex\LaravelTools\Support\Listeners\HistoryModelUpdatedListener;
use Omadonex\LaravelTools\Support\Listeners\HistoryModelUpdatedTListener;

class SupportEventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        ModelCreated::class => [
            HistoryModelCreatedListener::class,
        ],
        ModelCreatedT::class => [
            HistoryModelCreatedTListener::class,
        ],
        ModelUpdated::class => [
            HistoryModelUpdatedListener::class,
        ],
        ModelUpdatedT::class => [
            HistoryModelUpdatedTListener::class,
        ],
        ModelDeleted::class => [
            HistoryModelDeletedListener::class,
        ],
        ModelDeletedT::class => [
            HistoryModelDeletedTListener::class,
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
