<?php

namespace Alvtek\OpenIdConnect;

interface JWAInterface
{
    public function hash($data) : string;
}