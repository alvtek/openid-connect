<?php

namespace Alvtek\OpenIdConnectTest\JWK;

use Alvtek\OpenIdConnect\JWK\KeyOperation;

use Alvtek\OpenIdConnect\Exception\InvalidArgumentException;

use PHPUnit\Framework\TestCase;

class KeyOperationTest extends TestCase
{
    public function testValid()
    {
        $keyOperation = new KeyOperation(KeyOperation::SIGN);
        $this->assertEquals(KeyOperation::SIGN, (string) $keyOperation);
    }
    
    public function testInvalid()
    {
        $this->expectException(InvalidArgumentException::class);
        
        new KeyOperation("some made up key operation that "
            . "couldn't possibly exist?");
    }
    
    public function testEquality()
    {
        $keyOperation1 = new KeyOperation(KeyOperation::SIGN);
        $keyOperation2 = new KeyOperation(KeyOperation::SIGN);
        $keyOperation3 = new KeyOperation(KeyOperation::ENCRYPT);
        
        $this->assertTrue($keyOperation1->equals($keyOperation2));
        $this->assertFalse($keyOperation1->equals($keyOperation3));
    }
}