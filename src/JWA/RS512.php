<?php

namespace Alvtek\OpenIdConnect\JWA;

final class RS512 extends RSA
{
    protected function getAlg(): string
    {
        return 'SHA512';
    }
}
