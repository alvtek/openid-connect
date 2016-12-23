<?php

namespace Alvtek\OpenIdConnect\Provider\Option;

use Alvtek\OpenIdConnect\Provider\Option;

use Alvtek\OpenIdConnect\Provider\Option\Exception\UndefinedOptionException;
use Alvtek\OpenIdConnect\Provider\Option\Exception\DuplicateOptionTypeException;

use Assert\Assert;

class OptionCollection
{
    /** @var Option[] */
    private $options;
    
    private $position;

    /**
     * @param Option[] $options
     */
    public function __construct($options)
    {
        Assert::that($options)
            ->isArray()
            ->all()
            ->isInstanceOf(Option::class);
       
        $this->position = 0;
        $this->options = [];
        
        foreach ($options as $option) {
            if ($this->hasOptionType($option->type())) {
                throw new DuplicateOptionTypeException(sprintf("An option of "
                    . "type '%s' already exists.", $option->type()));
            }
            
            $this->options[] = $option;
        }
    }
    
    /**
     * @return boolean
     */
    public function isEmpty()
    {
        return empty($this->options);
    }
    
    /**
     * Check if an option of type is present in this collection
     * 
     * @param string $type
     * @return boolean
     */
    public function hasOptionType($type)
    {
        foreach ($this->options as $option) {
            if ($option->type() === $type) {
                return true;
            }
        }

        return false;
    }
    
    /**
     * Check if this collection has all option types
     * 
     * @param array $types
     * @return boolean
     */
    public function hasOptionTypes($types)
    {
        Assert::that($types)->isArray()->all()->string();

        foreach ($types as $type) {
            if (!$this->hasOptionType($type)) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * @param Option $option
     * @return self
     */
    public function addOption(Option $option)
    {
        $options = $this->options;
        $options[] = $options;
        
        return new self($options);
    }

    /**
     * 
     * @param string $type
     * @return Option
     * @throws UndefinedOptionException
     */
    public function get($type)
    {
        Assert::that($type)->notEmpty()->string();

        foreach ($this->options as $option) {
            if ($option->type() === $type) {
                return $option;
            }
        }
        
        throw new UndefinedOptionException(sprintf("The option '%s' does "
            . "not exist", $type));
    }
    
    /**
     * 
     * @return integer
     */
    public function count()
    {
        return count($this->options);
    }

    public function rewind()
    {
        $this->position = 0;
    }

    /** @return Endpoint */
    public function current()
    {
        return $this->options[$this->position];
    }

    /**
     * @return integer
     */
    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        ++$this->position;
    }

    /**
     * @return boolean
     */
    public function valid()
    {
        return isset($this->options[$this->position]);
    }
}
