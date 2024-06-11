<?php

/*
 *
 *  This is file is part of DGL's tech stack.
 *
 *  @copyright (c) 2024, Deadan Group Limited (DGL).
 *  @link https://www.dgl.co.ke/apps
 *  All rights reserved.
 *
 *  <code>Build something people want!</code>
 */

namespace DGL\QBO\Providers;

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
            __DIR__ . '/../../config/laravel_quickbooks.php' => config_path('tenancy_quickbooks.php'),
        ], 'quickbooks-config');

        $this->publishes([
            __DIR__.'/../../resources/database/migrations' => database_path('migrations'),
        ], 'quickbooks-migrations');

        $this->publishes([
            __DIR__.'/../../resources/views' => base_path('resources/views/vendor/tenancy_quickbooks'),
        ], 'quickbooks-views');
    }

    /**
     * Register the module services.
     *
     * @return void
     */
    public function register()
    {
        $config = __DIR__ . '/../../config/laravel_quickbooks.php';
        $this->mergeConfigFrom($config, 'tenancy_quickbooks');

        $this->app->register(RouteServiceProvider::class);
    }
}
