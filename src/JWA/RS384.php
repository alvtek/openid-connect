<?php

namespace Alvtek\OpenIdConnect\JWA;

use Alvtek\OpenIdConnect\JWAInterface;
use Alvtek\OpenIdConnect\JWA\Exception\RuntimeException;

final class RS384 implements JWAInterface
{
    public function hash($data): string
    {
        $hashed = hash('sha384', $data, true);
        
        if (empty($hashed)) {
            throw new RuntimeException("JWA Algorithm returned an unexpected "
                . "response.");
        }
        
        return $hashed;
    }
    
    public function asn1DigestAlgorithm() : string
    {
        return pack('H*', '3041300d060960864801650304020205000430');
    }
}
