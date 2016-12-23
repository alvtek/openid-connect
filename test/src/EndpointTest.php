<?php

namespace Alvtek\OpenIdConnectTest;

use Alvtek\OpenIdConnect\Endpoint;
use Alvtek\OpenIdConnect\Uri;

use PHPUnit\Framework\TestCase;

/**
 * Description of Endpoint
 */
class EndpointTest extends TestCase
{
    public function testValid()
    {
        $endpoint = new Endpoint('issuer', Uri::fromString('http://test.co.uk'));

        $this->assertInstanceOf(Endpoint::class, $endpoint);
        $this->assertEquals('issuer', $endpoint->type());
        $this->assertTrue(Uri::fromString('http://test.co.uk')->equals($endpoint->uri()));
    }

    public function testEquality()
    {
        $endpoint = new Endpoint('something', Uri::fromString('https://www.test.com'));
        $identicalEndpoint = new Endpoint('something', Uri::fromString('https://www.test.com'));
        $differentTypeEndpoint = new Endpoint('something-else', Uri::fromString('https://www.test.com'));
        $differentValueEndpoint = new Endpoint('something', Uri::fromString('https://www.test.co.uk'));
        
        $this->assertTrue($endpoint->equals($identicalEndpoint));
        $this->assertFalse($endpoint->equals($differentTypeEndpoint));
        $this->assertFalse($endpoint->equals($differentValueEndpoint));
    }
    
    public function testToString()
    {
        $endpoint = new Endpoint('test', Uri::fromString('https://www.test.com'));
        $this->assertEquals('https://www.test.com', (string) $endpoint);
    }
}
