<?php

namespace Alvtek\OpenIdConnect;

interface JWAInterface
{
    public function hash(string $data) : string;
    public function asn1DigestAlgorithm() : string;
}