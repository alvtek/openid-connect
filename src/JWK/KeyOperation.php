<?php

declare(strict_types=1);

namespace Alvtek\OpenIdConnect\JWK;

use Alvtek\OpenIdConnect\Exception\InvalidArgumentException;

class KeyOperation
{
    const SIGN = 'sign';
    const VERIFY = 'verify';
    const ENCRYPT = 'encrypt';
    const DECRYPT = 'decrypt';
    const WRAP_KEY = 'wrapKey';
    const UNWRAP_KEY = 'unwrapKey';
    const DERIVE_KEY = 'deriveKey';
    const DERIVE_BITS = 'deriveBits';
    
    /** @var string */
    private $value;
    
    /**
     * @param string $keyOperation
     * @throws InvalidArgumentException
     */
    public function __construct(string $keyOperation)
    {
        if (!in_array($keyOperation, [
            self::SIGN, self::VERIFY, self::ENCRYPT, self::DECRYPT, 
            self::WRAP_KEY, self::UNWRAP_KEY, self::DERIVE_KEY, 
            self::DERIVE_BITS
        ])) {
            throw new InvalidArgumentException("Unrecognised key operation "
                . "'$keyOperation'");
        }
        
        $this->value = $keyOperation;
    }
    
    public function __toString()
    {
        return $this->value;
    }
    
    /**
     * 
     * @param KeyOperation $keyOperation
     * @return boolean
     */
    public function equals(KeyOperation $keyOperation)
    {
        return ((string) $keyOperation === $this->value);
    }
}