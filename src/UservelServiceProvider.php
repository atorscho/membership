<?php

namespace Atorscho\Uservel;

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
        // Publish Config
        $this->publishes([
            __DIR__ . '/../config/uservel.php' => config_path('uservel.php')
        ], 'config');
        $this->mergeConfigFrom(__DIR__ . '/../config/uservel.php', 'uservel');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
