<?php

namespace Alvtek\OpenIdConnect;

interface JWAInterface
{
    public function hash(string $data) : string;
}