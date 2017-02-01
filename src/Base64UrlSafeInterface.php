<?php

namespace Alvtek\OpenIdConnect;

/**
 * Interface Base64UrlSafeInterface is an interface for a Base64 encoder/decoder
 */
interface Base64UrlSafeInterface
{
    public function encode(string $data) : string;
    public function decode(string $data) : string;
}
