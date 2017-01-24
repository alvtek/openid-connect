<?php

namespace Alvtek\OpenIdConnect\JWK\RSA;

use Alvtek\OpenIdConnect\JWAInterface;
use Alvtek\OpenIdConnect\JWK;
use Alvtek\OpenIdConnect\JWK\Exception\RuntimeException;
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
        // First check the length of the signature against the modulus
        $unpackedModulusAsInteger = @unpack('n*', $this->n->toBytes());
        if (empty($unpackedModulusAsInteger) || count($unpackedModulusAsInteger) > 1) {
            throw new RuntimeException("Unexpected result when unpacking modulus");
        }
        $modulusAsInteger = $unpackedModulusAsInteger[1];
        if (strlen($signature) > $modulusAsInteger) {
            return false;
        }
        
        $signatureAsInteger = 
        
        // Now lets hash the message using the JWA
        $hashedMessage = $jwa->hash($message);
        
        // We will add the correct padding to the hashed message
        
    }
}
