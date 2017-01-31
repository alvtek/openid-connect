<?php

declare(strict_types=1);

namespace Alvtek\OpenIdConnect\JWA;

use Alvtek\OpenIdConnect\JWA\DigestInformationProviderInterface;
use Alvtek\OpenIdConnect\JWA\Exception\RuntimeException;
use Alvtek\OpenIdConnect\JWAInterface;

final class RS256 implements JWAInterface, DigestInformationProviderInterface
{
    public function getDigestInfo() : string
    {
        return pack('H*', '3031300d060960864801650304020105000420');
    }
    
    public function hash(string $data): string
    {
        $hashed = hash('sha256', $data, true);
        
        if (empty($hashed)) {
            throw new RuntimeException("JWA Algorithm returned an unexpected "
                . "response.");
        }
        
        return $hashed;
    }
}
