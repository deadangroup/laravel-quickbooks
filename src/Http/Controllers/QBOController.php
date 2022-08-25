<?php

namespace TenancyQBO\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as LaravelController;
use TenancyQBO\Models\QBOToken;
use TenancyQBO\QBOClient;

class QBOController extends LaravelController
{
    /**
     * Form to connect/disconnect user to QuickBooks
     *
     * If the user has a valid OAuth, then give form to disconnect, otherwise link to connect it
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\View\View
     * @throws \QuickBooksOnline\API\Exception\SdkException
     * @throws \QuickBooksOnline\API\Exception\ServiceException
     */
    public function connect(Request $request)
    {
        $token_id = $request->get('token_id');

        $token = QBOToken::findOrFail($token_id);
        $tenant = $token->tenant;

        $quickbooks = new QBOClient(config('quickbooks'), $token);

        // Give view to remove token if user already linked account
        if ($quickbooks->hasValidRefreshToken()) {
            return view()
                ->make('quickbooks::disconnect')
                ->with('company', $quickbooks->getDataService()
                                             ->getCompanyInfo());
        }

        session()->put('token_id', $token_id);

        // Give view to link account
        return view()
            ->make('quickbooks::connect')
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
        $config = config('quickbooks');
        $quickbooks = new QBOClient($config, $token);

        // TODO: Deal with exceptions
        $quickbooks->exchangeCodeForToken($request->get('code'), $request->get('realmId'));

        $request->session()
                ->flash('success', 'Connected to QuickBooks');

        // TODO: Deal with exceptions

        // Give view to disconnect account
        return redirect()->route('quickbooks.success', ['token_id' => $token_id]);
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

        $config = config('quickbooks');
        $quickbooks = new QBOClient($config, $token);
        $company = $quickbooks->getDataService()
                              ->getCompanyInfo();

        // Give view to disconnect account
        return view()
            ->make('quickbooks::success')
            ->with('company', $company)
            ->with('tenant', $tenant);
    }
}