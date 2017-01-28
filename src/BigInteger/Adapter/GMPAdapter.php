<?php

namespace Alvtek\OpenIdConnect\BigInteger\Adapter;

use Alvtek\OpenIdConnect\BigInteger\AdapterInterface;
use Alvtek\OpenIdConnect\BigIntegerInterface;
use Alvtek\OpenIdConnect\BigInteger\BigIntegerFactory;
use Alvtek\OpenIdConnect\BigInteger\Exception\OverflowException;

class GMPAdapter implements AdapterInterface
{
    public function toDecimal(BigIntegerInterface $a): string
    {
        return gmp_strval(gmp_import($a->toBytes()), 10);
    }

    public function toHex(BigIntegerInterface $a): string
    {
        return gmp_strval(gmp_import($a->toBytes()), 16);
    }
    
    public function toInt(BigIntegerInterface $a): int
    {
        $intSize = defined('PHP_INT_SIZE') ? PHP_INT_SIZE : 4;
        
        $byteString = $a->toBytes();
        
        if (strlen($byteString) > $intSize)  {
            throw new OverflowException("number is too big to convert to an "
                . "integer type on this machine.");
        }
        
        return gmp_intval(gmp_import($byteString));
    }
    
    public function decimalToBytes(string $decimal): string
    {
        $gmpNumber = gmp_init($decimal, 10);
        return gmp_export($gmpNumber);
    }

    public function hexToBytes(string $hex): string
    {
        $gmpNumber = gmp_init($hex, 16);
        return gmp_export($gmpNumber);
    }

    public function integerToBytes(int $integer): string
    {
        return gmp_export($integer);
    }
    
    public function add(BigIntegerInterface $a, BigIntegerInterface $b): BigIntegerInterface
    {
        $gmpA = gmp_import($a->toBytes());
        $gmpB = gmp_import($b->toBytes());
        $result = gmp_add($gmpA, $gmpB);
        return BigIntegerFactory::fromBytes(gmp_export($result));
    }
    
    public function subtract(BigIntegerInterface $a, BigIntegerInterface $b): BigIntegerInterface
    {
        $gmpA = gmp_import($a->toBytes());
        $gmpB = gmp_import($b->toBytes());
        $result = gmp_sub($gmpA, $gmpB);
        return BigIntegerFactory::fromBytes(gmp_export($result));
    }
    
    public function multiply(BigIntegerInterface $a, BigIntegerInterface $b): BigIntegerInterface
    {
        $gmpA = gmp_import($a->toBytes());
        $gmpB = gmp_import($b->toBytes());
        $result = gmp_mul($gmpA, $gmpB);
        return BigIntegerFactory::fromBytes(gmp_export($result));
    }

    public function divide(BigIntegerInterface $a, BigIntegerInterface $b): BigIntegerInterface
    {
        $gmpA = gmp_import($a->toBytes());
        $gmpB = gmp_import($b->toBytes());
        $result = gmp_div_q($gmpA, $gmpB);
        return BigIntegerFactory::fromBytes(gmp_export($result));
    }
    
    public function modulus(BigIntegerInterface $a, BigIntegerInterface $b) : BigIntegerInterface
    {
        $gmpA = gmp_import($a->toBytes());
        $gmpB = gmp_import($b->toBytes());
        $result = gmp_mod($gmpA, $gmpB);
        return BigIntegerFactory::fromBytes(gmp_export($result));
    }

    public function power(BigIntegerInterface $a, int $b): BigIntegerInterface
    {
        $gmpA = gmp_import($a->toBytes());
        $result = gmp_pow($gmpA, $b);
        return BigIntegerFactory::fromBytes(gmp_export($result));
    }
    
    public function compare(BigIntegerInterface $a, BigIntegerInterface $b) : int
    {
        $gmpA = gmp_import($a->toBytes());
        $gmpB = gmp_import($b->toBytes());
        
        return gmp_cmp($gmpA, $gmpB);
    }

    public function powerModulus(BigIntegerInterface $a, int $exponent, BigIntegerInterface $modulus): BigIntegerInterface
    {
        $aBytes = gmp_import($a->toBytes());
        $modulusBytes = gmp_import($modulus->toBytes());
        
        return BigIntegerFactory::fromBytes(gmp_export(gmp_powm($aBytes, $exponent, $modulusBytes)));
    }
}
