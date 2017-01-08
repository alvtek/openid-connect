<?php

declare(strict_types=1);

namespace Alvtek\OpenIdConnect;

interface JWAInterface
{
    public function sign(string $message, string $key) : string;
    public function verify(string $message, string $signaute, string $key) : bool;
}