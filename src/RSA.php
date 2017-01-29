<?php

namespace Alvtek\OpenIdConnect;

use Alvtek\OpenIdConnect\BigIntegerInterface;
use Alvtek\OpenIdConnect\Exception\RuntimeException;

class RSA
{
    public function rsavp1(BigIntegerInterface $s, int $exponent, 
        BigIntegerInterface $modulus)
    {
        return $s->powerModulus($exponent, $modulus);
    }
    
    public function i2osp(BigIntegerInterface $x, $l)
    {
        $byteString = $x->toBytes();

        $y = BigIntegerFactory::fromInteger(256)->power($l);
        
        if ($x->compare($y) >= 0) {
            throw new RuntimeException("Integer too large");
        }
        
        return str_pad($byteString, $l, chr(0), STR_PAD_LEFT);
    }
    
    public function emsaPkcs1V15Encode(JWAInterface $jwa, $M, $emLen)
    {
        $H = $jwa->hash($M);
        
        if ($H === false) {
            throw new RuntimeException("Message too long");
        }
        
        switch ($jwa->getAlgorithmName()) {
            case 'sha256':
                $digest = pack('H*', '3031300d060960864801650304020105000420');
                break;
            case 'sha384':
                $digest = pack('H*', '3041300d060960864801650304020205000430');
                break;
            case 'sha512':
                $digest = pack('H*', '3051300d060960864801650304020305000440');
                break;
            default:
                throw new RuntimeException("Unrecognised hashing algorithmc");
        }

        $T = $digest . $H;

        if ($emLen < strlen($T) + 10) {
            throw new RuntimeException('Intended encoded message length too short');
        }

        $PS = str_repeat(chr(0xff), $emLen - strlen($T) - 2);

        $em = "\1{$PS}\0$T";

        return $em;
    }
}
