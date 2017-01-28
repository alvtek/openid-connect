<?php

namespace Alvtek\OpenIdConnectTest\JWK\RSA;

use Alvtek\OpenIdConnect\JWK\RSA\PublicKey\PublicKeyBuilder;
use Alvtek\OpenIdConnect\JWK\RSA\PublicKey;
use Alvtek\OpenIdConnect\Base64UrlSafe;
use Alvtek\OpenIdConnect\JWA\JWAFactory;

use PHPUnit\Framework\TestCase;

class PublicKeyTest extends TestCase
{
    /** @var PublicKey */
    private $publicKey;
    
    public function setup()
    {
        $this->publicKey = PublicKeyBuilder::fromResource(openssl_pkey_get_public(
            'file://' . TEST_ASSETS . DIRECTORY_SEPARATOR . 'rsa.pub'))->build();
    }
    
    public function testSignatureVerification()
    {
        $accessTokenFile = TEST_ASSETS . DIRECTORY_SEPARATOR . 'accesstoken.txt';
        $accessToken = file_get_contents($accessTokenFile);
        
        $segments = explode('.', $accessToken);

        $this->assertCount(3, $segments);
        
        $message = "{$segments[0]}.{$segments[1]}";
        $signature = Base64UrlSafe::decode($segments[2]);
        
        $this->assertTrue(
            $this->publicKey->verify(
                JWAFactory::createFromName('RS256'), 
                $message, 
                $signature
            )
        );
    }
}