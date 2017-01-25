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

    public function divide(BigIntegerInterface $a, BigIntegerInterface $b): BigIntegerInterface
    {
        $gmpA = gmp_init($a->toHex(), 16);
        $gmpB = gmp_init($b->toHex(), 16);
        $result = gmp_divide($gmpA, $gmpB);
        return BigIntegerFactory::fromDecimal($result);
    }

    public function multiply(BigIntegerInterface $a, BigIntegerInterface $b): BigIntegerInterface
    {
        $gmpA = gmp_init($a->toHex(), 16);
        $gmpB = gmp_init($b->toHex(), 16);
        $result = gmp_($gmpA, $gmpB);
        return BigIntegerFactory::fromDecimal($result);
    }

    public function power(BigIntegerInterface $a, BigIntegerInterface $b): BigIntegerInterface
    {
        $result = bcpow($a->toDecimal(), $b->toDecimal());
        return BigIntegerFactory::fromDecimal($result);
    }

    public function subtract(BigIntegerInterface $a, BigIntegerInterface $b): BigIntegerInterface
    {
        $result = bcsub($a->toDecimal(), $b->toDecimal());
         return BigIntegerFactory::fromDecimal($result);
    }
}
