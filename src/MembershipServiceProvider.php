<?php

namespace Atorscho\Membership;

use Blade;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Foundation\Application;
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
        // Publish Config
        $this->publishes([
            __DIR__ . '/../config/membership.php' => config_path('membership.php')
        ], 'config');
        $this->mergeConfigFrom(__DIR__ . '/../config/membership.php', 'membership');

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
        //$this->app['command.membership.install'] = $this->app->share(function (Application $app) {
        //    return $app->make(InstallUservel::class);
        //});
        //$this->commands('command.membership.install');

        // Facade
        //$this->app->bind('membership', function (Application $app) {
        //    return $app->make(Uservel::class);
        //});

        // Alias
        //$this->app->booting(function () {
        //    $loader = AliasLoader::getInstance();
        //    $loader->alias('Uservel', UservelFacade::class);
        //});

        // Blade Directives
        $this->bladeExtensions();
    }

    /**
     * Register custom Blade directives.
     */
    protected function bladeExtensions()
    {
        // `current_user()` Blade Directive
        Blade::directive('current', function ($attribute = null) {
            return "<?php if (current_user({$attribute})): ?>";
        });
        Blade::directive('endcurrent', function () {
            return '<?php endif; ?>';
        });

        // `current_user_is()` Blade Directive
        Blade::directive('is', function ($group) {
            return "<?php if (current_user_is({$group})): ?>";
        });
        Blade::directive('endis', function () {
            return '<?php endif; ?>';
        });

        // `is_logged_in()` Blade Directive
        Blade::directive('logged', function () {
            return '<?php if (is_logged_in()): ?>';
        });
        Blade::directive('endlogged', function () {
            return '<?php endif; ?>';
        });

        // `avatar_exists()` Blade Directive
        Blade::directive('avatar', function ($avatar = null) {
            return "<?php if (avatar_exists($avatar)): ?>";
        });
        Blade::directive('endavatar', function () {
            return '<?php endif; ?>';
        });
    }
}
