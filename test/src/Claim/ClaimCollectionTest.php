<?php

namespace Alvtek\OpenIdConnectTest\Claim;

use Alvtek\OpenIdConnect\Claim\ClaimCollection;
use Alvtek\OpenIdConnect\ClaimInterface;

use Alvtek\OpenIdConnect\Claim\Exception\UndefinedClaimException;
use Alvtek\OpenIdConnect\Claim\Exception\AmbiguousClaimException;

use PHPUnit\Framework\TestCase;

/**
 * Description of CollectionTest
 *
 * @author Alvin Chevolleaux <alvin@alvinchevolleaux.com>
 */
class ClaimCollectionTest extends TestCase
{
    /** @var Claim */
    private $mockClaim1;
    
    /** @var Claim */
    private $mockClaim2;
    
    /** @var Claim */
    private $mockClaim3;
    
    public function setup()
    {
        $this->mockClaim1 = $this->getMockBuilder(ClaimInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $this->mockClaim2 = $this->getMockBuilder(ClaimInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $this->mockClaim3 = $this->getMockBuilder(ClaimInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
    
    public function testValidInstantiation()
    {
        $mockClaim = $this->getMockBuilder(Claim::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        new ClaimCollection([$mockClaim]);
    }

    public function testNonArrayConstructorException()
    {
        $this->expectException(\InvalidArgumentException::class);

        new ClaimCollection('this is not a claim!');
    }

    public function testInvalidArrayConstructorException()
    {
        $this->expectException(\InvalidArgumentException::class);

        new ClaimCollection([$this->mockClaim1, 'claim2']);
    }

    public function testCount()
    {
        $this->mockClaim1
            ->method('type')
            ->willReturn('claim1');
        
        $this->mockClaim1
            ->method('value')
            ->willReturn('value1');
        

        $this->mockClaim2
            ->method('type')
            ->willReturn('claim2');
            
        $this->mockClaim2
            ->method('value')
            ->willReturn('value2');

        $this->mockClaim3
            ->method('type')
            ->willReturn('claim2');
        
        $this->mockClaim3
            ->method('value')
            ->willReturn('value3');

        $claims = new ClaimCollection([
            $this->mockClaim1, $this->mockClaim2, $this->mockClaim3
        ]);
        
        $this->assertCount(3, $claims);
    }

    public function testIterator()
    {
        $this->mockClaim1
            ->method('type')
            ->will($this->returnValue('claim1'));
        
        $this->mockClaim2
            ->method('type')
            ->will($this->returnValue('claim2'));
        
        $this->mockClaim3
            ->method('type')
            ->will($this->returnValue('claim2'));

        $claims = new ClaimCollection([
            $this->mockClaim1, $this->mockClaim2, $this->mockClaim3
        ]);

        $i = 0;
        
        foreach ($claims as $key => $claim) {
            $this->assertEquals($i, $key);
            $this->assertEquals($this->{'mockClaim' . ($i + 1)}, $claim);
            $i++;
        }
    }

    public function testGetApiValues()
    {
        $this->mockClaim1
            ->method('type')
            ->willReturn('claim1');
        
        $this->mockClaim1
            ->method('value')
            ->willReturn('value1');
        
        $this->mockClaim1
            ->method('jsonSerialize')
            ->willReturn([
                'type' => 'claim1',
                'value' => 'value1'
            ]);

        $this->mockClaim2
            ->method('type')
            ->willReturn('claim2');
            
        $this->mockClaim2
            ->method('value')
            ->willReturn('value2');
        
        $this->mockClaim2
            ->method('jsonSerialize')
            ->willReturn([
                'type' => 'claim2',
                'value' => 'value2'
            ]);

        $this->mockClaim3
            ->method('type')
            ->willReturn('claim2');
        
        $this->mockClaim3
            ->method('value')
            ->willReturn('value3');
        
        $this->mockClaim3
            ->method('jsonSerialize')
            ->willReturn([
                'type' => 'claim2',
                'value' => 'value3'
            ]);
        
        $claims = new ClaimCollection([$this->mockClaim1, $this->mockClaim2, $this->mockClaim3]);
        
        $this->assertEquals([
            [
                'type' => 'claim1',
                'value' => 'value1',
            ],
            [
                'type' => 'claim2',
                'value' => 'value2',
            ],
            [
                'type' => 'claim2',
                'value' => 'value3',
            ],
        ], $claims->jsonSerialize());
    }
    
    public function testFromApi()
    {
        $claims = ClaimCollection::fromApi([
            [
                'type' => 'claim1',
                'value' => 'value1',
            ],
            [
                'type' => 'claim2',
                'value' => 'value2',
            ],
            [
                'type' => 'claim2',
                'value' => 'value3',
            ],
        ]);
        
        $this->assertCount(3, $claims);
    }
    
    public function testToArray()
    {
        $this->mockClaim1
            ->method('type')
            ->will($this->returnValue('claim1'));
        
        $this->mockClaim1
            ->method('value')
            ->will($this->returnValue('value1'));
        
        $this->mockClaim2
            ->method('type')
            ->will($this->returnValue('claim2'));
        
        $this->mockClaim2
            ->method('value')
            ->will($this->returnValue('value2'));
    
        $this->mockClaim3
            ->method('type')
            ->will($this->returnValue('claim2'));
    
        $this->mockClaim3
            ->method('value')
            ->will($this->returnValue('value3'));
        
        $claims = new ClaimCollection([$this->mockClaim1, $this->mockClaim2, $this->mockClaim3]);
        
        $this->assertEquals([
            'claim1' => 'value1',
            'claim2' => ['value2', 'value3'],
        ], $claims->toArray());
    }
    
    public function testUndefinedUniqueClaimException()
    {
        $claims = new ClaimCollection([]);
        
        $this->expectException(UndefinedClaimException::class);
        $claims->getUniqueClaimByType('non_existent_claim');
    }

    public function testAmbiguousUniqueClaimException()
    {
        $this->mockClaim1
            ->method('type')
            ->will($this->returnValue('multi_claim'));
    
        $this->mockClaim1
            ->method('value')
            ->will($this->returnValue('value1'));
    
        $this->mockClaim2
            ->method('type')
            ->will($this->returnValue('multi_claim'));
    
        $this->mockClaim2
            ->method('value')
            ->will($this->returnValue('value2'));
    
        $claims = new ClaimCollection([$this->mockClaim1, $this->mockClaim2]);
        
        $this->expectException(AmbiguousClaimException::class);
        $claims->getUniqueClaimByType('multi_claim');
    }
    
    public function testHasClaimTypeFalse()
    {
        $claims = new ClaimCollection([]);
        $this->assertFalse($claims->hasClaimType('something'));
    }
    
    public function testWithClaim()
    {
        $this->mockClaim1
            ->method('type')
            ->willReturn('claim1');
        
        $claims = new ClaimCollection([]);
        
        $this->assertEquals(0, count($claims));
        
        $newClaims = $claims->withClaim($this->mockClaim1);
    
        $this->assertEquals(1, count($newClaims));
    }
    
    public function testMerge()
    {
        $claims1 = ClaimCollection::fromArray([
            'key1' => 'value1',
            'key2' => 'value2',
            'duplicate' => 'duplicate_value',
        ]);
        
        $claims2 = ClaimCollection::fromArray([
            'key3' => 'value3',
            'key4' => 'value4',
            'duplicate' => 'duplicate_value',
        ]);
        
        $claims3 = $claims1->merge($claims2);
        
        $this->assertEquals([
            'key1' => 'value1',
            'key2' => 'value2',
            'key3' => 'value3',
            'key4' => 'value4',
            'duplicate' => 'duplicate_value',
        ], $claims3->toArray());
    }
    
    public function testEquality()
    {
        $mockClaim1 = $this->getMockBuilder(ClaimInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $mockClaim1
            ->method('type')
            ->willReturn('type1');
        
        $mockClaim1
            ->method('value')
            ->willReturn('value1');
    
        $mockClaim2 = $this->getMockBuilder(ClaimInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $mockClaim2
            ->method('type')
            ->willReturn('type2');
    
        $mockClaim2
            ->method('value')
            ->willReturn('value2');
        
        $mockClaim1Duplicate = $this->getMockBuilder(ClaimInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $mockClaim1Duplicate
            ->method('type')
            ->willReturn('type1');
        
        $mockClaim1Duplicate
            ->method('value')
            ->willReturn('value1');
        
        $mockClaim2Duplicate = $this->getMockBuilder(ClaimInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $mockClaim2Duplicate
            ->method('type')
            ->willReturn('type2');
        
        $mockClaim2Duplicate
            ->method('value')
            ->willReturn('value2');
        
    
        $claims = new ClaimCollection([$mockClaim1, $mockClaim2]);
        $claimsDuplicate = new ClaimCollection(([$mockClaim1Duplicate, $mockClaim2Duplicate]));
        
        $this->assertTrue($claims->equals($claimsDuplicate));
        
        $mockClaim3 = $this->getMockBuilder(ClaimInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $mockClaim3
            ->method('type')
            ->willReturn('type3');
        
        $mockClaim3
            ->method('value')
            ->willReturn('value3');
        
        $claimsDifferent = new ClaimCollection([$mockClaim2Duplicate, $mockClaim3]);
        
        $this->assertFalse($claims->equals($claimsDifferent));
        
        $claimsEmpty = new ClaimCollection([]);
        
        $this->assertFalse($claims->equals($claimsEmpty));
    }
}
