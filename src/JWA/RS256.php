<?php

namespace Alvtek\OpenIdConnect\JWA;

final class RS256 extends RSA
{
    protected function getAlg() : string
    {
        return 'SHA256';
    }
}
