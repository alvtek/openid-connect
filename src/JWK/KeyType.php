<?php

declare(strict_types=1);

namespace Alvtek\OpenIdConnect\JWK;

use Alvtek\OpenIdConnect\Exception\InvalidArgumentException;

/**
 * KeyType value object
 */
class KeyType
{
    const EC = 'EC';
    const RSA = 'RSA';
    const OCT = 'oct';
    
    /** @var string */
    private $value;
    
    /**
     * @param string $keyType
     * @throws InvalidArgumentException
     */
    public function __construct(string $keyType)
    {
        if (!in_array($keyType, [self::EC, self::OCT, self::RSA])) {
            throw new InvalidArgumentException("Unrecognised Key type '$keyType'");
        }
        
        $this->value = $keyType;
    }
    
    public function __toString()
    {
        return $this->value;
    }
    
    /**
     * @param KeyType $keyType
     * @return boolean
     */
    public function equals(KeyType $keyType)
    {
        return ($this->value === (string) $keyType);
    }
}
