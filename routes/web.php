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

Route::get('quickbooks_connect', 'QBOController@connect')
     ->name('quickbooks.connect');

Route::get('quickbooks_token', 'QBOController@token')
     ->name('quickbooks.token');

Route::get('quickbooks_success', 'QBOController@success')
     ->name('quickbooks.success');

