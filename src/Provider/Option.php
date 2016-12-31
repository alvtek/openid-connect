<?php

namespace Alvtek\OpenIdConnect\Provider;

use Assert\Assert;

use JsonSerializable;

/**
 * A value object modelling a Provider's support options
 */
class Option implements JsonSerializable
{
    /** @var string */
    private $type;
    
    /** @var array */
    private $values;

    public function __construct($type, $values)
    {
        Assert::that($type)->string();
        Assert::that($values)->isArray()->all()->scalar();
        
        $this->type = $type;
        $this->values = $values;
    }
    
    public function jsonSerialize()
    {
        return [$this->type => $this->values];
    }
    
    /**
     * @return string
     */
    public function type()
    {
        return $this->type;
    }
    
    /**
     * 
     * @return array
     */
    public function values()
    {
        return $this->values;
    }
    
    /**
     * @param \Alvtek\OpenIdConnect\Provider\Option $option
     * @return boolean
     */
    public function equals(Option $option)
    {
        return (
            $this->type === $option->type() &&
            $this->values == $option->values()
        );
    }
    
    /**
     * @param mixed $value
     * @return boolean
     */
    public function supports($value)
    {
        return in_array($value, $this->values);
    }
}
