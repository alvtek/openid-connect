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
        
        $components = array(
            'modulus' => pack('Ca*a*', self::ASN1_INTEGER, $this->_encodeLength(strlen($modulus)), $modulus),
            'publicExponent' => pack('Ca*a*', self::ASN1_INTEGER, $this->_encodeLength(strlen($publicExponent)), $publicExponent)
        );

        $RSAPublicKey = pack(
            'Ca*a*a*',
            self::ASN1_SEQUENCE,
            $this->_encodeLength(strlen($components['modulus']) + strlen($components['publicExponent'])),
            $components['modulus'],
            $components['publicExponent']
        );

        // sequence(oid(1.2.840.113549.1.1.1), null)) = rsaEncryption.
        $rsaOID = pack('H*', '300d06092a864886f70d0101010500'); // hex version of MA0GCSqGSIb3DQEBAQUA
        $RSAPublicKey = chr(0) . $RSAPublicKey;
        $RSAPublicKey = chr(3) . $this->_encodeLength(strlen($RSAPublicKey)) . $RSAPublicKey;

        $RSAPublicKey = pack(
            'Ca*a*',
            self::ASN1_SEQUENCE,
            $this->_encodeLength(strlen($rsaOID . $RSAPublicKey)),
            $rsaOID . $RSAPublicKey
        );

        $RSAPublicKey = "-----BEGIN PUBLIC KEY-----\r\n" .
                         chunk_split(base64_encode($RSAPublicKey), 64) .
                         '-----END PUBLIC KEY-----';
        
        return $RSAPublicKey;
    }
}
