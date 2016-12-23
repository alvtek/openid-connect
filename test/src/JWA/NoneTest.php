<?php

namespace Alvtek\OpenIdConnectTest\JWA;

use Alvtek\OpenIdConnect\JWA\Exception\InvalidAlgorithmUseException;
use Alvtek\OpenIdConnect\JWA\None;

use PHPUnit\Framework\TestCase;

class NoneTest extends TestCase
{
    public function testSignException()
    {
        $this->expectException(InvalidAlgorithmUseException::class);
    
        $jwa = new None;
        $jwa->sign('message', 'key');
    }

    public function testVerifyException()
    {
        $this->expectException(InvalidAlgorithmUseException::class);
    
        $jwa = new None;
        $jwa->verify('message', 'signature', 'key');
    }
}
