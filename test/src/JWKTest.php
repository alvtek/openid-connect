<?php

namespace Alvtek\OpenIdConnectTest;

use Alvtek\OpenIdConnect\Exception\UnsupportedException;
use Alvtek\OpenIdConnect\JWK;
use Alvtek\OpenIdConnect\JWK\JWKFactory;
use PHPUnit\Framework\TestCase;

class JWKTest extends TestCase
{
    public function testCreate()
    {
        $jwk = JWKFactory::fromJWKData([
            "kty"   => "RSA",
            "use"   => "sig",
            "kid"   => "1Q0M4pyXdxl58g8Xj82VUSV9Mic",
            "x5t"   => "1Q0M4pyXdxl58g8Xj82VUSV9Mic",
            "e"     => "AQAB",
            "n"     => "o0XEc_gsWJUKHEVDDClnibQL8rvE5zIB208gnND_WFP6ZRwtptvwbO3OdVj-QfxHRa3_q-rhC9dSML-Ebfgp9nb1WqnX7B0pkbeCxDYmSI2AXS4GZnbPnJc1zuwCuMOHhVcAjfQvyTegArYe2kLwArAYE5KrWf0JIhz4rYetolQBS5_9yjqPTQ_mkwgSi-ZPrzKeh-SA091KMP0OU2Jl8f1mQYuCR58it0L0YSB0x6pRyzW6JccUriyRn6OMV9mRuEdA4xirNJfRV9IHzcQiXlG2yqR3xpxncm4YkDG4UA5MwC2trF5ljl6b4vxqt8Qfqc_3th6Gpbh1C59V_28JBw",
        ]);

        $this->assertInstanceOf(JWK::class, $jwk);
    }
    
    public function testECExceptionThrown()
    {
        $this->expectException(UnsupportedException::class);

        JWKFactory::fromJWKData([
            "kty"   => "EC",
            "crv"   => "P-256",
            "x"     => "f83OJ3D2xF1Bg8vub9tLe1gHMzV76e8Tus9uPHvRVEU",
            "y"     => "x_FEzRu9m36HLN_tue659LNpXW6pCyStikYjKIWI5a0",
            "kid"   => "testkid",
        ]);
    }

    public function testEmptyKtyExceptionThrown()
    {
        $this->expectException(UnsupportedException::class);

        JWKFactory::fromJWKData([
            "kty"   => "",
            "crv"   => "P-256",
            "x"     => "f83OJ3D2xF1Bg8vub9tLe1gHMzV76e8Tus9uPHvRVEU",
            "y"     => "x_FEzRu9m36HLN_tue659LNpXW6pCyStikYjKIWI5a0",
            "kid"   => "testkid",
        ]);
    }

    public function testOCTExceptionThrown()
    {
        $this->expectException(UnsupportedException::class);

        JWKFactory::fromJWKData([
            "kty"   => "oct",
            "k"     => "AyM1SysPpbyDfgZld3umj1qzKObwVMkoqQ-EstJQLr_T-1qS0gZH75aKtMN3Yj0iPS4hcgUuTwjAzZr1Z9CAow",
            "kid"   => "testkid",
        ]);
    }
}
