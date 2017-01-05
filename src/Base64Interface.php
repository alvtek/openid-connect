<?php

namespace Alvtek\OpenIdConnect;

interface Base64Interface
{
    public static function encode($string);
    public static function decode(string $string);
}
