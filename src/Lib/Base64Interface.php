<?php

namespace Alvtek\OpenIdConnect\Lib;

/**
 * Interface Base64Interface is an interface for a Json encoder/decoder
 *
 * @package Alvtek\OpenIdConnect
 */
interface Base64Interface
{
    public static function encode($data);
    public static function decode(string $jsonData);
}
