<?php

namespace Alvtek\OpenIdConnect\JWA;

use Alvtek\OpenIdConnect\JWAInterface;

final class RS384 implements JWAInterface
{
    public function hash($data): string
    {
        return hash('sha384', $data);
    }
}
