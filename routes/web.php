<?php

/*
 *
 *  This is file is part of DGL's tech stack.
 *
 *  @copyright (c) 2024, Deadan Group Limited (DGL).
 *  @link https://www.dgl.co.ke/products
 *  All rights reserved.
 *
 *  <code>Build something people want!</code>
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

Route::get('quickbooks_preconnect', 'QBOController@initiateConnection')
     ->name('tenancy_quickbooks.pre_connect');

Route::get('quickbooks_connect', 'QBOController@connect')
     ->name('tenancy_quickbooks.connect');

Route::get('quickbooks_token', 'QBOController@token')
     ->name('tenancy_quickbooks.token');

Route::get('quickbooks_success', 'QBOController@success')
     ->name('tenancy_quickbooks.success');

