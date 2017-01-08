<?php

declare(strict_types=1);

namespace Alvtek\OpenIdConnect;

use Alvtek\OpenIdConnect\ClaimInterface;
use Alvtek\OpenIdConnect\Exception\InvalidArgumentException;
use Alvtek\OpenIdConnect\SerialisableInterface;

final class Claim implements ClaimInterface, SerialisableInterface
{
    /** @var string */
    private $type;
    
    /** @var mixed */
    private $value;
    
    private function __construct(string $type, $value)
    {
        $this->type = $type;
        $this->value = $value;
    }

    /**
     * @param string $type
     * @param string $value
     * @return Claim
     */
    public static function fromString(string $type, string $value) : Claim
    {
        return new static($type, $value);
    }

    /**
     * @param string $type
     * @param int $value
     * @return Claim
     */
    public static function fromInt(string $type, int $value) : Claim
    {
        return new static($type, $value);
    }
    
    /**
     * @param string $type
     * @param bool $value
     * @return Claim
     */
    public static function fromBoolean(string $type, bool $value) : Claim
    {
        return new static($type, $value);
    }
    
    /**
     * @param array $data
     * @return Claim
     * @throws InvalidArgumentException
     */
    public static function fromArray(array $data) : Claim
    {
        if (!array_key_exists('type', $data)) {
            throw new InvalidArgumentException("expected array key 'type' does "
                . "not exist.");
        }
        
        if (!array_key_exists('value', $data)) {
            throw new InvalidArgumentException("expected array key 'value' "
                . "does not exist.");
        }
        
        return new static($data['type'], $data['value']);
    }
    
    /**
     * @return string
     */
    public function type() : string
    {
        return $this->type;
    }

    public function value()
    {
        return $this->value;
    }
    
    /**
     * @return array
     */
    public function serialise() : array
    {
        return [
            'type' => $this->type,
            'value' => $this->value,
        ];
    }
    
    /**
     * @param ClaimInterface $claim
     * @return bool
     */
    public function equals(ClaimInterface $claim) : bool
    {
        return $this->type === $claim->type() && $this->value === $claim->value();
    }
}