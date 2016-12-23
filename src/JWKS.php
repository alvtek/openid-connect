<?php

namespace Alvtek\OpenIdConnect;

use Alvtek\OpenIdConnect\JWK;
use Alvtek\OpenIdConnect\JWK\JWKFactory;
use Alvtek\OpenIdConnect\JWK\VerificationInterface;

use Alvtek\OpenIdConnect\Exception\RuntimeException;

use Assert\Assert;

use Iterator;
use Countable;
use JsonSerializable;

class JWKS implements Iterator, Countable, JsonSerializable
{
    /** @var JWK[] */
    private $keys;
    
    /** @var integer */
    private $position;

    public function __construct($keys)
    {
        Assert::that($keys)
            ->isArray("Expecting array")
            ->all()
            ->isInstanceOf(JWK::class, "Expecting array of JWK objects");

        $this->position = 0;
        foreach ($keys as $key) { /* @var $key JWK */
            $this->keys[$key->keyId()] = $key;
        }
    }
    
    public static function fromJWKSData($data)
    {
        Assert::that($data)->isArray()->keyExists('keys');
        Assert::that($data['keys'])->isArray();

        $keys = [];

        foreach ($data['keys'] as $keyData) {
            $keys[] = JWKFactory::create($keyData);
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