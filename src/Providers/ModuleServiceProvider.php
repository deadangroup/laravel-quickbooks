<?php

/*
 * @copyright Deadan Group Limited
 * <code> Build something people want </code>
 */

namespace TenancyQBO\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class ModuleServiceProvider.
 */
class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the module services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__.'/../../resources/lang', 'quickbooks');
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'quickbooks');

        $this->loadMigrationsFrom(__DIR__.'/../../resources/database/migrations');

        $this->publishes([
            __DIR__.'/../../config/quickbooks.php' => config_path('quickbooks.php'),
        ], 'quickbooks-config');

        $this->publishes([
            __DIR__.'/../../resources/database/migrations' => database_path('migrations'),
        ], 'quickbooks-migrations');

        $this->publishes([
            __DIR__.'/../../resources/views' => base_path('resources/views/vendor/quickbooks'),
        ], 'quickbooks-views');
    }

    /**
     * Register the module services.
     *
     * @return void
     */
    public function register()
    {
        $config = __DIR__.'/../../config/quickbooks.php';
        $this->mergeConfigFrom($config, 'quickbooks');

        $this->app->register(RouteServiceProvider::class);
    }
}
