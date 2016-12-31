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
        
        $this->assertEquals('http:', (string) $uri->getScheme());
        $this->assertEquals('www.google.com', (string) $uri->getHost());
        $this->assertEquals('/something', $uri->getPath());
    }
    
    public function testAdvancedUri()
    {
        $uri = Uri::fromString('https://subdomain.domain.tld:1234/path?querystring=1#fragment');
        $this->assertEquals('https://subdomain.domain.tld:1234/path?querystring=1#fragment', (string) $uri);
        
        $uri = Uri::fromString('https://www.alvinchevolleaux.com:8080/my/'
            . 'custom/path?foo=bar&baz=bop&a=b&c=d#fragz');
        
        $this->assertEquals('https:', (string) $uri->getScheme());
        $this->assertEquals('www.alvinchevolleaux.com', (string) $uri->getHost());
        $this->assertEquals(':8080', (string) $uri->getPort());
        $this->assertEquals('/my/custom/path', $uri->getPath());
        $this->assertEquals('?foo=bar&baz=bop&a=b&c=d', (string) $uri->getQuery());
        $this->assertEquals('#fragz', (string) $uri->getFragment());
    }
    
    public function testMalformedUri()
    {
        $this->expectException(\InvalidArgumentException::class);
        
        Uri::fromString('malformeduri');
    }
}
