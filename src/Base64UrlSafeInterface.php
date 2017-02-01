<?php

namespace Alvtek\OpenIdConnect;

use Alvtek\OpenIdConnect\Base64UrlSafe\InvalidBase64Exception;

/**
 * Interface Base64UrlSafeInterface is an interface for a Base64 encoder/decoder
 */
interface Base64UrlSafeInterface
{
    public function encode(string $data) : string;
    
    /**
     * @param string $data
     * @return string
     * @throws InvalidBase64Exception
     */
    public function decode(string $data) : string;
}
