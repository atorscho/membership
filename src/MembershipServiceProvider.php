<?php

namespace Atorscho\Membership;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

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
            __DIR__.'/../config/membership.php' => config_path('membership.php'),
        ]);

        // Migrations
        $this->loadMigrationsFrom(__DIR__ . '/../migrations');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // Merge Configuration
        $this->mergeConfigFrom(
            __DIR__.'/../config/membership.php', 'membership'
        );

        // Register the Façade
        $this->app->bind('membership', function ($app) {
            return $app->make(Membership::class);
        });
        $this->app->booting(function () {
            $loader = AliasLoader::getInstance();
            $loader->alias('Membership', MembershipFacade::class);
        });
    }
}
