<?php

declare(strict_types=1);

namespace Alvtek\OpenIdConnect\JWA;

use Alvtek\OpenIdConnect\JWAInterface;

abstract class RSA implements JWAInterface
{
    /**
     * @var string
     */
    protected $alg;
    
    public function sign(string $message, string $key) : string
    {
        $signature = null;

        \openssl_sign(
            $message,
            $signature,
            $key,
            $this->alg
        );

        return $signature;
    }

    public function verify(string $message, string $signature, string $key) : bool
    {
        return (bool) \openssl_verify(
            $message,
            $signature,
            $key,
            $this->alg
        );
    }
}
