<?php

namespace Alvtek\OpenIdConnect\Provider\Flag;

use Alvtek\OpenIdConnect\Provider\Flag;

use Alvtek\OpenIdConnect\Provider\Flag\Exception\UndefinedFlagException;
use Alvtek\OpenIdConnect\Provider\Flag\Exception\DuplicateFlagTypeException;

use Assert\Assert;

class FlagCollection
{
    /** @var Flag[] */
    private $flags;
    
    private $position;

    /**
     * @param array $flags
     */
    public function __construct($flags)
    {
        Assert::that($flags)
            ->isArray()
            ->all()
            ->isInstanceOf(Flag::class);
        
        $this->position = 0;
        $this->flags = [];
        
        foreach ($flags as $flag) {
            if ($this->hasFlagType($flag->type())) {
                throw new DuplicateFlagTypeException(sprintf("A flag of type "
                    . "'%s' already exists.", $flag->type()));
            }
            
            $this->flags[] = $flag;
        }
    }
    
    /**
     * @return boolean
     */
    public function isEmpty()
    {
        return empty($this->flags);
    }
    
    /**
     * @param string $type
     * @return boolean
     */
    public function hasFlagType($type)
    {
        foreach ($this->flags as $flag) {
            if ($flag->type() === $type) {
                return true;
            }
        }

        return false;
    }
    
    /**
     * @param array $types
     * @return boolean
     */
    public function hasFlagTypes($types)
    {
        Assert::that($types)->isArray()->all()->string();

        foreach ($types as $type) {
            if (!$this->hasFlagType($type)) {
                return false;
            }
        }
        
        return true;
    }
    
    public function addFlag(Flag $flag)
    {
        $flags = $this->flags;
        $flags[] = $flag;
        
        return new self($flags);
    }

    
    public function getFlagByType($type)
    {
        Assert::that($type)->notEmpty()->string();
        
        foreach ($this->flags as $flag) {
            if ($flag->type() === $type) {
                return $flag;
            }
        }

        throw new UndefinedFlagException("The flag '%s' does "
            . "not exist");
    }
    
    /**
     * 
     * @return integer
     */
    public function count()
    {
        return count($this->flags);
    }

    public function rewind()
    {
        $this->position = 0;
    }

    /** @return Endpoint */
    public function current()
    {
        return $this->flags[$this->position];
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
        return isset($this->flags[$this->position]);
    }
}