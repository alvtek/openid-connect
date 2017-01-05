<?php

namespace Alvtek\OpenIdConnect;

interface BigIntegerInterface
{
    public static function fromBase64UrlSafe(string $encoded) : BigIntegerInterface;
}
