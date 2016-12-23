<?php

namespace Alvtek\OpenIdConnect;

use Alvtek\OpenIdConnect\JWA\Exception\UnsupportedAlgorithmException;

use Assert\Assert;

abstract class JWA
{
    const NONE = 'none'; // No digital signature or MAC
    const HS256 = 'HS256'; // HMAC using SHA-256
    const HS384 = 'HS384'; // HMAC using SHA-384
    const HS512 = 'HS512'; // HMAC using SHA-512
    const RS256 = 'RS256'; // RSASSA-PKCS1-v1_5 using SHA-256
    const RS384 = 'RS384'; // RSASSA-PKCS1-v1_5 using SHA-384
    const RS512 = 'RS512'; // RSASSA-PKCS1-v1_5 using SHA-512
    const ES256 = 'ES256'; // ECDSA using P-256 and SHA-256c
    const ES384 = 'ES384'; // ECDSA using P-384 and SHA-384
    const ES512 = 'ES512'; // ECDSA using P-521 and SHA-512
    const PS256 = 'PS256'; // RSASSA-PSS using SHA-256 and MGF1 with SHA-256
    const PS384 = 'PS384'; // RSASSA-PSS using SHA-384 and MGF1 with SHA-384
    const PS512 = 'PS512'; // RSASSA-PSS using SHA-512 and MGF1 with SHA-512
    

    private static $supportedAlgorithms = [
        self::NONE, self::RS256, self::RS384, self::RS512,
    ];
    
    /**
     * @param string $name
     * @return JWAInterface
     */
    public static function createFromName($name)
    {
        Assert::that($name)->notEmpty()->string();
        
        if (!in_array($name, static::$supportedAlgorithms)) {
            throw new UnsupportedAlgorithmException(sprintf("The algorithm "
                . "'%s' is not supported by this library. If it is a valid "
                . "Json Web Algorithm, please consider adding it to this "
                . "library via a PR.", $name));
        }
        
        // Exception for the none algorithm due to formatting of the class name
        if ($name === 'none') {
            return new None;
        }

        $classname = __NAMESPACE__ . '\\JWA\\' . str_replace(' ', '', strtoupper($name));

        Assert::that($classname)->classExists();

        return new $classname;
    }
}
