<?php

namespace Alvtek\OpenIdConnect\BigInteger;

use Alvtek\OpenIdConnect\BigIntegerInterface;

interface AdapterInterface
{
    public function toHex(BigIntegerInterface $a) : string;
    public function toDecimal(BigIntegerInterface $a) : string;
    
    public function decimalToBytes(string $decimal) : string;
    public function hexToBytes(string $hex) : string;
    public function integerToBytes(int $integer) : string;
    
    public function add(BigIntegerInterface $a, BigIntegerInterface $b) : BigIntegerInterface;
    public function subtract(BigIntegerInterface $a, BigIntegerInterface $b) : BigIntegerInterface;
    public function multiply(BigIntegerInterface $a, BigIntegerInterface $b) : BigIntegerInterface;
    public function divide(BigIntegerInterface $a, BigIntegerInterface $b) : BigIntegerInterface;
    public function modulus(BigIntegerInterface $a, BigIntegerInterface $b) : BigIntegerInterface;
    public function power(BigIntegerInterface $a, BigIntegerInterface $exponent) : BigIntegerInterface;
}
