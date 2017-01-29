<?php

namespace Alvtek\OpenIdConnect;

interface JWAInterface
{
    public function getAlgorithmName() : string;
    public function hash(string $data) : string;
}