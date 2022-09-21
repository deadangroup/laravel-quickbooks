<?php

/*
 * @copyright Deadan Group Limited
 * <code> Build something people want </code>
 */

namespace Deadan\TenancyQBO\Providers;

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
        $this->loadTranslationsFrom(__DIR__.'/../../resources/lang', 'tenancy_quickbooks');
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'tenancy_quickbooks');

        $this->loadMigrationsFrom(__DIR__.'/../../resources/database/migrations');

        $this->publishes([
            __DIR__.'/../../config/tenancy_quickbooks.php' => config_path('tenancy_quickbooks.php'),
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
        $config = __DIR__.'/../../config/tenancy_quickbooks.php';
        $this->mergeConfigFrom($config, 'tenancy_quickbooks');

        $this->app->register(RouteServiceProvider::class);
    }
}
