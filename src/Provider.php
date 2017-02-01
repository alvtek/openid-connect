<?php

declare(strict_types=1);

namespace Alvtek\OpenIdConnect;

use Alvtek\OpenIdConnect\Endpoint\EndpointCollection;
use Alvtek\OpenIdConnect\Provider\Exception\InvalidProviderException;
use Alvtek\OpenIdConnect\Provider\Flag;
use Alvtek\OpenIdConnect\Provider\Flag\FlagCollection;
use Alvtek\OpenIdConnect\Provider\Option;
use Alvtek\OpenIdConnect\Provider\Option\OptionCollection;
use Alvtek\OpenIdConnect\Uri\Query;


/**
 * This class contains the settings for an OpenId Provider.
 */
class Provider
{
    // Endpoints
    const ISSUER = 'issuer';
    const AUTHORIZATION_ENDPOINT = 'authorization_endpoint';
    const TOKEN_ENDPOINT = 'token_endpoint';
    const USERINFO_ENDPOINT = 'userinfo_endpoint';
    const JWKS_URI = 'jwks_uri';
    const REGISTRATION_ENDPOINT = 'registration_endpoint';
    const END_SESSION_ENDPOINT = 'end_session_endpoint';
    const CHECK_SESSION_IFRAME = 'check_session_iframe';
    const REVOCATION_ENDPOINT = 'revocation_endpoint';
    const INTROSPECTION_ENDPOINT = 'introspection_endpoint';

    // Support Options
    const SCOPES_SUPPORTED = 'scopes_supported';
    const RESPONSE_TYPES_SUPPORTED = 'response_types_supported';
    const RESPONSE_MODES_SUPPORTED = 'response_modes_supported';
    const GRANT_TYPES_SUPPORTED = 'grant_types_supported';
    const SUBJECT_TYPES_SUPPORTED = 'subject_types_supported';
    const ID_TOKEN_SIGNING_ALG_VALUES_SUPPORTED = 'id_token_signing_alg_values_supported';
    const CLAIMS_SUPPORTED = 'claims_supported';
    const CODE_CHALLENGE_METHODS_SUPPORTED = 'code_challenge_methods_supported';
    const TOKEN_ENDPOINT_AUTH_METHODS_SUPPORTED = 'token_endpoint_auth_methods_supported';

    // Support Flags
    const FRONTCHANNEL_LOGOUT_SUPPORTED = 'frontchannel_logout_supported';
    const FRONTCHANNEL_LOGOUT_SESSION_SUPPORTED = 'frontchannel_logout_session_supported';
    
    /** 
     * @var array 
     */
    private $requiredEndpoints = [
        self::ISSUER,
        self::AUTHORIZATION_ENDPOINT,
        self::JWKS_URI,
    ];
    
    /** 
     * @var array 
     */
    private $requiredOptions = [
        self::RESPONSE_TYPES_SUPPORTED,
        self::SUBJECT_TYPES_SUPPORTED,
        self::ID_TOKEN_SIGNING_ALG_VALUES_SUPPORTED,
    ];
    
    /** 
     * @var array 
     */
    private static $knownEndpoints = [
        Provider::ISSUER, Provider::AUTHORIZATION_ENDPOINT, 
        Provider::TOKEN_ENDPOINT, Provider::USERINFO_ENDPOINT,
        Provider::JWKS_URI, Provider::REGISTRATION_ENDPOINT,
        Provider::END_SESSION_ENDPOINT, Provider::CHECK_SESSION_IFRAME,
        Provider::REVOCATION_ENDPOINT, Provider::INTROSPECTION_ENDPOINT,
    ];
    
    /** 
     * @var array 
     */
    private static $knownOptions = [
        Provider::SCOPES_SUPPORTED, Provider::RESPONSE_TYPES_SUPPORTED,
        Provider::RESPONSE_MODES_SUPPORTED, Provider::GRANT_TYPES_SUPPORTED,
        Provider::SUBJECT_TYPES_SUPPORTED, Provider::ID_TOKEN_SIGNING_ALG_VALUES_SUPPORTED,
        Provider::CLAIMS_SUPPORTED, Provider::CODE_CHALLENGE_METHODS_SUPPORTED,
        Provider::TOKEN_ENDPOINT_AUTH_METHODS_SUPPORTED,
    ];
    
    /** 
     * @var array 
     */
    private static $knownFlags = [
        Provider::FRONTCHANNEL_LOGOUT_SUPPORTED, 
        Provider::FRONTCHANNEL_LOGOUT_SESSION_SUPPORTED,
    ];
    
    /** 
     * @var EndpointCollection 
     */
    private $endpoints;
    
    /** 
     * @var OptionCollection 
     */
    private $options;

    /** 
     * @var FlagCollection 
     */
    private $flags;
    
    /**
     * @param EndpointCollection $endpoints
     * @param OptionCollection $options
     * @param FlagCollection $flags
     */
    public function __construct(
        EndpointCollection $endpoints, 
        OptionCollection $options, 
        FlagCollection $flags
    )
    {
        // Check that the required endpoints are available for the provider
        if (!$endpoints->hasEndpointTypes($this->requiredEndpoints)) {
            throw new InvalidProviderException("The required endpoints are "
                . "not present in the endpoint collectoin");
        }
        
        if (!$options->hasOptionTypes($this->requiredOptions)) {
            throw new InvalidProviderException("The required support options "
                . "are not present in the options collection");
        }
        
        $this->endpoints = $endpoints;
        $this->options = $options;
        $this->flags = $flags;
    }
    
    public static function fromArray(array $data)
    {
        $endpoints = [];
        $options = [];
        $flags = [];

        foreach (static::$knownEndpoints as $type) {
            if (isset($data[$type])) {
                $endpoints[] = new Endpoint($type, Uri::fromString($data[$type]));
            }
        }

        foreach (static::$knownOptions as $type) {
            if (isset($data[$type])) {
                $options[] = new Option($type, $data[$type]);
            }
        }

        foreach (static::$knownFlags as $type) {
            if (isset($data[$type])) {
                $flags[] = new Flag($type, $data[$type]);
            }
        }

        return new static(new EndpointCollection($endpoints),
            new OptionCollection($options), new FlagCollection($flags));
    }
    
    /** 
     * @return EndpointCollection 
     */
    public function endpoints()
    {
        return $this->endpoints;
    }
    
    /** 
     * @return OptionCollection 
     */
    public function options()
    {
        return $this->options;
    }
    
    /** 
     * @return FlagCollection 
     */
    public function flags()
    {
        return $this->flags;
    }

    /** 
     * @return bool
     */
    public function issuerEquals(Uri $issuer)
    {
        return $this->endpoints
            ->get(self::ISSUER)
            ->uri()
            ->equals($issuer);
    }
    
    /**
     * @param string $algorithm
     * 
     * @return bool
     */
    public function idTokenSigningAlgorithmSupported(string $algorithm)
    {
        $key = self::ID_TOKEN_SIGNING_ALG_VALUES_SUPPORTED;

        return $this->options->get($key)->supports($algorithm);
    }

    /**
     * 
     * @param Query $query
     * 
     * @return Uri
     */
    public function buildAuthUri(Query $query) : Uri
    {
        $endpoint = $this->endpoints->get(self::AUTHORIZATION_ENDPOINT)->uri();
        return $endpoint->withQuery($query);
    }

    /**
     * @param string $queryString
     * 
     * @return string
     */
    public function buildEndSessionUri($queryString)
    {
        $endSessionEndpoint = $this->endpoints->get(self::END_SESSION_ENDPOINT);
        
        return $endSessionEndpoint .
            '?' . $queryString;
    }
}
