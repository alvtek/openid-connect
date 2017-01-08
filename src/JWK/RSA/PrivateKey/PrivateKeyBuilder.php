<?php

declare(strict_types=1); 

namespace Alvtek\OpenIdConnect\JWK\RSA\PrivateKey;

use Alvtek\OpenIdConnect\JWK;
use Alvtek\OpenIdConnect\JWK\RSA\PublicKey\PublicKeyBuilder;
use Alvtek\OpenIdConnect\JWK\RSA\Prime;
use Alvtek\OpenIdConnect\JWK\RSA\PrivateKey;
use Alvtek\OpenIdConnect\BigInteger;
use phpseclib\Crypt\RSA as phpseclibRSA;

use Alvtek\OpenIdConnect\Exception\InvalidArgumentException;

use Assert\Assert;

class PrivateKeyBuilder extends PublicKeyBuilder
{
    /** @var BigInteger */
    protected $d;

    /** @var BigInteger */
    protected $p;

    /** @var BigInteger */
    protected $q;

    /** @var BigInteger */
    protected $dp;

    /** @var BigInteger */
    protected $dq;

    /** @var BigInteger */
    protected $qi;

    /** @var Prime[] */
    protected $otherPrimes = [];
    
    private static $bigIntegerKeys = ['n', 'e', 'd', 'p', 'q', 'dp', 'dq', 'qi'];
    private static $resourceKeyMappings = [
        'n'     => 'n',
        'e'     => 'e',
        'd'     => 'd',
        'p'     => 'p',
        'q'     => 'q',
        'dp'    => 'dmp1',
        'dq'    => 'dmq1',
        'qi'    => 'iqmp',
    ];

    private function __construct(array $data)
    {
        Assert::that($data)
            ->choicesNotEmpty(static::$bigIntegerKeys);
        
        foreach (static::$bigIntegerKeys as $bigIntegerKey) {
            if (!$data[$bigIntegerKey] instanceof BigInteger) {
                throw new InvalidArgumentException(sprintf("key %s must be "
                    . "an instance of %s"), $bigIntegerKey, BigInteger::class);
            }
        }
        
        if (!array_key_exists('rsaToolkit', $data)) {
            throw new InvalidArgumentException("rsaToolkit array key must be "
                . "present in data");
        }
        
        parent::__construct($data['rsaToolkit'], $data['n'], $data['e']);
        
        $this->d    = clone $data['d'];
        $this->p    = clone $data['p'];
        $this->q    = clone $data['q'];
        $this->dp   = clone $data['dp'];
        $this->dq   = clone $data['dq'];
        $this->qi   = clone $data['qi'];
    }
    
    /**
     * 
     * @param resource $privateKey
     * @return PrivateKey
     * @throws InvalidArgumentException
     */
    public static function fromResource($privateKey)
    {
        if (!is_resource($privateKey)) {
            throw new InvalidArgumentException("Argument must be a resource.");
        }

        $details = openssl_pkey_get_details($privateKey);
        
        Assert::that($details)
            ->isArray()
            ->keyExists('rsa');
        
        Assert::that($details['rsa'])
            ->isArray()
            ->choicesNotEmpty(static::$resourceKeyMappings);
        
        $dataConverted = [];

        foreach (static::$resourceKeyMappings as $key => $resourceKey) {
            $dataConverted[$key] = new BigInteger($details['rsa'][$resourceKey], 256);
        }
        
        return static::fromArray(array_merge($dataConverted));
    }

    public static function fromJWKData(array $data)
    {
        $dataConverted = [];
        
        foreach ($data as $key => $value) {
            if (array_key_exists($key, static::$bigIntegerKeys)) {
                $dataConverted[$key] = BigInteger::fromBase64UrlSafe($value);
            }
        }

        if (isset($data['oth']) && is_array($data['oth'])) {
            $primes = [];
            
            foreach ($data['oth'] as $primeData) {
                Assert::that($primeData)->isArray()->choicesNotEmpty(['r', 'd', 't']);
                $primes[] = new Prime(
                    BigInteger::fromBase64UrlSafe($primeData['r']), 
                    BigInteger::fromBase64UrlSafe($primeData['d']), 
                    BigInteger::fromBase64UrlSafe($primeData['t'])
                );
            }
            
            $dataConverted['oth'] = $primes;
        }

        return static::fromArray($dataConverted);
    }
    
    public static function fromArray(array $data)
    {
        $bigIntKeys = static::$bigIntegerKeys;
        
        Assert::that($data)
            ->choicesNotEmpty($bigIntKeys);
        
        if (!isset($data['rsaToolkit'])) {
            $data['rsaToolkit'] = new phpseclibRSA(); 
        }
        
        $builder =  new static($data);
        
        foreach (static::$arrayMappings as $key => $method) {
            if (array_key_exists($key, $data)) {
                $builder->{$method}($data[$key]);
            }
        }
        
        return $builder;
    }
    
    /**
     * Set other primes if more than two primes are used in this key
     *
     * @param Prime[] $otherPrimes
     */
    public function setOtherPrimes(array $otherPrimes)
    {
        Assert::that($otherPrimes)
            ->all()
            ->isInstanceOf(Prime::class);

        $this->otherPrimes = $otherPrimes;
    }

    public function build() : JWK
    {
        return new PrivateKey($this);
    }
}
