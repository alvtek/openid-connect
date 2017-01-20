<?php

declare(strict_types=1); 

namespace Alvtek\OpenIdConnect\JWK\RSA\PublicKey;

use Alvtek\OpenIdConnect\JWK;
use Alvtek\OpenIdConnect\JWK\JWKBuilder;
use Alvtek\OpenIdConnect\JWK\KeyType;
use Alvtek\OpenIdConnect\JWK\RSA\PublicKey;
use Alvtek\OpenIdConnect\Lib\BigIntegerInterface;

class PublicKeyBuilder extends JWKBuilder
{
    /** @var BigIntegerInterface */
    protected $n;

    /** @var BigIntegerInterface */
    protected $e;
    
    public function __construct(BigInteger $n, BigInteger $e)
    {
        parent::__construct(new KeyType(KeyType::RSA));

        $this->n = clone $n;
        $this->e = clone $e;
    }

    /**
     *
     * @param array $data
     * @return static
     */
    public static function fromJWKData(array $data)
    {
        $decoded = [];

        if (isset($data['n'])) {
            $decoded['n'] = BigInteger::fromBase64UrlSafe($data['n']);
        }
        
        if (isset($data['e'])) {
            $decoded['e'] = BigInteger::fromBase64UrlSafe($data['e']);
        }
        
        return parent::fromJWKData(array_merge($data, $decoded));
    }

    public static function fromResource($keyResource)
    {
        if (!is_resource($keyResource)) {
            throw new InvalidArgumentException("Argument must be a resource");
        }

        $details = openssl_pkey_get_details($keyResource);

        Assert::that($details)
            ->isArray()
            ->keyExists('rsa');
        
        Assert::that($details['rsa'])
            ->isArray()
            ->choicesNotEmpty(['n', 'e',]);

        $rsa = $details['rsa'];

        return static::fromArray([
            'n'  => new BigInteger($rsa['n'], 256),
            'e'  => new BigInteger($rsa['e'], 256),
        ]);
    }

    public static function fromArray(array $data)
    {
        Assert::that($data)
            ->choicesNotEmpty(['n', 'e']);
        
        $builder = new static($data['n'], $data['e']);
        
        foreach (static::$arrayMappings as $key => $method) {
            if (array_key_exists($key, $data)) {
                $builder->{$method}($data[$key]);
            }
        }

        return $builder;
    }

    public function build() : JWK
    {
        return new PublicKey($this);
    }
}
