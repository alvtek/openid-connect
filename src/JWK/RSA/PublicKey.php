<?php

namespace Alvtek\OpenIdConnect\JWK\RSA;

use Alvtek\OpenIdConnect\JWAInterface;
use Alvtek\OpenIdConnect\JWK;
use Alvtek\OpenIdConnect\JWK\RSA\PublicKey\PublicKeyBuilder;
use Alvtek\OpenIdConnect\JWK\VerificationInterface;
use Alvtek\OpenIdConnect\BigIntegerInterface;
use Alvtek\OpenIdConnect\BigInteger\BigIntegerFactory;
use Alvtek\OpenIdConnect\JWK\Exception\RuntimeException;

final class PublicKey extends JWK implements VerificationInterface
{
    /** @var BigIntegerInterface */
    protected $n;

    /** @var BigIntegerInterface */
    protected $e;
    
    public function __construct(PublicKeyBuilder $pubicKeyBuilder)
    {
        $this->n    = $pubicKeyBuilder->n;
        $this->e    = $pubicKeyBuilder->e;
        
        parent::__construct($pubicKeyBuilder);
    }
    
    public function verify(JWAInterface $jwa, $message, $signature)
    {
        $start = microtime(true);
        $k = strlen($this->n->toBytes());
        
        // First check the length of the signature against the modulus
        if (strlen($signature) != $k) {
            return false;
        }
        
        // Convert signature to a BigInteger
        $signatureAsNumber = BigIntegerFactory::fromBytes($signature);
        
        $m = $this->rsavp1($signatureAsNumber);
        
        $em = $this->i2osp($m, $k - 1);
        
        // Now lets hash the message using the JWA
        $em2 = $this->emsaPkcs1V15Encode($jwa, $message, $k - 1);
        
        echo "\nTime taken: " . (microtime(true) - $start);
        
        if (strcmp($em, $em2) !== 0) {
            return false;
        }
        
        return true;
    }
    
    private function rsavp1(BigIntegerInterface $s)
    {
        return $s->powerModulus($this->e->toInt(), $this->n);
    }
    
    private function i2osp(BigIntegerInterface $x, $l)
    {
        $byteString = $x->toBytes();

        $y = BigIntegerFactory::fromInteger(256)->power($l);
        
        if ($x->compare($y) >= 0) {
            throw new RuntimeException("Integer too large");
        }
        
        return str_pad($byteString, $l, chr(0), STR_PAD_LEFT);
    }
    
    private function emsaPkcs1V15Encode(JWAInterface $jwa, $M, $emLen)
    {
        $H = $jwa->hash($M);
        
        if ($H === false) {
            throw new RuntimeException("Message too long");
        }

        $T = $jwa->asn1DigestAlgorithm() . $H;

        if ($emLen < strlen($T) + 10) {
            throw new RuntimeException('Intended encoded message length too short');
        }

        $PS = str_repeat(chr(0xff), $emLen - strlen($T) - 2);

        $em = "\1{$PS}\0$T";

        return $em;
    }
}
