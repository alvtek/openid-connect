<?php

declare(strict_types=1);

namespace Alvtek\OpenIdConnect;

interface JWAInterface
{
    public function sign($message, $key) : string;
    public function verify($message, $signaute, $key) : bool;
}