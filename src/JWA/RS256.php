<?php

declare(strict_types=1);

namespace Alvtek\OpenIdConnect\JWA;

final class RS256 extends RSA
{
    public function __construct()
    {
        $this->alg = 'SHA256';
    }
}
