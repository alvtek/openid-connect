<?php

namespace Alvtek\OpenIdConnect\JWA;

use Alvtek\OpenIdConnect\JWA\Exception\InvalidAlgorithmUseException;
use Alvtek\OpenIdConnect\JWAInterface;

class None implements JWAInterface
{
    public function sign(string $message, string $key) : string
    {
        throw new InvalidAlgorithmUseException("The algorithm none cannot "
            . "be used to sign a message.");
    }

    public function verify(string $message, string $signature, string $key) : bool
    {
        throw new InvalidAlgorithmUseException("The algorithm none cannot "
            . "be used to verify a signature.");
    }
}
