<?php

namespace Alvtek\OpenIdConnect;

/**
 * Interface Base64UrlSafeInterface is an interface for a Base64 encoder/decoder
 */
interface Base64UrlSafeInterface
{
    public static function encode(string $data) : string;
    public static function decode(string $data) : string;
}
