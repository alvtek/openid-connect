<?php

declare(strict_types=1);

namespace Alvtek\OpenIdConnect\JWA;

use Alvtek\OpenIdConnect\JWA\DigestInformationProviderInterface;
use Alvtek\OpenIdConnect\JWA\Exception\RuntimeException;
use Alvtek\OpenIdConnect\JWAInterface;

final class RS512 implements JWAInterface, DigestInformationProviderInterface
{
    public function getDigestInfo() : string
    {
        return pack('H*', '3051300d060960864801650304020305000440');
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
