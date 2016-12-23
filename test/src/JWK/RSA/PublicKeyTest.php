<?php

namespace Alvtek\OpenIdConnectTest\JWK\RSA;

use Alvtek\OpenIdConnect\JWK\RSA\PublicKey\PublicKeyBuilder;

use PHPUnit\Framework\TestCase;

class PublicKeyTest extends TestCase
{
    /** @var resource */
    private $publicKeyFile;
    
    public function setup()
    {
        $this->publicKeyFile = TEST_ASSETS . DIRECTORY_SEPARATOR . 'rsa.pub';
    }
    
    public function testPublicKeyConversion()
    {
        
        $publicKey = PublicKeyBuilder::fromResource(openssl_pkey_get_public(
            'file://' . TEST_ASSETS . DIRECTORY_SEPARATOR . 'rsa.pub'))->build();
    }
}