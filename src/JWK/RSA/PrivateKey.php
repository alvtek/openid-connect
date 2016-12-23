<?php

namespace Alvtek\OpenIdConnect\JWK\RSA;

use Alvtek\OpenIdConnect\JWK\RSA as RSAKey;
use Alvtek\OpenIdConnect\JWK\RSA\Prime;
use Alvtek\OpenIdConnect\JWK\RSA\PrivateKey\PrivateKeyBuilder;
use Alvtek\OpenIdConnect\JWK\SigningInterface;
use Alvtek\OpenIdConnect\JWAInterface;

use phpseclib\Crypt\RSA as phpseclibRSA;
use phpseclib\Math\BigInteger;

/**
 * PKCS1 representation of an RSA private key. This class borrows heavily from
 * an implementation by phpseclib. See license for more details.
 */
final class PrivateKey extends RSAKey implements SigningInterface
{
    /** @var BigInteger */
    private $d;

    /** @var BigInteger */
    private $p;

    /** @var BigInteger */
    private $q;

    /** @var BigInteger */
    private $dp;

    /** @var BigInteger */
    private $dq;

    /** @var BigInteger */
    private $qi;

    /** @var Prime[] */
    private $otherPrimes;

    public function __construct(PrivateKeyBuilder $privateKeyBuilder)
    {
        parent::__construct($privateKeyBuilder);

        $this->otherPrimes = [];

        $this->n    = clone $privateKeyBuilder->n;
        $this->e    = clone $privateKeyBuilder->e;
        $this->d    = clone $privateKeyBuilder->d;
        $this->p    = clone $privateKeyBuilder->p;
        $this->q    = clone $privateKeyBuilder->q;
        $this->dp   = clone $privateKeyBuilder->dp;
        $this->dq   = clone $privateKeyBuilder->dq;
        $this->qi   = clone $privateKeyBuilder->qi;

        foreach ($privateKeyBuilder->otherPrimes as $prime) {
            $this->otherPrimes[] = clone $prime;
        }
    }

    public function sign(JWAInterface $jwa, $message) : string
    {
        return $jwa->sign($message, $this->toPem());
    }

    /**
     * @return string
     */
    protected function toPem() : string
    {
        $this->rsaToolkit->modulus = $this->n;
        $this->rsaToolkit->publicExponent = $this->e;
        $this->rsaToolkit->exponent = $this->d;
        $this->rsaToolkit->primes = $this->getPrimes();
        $this->rsaToolkit->exponents = $this->getExponents();
        $this->rsaToolkit->coefficients = $this->getCoefficients();
        
        return $this->rsaToolkit->getPrivateKey(phpseclibRSA::PUBLIC_FORMAT_PKCS1);
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
