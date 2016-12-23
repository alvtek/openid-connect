<?php

namespace Alvtek\OpenIdConnect;

interface JWAInterface
{
    public function sign($message, $key);
    public function verify($message, $signaute, $key);
}