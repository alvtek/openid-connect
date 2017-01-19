<?php


namespace Alvtek\OpenIdConnect\Lib;


class BigInteger implements BigIntegerInterface
{
    /** @var string */
    private $bytes;
    
    private function __construct(string $bytes)
    {
        $this->bytes = $bytes;
    }
    
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
    
    /**
     * @return string
     */
    public function __toString()
    {
        return $this->bytes;
    }
}
