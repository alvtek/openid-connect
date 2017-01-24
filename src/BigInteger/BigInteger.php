<?php

namespace Alvtek\OpenIdConnect;

use Alvtek\OpenIdConnect\AdapterInterface;

/*
 * This class handles integers that are potentially bigger than what the system
 * can handle natively. These integers need to be handled in a special way. 
 * They either need to use PHP extensions in order to operate on them, or they
 * need to use less efficient PHP algorithms in order to process these large
 * numbers.
 */
class BigInteger implements BigIntegerInterface
{
    /** @var Adapter */
    private $adapter;
    
    /** @var string */
    private $bytes;
    
    /** @var array */
    private $octets;
    
    private function __construct(AdapterInterface $adapter, string $bytes, int $base)
    {
        $this->adapter = $adapter;
        $this->bytes = $bytes;
    }
    
    /**
     * @return string
     */
    public function __toString()
    {
        return $this->bytes;
    }
    
    public function toBytes(): string
    {
        
    }

    public function toHex(): string
    {
        
    }

    public function add(BigIntegerInterface $number): BigIntegerInterface
    {
        
    }

    public function divide(BigIntegerInterface $divisor): BigIntegerInterface
    {
        
    }

    public function multiply(BigIntegerInterface $number): BigIntegerInterface
    {
        
    }

    public function power(BigIntegerInterface $exponent): BigIntegerInterface
    {
        
    }

    public function subtract(BigIntegerInterface $number): BigIntegerInterface
    {
        
    }
}
