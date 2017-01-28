<?php

namespace Alvtek\OpenIdConnect\BigInteger;

use Alvtek\OpenIdConnect\BigInteger\Adapter\BCMathAdapter;
use Alvtek\OpenIdConnect\BigInteger\Adapter\GMPAdapter;
use Alvtek\OpenIdConnect\BigInteger\Adapter\NativeAdapter;
use Alvtek\OpenIdConnect\BigInteger;
use Alvtek\OpenIdConnect\BigIntegerInterface;

class BigIntegerFactory
{   
    public static function fromBytes(string $bytes) : BigIntegerInterface
    {
        return new BigInteger(static::getAdapter(), $bytes);
    }
    
    /**
     * @param string $hex
     * @return BigIntegerInterface
     */
    public static function fromHex(string $hex) : BigIntegerInterface
    {
        $adapter = static::getAdapter();
        $byteString = $adapter->hexToBytes($hex);
        return new BigInteger($adapter, $byteString);
    }
    
    /**
     * @param string $number
     * @return BigIntegerInterface
     */
    public static function fromDecimal(string $number) : BigIntegerInterface
    {
        $adapter = static::getAdapter();
        $byteString = $adapter->decimalToBytes($number);
        return new BigInteger($adapter, $byteString);
    }
    
    /**
     * @param int $integer
     * @return BigIntegerInterface
     */
    public static function fromInteger(int $integer) : BigIntegerInterface
    {
        $adapter = static::getAdapter();
        $byteString = $adapter->integerToBytes($integer);
        return new BigInteger($adapter, $byteString);
    }
    
    private static function getAdapter()
    {
        // Use GNU Multiple Precision library if available
        if (extension_loaded('gmp')) {
            $adapter = new GMPAdapter;
        }
        
        // Use BC Math if GNU MP is not available and the extension is loaded
        if (!isset($adapter) && extension_loaded('bcmath')) {
            $adapter = new BCMathAdapter;
        }
        
        // If neither GNU MP or BC Math extensions are enabled we will use the native PHP adapter
        if (!isset($adapter)) {
            $adapter = new NativeAdapter;
        }
        
        return $adapter;
    }
}
