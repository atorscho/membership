<?php

namespace Atorscho\Membership;

use Atorscho\Membership\Setup\InstallUserMembershipSystem;
use Blade;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Slugify;

class MembershipServiceProvider extends ServiceProvider
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
            __DIR__ . '/../config/membership.php' => config_path('membership.php')
        ], 'config');
        $this->mergeConfigFrom(__DIR__ . '/../config/membership.php', 'membership');

        // Publish Migrations
        $this->publishes([
            __DIR__ . '/../database/migrations/' => base_path('/database/migrations')
        ], 'migrations');

        // Change foreign chars rules
        Slugify::addRules(config('membership.slugify'));
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // Install Command
        $this->app['command.membership.install'] = $this->app->share(function (Application $app) {
            return $app->make(InstallUserMembershipSystem::class);
        });
        $this->commands('command.membership.install');

        // Register Membership
        $this->app->singleton('membership', function (Application $app) {
            return $app->make(Membership::class);
        });

        // Facade
        $this->app->booting(function () {
            $loader = AliasLoader::getInstance();
            $loader->alias('Membership', MembershipFacade::class);
        });

        // Blade Directives
        $this->bladeExtensions();
    }

    /**
     * Register custom Blade directives.
     */
    protected function bladeExtensions()
    {
        // `current_user()` Blade Directive
        Blade::directive('user', function ($expression) {
            return "<?php echo current_user{$expression}; ?>";
        });

        // `current_user_is()` Blade Directive
        Blade::directive('is', function ($expression) {
            return "<?php if (current_user_is{$expression}): ?>";
        });
        Blade::directive('endis', function () {
            return '<?php endif; ?>';
        });
        Blade::directive('isnot', function ($expression) {
            return "<?php if (!current_user_is{$expression}): ?>";
        });
        Blade::directive('endisnot', function () {
            return '<?php endif; ?>';
        });

        // `is_logged_in()` Blade Directive
        Blade::directive('check', function () {
            return '<?php if (is_logged_in()): ?>';
        });
        Blade::directive('endcheck', function () {
            return '<?php endif; ?>';
        });
        Blade::directive('guest', function () {
            return '<?php if (!is_logged_in()): ?>';
        });
        Blade::directive('endguest', function () {
            return '<?php endif; ?>';
        });
    }
}
