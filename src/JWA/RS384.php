<?php

declare(strict_types=1);

namespace Alvtek\OpenIdConnect\JWA;

use Alvtek\OpenIdConnect\JWA\DigestInformationProviderInterface;
use Alvtek\OpenIdConnect\JWA\Exception\RuntimeException;
use Alvtek\OpenIdConnect\JWAInterface;

final class RS384 implements JWAInterface, DigestInformationProviderInterface
{
    public function getDigestInfo() : string
    {
        return pack('H*', '3041300d060960864801650304020205000430');
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
