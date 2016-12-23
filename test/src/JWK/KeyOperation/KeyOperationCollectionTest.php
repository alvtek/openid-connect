<?php

namespace Alvtek\OpenIdConnectTest\JWK\KeyOperation;

use Alvtek\OpenIdConnect\JWK\KeyOperation\KeyOperationCollection;
use Alvtek\OpenIdConnect\JWK\KeyOperation;

use PHPUnit\Framework\TestCase;

class KeyOperationCollectionTest extends TestCase
{
    public function testHasKeyOperation()
    {
        $keyOperation1 = new KeyOperation(KeyOperation::SIGN);
        $keyOperation2 = new KeyOperation(KeyOperation::ENCRYPT);
        $keyOperation3 = new KeyOperation(KeyOperation::VERIFY);
        
        $keyOperations = new KeyOperationCollection([
            $keyOperation1, $keyOperation2, $keyOperation3
        ]);
        
        $this->assertTrue($keyOperations->hasKeyOperation(
            new KeyOperation(KeyOperation::ENCRYPT)
        ));
        
        $this->assertTrue($keyOperations->hasKeyOperation(
            new KeyOperation(KeyOperation::ENCRYPT)
        ));
        
        $this->assertFalse($keyOperations->hasKeyOperation(
            new KeyOperation(KeyOperation::DERIVE_BITS)
        ));
    }
    
    public function testEquality()
    {
        $keyOperation1 = new KeyOperation(KeyOperation::SIGN);
        $keyOperation2 = new KeyOperation(KeyOperation::ENCRYPT);
        
        $keyOperations1 = new KeyOperationCollection([
            $keyOperation1, $keyOperation2
        ]);
        
        $keyOperation3 = new KeyOperation(KeyOperation::SIGN);
        $keyOperation4 = new KeyOperation(KeyOperation::ENCRYPT);
        
        $keyOperations2 = new KeyOperationCollection([
            $keyOperation3, $keyOperation4
        ]);
        
        $this->assertTrue($keyOperations1->equals($keyOperations2));
        
        $keyOperation5 = new KeyOperation(KeyOperation::SIGN);
        $keyOperation6 = new KeyOperation(KeyOperation::DERIVE_KEY);
        
        $keyOperations3 = new KeyOperationCollection([
            $keyOperation5, $keyOperation6
        ]);
        
        $this->assertFalse($keyOperations1->equals($keyOperations3));
    }
    
    public function testJsonSerialisation()
    {
        $keyOperation1 = new KeyOperation(KeyOperation::SIGN);
        $keyOperation2 = new KeyOperation(KeyOperation::ENCRYPT);
        
        $keyOperations1 = new KeyOperationCollection([
            $keyOperation1, $keyOperation2
        ]);
        
        $serialisedCollection = $keyOperations1->jsonSerialize();
        
        $this->assertEquals(
            [KeyOperation::SIGN, KeyOperation::ENCRYPT], $serialisedCollection
        );
    }
    
    public function testFromArray()
    {
        $keyOperations = KeyOperationCollection::fromArray([
            KeyOperation::DECRYPT,
            KeyOperation::SIGN,
            KeyOperation::ENCRYPT,
        ]);
        
        $this->assertCount(3, $keyOperations);
    }
}
