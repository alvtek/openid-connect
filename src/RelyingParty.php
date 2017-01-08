<?php

use Alvtek\OpenIdConnect\Provider;
use Alvtek\OpenIdConnect\Uri;
use Alvtek\OpenIdConnect\Uri\Query;

declare(strict_types=1);

namespace Alvtek\OpenIdConnect;

class RelyingParty
{
    /** 
     * @var string 
     */
    private $clientId;

    /** 
     * @var Uri 
     */
    private $clientUri;

    /** 
     * @var string 
     */
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
    private function __construct(Uri $clientUri, string $clientId, string $clientSecret)
    {
        if (!strlen($clientId)) {
            throw new InvalidArgumentException("clientId must be a non empty "
                . "string.");
        }

        $this->clientId = $clientId;
        $this->clientUri = $clientUri;
        $this->secret = $clientSecret;
    }
    
    public static function implicitClient(Uri $clientUri, string $clientId)
    {
        return new static($clientUri, $clientId, '');
    }
    
    public static function codeFlowClient(Uri $clientUri, string $clientId, 
        string $clientSecret)
    {
        return new static($clientUri, $clientId, $clientSecret);
    }

    /**
     * Get the login query string for this client
     *
     * @param array $scopes The requested scopes
     * @param string $responseType Expected response type from an auth request
     * @param string $nonce nonce value to be passed to the OP
     * 
     * @return Uri
     */
    public function getImplicitFlowAuthUri(Provider $provider, array $scopes, 
        string $responseType, string $nonce) : Uri
    {
        Assert::that($scopes)->all()->string();
        
        $queryValues = [
            'client_id' => $this->clientId,
            'redirect_uri' => (string) $this->clientUri,
            'response_mode' => 'form_post',
            'response_type' => $responseType,
            'scope' => implode(' ', $scopes),
            'nonce' => $nonce,
        ];
        
        $query = new Query($queryValues);
        return $provider->buildAuthUri($query);
    }

    /**
     * @return string
     */
    public function getBasicAuthString() : string
    {
        return "{$this->clientId}:{$this->secret}";
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
    
    /** 
     * @return string 
     */
    function clientId()
    {
        return $this->clientId;
    }

    /** 
     * @return Uri 
     */
    function uri()
    {
        return $this->url;
    }

    /** 
     * @return string 
     */
    function tenantName()
    {
        return $this->tenantName;
    }
}
