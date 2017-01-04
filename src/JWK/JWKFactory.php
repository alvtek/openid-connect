<?php

namespace Alvtek\OpenIdConnect\JWK;

use Alvtek\OpenIdConnect\Exception\InvalidArgumentException;
use Alvtek\OpenIdConnect\Exception\RuntimeException;
use Alvtek\OpenIdConnect\Exception\UnsupportedException;
use Alvtek\OpenIdConnect\JWK\RSA\PrivateKey\PrivateKeyBuilder;
use Alvtek\OpenIdConnect\JWK\RSA\PublicKey\PublicKeyBuilder;

class JWKFactory
{
    const TYPE_JWK = 'jwk';
    const TYPE_DATA = 'data';
    
    public static function fromJWKData($data)
    {
        if (!isset($data['kty'])) {
            throw new InvalidArgumentException("kty key must be set");
        }
        
        switch ($data['kty']) {
            case KeyType::RSA:
                if (isset($data['d'], $data['p'], $data['q'], $data['dp'], 
                    $data['dq'], $data['qi'])) {
                    return PrivateKeyBuilder::fromJWKData($data)->build();
                }
                
                if (isset($data['n'], $data['e'])) {
                    return PublicKeyBuilder::fromJWKData($data)->build();
                }
                
                throw new RuntimeException("RSA key does not appear to be valid");
                
            default:
                throw new UnsupportedException("Key type was not recognised or is "
                    . "not currently supported");
        }
    }
}
