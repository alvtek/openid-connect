<?php

namespace Alvtek\OpenIdConnectTest\JWK\RSA;

use Alvtek\OpenIdConnect\JWK\RSA\PrivateKey\PrivateKeyBuilder;

use Alvtek\OpenIdConnect\Exception\InvalidArgumentException;

use PHPUnit\Framework\TestCase;

class PrivateKeyTest extends TestCase
{
    /** @var resource */
    private $privateKeyFile;
    
    public function setup()
    {
        $this->privateKeyFile = TEST_ASSETS . DIRECTORY_SEPARATOR . 'rsa';
    }
    
    public function testPrivateKeyFromResource()
    {
        PrivateKeyBuilder::fromResource(openssl_pkey_get_private(
            'file://' . TEST_ASSETS . DIRECTORY_SEPARATOR . 'rsa'))->build();
    }
    
    public function testPrivateKeyFromResourceException()
    {
        $this->expectException(InvalidArgumentException::class);
        
        PrivateKeyBuilder::fromResource(openssl_pkey_get_private('not_resource'));
    }
}