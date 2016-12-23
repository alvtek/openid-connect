<?php

namespace Alvtek\OpenIdConnect\JWK;

use Alvtek\OpenIdConnect\JWAInterface;
use Alvtek\OpenIdConnect\JWK as BaseJWK;
use Alvtek\OpenIdConnect\JWK\VerificationInterface;
use Alvtek\OpenIdConnect\JWK\RSA\PublicKey\PublicKeyBuilder;
use Alvtek\OpenIdConnect\BigInteger;
use phpseclib\Crypt\RSA as phpseclibRSA;

use Assert\Assert;

use JsonSerializable;

abstract class RSA extends BaseJWK implements VerificationInterface, JsonSerializable
{
    /** @var BigInteger */
    protected $n;

    /** @var BigInteger */
    protected $e;
    
    /** @var phpseclibRSA */
    protected $rsaToolkit;

    /**
     * @param PublicKeyBuilder $publicKeyBuilder
     */
    public function __construct(PublicKeyBuilder $publicKeyBuilder)
    {
        parent::__construct($publicKeyBuilder);
        
        $this->n = clone $publicKeyBuilder->n;
        $this->e = clone $publicKeyBuilder->e;
        $this->rsaToolkit = $publicKeyBuilder->rsaToolkit;
    }
    
    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $output = parent::jsonSerialize();
        $output['n'] = $this->n->jsonSerialize();
        $output['e'] = $this->e->jsonSerialize();
        
        return $output;
    }

    /**
     * @param JWAInterface $jwa
     * @param string $message
     * @param string $signature
     * @return boolean
     */
    public function verify(JWAInterface $jwa, $message, $signature)
    {
        Assert::that($message)->notEmpty()->string();
        Assert::that($signature)->notEmpty()->string();

        return $jwa->verify($message, $signature, $this->toPem());
    }

    /**
     * @return string
     */
    abstract protected function toPem();
}