<?php

namespace Alvtek\OpenIdConnect;

/**
 * Interface BigIntegerInterface is an interface for a BigInteger value object 
 * implementation.
 */
interface BigIntegerInterface
{
    public function toDecimal() : string;
    public function toBytes() : string;
    public function toHex() : string;
    public function toInt() : int;
    
    public function add(BigIntegerInterface $number) : BigIntegerInterface;
    public function subtract(BigIntegerInterface $number) : BigIntegerInterface;
    public function multiply(BigIntegerInterface $number) : BigIntegerInterface;
    public function divide(BigIntegerInterface $divisor) : BigIntegerInterface;
    public function modulus(BigIntegerInterface $divisor) : BigIntegerInterface;
    public function power(int $exponent) : BigIntegerInterface; 
    public function compare(BigIntegerInterface $number) : int;
    public function powerModulus(int $exponent, BigIntegerInterface $modulus) : BigIntegerInterface;
}
