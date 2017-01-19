<?php

namespace Alvtek\OpenIdConnect\Lib;

/**
 * Interface BigIntegerInterface is an interface for a BigInteger value object implementation.
 *
 * @package Alvtek\OpenIdConnect
 */
interface BigIntegerInterface
{
    public static function fromBase64UrlSafe(string $encoded) : BigIntegerInterface;
    public static function fromHex(string $hex) : BigIntegerInterface;
    public static function fromString(string $number) : BigIntegerInterface;
    public static function fromInteger(int $integer) : BigIntegerInterface;
    public function __toString();
}
