<?php

/*
 * @copyright Deadan Group Limited
 * <code> Build something people want </code>
 */

namespace TenancyQBO\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

/**
 * Class RouteServiceProvider.
 */
class RouteServiceProvider extends ServiceProvider
{
    /**
     * @var string
     */
    protected $webNamespace = 'TenancyQBO\Http\Controllers';

    /**
     * Define the routes for the module.
     *
     * @return void
     */
    public function map()
    {
        $this->mapCentralWebRoutes();
    }

    /**
     * Define the "web" routes for the module.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapCentralWebRoutes()
    {

        foreach ($this->centralDomains() as $domain) {
            //central web routes
            Route::middleware('web')
                 ->domain($domain)
                 ->prefix('integrations')
                 ->namespace($this->webNamespace)
                 ->group(__DIR__.'/../../routes/web.php');
        }
    }

    /**
     * @return array
     */
    protected function centralDomains(): array
    {
        return config('tenancy.central_domains', []);
    }
}
