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
        return $this->bcbindec($a->toBytes());
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
    
    private function bchexdec(string $hex)
    {
        if(strlen($hex) == 1) {
            return hexdec($hex);
        }
        
        $remain = substr($hex, 0, -1);
        $last = substr($hex, -1);
        
        return bcadd(bcmul(16, $this->bchexdec($remain)), hexdec($last));
    }
    
    private function bcdechex(string $dec)
    {
        $last = bcmod($dec, 16);
        $remain = bcdiv(bcsub($dec, $last), 16);
        
        if($remain == 0) {
            return dechex($last);
        }
        
        return $this->bcdechex($remain).dechex($last);
    }
    
    private function bcbindec(string $bin)
    {
        if (strlen($bin) == 1) {
            return bindec($bin);
        }
        
        $remain = substr($bin, 0, -1);
        $last = substr($bin, -1);
        
        return bcadd(bcmul(256, $this->bcbindec($remain)), bindec($last));
        
    }
    
    private function bcdecbin(string $decimal)
    {
        $last = bcmod($decimal, 256);
        $remain = bcdiv(bcsub($decimal, $last), 256);
    
        if($remain == 0) {
            return decbin($last);
        }

        return $this->bcdecbin($remain) . decbin($last);
    }
    
    private function bcbinhex(string $bytes)
    {
        
    }
}
