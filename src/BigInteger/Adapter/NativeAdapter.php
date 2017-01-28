<?php

namespace Alvtek\OpenIdConnect\BigInteger\Adapter;

use Alvtek\OpenIdConnect\BigInteger\AdapterInterface;
use Alvtek\OpenIdConnect\BigIntegerInterface;
use Alvtek\OpenIdConnect\BigInteger\BigIntegerFactory;

class NativeAdapter implements AdapterInterface
{
    public function toDecimal(BigIntegerInterface $a) : string
    {
        
    }
    
    public function toHex(BigIntegerInterface $a) : string
    {
        return unpack('H*', $a->toBytes());
    }
    
    public function decimalToBytes(string $decimal): string
    {
        
    }
    
    public function hexToBytes(string $hex): string
    {
        
    }
    
    public function integerToBytes(int $integer): string
    {
        $phpIntSize = (defined('PHP_INT_SIZE')) ? PHP_INT_SIZE : 4;
    }
    
    public function add(BigIntegerInterface $a, BigIntegerInterface $b): BigIntegerInterface
    {
        
    }

    public function divide(BigIntegerInterface $a, BigIntegerInterface $b): BigIntegerInterface
    {
        
    }

    public function modulus(BigIntegerInterface $a, BigIntegerInterface $b): BigIntegerInterface
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
