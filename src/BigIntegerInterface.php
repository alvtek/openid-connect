<?php

namespace Alvtek\OpenIdConnect;

/**
 * Interface BigIntegerInterface is an interface for a BigInteger value object 
 * implementation.
 */
interface BigIntegerInterface
{
    public function __toString();
    public function toBytes() : string;
    public function toHex() : string;
    public function add(BigIntegerInterface $number) : BigIntegerInterface;
    public function subtract(BigIntegerInterface $number) : BigIntegerInterface;
    public function multiply(BigIntegerInterface $number) : BigIntegerInterface;
    public function divide(BigIntegerInterface $divisor) : BigIntegerInterface;
    public function power(BigIntegerInterface $exponent) : BigIntegerInterface;
}
