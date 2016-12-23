<?php

namespace Alvtek\OpenIdConnectTest\JWK;

use Alvtek\OpenIdConnect\JWK\KeyType;

use Alvtek\OpenIdConnect\Exception\InvalidArgumentException;

use PHPUnit\Framework\TestCase;

class KeyTypeTest extends TestCase
{
    public function testEquality()
    {
        $keyType = new KeyType(KeyType::RSA);
        
        $this->assertTrue($keyType->equals(new KeyType(KeyType::RSA)));
        $this->assertFalse($keyType->equals(new KeyType(KeyType::EC)));
    }
    
    public function testInvalid()
    {
        $this->expectException(InvalidArgumentException::class);
        
        new KeyType('Some invalid key type');
    }
}
