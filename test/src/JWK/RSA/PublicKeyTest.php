<?php

namespace Alvtek\OpenIdConnectTest\JWK\RSA;

use Alvtek\OpenIdConnect\Base64UrlSafe;
use Alvtek\OpenIdConnect\Base64UrlSafeInterface;
use Alvtek\OpenIdConnect\JWA\JWAFactory;
use Alvtek\OpenIdConnect\JWK\RSA\PublicKey;
use Alvtek\OpenIdConnect\JWK\RSA\PublicKey\PublicKeyBuilder;
use PHPUnit\Framework\TestCase;

class PublicKeyTest extends TestCase
{
    /** @var PublicKey */
    private $publicKey;
    
    /** @var Base64UrlSafeInterface */
    private $base64UrlSafe;
    
    public function setup()
    {
        $this->publicKey = PublicKeyBuilder::fromResource(openssl_pkey_get_public(
            'file://' . TEST_ASSETS . DIRECTORY_SEPARATOR . 'rsa.pub'))->build();
        
        $this->base64 = new Base64UrlSafe;
    }
    
    public function testSignatureVerification()
    {
        $accessTokenFile = TEST_ASSETS . DIRECTORY_SEPARATOR . 'accesstoken.txt';
        $accessToken = \file_get_contents($accessTokenFile);
        
        $segments = \explode('.', $accessToken);

        $this->assertCount(3, $segments);
        
        $message = "{$segments[0]}.{$segments[1]}";
        $signature = $this->base64UrlSafe->decode($segments[2]);
        
        $this->assertTrue(
            $this->publicKey->verify(
                JWAFactory::createFromName('RS256'), 
                $message, 
                $signature
            )
        );
    }
}