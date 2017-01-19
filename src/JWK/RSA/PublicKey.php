<?php

namespace Alvtek\OpenIdConnect\JWK\RSA;

use Alvtek\OpenIdConnect\JWAInterface;
use Alvtek\OpenIdConnect\JWK;
use Alvtek\OpenIdConnect\JWK\RSA\PublicKey\PublicKeyBuilder;
use Alvtek\OpenIdConnect\JWK\VerificationInterface;
use Alvtek\OpenIdConnect\Lib\BigIntegerInterface;

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
        // First hash the message using the JWA
        $hashedMessage = $jwa->hash($message);
        
        // Pad the hash
        
        
    }
}
