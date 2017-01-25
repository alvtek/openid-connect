<?php

namespace Alvtek\OpenIdConnect\BigInteger\Adapter;

use Alvtek\OpenIdConnect\BigInteger\AdapterInterface;
use Alvtek\OpenIdConnect\BigIntegerInterface;
use Alvtek\OpenIdConnect\BigInteger\BigIntegerFactory;

class BCMathAdapter implements AdapterInterface
{
    public function add(BigIntegerInterface $a, BigIntegerInterface $b): BigIntegerInterface
    {
        $result = bcadd($a->toDecimal(), $b->toDecimal());
        return BigIntegerFactory::fromDecimal($result);
    }
    
    public function subtract(BigIntegerInterface $a, BigIntegerInterface $b): BigIntegerInterface
    {
        $result = bcsub($a->toDecimal(), $b->toDecimal());
         return BigIntegerFactory::fromDecimal($result);
    }
    
    public function multiply(BigIntegerInterface $a, BigIntegerInterface $b): BigIntegerInterface
    {
        $result = bcmul($a->toDecimal(), $b->toDecimal());
        return BigIntegerFactory::fromDecimal($result);
    }

    public function divide(BigIntegerInterface $a, BigIntegerInterface $b): BigIntegerInterface
    {
        $result = bcadd($a->toDecimal(), $b->toDecimal());
        return BigIntegerFactory::fromDecimal($result);
    }
    
    public function modulus(BigIntegerInterface $a, BigIntegerInterface $b)
    {
        $result = bcmod($a->toDecimal(), $b->toDecimal());
        return BigIntegerFactory::fromDecimal($result);
    }

    public function power(BigIntegerInterface $a, BigIntegerInterface $b): BigIntegerInterface
    {
        $result = bcpow($a->toDecimal(), $b->toDecimal());
        return BigIntegerFactory::fromDecimal($result);
    }
}
