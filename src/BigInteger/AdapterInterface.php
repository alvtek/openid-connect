<?php

namespace Alvtek\OpenIdConnect\BigInteger;

use Alvtek\OpenIdConnect\BigIntegerInterface;

interface AdapterInterface
{
    public function add(BigIntegerInterface $a, BigIntegerInterface $b) : BigIntegerInterface;
    public function subtract(BigIntegerInterface $a, BigIntegerInterface $b) : BigIntegerInterface;
    public function multiply(BigIntegerInterface $a, BigIntegerInterface $b) : BigIntegerInterface;
    public function divide(BigIntegerInterface $a, BigIntegerInterface $b) : BigIntegerInterface;
    public function power(BigIntegerInterface $a, BigIntegerInterface $exponent) : BigIntegerInterface;
}
