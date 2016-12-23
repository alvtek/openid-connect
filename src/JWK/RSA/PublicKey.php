<?php

namespace Alvtek\OpenIdConnect\JWK\RSA;

use Alvtek\OpenIdConnect\JWK\RSA as RSAKey;
use Alvtek\OpenIdConnect\JWK\RSA\PublicKey\PublicKeyBuilder;
use phpseclib\Crypt\RSA as phpseclibRSA;

final class PublicKey extends RSAKey
{
    public function __construct(PublicKeyBuilder $pubicKeyBuilder)
    {
        parent::__construct($pubicKeyBuilder);
    }

    protected function toPem()
    {
        $this->rsaToolkit->modulus = $this->n;
        $this->rsaToolkit->publicExponent = $this->e;
        
        // PKCS8 is the format recognised by openssl_pkey_get_public
        return $this->rsaToolkit->getPublicKey(phpseclibRSA::PUBLIC_FORMAT_PKCS8);
    }
}
