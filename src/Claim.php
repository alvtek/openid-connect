<?php

declare(strict_types=1);

namespace Alvtek\OpenIdConnect;

use Assert\Assert;

use JsonSerializable;

class Claim implements JsonSerializable
{
    /** @var string */
    private $type;
    
    /** @var mixed */
    private $value;
    
    public function __construct(string $type, $value)
    {
        Assert::that($type)->notEmpty();
        Assert::that($value)->scalar();

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
        Assert::that($data)
            ->choicesNotEmpty(['type', 'value']);
        
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
        return $this->type === $claim->type() && $this->value === $claim->value();
    }
}