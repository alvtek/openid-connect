<?php

namespace Alvtek\OpenIdConnect\JWK;

use Alvtek\OpenIdConnect\JWK\RSA\PublicKey\PublicKeyBuilder;
use Alvtek\OpenIdConnect\JWK\RSA\PrivateKey\PrivateKeyBuilder;
use Alvtek\OpenIdConnect\JWK\KeyType;
use Alvtek\OpenIdConnect\JWK\Usage;

use Alvtek\OpenIdConnect\Exception\InvalidArgumentException;
use Alvtek\OpenIdConnect\Exception\RuntimeException;

class JWKFactory
{
    const TYPE_JWK = 'jwk';
    const TYPE_DATA = 'data';
    
    private static $methods = [
        self::TYPE_DATA => 'fromArray',
        self::TYPE_JWK => 'fromJWKData',
    ];

    public static function create($data, $type = self::TYPE_JWK)
    {
        if (!in_array($type, [static::TYPE_JWK, static::TYPE_DATA])) {
            throw new InvalidArgumentException("unknown type argument $type");
        }
        
        if (!isset($data['kty'])) {
            throw new InvalidArgumentException("kty key must be set");
        }
        
        if (!isset($data['use'])) {
            throw new InvalidArgumentException("use key must be set");
        }
        
        switch ($data['kty']) {
            case KeyType::RSA:
                switch ($data['use']) {
                    case Usage::ENCRYPTION:
                        return PrivateKeyBuilder::{static::$methods[$type]}($data)->build();
                    case Usage::SIGNATURE:
                        return PublicKeyBuilder::{static::$methods[$type]}($data)->build();
                }
            default:
                throw new RuntimeException("Key type was not recognised or is "
                    . "not supported");
        }
    }
}