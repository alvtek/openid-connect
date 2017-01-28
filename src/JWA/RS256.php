<?php

namespace Alvtek\OpenIdConnect\JWA;

use Alvtek\OpenIdConnect\JWAInterface;
use Alvtek\OpenIdConnect\JWA\Exception\RuntimeException;

final class RS256 implements JWAInterface
{
    public function hash(string $data): string
    {
        $hashed = hash('sha256', $data, true);
        
        if (empty($hashed)) {
            throw new RuntimeException("JWA Algorithm returned an unexpected "
                . "response.");
        }
        
        return $hashed;
    }
    
    public function asn1DigestAlgorithm() : string
    {
        return pack('H*', '3031300d060960864801650304020105000420');
    }
}
