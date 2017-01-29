<?php

namespace Alvtek\OpenIdConnect\JWK\RSA;

use Alvtek\OpenIdConnect\JWAInterface;
use Alvtek\OpenIdConnect\JWK;
use Alvtek\OpenIdConnect\JWK\RSA\PublicKey\PublicKeyBuilder;
use Alvtek\OpenIdConnect\JWK\VerificationInterface;
use Alvtek\OpenIdConnect\BigIntegerInterface;
use Alvtek\OpenIdConnect\BigInteger\BigIntegerFactory;
use Alvtek\OpenIdConnect\JWK\Exception\RuntimeException;
use Alvtek\OpenIdConnect\RSA;

final class PublicKey extends JWK implements VerificationInterface
{
    /** @var BigIntegerInterface */
    private $n;

    /** @var BigIntegerInterface */
    private $e;
    
    /** @var RSA */
    private $rsa;
    
    public function __construct(PublicKeyBuilder $pubicKeyBuilder)
    {
        $this->n    = $pubicKeyBuilder->n;
        $this->e    = $pubicKeyBuilder->e;
        $this->rsa  = $pubicKeyBuilder->rsa;
        
        parent::__construct($pubicKeyBuilder);
    }
    
    public function verify(JWAInterface $jwa, $message, $signature)
    {
        $k = strlen($this->n->toBytes());
        
        // First check the length of the signature against the modulus
        if (strlen($signature) != $k) {
            return false;
        }
        
        // Convert signature to a BigInteger
        $signatureAsNumber = BigIntegerFactory::fromBytes($signature);
        
        $m = $this->rsa->rsavp1($signatureAsNumber, $this->e, $this->n);
        $em = $this->rsa->i2osp($m, $k - 1);
        $em2 = $this->rsa->emsaPkcs1V15Encode($jwa, $message, $k - 1);
        
        if (strcmp($em, $em2) !== 0) {
            return false;
        }
        
        return true;
    }
}
