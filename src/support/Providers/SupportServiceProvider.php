<?php

namespace Omadonex\LaravelTools\Support\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Omadonex\LaravelTools\Support\Commands\Database\UnsafeSeeding;
use Omadonex\LaravelTools\Support\Commands\Module\Make;
use Omadonex\LaravelTools\Support\Commands\Module\MakeModel;
use Omadonex\LaravelTools\Support\Commands\Module\Remove;
use Omadonex\LaravelTools\Support\Commands\Module\RemoveModel;

class SupportServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $pathRoot = realpath(__DIR__.'/../..');

        $this->loadMigrationsFrom("{$pathRoot}/database/migrations");
        //$this->loadViewsFrom("{$pathRoot}/resources/views", 'support');
        $this->loadViewsFrom("{$pathRoot}/resources/views/modal", 'omx-modal');
        $this->loadTranslationsFrom("{$pathRoot}/resources/lang", 'support');

        $this->publishes([
            "{$pathRoot}/config/modules.php" => config_path('modules.php'),
        ], 'config');
        $this->publishes([
            "{$pathRoot}/resources/views" => resource_path('views/vendor/support'),
        ], 'views');
        $this->publishes([
            "{$pathRoot}/resources/lang" => resource_path('lang/vendor/support'),
        ], 'translations');

        if ($this->app->runningInConsole()) {
            $this->commands([
                Make::class,
                Remove::class,
                MakeModel::class,
                RemoveModel::class,
            ]);
        }

        $this->commands([
            UnsafeSeeding::class,
        ]);

        Validator::extend('time', 'Omadonex\LaravelTools\Support\Services\CustomValidator@timeValidate');
        Validator::extend('phone', 'Omadonex\LaravelTools\Support\Services\CustomValidator@phoneValidate');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
