<?php

namespace Alvtek\OpenIdConnect\JWK\RSA;

use Alvtek\OpenIdConnect\BigInteger;

class Prime
{
    /** @var BigInteger */
    private $r;

    /** @var BigInteger */
    private $d;

    /** @var BigInteger */
    private $t;

    public function __construct(BigInteger $r, BigInteger $d, BigInteger $t)
    {
        $this->r = clone $r;
        $this->d = clone $d;
        $this->t = clone $t;
    }

    /**
     * 
     * @return BigInteger
     */
    public function factor()
    {
        return new BigInteger($this->r);
    }
    
    /**
     * 
     * @return BigInteger
     */
    public function exponent()
    {
        return new BigInteger($this->d);
    }
    
    /**
     * 
     * @return BigInteger
     */
    public function coefficient()
    {
        return new BigInteger($this->t);
    }
}