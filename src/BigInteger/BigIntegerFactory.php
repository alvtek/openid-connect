<?php

namespace Alvtek\OpenIdConnect\BigInteger;

use Alvtek\OpenIdConnect\BigInteger\Adapter\BCMathAdapter;
use Alvtek\OpenIdConnect\BigInteger\Adapter\GMPAdapter;
use Alvtek\OpenIdConnect\BigInteger\Adapter\NativeAdapter;
use Alvtek\OpenIdConnect\BigInteger;

class BigIntegerFactory
{
    /**
     * @param string $encoded
     * @return BigIntegerInterface
     */
    public static function fromBase64UrlSafe(string $encoded) : BigIntegerInterface
    {
        // TODO: Implement fromBase64UrlSafe() method.
    }
    
    /**
     * @param string $hex
     * @return BigIntegerInterface
     */
    public static function fromHex(string $hex) : BigIntegerInterface
    {
        // TODO: Implement fromHex() method.
    }
    
    /**
     * @param string $number
     * @return BigIntegerInterface
     */
    public static function fromString(string $number) : BigIntegerInterface
    {
        // TODO: Implement fromString() method.
    }
    
    /**
     * @param int $integer
     * @return BigIntegerInterface
     */
    public static function fromInteger(int $integer) : BigIntegerInterface
    {
        // TODO: Implement fromInteger() method.
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
