<?php

namespace Omadonex\LaravelTools\Locale\Providers;

use Illuminate\Support\ServiceProvider;
use Omadonex\LaravelTools\Locale\Commands\Initialize;
use Omadonex\LaravelTools\Locale\Interfaces\ILocaleService;
use Omadonex\LaravelTools\Locale\Services\LocaleService;

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
            "{$pathRoot}/config/locale/localeCountryList.php" => config_path('omx/localeCountryList.php'),
            "{$pathRoot}/config/locale/localeCurrencyList.php" => config_path('omx/localeCurrencyList.php'),
            "{$pathRoot}/config/locale/localeLangList.php" => config_path('omx/localeLangList.php'),
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
        $pathRoot = realpath(__DIR__.'/../../..');
        $this->mergeConfigFrom("{$pathRoot}/config/locale/locale.php", 'omx.locale');
        $this->mergeConfigFrom("{$pathRoot}/config/locale/localeCountryList.php", 'omx.localeCountryList');
        $this->mergeConfigFrom("{$pathRoot}/config/locale/localeCurrencyList.php", 'omx.localeCurrencyList');
        $this->mergeConfigFrom("{$pathRoot}/config/locale/localeLangList.php", 'omx.localeLangList');
        
        $this->app->singleton(ILocaleService::class, function () {
            return new LocaleService;
        });
        $this->app->alias(ILocaleService::class, 'locale');
    }
}
