<?php

namespace Alvtek\OpenIdConnect;

use Alvtek\OpenIdConnect\Exception\InvalidArgumentException;
use Alvtek\OpenIdConnect\Exception\RuntimeException;
use Alvtek\OpenIdConnect\JWK;
use Alvtek\OpenIdConnect\JWK\JWKFactory;
use Alvtek\OpenIdConnect\JWK\VerificationInterface;
use Alvtek\OpenIdConnect\JWS;
use Alvtek\OpenIdConnect\SerialisableInterface;
use Countable;
use Iterator;

class JWKS implements Iterator, Countable, SerialisableInterface
{
    /** 
     * @var JWK[] 
     */
    private $keys;
    
    /** 
     * @var int 
     */
    private $position;

    /**
     * @param JWK[] $keys
     * @throws InvalidArgumentException
     */
    private function __construct(array $keys)
    {
        $this->position = 0;
        
        foreach ($keys as $key) {
            if (!$key instanceof JWK) {
                throw new InvalidArgumentException("Expecting array of JWK "
                    . "objects");
            }
            
            $this->keys[$key->keyId()] = $key;
        }
    }
    
    /**
     * @param array $keys
     * @return JWK
     */
    public static function fromArray(array $keys)
    {
        return new static($keys);
    }
    
    /**
     * @param string $data
     * @return JWK
     */
    public static function fromJson(string $data)
    {
        $data = \json_decode($data, true);
        
        if (!array_key_exists('keys', $data)) {
            throw new InvalidArgumentException("Expecting key 'keys' in JWKS "
                . "data.");
        }
        
        if (!is_array($data['keys'])) {
            throw new InvalidArgumentException("Expecting array of keys.");
        }
        
        $keys = [];

        foreach ($data['keys'] as $keyData) {
            $keys[] = JWKFactory::fromJWKData($keyData);
        }

        return new static($keys);
    }

    public function serialise() : array
    {
        $keys = [];
        foreach ($this->keys as $key) {
            $keys[] = $key->jsonSerialize();
        }
        
        $output = ['keys' => $keys];
        
        return $output;
    }

    public function count()
    {
        return count($this->keys);
    }

    public function rewind()
    {
        $this->position = 0;
    }

    /**
     *
     * @return Jwk
     */
    public function current()
    {
        return $this->keys[array_keys($this->keys)[$this->position]];
    }

    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        ++$this->position;
    }

    public function valid()
    {
        return isset(array_keys($this->keys)[$this->position]);
    }

    public function verifyJWS(JWS $jws)
    {
        if (!isset($this->keys[$jws->signingKeyId()])) {
            return false;
        }

        $verifyingKey = $this->keys[$jws->signingKeyId()];

        if (!$verifyingKey instanceof VerificationInterface) {
            throw new RuntimeException("The key in the Json Web Key Set is "
                . "not a verification key.");
        }
        
        return $jws->verifySignature($verifyingKey);
    }
}