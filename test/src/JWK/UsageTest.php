<?php

namespace Alvtek\OpenIdConnectTest\JWK;

use Alvtek\OpenIdConnect\JWK\Usage;

use Alvtek\OpenIdConnect\Exception\InvalidArgumentException;

use PHPUnit\Framework\TestCase;

class UsageTest extends TestCase
{
    public function testEquality()
    {
        $usage = new Usage(Usage::ENCRYPTION);
        
        $this->assertTrue($usage->equals(new Usage(Usage::ENCRYPTION)));
        $this->assertFalse($usage->equals(new Usage(Usage::SIGNATURE)));
    }
    
    public function testInvalid()
    {
        $this->expectException(InvalidArgumentException::class);
        
        new Usage('Some invalid key type');
    }
}