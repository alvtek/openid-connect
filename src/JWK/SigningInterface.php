<?php

namespace Alvtek\OpenIdConnect\JWK;

use Alvtek\OpenIdConnect\JWAInterface;

interface SigningInterface
{
    /**
     * @param JWAInterface $jwa
     * @param string $message
     */
    public function sign(JWAInterface $jwa, $message);
}