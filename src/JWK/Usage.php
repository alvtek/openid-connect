<?php

declare(strict_types=1);

namespace Alvtek\OpenIdConnect\JWK;

use Alvtek\OpenIdConnect\Exception\InvalidArgumentException;

class Usage
{
    const SIGNATURE = 'sig';
    const ENCRYPTION = 'enc';
    
    /** 
     * @var string 
     */
    private $value;
    
    /**
     * @param string $usage
     * @throws InvalidArgumentException
     */
    private function __construct(string $usage)
    {
        if (!in_array($usage, [self::SIGNATURE, self::ENCRYPTION])) {
            throw new InvalidArgumentException("Unrecognised key use '$usage'");
        }
        
        $this->value = $usage;
    }
    
    /**
     * @param string $usage
     * @return Usage
     */
    public static function fromString(string $usage) : Usage
    {
        return new static($usage);
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
        return ($usage->value === $this->value);
    }
}
