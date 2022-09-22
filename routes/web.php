<?php

/*
 * @copyright Deadan Group Limited
 * <code> Build something people want </code>
 */

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your module. Just tell Laravel the URIs it should respond
| to using a Closure or QBOController method. Build something great!
|
 */

Route::get('quickbooks_preconnect', 'QuickbooksSyncController@initiateConnection')
     ->name('tenancy_quickbooks.pre_connect');

Route::get('quickbooks_connect', 'QBOController@connect')
     ->name('tenancy_quickbooks.connect');

Route::get('quickbooks_token', 'QBOController@token')
     ->name('tenancy_quickbooks.token');

Route::get('quickbooks_success', 'QBOController@success')
     ->name('tenancy_quickbooks.success');

