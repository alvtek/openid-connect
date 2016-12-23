<?php

namespace Alvtek\OpenIdConnectTest;

use Alvtek\OpenIdConnect\Uri;

use PHPUnit\Framework\TestCase;

/**
 * Description of Uri
 */
class UriTest extends TestCase
{
    public function testBasicUri()
    {
        $uri = Uri::fromString('http://www.google.com/something');
        
        $this->assertEquals('http', $uri->getScheme());
        $this->assertEquals('www.google.com', $uri->getDomain());
        $this->assertEquals('/something', $uri->getPath());
    }
    
    public function testAdvancedUri()
    {
        $uri = Uri::fromString('https://subdomain.domain.tld:1234/path?querystring=1#fragment');
        $this->assertEquals('https://subdomain.domain.tld:1234/path?querystring=1#fragment', (string) $uri);
        
        $uri = Uri::fromString('https://www.alvinchevolleaux.com:8080/my/'
            . 'custom/path?foo=bar&baz=bop&a=b&c=d#fragz');
        
        $this->assertEquals('https', $uri->getScheme());
        $this->assertEquals('www.alvinchevolleaux.com', $uri->getDomain());
        $this->assertEquals('8080', $uri->getPort());
        $this->assertEquals('/my/custom/path', $uri->getPath());
        $this->assertEquals('?foo=bar&baz=bop&a=b&c=d', $uri->getQueryString());
        $this->assertEquals('fragz', $uri->getFragmentId());
    }
    
    public function testMalformedUri()
    {
        $this->expectException(\InvalidArgumentException::class);
        
        $uri = Uri::fromString('malformeduri');
    }
}
