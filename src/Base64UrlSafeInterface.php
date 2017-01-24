<?php

namespace Alvtek\OpenIdConnect;

/**
 * Interface Base64UrlSafeInterface is an interface for a Json encoder/decoder
 */
interface Base64UrlSafeInterface
{
    public static function encode($data) : string;
    public static function decode(string $jsonData) : array;
}
