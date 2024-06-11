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

return [

    /*
    |--------------------------------------------------------------------------
    | Properties for the QuickBooks SDK DataService
    |--------------------------------------------------------------------------
    |
    | The configuration keys for the SDK are inconsistent in naming convention.
    | We are adhering to snake_case.  We make a sensible guess for 'base_url'
    | using the app's env, but you can can it with 'QUICKBOOKS_API_URL'.  Also,
    | the 'redirect_uri' is made in the client from the 'tenancy_quickbooks.token'
    | named route, so it cannot be configured here.
    |
    | Most of the time, only 'QUICKBOOKS_CLIENT_ID' & 'QUICKBOOKS_CLIENT_SECRET'
    | needs to be set.
    |
    | See: https://intuit.github.io/QuickBooks-V3-PHP-SDK/configuration.html
    |
    */

    'data_service' => [
        'auth_mode'     => 'oauth2',
        //'base_url'      => env('QUICKBOOKS_API_URL', config('app.env') === 'production' ? 'Production' : 'Development'),
        'base_url'      => env('QUICKBOOKS_API_URL', config('app.env') === 'production' ? 'Production' : 'Development'),
        'client_id'     => env('QUICKBOOKS_CLIENT_ID'),
        'client_secret' => env('QUICKBOOKS_CLIENT_SECRET'),
        'scope'         => 'com.intuit.quickbooks.accounting',
    ],

    /*
    |--------------------------------------------------------------------------
    | Properties to control logging
    |--------------------------------------------------------------------------
    |
    | Configures logging to <storage_path>/logs/quickbooks.log when in debug
    | mode or when 'QUICKBOOKS_DEBUG' is true.
    |
    */

    'logging' => [
        'enabled' => env('QUICKBOOKS_DEBUG', config('app.debug')),
        'location' => storage_path('logs'),
    ],
];
