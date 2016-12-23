<?php

namespace Alvtek\OpenIdConnectTest\Endpoint;

use Alvtek\OpenIdConnect\Endpoint\EndpointCollection;
use Alvtek\OpenIdConnect\Endpoint;

use Alvtek\OpenIdConnect\Endpoint\Exception\UndefinedEndpointException;

use PHPUnit\Framework\TestCase;

/**
 * Description of Endpoint
 */
class EndpointCollectionTest extends TestCase
{
    public function testAccess()
    {
        $endpoint1 = $this->getMockBuilder(Endpoint::class)
            ->disableOriginalConstructor()
            ->getMock();

        $endpoint1->expects($this->any())
            ->method('type')
            ->willReturn('test_type_1');

        $endpoint2 = $this->getMockBuilder(Endpoint::class)
            ->disableOriginalConstructor()
            ->getMock();

        $endpoint2->expects($this->any())
            ->method('type')
            ->willReturn('test_type_2');

        $endpoints = new EndpointCollection([$endpoint1, $endpoint2]);

        $this->assertTrue($endpoints->hasEndpointType('test_type_1'));

        $this->assertFalse($endpoints->hasEndpointType(
            'some_unknown_endpoint_type'));

        $this->assertTrue($endpoints->hasEndpointTypes([
            'test_type_1',
            'test_type_2',
        ]));

        $this->assertFalse($endpoints->hasEndpointTypes([
            'test_type_1',
            'some_unknown_endpoint_type',
        ]));

        $this->assertInstanceOf(Endpoint::class, $endpoints->get('test_type_1'));
        
        foreach ($endpoints as $key => $endpoint) {
            $this->assertInstanceOf(Endpoint::class, $endpoint);
        }
    }
    
    public function testGetException()
    {
        $endpoint1 = $this->getMockBuilder(Endpoint::class)
            ->disableOriginalConstructor()
            ->getMock();

        $endpoint1->expects($this->once())
            ->method('type')
            ->willReturn('endpoint1');

        $endpoint2 = $this->getMockBuilder(Endpoint::class)
            ->disableOriginalConstructor()
            ->getMock();

        $endpoint2->expects($this->once())
            ->method('type')
            ->willReturn('endpoint2');

        $endpoints = new EndpointCollection([$endpoint1, $endpoint2]);

        $this->expectException(UndefinedEndpointException::class);

        $endpoints->get('someUndefinedEndpoint');
    }
    
    public function testEmpty()
    {
        $endpoints = new EndpointCollection([]);
        $this->assertTrue($endpoints->isEmpty());
    }
    
    public function testAddEndpoint()
    {
        $endpoint1 = $this->getMockBuilder(Endpoint::class)
            ->disableOriginalConstructor()
            ->getMock();

        $endpoint1->expects($this->any())
            ->method('type')
            ->willReturn('test_type_1');

        $endpoint2 = $this->getMockBuilder(Endpoint::class)
            ->disableOriginalConstructor()
            ->getMock();

        $endpoint2->expects($this->any())
            ->method('type')
            ->willReturn('test_type_2');

        $endpoints = new EndpointCollection([$endpoint1, $endpoint2]);
        
        $this->assertCount(2, $endpoints);
        
        $endpoint3 = $this->getMockBuilder(Endpoint::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $endpoint3->expects($this->any())
            ->method('type')
            ->willReturn('test_type_3');
        
        $endpointsNew = $endpoints->addEndpoint($endpoint3);
        
        $this->assertCount(3, $endpointsNew);
        $this->assertTrue($endpointsNew->hasEndpointType('test_type_3'));
    }
    
    public function testDuplicateEndpoints()
    {
        $endpoint1 = $this->getMockBuilder(Endpoint::class)
            ->disableOriginalConstructor()
            ->getMock();

        $endpoint2 = $this->getMockBuilder(Endpoint::class)
            ->disableOriginalConstructor()
            ->getMock();

        $endpoint3 = $this->getMockBuilder(Endpoint::class)
            ->disableOriginalConstructor()
            ->getMock();

        $endpoint1->expects($this->at(1))
            ->method('equals')
            ->with($endpoint3)
            ->willReturn(true);

        $endpoints = new EndpointCollection([$endpoint1, $endpoint2, $endpoint3]);
        
        $this->assertCount(2, $endpoints);
    }
}