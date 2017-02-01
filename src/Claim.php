<?php

declare(strict_types=1);

namespace Alvtek\OpenIdConnect;

use Alvtek\OpenIdConnect\Exception\InvalidArgumentException;
use JsonSerializable;

class Claim implements JsonSerializable
{
    /** @var string */
    private $type;
    
    /** @var mixed */
    private $value;
    
    public function __construct(string $type, $value)
    {
        if (empty($type)) {
            throw new InvalidArgumentException("Claim type argument must be a non empty string");
        }
        
        if (!is_scalar($value)) {
            throw new InvalidArgumentException("Claim value must be a scalar value");
        }
        
        $this->type = $type;
        $this->value = $value;
    }
    
    public function type()
    {
        return $this->type;
    }

    public function value()
    {
        return $this->value;
    }
    
    public function fromArray(array $data)
    {
        if (!array_key_exists($data, 'type') || !array_key_exists($data, 'value')) {
            throw new InvalidArgumentException("'type' and 'value' array keys must be set");
        }
        
        return new static($data['type'], $data['value']);
    }
    
    public function jsonSerialize()
    {
        return $this->toArray();
    }
    
    public function toArray()
    {
        return [
            'type' => $this->type,
            'value' => $this->value,
        ];
    }

    public function equals(Claim $claim)
    {
        return $this->type === $claim->type && $this->value === $claim->value;
    }
}
