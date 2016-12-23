<?php

declare(strict_types=1);

namespace Alvtek\OpenIdConnect\JWK\KeyOperation;

use Alvtek\OpenIdConnect\JWK\KeyOperation;

use Alvtek\OpenIdConnect\Exception\InvalidArgumentException;

use Assert\Assert;

use Countable;
use JsonSerializable;

final class KeyOperationCollection implements Countable, JsonSerializable
{
    /** @var KeyOperation[] */
    private $keyOperations;
    
    public function __construct(array $keyOperations)
    {
        Assert::that($keyOperations)
            ->isArray()
            ->all()
            ->isInstanceOf(KeyOperation::class);
        
        $this->keyOperations = [];
        
        foreach ($keyOperations as $keyOperation) {
            if (!$this->hasKeyOperation($keyOperation)) {
                $this->keyOperations[] = $keyOperation;
            }
        }
    }
    
    public static function fromArray(array $data)
    {
        $keyOperations = [];
        
        foreach ($data as $item) {
            $keyOperations[] = new KeyOperation($item);
        }
        
        return new static($keyOperations);
    }
    
    /**
     * Tests if this an operation
     * 
     * @param string $keyOperation
     * @return boolean
     * @throws InvalidArgumentException
     */
    public function hasKeyOperation(KeyOperation $keyOperation)
    {
        foreach ($this->keyOperations as $existingKeyOperation) {
            if ($keyOperation->equals($existingKeyOperation)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Test for equality against another collection object
     * 
     * @param KeyOperationCollection $keyOperations
     * @return boolean
     */
    public function equals(KeyOperationCollection $keyOperations)
    {
        $intersectedOperations = array_filter($this->keyOperations, 
            [$keyOperations, 'hasKeyOperation']);
        
        return (
            $this->count() === count($keyOperations) && 
            $this->count() === count($intersectedOperations)
        );
    }
    
    public function count()
    {
        return count($this->keyOperations);
    }
    
    public function jsonSerialize()
    {
        return array_map(function(KeyOperation $keyOperation){
            return (string) $keyOperation;
        }, $this->keyOperations);
    }
}
