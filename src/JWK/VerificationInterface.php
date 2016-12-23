<?php

namespace Alvtek\OpenIdConnect\JWK;

use Alvtek\OpenIdConnect\JWAInterface;

interface VerificationInterface
{
    /**
     * 
     * @param JWAInterface $jwa
     * @param string $message
     * @param string $signature
     * @return boolean
     */
    public function verify(JWAInterface $jwa, $message, $signature);
}