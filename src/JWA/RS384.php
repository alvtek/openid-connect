<?php

namespace Alvtek\OpenIdConnect\JWA;

use Alvtek\OpenIdConnect\JWAInterface;
use Alvtek\OpenIdConnect\JWA\Exception\RuntimeException;

final class RS384 implements JWAInterface
{
    public function getAlgorithmName()
    {
        return 'sha384';
    }
    
    public function hash(string $data): string
    {
        $hashed = hash('sha384', $data, true);
        
        if (empty($hashed)) {
            throw new RuntimeException("JWA Algorithm returned an unexpected "
                . "response.");
        }
        
        return $hashed;
    }
}
