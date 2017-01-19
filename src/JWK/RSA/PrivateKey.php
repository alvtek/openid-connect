<?php

namespace Alvtek\OpenIdConnect\JWK\RSA;

use Alvtek\OpenIdConnect\JWAInterface;
use Alvtek\OpenIdConnect\JWK;
use Alvtek\OpenIdConnect\JWK\RSA\Prime;
use Alvtek\OpenIdConnect\JWK\RSA\PrivateKey\PrivateKeyBuilder;
use Alvtek\OpenIdConnect\JWK\SigningInterface;
use Alvtek\OpenIdConnect\Lib\BigIntegerInterface;

final class PrivateKey extends JWK implements SigningInterface
{
    /** @var BigIntegerInterface */
    private $n;

    /** @var BigIntegerInterface */
    private $e;
    
    /** @var BigIntegerInterface */
    private $d;

    /** @var BigIntegerInterface */
    private $p;

    /** @var BigIntegerInterface */
    private $q;

    /** @var BigIntegerInterface */
    private $dp;

    /** @var BigIntegerInterface */
    private $dq;

    /** @var BigIntegerInterface */
    private $qi;

    /** @var Prime[] */
    private $otherPrimes;

    public function __construct(PrivateKeyBuilder $privateKeyBuilder)
    {
        parent::__construct($privateKeyBuilder);

        $this->otherPrimes = [];

        $this->n    = $privateKeyBuilder->n;
        $this->e    = $privateKeyBuilder->e;
        $this->d    = $privateKeyBuilder->d;
        $this->p    = $privateKeyBuilder->p;
        $this->q    = $privateKeyBuilder->q;
        $this->dp   = $privateKeyBuilder->dp;
        $this->dq   = $privateKeyBuilder->dq;
        $this->qi   = $privateKeyBuilder->qi;

        foreach ($privateKeyBuilder->otherPrimes as $prime) {
            $this->otherPrimes[] = $prime;
        }
    }

    public function sign(JWAInterface $jwa, $message) : string
    {
        return $jwa->sign($message, $this->toPem());
    }

    /**
     * @return BigInteger[]
     */
    private function getPrimes() : array
    {
        $primes = [
            1 => $this->p,
            2 => $this->q,
        ];
        
        $i = 3;
        foreach ($this->otherPrimes as $prime) {
            $primes[$i] = $prime->factor();
            $i++;
        }
        
        return $primes;
    }

    private function getExponents() : array
    {
        $exponents = [
            1 => $this->dp,
            2 => $this->dq,
        ];
        
        $i = 3;
        foreach ($this->otherPrimes as $prime) {
            $exponents[$i] = $prime->exponent();
            $i++;
        }
        
        return $exponents;
    }

    private function getCoefficients() : array
    {
        $coefficients = [
            2 => $this->qi,
        ];
        
        $i = 3;
        foreach ($this->otherPrimes as $prime) {
            $coefficients[$i] = $prime->coefficient();
            $i++;
        }
        
        return $coefficients;
    }
}
