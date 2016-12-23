<?php

namespace Alvtek\OpenIdConnect;

use phpseclib\Math\BigInteger as SecLibBigInteger;
use ParagonIE\ConstantTime\Base64UrlSafe;

use JsonSerializable;

class BigInteger extends SecLibBigInteger implements JsonSerializable
{
    public static function fromBase64UrlSafe($encoded)
    {
        return new static(Base64UrlSafe::decode($encoded), 256);
    }
        
    public function jsonSerialize()
    {
        return rtrim(Base64UrlSafe::encode($this->toBytes()), '=');
    }
}