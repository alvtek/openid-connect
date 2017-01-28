<?php

namespace Alvtek\OpenIdConnect\JWA;

use Alvtek\OpenIdConnect\JWAInterface;

use Alvtek\OpenIdConnect\JWA\Exception\InvalidAlgorithmUseException;

class None implements JWAInterface
{
    public function hash($data): string
    {
        throw new InvalidAlgorithmUseException("The algorithm none cannot "
            . "be used to hash a message.");
    }
}
