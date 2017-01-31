<?php

namespace Alvtek\OpenIdConnect\JWK;

use Alvtek\OpenIdConnect\BigInteger\BigIntegerFactory;
use Alvtek\OpenIdConnect\BigIntegerInterface;
use Alvtek\OpenIdConnect\JWA\DigestInformationProviderInterface;
use Alvtek\OpenIdConnect\JWAInterface;
use Alvtek\OpenIdConnect\JWK;
use Alvtek\OpenIdConnect\JWK\Exception\RuntimeException;

abstract class RSA extends JWK
{
     protected function rsavp1(BigIntegerInterface $s, int $exponent, 
        BigIntegerInterface $modulus)
    {
        return $s->powerModulus($exponent, $modulus);
    }
    
    protected function i2osp(BigIntegerInterface $x, $l)
    {
        $byteString = $x->toBytes();

        $y = BigIntegerFactory::fromInteger(256)->power($l);
        
        if ($x->compare($y) >= 0) {
            throw new RuntimeException("Integer too large");
        }
        
        return str_pad($byteString, $l, chr(0), STR_PAD_LEFT);
    }
    
    protected function emsaPkcs1V15Encode(JWAInterface $jwa, $M, $emLen)
    {
        $H = $jwa->hash($M);
        
        if ($H === false) {
            throw new RuntimeException("Message too long");
        }
        
        if (!$jwa instanceof DigestInformationProviderInterface) {
            throw new RuntimeException(sprintf("JWA must implement %s for RSA "
                . "algorithms.", DigestInformationProviderInterface::class));
        }
        
        $T = $jwa->getDigestInfo() . $H;

        if ($emLen < strlen($T) + 10) {
            throw new RuntimeException('Intended encoded message length too short');
        }

        $PS = str_repeat(chr(0xff), $emLen - strlen($T) - 2);

        $em = "\1{$PS}\0$T";

        return $em;
    }
}
