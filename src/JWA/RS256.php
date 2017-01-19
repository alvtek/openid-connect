<?php

namespace Alvtek\OpenIdConnect\JWA;

use Alvtek\OpenIdConnect\JWAInterface;

final class RS256 implements JWAInterface
{
    public function hash($data): string
    {
        return hash('sha256', $data);
    }
}
