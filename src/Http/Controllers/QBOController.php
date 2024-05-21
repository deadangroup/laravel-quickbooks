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

namespace DGL\QBO\Http\Controllers;

use DGL\QBO\QBOClient;
use DGL\QBO\QBOToken;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as LaravelController;

/**
 *
 */
class QBOController extends LaravelController
{
    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function initiateConnection()
    {
        $tenantId = request('tenant_id');

        //invalidate all previous tokens
        QBOToken::invalidateAllFor($tenantId);

        //invalidate all previous tokens
        $token = QBOToken::init($tenantId);

        track('QuickBooksConnectionInit');

        return redirect()->route('tenancy_quickbooks.connect', ['token_id' => $token->id]);
    }

    /**
     * Form to connect/disconnect user to QuickBooks
     *
     * If the user has a valid OAuth, then give form to disconnect, otherwise link to connect it
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\View\View
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \QuickBooksOnline\API\Exception\SdkException
     * @throws \QuickBooksOnline\API\Exception\ServiceException
     */
    public function connect(Request $request)
    {
        $token_id = $request->get('token_id');

        $token = QBOToken::findOrFail($token_id);
        $tenant = $token->tenant;

        $quickbooks = new QBOClient(config('tenancy_quickbooks'), $token);

        // Give view to remove token if user already linked account
        if ($quickbooks->hasValidRefreshToken()) {
            return view()
                ->make('tenancy_quickbooks::disconnect')
                ->with('company', $quickbooks->getDataService()
                                             ->getCompanyInfo());
        }

        session()->put('token_id', $token_id);

        // Give view to link account
        return view()
            ->make('tenancy_quickbooks::connect')
            ->with('authorization_uri', $quickbooks->authorizationUri())
            ->with('tenant', $tenant);
    }

    /**
     * Accept the code from QuickBooks to request token
     *
     * Once a user approves linking account, then QuickBooks sends back
     * a code which can be converted to an OAuth token.
     *
     * @param  Request  $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \QuickBooksOnline\API\Exception\SdkException
     * @throws \QuickBooksOnline\API\Exception\ServiceException
     */
    public function token(Request $request)
    {
        $token_id = session('token_id');

        $token = QBOToken::findOrFail($token_id);
        $config = config('tenancy_quickbooks');
        $quickbooks = new QBOClient($config, $token);

        // TODO: Deal with exceptions
        $quickbooks->exchangeCodeForToken($request->get('code'), $request->get('realmId'));

        $request->session()
                ->flash('success', 'Connected to QuickBooks');

        // TODO: Deal with exceptions

        // Give view to disconnect account
        return redirect()->route('tenancy_quickbooks.success', ['token_id' => $token_id]);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Contracts\View\View|mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \QuickBooksOnline\API\Exception\SdkException
     * @throws \QuickBooksOnline\API\Exception\ServiceException
     */
    public function success(Request $request)
    {
        $token_id = $request->get('token_id');

        $token = QBOToken::findOrFail($token_id);
        $tenant = $token->tenant;

        $config = config('tenancy_quickbooks');
        $quickbooks = new QBOClient($config, $token);
        $company = $quickbooks->getDataService()
                              ->getCompanyInfo();

        // Give view to disconnect account
        return view()
            ->make('tenancy_quickbooks::success')
            ->with('company', $company)
            ->with('tenant', $tenant);
    }
}
