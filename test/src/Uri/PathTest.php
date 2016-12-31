<?php

namespace Alvtek\OpenIdConnectTest\Uri;

use Alvtek\OpenIdConnect\Uri\Path;

use PHPUnit\Framework\TestCase;

class PathTest extends TestCase
{
    public function testPathMatch()
    {
        $path = new Path('/this/is/my/path');
        $this->assertEquals('/this/is/my/path', (string) $path);
    }
    
    public function testAppendedPath()
    {
        $path = new Path('/some');
        $combinedPath1 = $path->withAppendedPath(new Path('combined/path'));
        
        $this->assertEquals('/some/combined/path', (string) $combinedPath1);
        
        $pathWithTrailingSlash = new Path('/some/');
        $combinedPath2 = $pathWithTrailingSlash->withAppendedPath(new Path('/combined/path'));
        
        $this->assertEquals('/some/combined/path', (string) $combinedPath2);
    }
}
