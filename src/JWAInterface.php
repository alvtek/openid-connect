<?php

namespace Alvtek\OpenIdConnect;

interface JWAInterface
{
    public function hash($message) : string;
}