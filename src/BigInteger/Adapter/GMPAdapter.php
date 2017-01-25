<?php

namespace Alvtek\OpenIdConnect\BigInteger\Adapter;

use Alvtek\OpenIdConnect\BigInteger\AdapterInterface;
use Alvtek\OpenIdConnect\BigIntegerInterface;
use Alvtek\OpenIdConnect\BigInteger\BigIntegerFactory;

class GMPAdapter implements AdapterInterface
{
    public function add(BigIntegerInterface $a, BigIntegerInterface $b): BigIntegerInterface
    {
        $gmpA = gmp_init($a->toHex(), 16);
        $gmpB = gmp_init($b->toHex(), 16);
        $result = gmp_add($gmpA, $gmpB);
        return BigIntegerFactory::fromDecimal($result);
    }
    
    public function subtract(BigIntegerInterface $a, BigIntegerInterface $b): BigIntegerInterface
    {
        $gmpA = gmp_init($a->toHex(), 16);
        $gmpB = gmp_init($b->toHex(), 16);
        $result = gmp_sub($gmpA, $gmpB);
        return BigIntegerFactory::fromDecimal($result);
    }
    
    public function multiply(BigIntegerInterface $a, BigIntegerInterface $b): BigIntegerInterface
    {
        $gmpA = gmp_init($a->toHex(), 16);
        $gmpB = gmp_init($b->toHex(), 16);
        $result = gmp_mul($gmpA, $gmpB);
        return BigIntegerFactory::fromDecimal($result);
    }

    public function divide(BigIntegerInterface $a, BigIntegerInterface $b): BigIntegerInterface
    {
        $gmpA = gmp_init($a->toHex(), 16);
        $gmpB = gmp_init($b->toHex(), 16);
        $result = gmp_div_q($gmpA, $gmpB);
        return BigIntegerFactory::fromDecimal($result);
    }
    
    public function modulus(BigIntegerInterface $a, BigIntegerInterface $b)
    {
        $gmpA = gmp_init($a->toHex(), 16);
        $gmpB = gmp_init($b->toHex(), 16);
        $result = gmp_mod($gmpA, $gmpB);
        return BigIntegerFactory::fromDecimal($result);
    }

    public function power(BigIntegerInterface $a, BigIntegerInterface $b): BigIntegerInterface
    {
        $gmpA = gmp_init($a->toHex(), 16);
        $gmpB = gmp_init($b->toHex(), 16);
        $result = gmp_pow($gmpA, $gmpB);
        return BigIntegerFactory::fromDecimal($result);
    }
}
