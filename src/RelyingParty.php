<?php

namespace Alvtek\OpenIdConnect;

use Alvtek\OpenIdConnect\Uri;

use Assert\Assert;

class RelyingParty
{
    /** @var string */
    private $id;

    /** @var Uri */
    private $clientUri;

    /** @var string */
    private $tenantName;

    /** @var string */
    private $secret;

    /**
     * Construct a new client entity. The client entity represents an OpenID
     * Connect RP. The client is responsible for redirecting
     * to the correct login URL and obtain refresh tokens from the OP.
     * 
     * @param string $clientId
     * @param Uri $clientUri
     * @param string $tenantName
     * @param string $clientSecret
     */
    public function __construct(Uri $clientUri, $clientId, $tenantName, $clientSecret)
    {
        Assert::that($clientId)->scalar()->notEmpty();
        Assert::that($tenantName)->string()->notEmpty();
        Assert::that($clientSecret)->string()->notEmpty();

        $this->id = $clientId;
        $this->clientUri = $clientUri;
        $this->tenantName = $tenantName;
        $this->secret = $clientSecret;
    }

    /**
     * Get the login query string for this client
     *
     * @param Uri $siteUri URL of the client website
     * @param string $responseType Expected response type from an auth request
     * @param string $nonce nonce value to be passed to the OP
     * @return string
     */
    public function getLoginQuery(Uri $siteUri, $scopes, $responseType, $nonce)
    {
        Assert::that($scopes)->isArray()->all()->string();
        Assert::that($responseType)->notEmpty()->string();
        Assert::that($nonce)->notEmpty()->string();
        
        $query = [
            'client_id' => $this->id,
            'redirect_uri' => (string) $siteUri,
            'response_mode' => 'form_post',
            'response_type' => $responseType,
            'scope' => implode(' ', $scopes),
            'nonce' => $nonce,
            'acr_values' => "tenant:{$this->tenantName}",
        ];

        return http_build_query($query);
    }

    public function getBasicAuthString()
    {
        return "{$this->id}:{$this->secret}";
    }

    public function authorizationCodeParams($code, $redirectUri)
    {
        Assert::that($code)->notEmpty()->string();
        Assert::that($redirectUri)->notEmpty()->string();
        
        return [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $redirectUri,
        ];
    }

    public function refreshTokenParams($refreshToken)
    {
        Assert::that($refreshToken)->notEmpty()->string();

        return [
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
        ];
    }
    
    /** @return string */
    function id()
    {
        return $this->id;
    }

    /** @return Uri */
    function uri()
    {
        return $this->url;
    }

    /** @return string */
    function tenantName()
    {
        return $this->tenantName;
    }
}
