<?php

declare(strict_types=1);

namespace Alvtek\OpenIdConnect\JWK;

use Alvtek\OpenIdConnect\Exception\InvalidArgumentException;

class Usage
{
    const SIGNATURE = 'sig';
    const ENCRYPTION = 'enc';
    
    /** @var string */
    private $value;
    
    /**
     * @param string $usage
     * @throws InvalidArgumentException
     */
    public function __construct(string $usage)
    {
        if (!in_array($usage, [self::SIGNATURE, self::ENCRYPTION])) {
            throw new InvalidArgumentException("Unrecognised key use '$usage'");
        }
        
        $this->value = $usage;
    }
    
    public function __toString()
    {
        return $this->value;
    }
    
    /**
     * @param Usage $usage
     * @return boolean
     */
    public function equals(Usage $usage)
    {
        return ((string) $usage === $this->value);
    }
}
