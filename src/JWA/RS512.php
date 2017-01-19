<?php

namespace Alvtek\OpenIdConnect\JWA;

use Alvtek\OpenIdConnect\JWAInterface;

final class RS512 implements JWAInterface
{
    public function hash($data): string
    {
        return hash('sha512', $data);
    }
}
