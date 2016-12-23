<?php

namespace Alvtek\OpenIdConnectTest;

use Alvtek\OpenIdConnect\Provider;

use Alvtek\OpenIdConnect\Provider\Exception\InvalidProviderException;

class ProviderTest extends \PHPUnit_Framework_TestCase
{
    /** @var string */
    private $providerDiscoveryJson;
    
    public function setup()
    {
        $this->providerDiscoveryJson = file_get_contents(
            'test' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR 
            . 'discovery.json');
    }

    public function testFromDiscovery()
    {
        $provider = Provider::fromArray(
            json_decode($this->providerDiscoveryJson, true));
        
        $this->assertInstanceOf(Provider::class, $provider);
    }
    
    public function testFromArray()
    {
        $provider = Provider::fromArray([
            'issuer' => 'http://test.com',
            'authorization_endpoint' => 'http://test.com/authorize',
            'jwks_uri' => 'http://www.test.com/jwks',
            'response_types_supported' => ['a', 'b', 'c'],
            'subject_types_supported' => ['a', 'b', 'c'],
            'id_token_signing_alg_values_supported' => ['a', 'b', 'c'],
        ]);
        
        $this->assertInstanceOf(Provider::class, $provider);
    }
    
    public function testMissingData()
    {
        $this->expectException(InvalidProviderException::class);
        
        Provider::fromArray([
            'issuer' => 'http://test.com',
            'authorization_endpoint' => 'http://test.com/authorize',
            // Missing jwks_uri
            'response_types_supported' => ['a', 'b', 'c'],
            'subject_types_supported' => ['a', 'b', 'c'],
            'id_token_signing_alg_values_supported' => ['a', 'b', 'c'],
        ]);
    }
}