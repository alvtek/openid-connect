<?php

namespace Alvtek\OpenIdConnect;

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