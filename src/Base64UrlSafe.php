<?php

namespace Alvtek\OpenIdConnect;

class Base64UrlSafe implements Base64UrlSafeInterface
{
    public static function encode(string $data) : string
    {
        return base64_encode($data);
    }

    public static function decode(string $data) : string
    {
        return base64_decode(strtr($data, '-_', '+/'));
    }
}
