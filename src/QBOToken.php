<?php

namespace Deadan\TenancyQBO;

use Carbon\Carbon;
use Deadan\TenancyAccount\Models\Tenant;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2AccessToken;
use QuickBooksOnline\API\Data\IPPCompanyInfo;
use QuickBooksOnline\API\Exception\SdkException;
use Stancl\Tenancy\Database\Concerns\CentralConnection;

/**
 * Class QBOToken
 *
 * @package Deadan\TenancyQBO
 *
 * @property boolean $hasValidAccessToken Is the access token valid
 * @property boolean $hasValidRefreshToken Is the refresh token valid
 * @property Carbon $access_token_expires_at Timestamp that the access token expires
 * @property Carbon $refresh_token_expires_at Timestamp that the refresh token expires
 * @property integer $tenant_id Id of the related tenant
 * @property string $access_token The access token
 * @property string $realm_id Realm Id from the OAuth token
 * @property string $refresh_token The refresh token
 */
class QBOToken extends Model
{
    use CentralConnection;
    use SoftDeletes;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'quickbooks_tokens';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'access_token_expires_at',
        'refresh_token_expires_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'auth_code',
        'access_token',
        'access_token_expires_at',
        'realm_id',
        'refresh_token',
        'refresh_token_expires_at',
        'tenant_id',
        'qbo_company_id',
        'qbo_company_name',
        'qbo_company_address',
        'qbo_company_email',
    ];

    /**
     * Remove the token
     *
     * When a token is deleted, we still need a token for the client for the owner.
     *
     * @return QBOToken
     * @throws Exception
     */
    public static function init($tenantId)
    {
        $id = substr(md5($tenantId.time()), 0, 12);

        return QBOToken::create([
            'id'        => $id,
            'tenant_id' => $tenantId,
        ]);
    }

    /**
     * @param $tenantId
     *
     * @return mixed
     */
    public static function latestFor($tenantId)
    {
        return QBOToken::where('tenant_id', $tenantId)
                       ->latest()
                       ->first();
    }

    /**
     * @param $tenantId
     *
     * @return mixed
     */
    public static function invalidateAllFor($tenantId)
    {
        return QBOToken::where('tenant_id', $tenantId)
                       ->delete();
    }

    /**
     * Check if access token is valid
     *
     * A token is good for 1 hour, so if it's expires greater than 1 hour from now, it is still valid
     *
     * @return bool
     */
    public function getHasValidAccessTokenAttribute()
    {
        return $this->access_token_expires_at && Carbon::now()
                                                       ->lt($this->access_token_expires_at);
    }

    /**
     * Check if refresh token is valid
     *
     * A token is good for 101 days, so if it's expires greater than 101 days from now, it is still valid
     *
     * @return bool
     */
    public function getHasValidRefreshTokenAttribute()
    {
        return $this->refresh_token_expires_at && Carbon::now()
                                                        ->lt($this->refresh_token_expires_at);
    }

    /**
     * Parse OauthToken.
     *
     * Process the OAuth token & store it in the persistent storage
     *
     * @param  OAuth2AccessToken  $oauth_token
     *
     * @return QBOToken
     * @throws SdkException
     */
    public function parseOauthToken(OAuth2AccessToken $oauth_token)
    {
        // TODO: Deal with exception
        $this->access_token = $oauth_token->getAccessToken();
        $this->access_token_expires_at = Carbon::parse($oauth_token->getAccessTokenExpiresAt());
        $this->realm_id = $oauth_token->getRealmID();
        $this->refresh_token = $oauth_token->getRefreshToken();
        $this->refresh_token_expires_at = Carbon::parse($oauth_token->getRefreshTokenExpiresAt());

        return $this;
    }

    /**
     * Parse Company details.
     *
     * Process the Company info & store it in the persistent storage
     *
     * @param  \QuickBooksOnline\API\Data\IPPCompanyInfo  $companyInfo
     *
     * @return QBOToken
     */
    public function parseCompanyDetails(IPPCompanyInfo $companyInfo)
    {
        $this->qbo_company_id = $companyInfo->Id;
        $this->qbo_company_name = $companyInfo->CompanyName;

        $address = join(",", (array) $companyInfo->CompanyAddr);
        $address = str_replace(",,", ",", $address);

        $this->qbo_company_address = $address;

        $email = join(",", (array) $companyInfo->CompanyEmailAddr);
        $email = str_replace(",,", ",", $email);
        $this->qbo_company_email = $email;

        return $this;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }
}
