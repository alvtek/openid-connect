<?php

declare(strict_types=1);

namespace Alvtek\OpenIdConnect\BigInteger\Adapter;

use Alvtek\OpenIdConnect\BigInteger\AdapterInterface;
use Alvtek\OpenIdConnect\BigInteger\BigIntegerFactory;
use Alvtek\OpenIdConnect\BigInteger\Exception\OverflowException;
use Alvtek\OpenIdConnect\BigIntegerInterface;

class BCMathAdapter implements AdapterInterface
{
    public function toDecimal(BigIntegerInterface $a): string
    {
        $bytes = $a->toBytes();
        $twoc = false;
        
        $isNegative = ((ord($bytes[0]) & 0x80) && $twoc);
        
        if ($isNegative) {
            $bytes = ~$bytes;
        }
        
        $len = (mb_strlen($bytes, '8bit') + 3) & 0xfffffffc;
        $bytes = str_pad($bytes, $len, chr(0), STR_PAD_LEFT);
        $result = '0';
        
        for ($i = 0; $i < $len; $i += 4) {
            $result = bcmul($result, '4294967296'); // 2**32
            $result = bcadd(
                $result,
                (string) (0x1000000 * ord($bytes[$i]) + ((ord($bytes[$i + 1]) << 16) | (ord($bytes[$i + 2]) << 8) | ord($bytes[$i + 3])))
            );
        }
        
        if ($isNegative) {
            $result = bcsub('-' . $result, '1');
        }
        
        return $result;
    }

    public function toHex(BigIntegerInterface $a): string
    {
        return \implode('', \unpack('H*', $a->toBytes()));
    }
    
    /**
     * @param BigIntegerInterface $a
     * @return int
     */
    public function toInt(BigIntegerInterface $a): int
    {
        $intSize = defined('PHP_INT_SIZE') ? PHP_INT_SIZE : 4;
    
        $byteString = $a->toBytes();
    
        if (strlen($byteString) > $intSize)  {
            throw new OverflowException("number is too big to convert to an "
                . "integer type on this machine.");
        }
        
        return (int) $a->toDecimal();
    }
    
    /**
     * @param BigIntegerInterface $a
     * @param BigIntegerInterface $b
     * @return int
     */
    public function compare(BigIntegerInterface $a, BigIntegerInterface $b): int
    {
        return bccomp($a->toDecimal(), $b->toDecimal());
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
    
    /**
     * @param BigIntegerInterface $a
     * @param int $exponent
     * @param BigIntegerInterface $modulus
     * @return BigIntegerInterface
     */
    public function powerModulus(BigIntegerInterface $a, int $exponent, BigIntegerInterface $modulus): BigIntegerInterface
    {
        $result = bcpowmod($a->toDecimal(), $exponent, $modulus->toDecimal());
        return BigIntegerFactory::fromDecimal($result);
    }
}
