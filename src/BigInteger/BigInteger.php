<?php

namespace Alvtek\OpenIdConnect;

use Alvtek\OpenIdConnect\BigInteger\AdapterInterface;

/*
 * This class handles integers that are potentially bigger than what the system
 * can handle natively. These integers need to be handled in a special way. 
 * They either need to use PHP extensions in order to operate on them, or they
 * need to use less efficient PHP algorithms in order to process these large
 * numbers.
 */
class BigInteger implements BigIntegerInterface
{
    /** @var AdapterInterface */
    private $adapter;
    
    /** @var string */
    private $bytes;
    
    public function __construct(AdapterInterface $adapter, string $bytes)
    {
        $this->adapter = $adapter;
        $this->bytes = $bytes;
    }
    
    public function toBytes(): string
    {
        return $this->bytes;
    }

    public function toHex(): string
    {
        return $this->adapter->toHex($this);
    }
    
    public function toDecimal(): string
    {
        return $this->adapter->toDecimal($this);
    }
    
    public function toInt() : int
    {
        return $this->adapter->toInt($this);
    }

    public function add(BigIntegerInterface $number): BigIntegerInterface
    {
        return $this->adapter->add($this, $number);
    }

    public function divide(BigIntegerInterface $divisor): BigIntegerInterface
    {
        return $this->adapter->divide($this, $divisor);
    }

    public function multiply(BigIntegerInterface $number): BigIntegerInterface
    {
        return $this->adapter->multiply($this, $number);
    }

    public function power(int $exponent): BigIntegerInterface
    {
        return $this->adapter->power($this, $exponent);
    }

    public function subtract(BigIntegerInterface $number): BigIntegerInterface
    {
        return $this->adapter->subtract($this, $number);
    }

    public function modulus(BigIntegerInterface $divisor): BigIntegerInterface
    {
        return $this->adapter->modulus($this, $divisor);
    }
    
    public function compare(BigIntegerInterface $number) : int
    {
        return $this->adapter->compare($this, $number);
    }

    public function powerModulus(int $exponent, BigIntegerInterface $modulus): BigIntegerInterface
    {
        return $this->adapter->powerModulus($this, $exponent, $modulus);
    }
}
