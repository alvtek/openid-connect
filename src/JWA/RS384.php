<?php

namespace Alvtek\OpenIdConnect\JWA;

final class RS384 extends RSA
{
    protected function getAlg(): string
    {
        return 'SHA384';
    }
}
