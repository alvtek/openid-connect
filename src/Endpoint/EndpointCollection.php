<?php

declare(strict_types=1);

namespace Alvtek\OpenIdConnect\Endpoint;

use Alvtek\OpenIdConnect\Endpoint;
use Alvtek\OpenIdConnect\Endpoint\Exception\UndefinedEndpointException;
use Alvtek\OpenIdConnect\EndpointInterface;
use Alvtek\OpenIdConnect\Exception\InvalidArgumentException;
use Alvtek\OpenIdConnect\Provider\Exception\UnrecognisedEndpointException;
use Countable;
use Iterator;

final class EndpointCollection implements Countable, Iterator
{
    /** 
     * @var EndpointInterface[] 
     */
    private $endpoints;
    
    /**
     * @var int
     */
    private $position;

    /**
     * @param array $endpoints
     */
    private function __construct(array $endpoints)
    {
        $this->position = 0;
        $this->endpoints = [];
        
        foreach ($endpoints as $endpoint) {
            if (!$endpoint instanceof EndpointInterface) {
                throw new InvalidArgumentException("Expecting array of Endpoints");
            }
            
            if ($this->hasEndpoint($endpoint)) {
                continue;
            }
            
            $this->endpoints[] = $endpoint;
        }
    }
    
    public static function fromArray(array $endpoints) : EndpointCollection
    {
        return new static($endpoints);
    }
    
    /**
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->endpoints);
    }
    
    /**
     * @param string $type
     * @return bool
     */
    public function hasEndpointType($type)
    {
        foreach ($this->endpoints as $endpoint) {
            if ($endpoint->type() === $type) {
                return true;
            }
        }

        return false;
    }
    
    /**
     * @param array $types
     * @return bool
     */
    public function hasEndpointTypes(array $types)
    {
        foreach ($types as $type) {
            if (!is_string($type)) {
                throw new InvalidArgumentException("Expecting array of strings");
            }
            
            if (!$this->hasEndpointType($type)) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * @param EndpointInterface $endpoint
     * @return bool
     */
    public function hasEndpoint(EndpointInterface $endpoint)
    {
        foreach ($this->endpoints as $existingEndpoint) {
            if ($existingEndpoint->equals($endpoint)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * @param EndpointInterface $endpoint
     * @return EndpointCollection
     */
    public function withEndpoint(EndpointInterface $endpoint)
    {
        $endpoints = $this->endpoints;
        $endpoints[] = $endpoint;
        
        return new self($endpoints);
    }

    /**
     * @param string $type
     * @return EndpointInterface
     * @throws UnrecognisedEndpointException
     * @throws UndefinedEndpointException
     */
    public function get(string $type)
    {
        foreach ($this->endpoints as $endpoint) {
            if ($endpoint->type() === $type) {
                return $endpoint;
            }
        }
        
        throw new UndefinedEndpointException(sprintf("The endpoint '%s' does "
                . "not exist", $type));
    }
    
    /**
     * 
     * @return integer
     */
    public function count()
    {
        return count($this->endpoints);
    }

    public function rewind()
    {
        $this->position = 0;
    }

    /** @return Endpoint */
    public function current()
    {
        return $this->endpoints[$this->position];
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
        return isset($this->endpoints[$this->position]);
    }
}