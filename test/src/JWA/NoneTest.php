<?php

namespace Alvtek\OpenIdConnectTest\JWA;

use Alvtek\OpenIdConnect\JWA\Exception\InvalidAlgorithmUseException;
use Alvtek\OpenIdConnect\JWA\None;

use PHPUnit\Framework\TestCase;

class NoneTest extends TestCase
{
    public function testHashException()
    {
        $this->expectException(InvalidAlgorithmUseException::class);
    
        $jwa = new None;
        $jwa->hash('message');
    }
}
