<?php

namespace Atorscho\Membership;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

/**
 * Class MembershipServiceProvider
 *
 * @package Atorscho\Membership
 * @author  Alex Torscho <contact@alextorscho.com>
 * @version 2.0.0
 */
class MembershipServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Configuration
        $this->publishes([
            __DIR__ . '/../config/membership.php' => config_path('membership.php'),
        ], 'config');

        // Migrations
        $this->loadMigrationsFrom(__DIR__ . '/../migrations');
        $this->publishes([
            __DIR__ . '/../migrations/' => database_path('migrations')
        ], 'migrations');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // Merge Configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/membership.php', 'membership');

        // Register the Facade
        $this->app->bind('membership', function ($app) {
            return $app->make(Membership::class);
        });
        $this->app->booting(function () {
            $loader = AliasLoader::getInstance();
            $loader->alias('Membership', MembershipFacade::class);
        });
    }
}
