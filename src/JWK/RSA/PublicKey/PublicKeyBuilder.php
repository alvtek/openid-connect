<?php

declare(strict_types=1); 

namespace Alvtek\OpenIdConnect\JWK\RSA\PublicKey;

use Alvtek\OpenIdConnect\BigInteger\BigIntegerFactory;
use Alvtek\OpenIdConnect\BigIntegerInterface;
use Alvtek\OpenIdConnect\JWK\JWKBuilder;
use Alvtek\OpenIdConnect\JWK\KeyType;
use Alvtek\OpenIdConnect\JWK\RSA\PublicKey;

class PublicKeyBuilder extends JWKBuilder
{
    /** @var BigIntegerInterface */
    protected $n;

    /** @var BigIntegerInterface */
    protected $e;
    
    public function __construct(BigIntegerInterface $n, BigIntegerInterface $e)
    {
        parent::__construct(new KeyType(KeyType::RSA));

        $this->n = $n;
        $this->e = $e;
    }

    /**
     *
     * @param array $data
     * @return static
     */
    public static function fromJWKData(array $data) : self
    {
        $decoded = [];

        if (isset($data['n'])) {
            $decoded['n'] = BigIntegerFactory::fromBytes($data['n']);
        }
        
        if (isset($data['e'])) {
            $decoded['e'] = BigIntegerFactory::fromBytes($data['e']);
        }
        
        return parent::fromJWKData(array_merge($data, $decoded));
    }

    public static function fromResource($keyResource) : self
    {
        if (!is_resource($keyResource)) {
            throw new InvalidArgumentException("Argument must be a resource");
        }

        $details = openssl_pkey_get_details($keyResource);

        if (!is_array($details)) {
            throw new RuntimeException("Failed to get details of RSA public key "
                . "from resource.");
        }
        
        if (!isset($details['rsa']) || !is_array($details['rsa'])) {
            throw new RuntimeException("Unexpected response from openssl while "
                . "attempting to parse key resource");
        }
        
        if (!isset($details['rsa']['n']) || !isset($details['rsa']['e'])) {
            throw new RuntimeException("Unexpected response from openssl while "
                . "attempting to get key details");
        }
        
        
        $rsa = $details['rsa'];

        return static::fromArray([
            'n'  => BigIntegerFactory::fromBytes($rsa['n']),
            'e'  => BigIntegerFactory::fromBytes($rsa['e']),
        ]);
    }

    public static function fromArray(array $data) : self
    {
        
        if (!isset($data['n']) || !isset($data['e'])) {
            throw new InvalidArgumentException("Array keys n and e must be set.");
        }
        
        $builder = new static($data['n'], $data['e']);
        
        foreach (static::$arrayMappings as $key => $method) {
            if (array_key_exists($key, $data)) {
                $builder->{$method}($data[$key]);
            }
        }

        return $builder;
    }

    public function build() : PublicKey
    {
        return new PublicKey($this);
    }
}
