<?php

declare(strict_types=1);

namespace Alvtek\OpenIdConnect\JWS;

use Alvtek\OpenIdConnect\JWS\Header\Exception\InvalidJWSHeaderException;
use Alvtek\OpenIdConnect\JWS\Header\Exception\UndefinedParameterException;

class Header
{
    const TYPE_JWT = 'JWT';
    
    const ALGORITHM = 'alg';
    const JSON_WEB_KEY_SET_URL = 'jku';
    const JSON_WEB_KEY = 'jwk';
    const KEY_ID = 'kid';
    const X509_URL = 'x5u';
    const X509_CERTIFICATE_CHAIN = 'x5c';
    const X509_CERTIFICATE_SHA1_THUMBPRINT = 'x5t';
    const X509_CERTIFICATE_SHA256_THUMBPRINT = 'x5t#S256';
    const TYPE = 'typ';
    const CONTENT_TYPE = 'cty';
    const CRITICAL = 'crit';
    
    private static $validParameters = [
        self::ALGORITHM,
        self::JSON_WEB_KEY_SET_URL,
        self::JSON_WEB_KEY,
        self::KEY_ID,
        self::X509_URL,
        self::X509_CERTIFICATE_CHAIN,
        self::X509_CERTIFICATE_SHA1_THUMBPRINT,
        self::X509_CERTIFICATE_SHA256_THUMBPRINT,
        self::TYPE,
        self::CONTENT_TYPE,
        self::CRITICAL,
    ];
    
    /** @var array */
    private $data;
    
    /**
     * @param array $data
     * @throws InvalidJWSHeaderException
     */
    public function __construct(array $data)
    {
        if (!key_exists(self::ALGORITHM, $data) || empty($data[self::ALGORITHM])) {
            throw new InvalidJWSHeaderException("%s key must be set in the "
                . "JWS header data.");
        }
        
        foreach ($data as $key => $value) {
            if (!in_array($key, static::$validParameters)) {
                throw new InvalidJWSHeaderException(sprintf("Unrecognised "
                    . "header parameter '%s' in JWS header.", $key));
            }
        }
        
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->data;
    }
    
    /**
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->data, JSON_UNESCAPED_SLASHES);
    }
    
    /**
     * @param string $key
     * @return boolean
     */
    public function hasParameter($key)
    {
        return isset($this->data[$key]);
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function getParameter($key)
    {
        if (!isset($this->data[$key])) {
            throw new UndefinedParameterException(sprintf("The parameter with "
                . "key '%s' is undefined", $key));
        }
        
        return $this->data[$key];
    }
}
