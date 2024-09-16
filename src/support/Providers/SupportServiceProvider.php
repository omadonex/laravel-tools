<?php

namespace Omadonex\LaravelTools\Support\Providers;

use Illuminate\Support\ServiceProvider;
use Omadonex\LaravelTools\Support\Commands\HistoryGenerate;

class SupportServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $pathRoot = realpath(__DIR__.'/../../..');

        $this->loadRoutesFrom("{$pathRoot}/src/support/Routes/web.php");
        $this->loadTranslationsFrom("{$pathRoot}/resources/lang/support", 'omx-support');
        $this->loadMigrationsFrom("{$pathRoot}/database/migrations/support");
        $this->loadViewsFrom("{$pathRoot}/resources/views/form", 'omx-form');
        $this->loadViewsFrom("{$pathRoot}/resources/views/modal", 'omx-modal');
        $this->loadViewsFrom("{$pathRoot}/resources/views/icon", 'omx-icon');

        $this->publishes([
            "{$pathRoot}/resources/assets/sass" => resource_path('assets/sass/vendor/omx'),
        ]);

//        $this->publishes([
//            "{$pathRoot}/config/modules.php" => config_path('modules.php'),
//        ], 'config');
//        $this->publishes([
//            "{$pathRoot}/resources/views" => resource_path('views/vendor/support'),
//        ], 'views');
//        $this->publishes([
//            "{$pathRoot}/resources/lang" => resource_path('lang/vendor/support'),
//        ], 'translations');
//
//        if ($this->app->runningInConsole()) {
//            $this->commands([
//                Make::class,
//                Remove::class,
//                MakeModel::class,
//                RemoveModel::class,
//            ]);
//        }
//
//        $this->commands([
//            UnsafeSeeding::class,
//        ]);
//
//        Validator::extend('time', 'Omadonex\LaravelTools\Support\Services\CustomValidator@timeValidate');
//        Validator::extend('phone', 'Omadonex\LaravelTools\Support\Services\CustomValidator@phoneValidate');

        $this->publishes([
            "{$pathRoot}/resources/lang/support" => lang_path('vendor/omx-support'),
        ], 'translations');

        $this->commands([
            HistoryGenerate::class,
        ]);
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
