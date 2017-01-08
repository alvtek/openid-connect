<?php

declare(strict_types=1);

namespace Alvtek\OpenIdConnect\JWA;

final class RS512 extends RSA
{
    public function __construct()
    {
        $this->alg = 'SHA512';
    }
}
