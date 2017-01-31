<?php

namespace Alvtek\OpenIdConnect\JWA;

use Alvtek\OpenIdConnect\JWA\Exception\InvalidAlgorithmUseException;
use Alvtek\OpenIdConnect\JWAInterface;

class None implements JWAInterface
{
    public function hash(string $data): string
    {
        throw new InvalidAlgorithmUseException("The algorithm none cannot "
            . "be used to hash a message.");
    }
}
