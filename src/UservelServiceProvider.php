<?php

namespace Atorscho\Uservel;

use Atorscho\Uservel\Console\InstallUservel;
use Atorscho\Uservel\Uservel;
use Atorscho\Uservel\UservelFacade;
use Blade;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class UservelServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        require __DIR__ . '/../helpers/uservel.php';

        // Publish Config
        $this->publishes([
            __DIR__ . '/../config/uservel.php' => config_path('uservel.php')
        ], 'config');
        $this->mergeConfigFrom(__DIR__ . '/../config/uservel.php', 'uservel');

        // Publish Migrations
        $this->publishes([
            __DIR__ . '/../database/migrations/' => base_path('/database/migrations')
        ], 'migrations');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // Uservel Install Command
        $this->app['command.uservel.install'] = $this->app->share(function (Application $app) {
            return $app->make(InstallUservel::class);
        });
        $this->commands('command.uservel.install');

        // Facade
        $this->app->bind('uservel', function (Application $app) {
            return $app->make(Uservel::class);
        });

        // Alias
        $this->app->booting(function () {
            $loader = AliasLoader::getInstance();
            $loader->alias('Uservel', UservelFacade::class);
        });
    }
}
