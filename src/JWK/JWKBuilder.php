<?php

declare(strict_types=1); 

namespace Alvtek\OpenIdConnect\JWK;

use Alvtek\OpenIdConnect\Exception\InvalidArgumentException;
use Alvtek\OpenIdConnect\Exception\RuntimeException;
use Alvtek\OpenIdConnect\JWA\JWAFactory;
use Alvtek\OpenIdConnect\JWAInterface;
use Alvtek\OpenIdConnect\JWK;
use Alvtek\OpenIdConnect\JWK\KeyOperation\KeyOperationCollection as KeyOperations;

abstract class JWKBuilder
{
    /** @var KeyType */
    protected $kty;

    /** @var Usage */
    protected $use;

    /** @var KeyOperations */
    protected $keyOps;

    /** @var JWA */
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

    protected static $arrayMappings = [
        'use'       => 'setUse',
        'key_ops'   => 'setKeyOps',
        'alg'       => 'setAlg',
        'kid'       => 'setKid',
        'x5u'       => 'setX5u',
        'x5c'       => 'setX5c',
        'x5t'       => 'setX5t',
        'x5t#S256'  => 'setX5tS256',
    ];

    public function __construct(KeyType $keyType)
    {
        $this->keyType = $keyType;
    }

    public function __get($name)
    {
        if (!property_exists($this, $name)) {
            throw new RuntimeException(sprintf(
                "Property '%s' is not recognised", $name));
        }

        return $this->{$name};
    }

    abstract public function build();

    /**
     * This method creates a JWKBuilder object from an array. Note that if the
     * data is not in the expected format, an exception or error will be thrown.
     * 
     * @param array $data
     * @return \static
     * @throws InvalidArgumentException
     */
    public static function fromArray(array $data)
    {
        if (!array_key_exists('kty', $data)) {
            throw new InvalidArgumentException("kty parameter must be set");
        }
        
        $builder = new static($data['kty']);
        
        foreach (static::$arrayMappings as $key => $method) {
            if (array_key_exists($key, $data)) {
                $builder->{$method}($data[$key]);
            }
        }
        
        return $builder;
    }

    /**
     * This method converts raw JWK data from say JSON into a JWKBuilder
     * object.
     * 
     * @param array $data
     * @return static
     * @throws InvalidArgumentException
     */
    public static function fromJWKData(array $data)
    {
        $dataConverted = [];
        
        if (!array_key_exists('kty', $data)) {
            throw new InvalidArgumentException("kty key must be set");
        }
        
        $dataConverted['kty'] = new KeyType($data['kty']);
        
        if (array_key_exists('use', $data)) {
            $dataConverted['use'] = new Usage($data['use']);
        }
        
        if (array_key_exists('key_ops', $data)) {
            $dataConverted['key_ops'] = KeyOperations::fromArray($dataConverted['key_ops']);
        }
        
        if (array_key_exists('alg', $data)) {
            $dataConverted['alg'] = JWAFactory::createFromName($data['alg']);
        }
        
        return static::fromArray(array_merge($data, $dataConverted));
    }

    public function setUse(Usage $use) : self
    {
        $this->use = $use;
        return $this;
    }

    public function setKeyOps(KeyOperations $keyOps) : self
    {
        $this->keyOps = $keyOps;
        return $this;
    }

    public function setAlg(JWAInterface $alg) : self
    {
        $this->alg = $alg;
        return $this;
    }

    public function setKid(string $kid) : self
    {
        $this->kid = $kid;
        return $this;
    }

    public function setX5u(string $x5u) : self
    {
        $this->x5u = $x5u;
        return $this;
    }

    public function setX5c(array $x5c) : self
    {
        $this->x5c = $x5c;
        return $this;
    }

    public function setX5t(string $x5t) : self
    {
        $this->x5t = $x5t;
        return $this;
    }
    
    public function setX5tS256(string $x5tS256) : self
    {
        $this->x5tS256 = $x5tS256;
        return $this;
    }
}
