<?php

namespace Alvtek\OpenIdConnect\Provider;

use Assert\Assert;

/**
 * A value object modelling a boolean support option of a provider
 */
class Flag
{
    /** @var string */
    private $type;
    
    /** @var boolean */
    private $value;

    public function __construct($type, $value)
    {
        Assert::that($type)->string();
        Assert::that($value)->boolean();
        
        $this->type = $type;
        $this->value = $value;
    }
    
    /**
     * @return string
     */
    public function type()
    {
        return $this->type;
    }

    /**
     * @return boolean
     */
    public function value()
    {
        return $this->value;
    }
    
    /**
     * @param \Alvtek\OpenIdConnect\Provider\Flag $flag
     * @return boolean
     */
    public function equals(Flag $flag)
    {
        return (
            $this->type === $flag->type() &&
            $this->value === $flag->value()
        );
    }
}
