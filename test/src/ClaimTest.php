<?php

namespace Alvtek\OpenIdConnectTest;

use Alvtek\OpenIdConnect\Claim;

use PHPUnit\Framework\TestCase;

class ClaimTest extends TestCase
{
    public function testBasicClaim()
    {
        $claim = new Claim('claim1', 'value1');
        
        $this->assertEquals('claim1', $claim->type());
        $this->assertEquals('value1', $claim->value());
    }
    
    public function testEquality()
    {
        $claim1 = new Claim('claim_type_1', 'value_1');
        $claim2 = new Claim('claim_type_2', 'value_2');
        $claim3 = new Claim('claim_type_1', 'value_1');
        $claim4 = new Claim('claim_type_1', 'value_3');
        
        $this->assertTrue($claim1->equals($claim3));
        $this->assertFalse($claim1->equals($claim2));
        $this->assertFalse($claim1->equals($claim4));
    }
    
    public function testSerialisation()
    {
        $claim1 = new Claim('claim_type_1', 'value_1');
        
        $this->assertEquals([
            'type' => 'claim_type_1',
            'value' => 'value_1',
        ], $claim1->jsonSerialize());
    }
}
