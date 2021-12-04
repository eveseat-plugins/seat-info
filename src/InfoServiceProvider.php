<?php

namespace RecursiveTree\Seat\InfoPlugin;

use Seat\Services\AbstractSeatPlugin;
use Illuminate\Support\Facades\Blade;


class InfoServiceProvider extends AbstractSeatPlugin
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(){

        $this->publishes([
            __DIR__ . '/resources/js' => public_path('info/js')
        ]);

        if (! $this->app->routesAreCached()) {
            include __DIR__ . '/Http/routes.php';
        }
        $this->loadTranslationsFrom(__DIR__ . '/lang', 'info');
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'info');
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations/');

        $version = $this->getVersion();

        Blade::directive('versionedAsset', function($path) use ($version) {
            return "<?php echo asset({$path}) . '?v=$version'; ?>";
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register(){
        $this->mergeConfigFrom(__DIR__ . '/Config/info.sidebar.php','package.sidebar');
        $this->registerPermissions(__DIR__ . '/Config/Permissions/info.permissions.php', 'info');
    }


    /**
     * Return the plugin public name as it should be displayed into settings.
     *
     * @example SeAT Web
     *
     * @return string
     */
    public function getName(): string
    {
        return 'SeAT Info';
    }


    /**
     * Return the plugin repository address.
     *
     * @example https://github.com/eveseat/web
     *
     * @return string
     */
    public function getPackageRepositoryUrl(): string
    {
        return 'https://github.com/recursivetree/seat-info';
    }

    /**
     * Return the plugin technical name as published on package manager.
     *
     * @example web
     *
     * @return string
     */
    public function getPackagistPackageName(): string
    {
        return 'seat-info';
    }

    /**
     * Return the plugin vendor tag as published on package manager.
     *
     * @example eveseat
     *
     * @return string
     */
    public function getPackagistVendorName(): string
    {
        return 'recursivetree';
    }
}