<?php

namespace Alvtek\OpenIdConnect\Lib;

class Base64 implements Base64Interface
{
    public static function encode($data)
    {
        return json_encode($data);
    }

    public static function decode(string $jsonData)
    {
        return json_decode($jsonData);
    }
}
