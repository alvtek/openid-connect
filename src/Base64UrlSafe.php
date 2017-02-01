<?php

namespace Alvtek\OpenIdConnect;

use Alvtek\OpenIdConnect\Base64UrlSafe\InvalidBase64Exception;

class Base64UrlSafe implements Base64UrlSafeInterface
{
    public function encode(string $data) : string
    {
        $encoded = base64_encode($data);
        
        return strtr($encoded, '+/', '-_');
    }

    public function decode(string $data) : string
    {
        $decoded = base64_decode(strtr($data, '-_', '+/'));
        
        if ($decoded === false) {
            throw new InvalidBase64Exception("The data to encode is not a valid base64 string");
        }
        
        return $decoded;
    }
}
