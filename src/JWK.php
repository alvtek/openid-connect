<?php

namespace Alvtek\OpenIdConnect;

use Alvtek\OpenIdConnect\JWK\JWKBuilder;
use Alvtek\OpenIdConnect\JWK\KeyOperation\KeyOperationCollection as KeyOperations;
use Alvtek\OpenIdConnect\JWK\KeyType;
use Alvtek\OpenIdConnect\JWK\Usage;
use JsonSerializable;
use ReflectionClass;

abstract class JWK implements JsonSerializable
{
    /** @var KeyType */
    protected $kty;

    /** @var Usage */
    protected $use;

    /** @var KeyOperations */
    protected $keyOps;

    /** @var JWAInterface */
    protected $alg;

    /** @var string */
    protected $kid;

    /** @var string */
    protected $x5u;

    /** @var array */
    protected $x5c;

    /** @var string */
    protected $x5t;

    /** @var string */
    protected $x5tS256;

    /**
     * @param JWKBuilder $jwkBuilder
     */
    public function __construct(JWKBuilder $jwkBuilder)
    {
        $reflection = new ReflectionClass(self::class);
        
        foreach ($reflection->getProperties() as $property) {
            $propertyName = $property->name;
            if (property_exists($jwkBuilder, $propertyName)) {
                $this->{$propertyName} = $jwkBuilder->{$propertyName};
            }
        }
    }

    public function jsonSerialize()
    {
        $reflection = new ReflectionClass(self::class);
        
        $output = [];
        
        foreach ($reflection->getProperties() as $property) {
            $propertyName = $property->name;
            if (isset($this->{$propertyName})) {
                $output[$propertyName] = $this->{$propertyName};
            }
        }
        
        return $output;
    }

    public function keyId()
    {
        return $this->kid;
    }
}
