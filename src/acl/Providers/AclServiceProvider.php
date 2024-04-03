<?php

namespace Omadonex\LaravelTools\Acl\Providers;

use Illuminate\Support\ServiceProvider;
use Omadonex\LaravelTools\Acl\Commands\Generate;
use Omadonex\LaravelTools\Acl\Interfaces\IAclService;
use Omadonex\LaravelTools\Acl\Services\AclService;

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

        $this->loadRoutesFrom("{$pathRoot}/src/acl/Routes/web.php");
        $this->loadTranslationsFrom("{$pathRoot}/resources/lang/acl", 'omx-acl');
        $this->loadMigrationsFrom("{$pathRoot}/database/migrations/acl");
        $this->loadViewsFrom("{$pathRoot}/resources/views/acl", 'omx-acl');
        $this->loadViewsFrom("{$pathRoot}/resources/views/common", 'omx-common');

        $this->publishes([
            "{$pathRoot}/config/acl/acl.php" => config_path('omx/acl/acl.php'),
            "{$pathRoot}/config/acl/auth.php" => config_path('omx/acl/auth.php'),
            "{$pathRoot}/config/acl/role.php" => config_path('omx/acl/role.php'),
            "{$pathRoot}/config/acl/permission.php" => config_path('omx/acl/permission.php'),
            "{$pathRoot}/config/acl/route.php" => config_path('omx/acl/route.php'),
            "{$pathRoot}/src/acl/Routes/auth.php" => base_path('routes/omx/auth.php'),
        ], 'config');

        $this->publishes([
            "{$pathRoot}/resources/lang/acl" => lang_path('vendor/omx-acl'),
            "{$pathRoot}/resources/views/acl" => resource_path('views/vendor/omx-acl'),
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
        $pathRoot = realpath(__DIR__.'/../../..');
        $this->mergeConfigFrom("{$pathRoot}/config/acl/auth.php", 'omx.acl.auth');

        $this->app->singleton(IAclService::class, function () {
            return new AclService;
        });

        $this->app->alias(IAclService::class, 'acl');
    }
}
