<?php

namespace Alvtek\OpenIdConnectTest\JWK\RSA;

use Alvtek\OpenIdConnect\JWK\RSA\PublicKey\PublicKeyBuilder;
use Alvtek\OpenIdConnect\JWK\RSA\PublicKey;

use PHPUnit\Framework\TestCase;

class PublicKeyBuilderTest extends TestCase
{
    public function testPublicKeyFromResource()
    {
        $publicKey = PublicKeyBuilder::fromResource(openssl_pkey_get_public(
            'file://' . TEST_ASSETS . DIRECTORY_SEPARATOR . 'rsa.pub'))->build();
        
        $this->assertInstanceOf(PublicKey::class, $publicKey);
    }
}
