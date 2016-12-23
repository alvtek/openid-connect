<?php

namespace Alvtek\OpenIdConnect\JWA;

use Alvtek\OpenIdConnect\JWAInterface;

abstract class RSA implements JWAInterface
{
    abstract protected function getAlg() : string;

    public function sign($message, $key) : string
    {
        $signature = null;

        openssl_sign(
            $message,
            $signature,
            $key,
            $this->getAlg()
        );

        return $signature;
    }

    public function verify($message, $signature, $key) : bool
    {
        return (bool) openssl_verify(
            $message,
            $signature,
            $key,
            $this->getAlg()
        );
    }
}
