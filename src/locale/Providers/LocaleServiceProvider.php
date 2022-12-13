<?php

namespace Omadonex\LaravelTools\Locale\Providers;

use Illuminate\Support\ServiceProvider;
use Omadonex\LaravelTools\Locale\Commands\Initialize;

class LocaleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $pathRoot = realpath(__DIR__.'/../../..');

        $this->loadTranslationsFrom(lang_path('vendor/omx-locale'), 'omx-locale');

        $this->publishes([
            "{$pathRoot}/config/locale/locale.php" => config_path('omx/locale.php'),
        ], 'config');

        if ($this->app->runningInConsole()) {
            $this->commands([
                Initialize::class,
            ]);
        }
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
