<?php

namespace Alvtek\OpenIdConnect\JWA;

use Alvtek\OpenIdConnect\JWAInterface;
use Alvtek\OpenIdConnect\JWA\Exception\RuntimeException;

final class RS512 implements JWAInterface
{
    public function getAlgorithmName()
    {
        return 'sha512';
    }
    
    public function hash(string $data): string
    {
        $hashed = hash('sha512', $data, true);
        
        if (empty($hashed)) {
            throw new RuntimeException("JWA Algorithm returned an unexpected "
                . "response.");
        }
        
        return $hashed;
    }
}
