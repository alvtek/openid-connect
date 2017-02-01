<?php

declare(strict_types=1);

namespace Alvtek\OpenIdConnect;

use Alvtek\OpenIdConnect\Exception\InvalidArgumentException;
use Alvtek\OpenIdConnect\Exception\RuntimeException;
use Alvtek\OpenIdConnect\JWK;
use Alvtek\OpenIdConnect\JWK\JWKFactory;
use Alvtek\OpenIdConnect\JWK\VerificationInterface;
use Countable;
use Iterator;
use JsonSerializable;

class JWKS implements Iterator, Countable, JsonSerializable
{
    /** @var JWK[] */
    private $keys;
    
    /** @var integer */
    private $position;

    public function __construct(array $keys)
    {
        $this->position = 0;
        foreach ($keys as $key) {
            if (!$key instanceof JWK) {
                throw new InvalidArgumentException(sprint("Expecting argument "
                    . "to be an array of type %s", JWK::class));
            }
            $this->keys[$key->keyId()] = $key;
        }
    }
    
    /**
     * @param array $data
     * @return JWKS
     */
    public static function fromJWKSData(array $data) : JWKS
    {
        if (!array_key_exists('keys', $data)) {
            throw new InvalidArgumentException("Array key 'keys' must be set");
        }
        
        if (!is_array($data['keys'])) {
            throw new InvalidArgumentException("Array key 'keys' must be an array");
        }

        $keys = [];

        foreach ($data['keys'] as $keyData) {
            $keys[] = JWKFactory::fromJWKData($keyData);
        }
        
        return new static($keys);
    }

    public function jsonSerialize()
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
