<?php

namespace Alvtek\OpenIdConnect\BigInteger\Adapter;

use Alvtek\OpenIdConnect\BigInteger\AdapterInterface;
use Alvtek\OpenIdConnect\BigIntegerInterface;

class BCMathAdapter implements AdapterInterface
{
    public function add(BigIntegerInterface $a, BigIntegerInterface $b): BigIntegerInterface
    {
        $result = bcadd($a, $b);
    }

    public function divide(BigIntegerInterface $a, BigIntegerInterface $b): BigIntegerInterface
    {
        
    }

    public function multiply(BigIntegerInterface $a, BigIntegerInterface $b): BigIntegerInterface
    {
        
    }

    public function power(BigIntegerInterface $a, BigIntegerInterface $exponent): BigIntegerInterface
    {
        
    }

    public function subtract(BigIntegerInterface $a, BigIntegerInterface $b): BigIntegerInterface
    {
        
    }
}
