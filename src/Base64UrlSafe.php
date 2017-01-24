<?php

namespace Alvtek\OpenIdConnect;

class Base64UrlSafe implements Base64UrlSafeInterface
{
    public static function encode($data) : string
    {
        return json_encode($data);
    }

    public static function decode(string $jsonData) : array
    {
        return json_decode($jsonData);
    }
}
