<?php

declare(strict_types=1);

namespace Alvtek\OpenIdConnect\BigInteger\Adapter;

use Alvtek\OpenIdConnect\BigInteger\AdapterInterface;
use Alvtek\OpenIdConnect\BigIntegerInterface;
use Alvtek\OpenIdConnect\BigInteger\BigIntegerFactory;

class BCMathAdapter implements AdapterInterface
{
    public function toDecimal(BigIntegerInterface $a): string
    {
        
    }

    public function toHex(BigIntegerInterface $a): string
    {
        
    }
    
    /**
     * @param BigIntegerInterface $a
     * @return int
     */
    public function toInt(BigIntegerInterface $a): int
    {
        // TODO: Implement toInt() method.
    }
    
    /**
     * @param BigIntegerInterface $a
     * @param BigIntegerInterface $b
     * @return int
     */
    public function compare(BigIntegerInterface $a, BigIntegerInterface $b): int
    {
        // TODO: Implement compare() method.
    }
    
    /**
     * @param BigIntegerInterface $a
     * @param int $exponent
     * @param BigIntegerInterface $modulus
     * @return BigIntegerInterface
     */
    public function powerModulus(BigIntegerInterface $a, int $exponent, BigIntegerInterface $modulus): BigIntegerInterface
    {
        // TODO: Implement powerModulus() method.
    }
    
    public function decimalToBytes(string $decimal): string
    {
        
    }

    public function hexToBytes(string $hex): string
    {
        
    }

    public function integerToBytes(int $integer): string
    {
        
    }
    
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
    
    public function modulus(BigIntegerInterface $a, BigIntegerInterface $b) : BigIntegerInterface
    {
        $result = bcmod($a->toDecimal(), $b->toDecimal());
        return BigIntegerFactory::fromDecimal($result);
    }

    public function power(BigIntegerInterface $a, int $b): BigIntegerInterface
    {
        $result = bcpow($a->toDecimal(), $b->toDecimal());
        return BigIntegerFactory::fromDecimal($result);
    }
}
