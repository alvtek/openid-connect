<?php

namespace Alvtek\OpenIdConnect\JWA;

use Alvtek\OpenIdConnect\JWA\Exception\InvalidAlgorithmUseException;
use Alvtek\OpenIdConnect\JWAInterface;

class None implements JWAInterface
{
    public function sign($message, $key)
    {
        throw new InvalidAlgorithmUseException("The algorithm none cannot "
            . "be used to sign a message.");
    }

    public function verify($message, $signature, $key)
    {
        throw new InvalidAlgorithmUseException("The algorithm none cannot "
            . "be used to verify a signature.");
    }
}
