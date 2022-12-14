<?php

namespace Omadonex\LaravelTools\Acl\Providers;

use Illuminate\Support\ServiceProvider;
use Omadonex\LaravelTools\Acl\Commands\Generate;
use Omadonex\LaravelTools\Acl\Interfaces\IAclService;

class AclServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $pathRoot = realpath(__DIR__.'/../../..');

        $this->loadTranslationsFrom("{$pathRoot}/resources/lang/acl", 'omx-acl');
        $this->loadMigrationsFrom("{$pathRoot}/database/migrations/acl");

        $this->publishes([
            "{$pathRoot}/config/acl/acl.php" => config_path('omx/acl/acl.php'),
            "{$pathRoot}/config/acl/role.php" => config_path('omx/acl/role.php'),
            "{$pathRoot}/config/acl/permission.php" => config_path('omx/acl/permission.php'),
            "{$pathRoot}/config/acl/route.php" => config_path('omx/acl/route.php'),
        ], 'config');

        $this->publishes([
            "{$pathRoot}/resources/lang/acl" => lang_path('vendor/omx-acl'),
        ], 'translations');

        $this->commands([
            Generate::class,
        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->alias(IAclService::class, 'acl');
    }
}
