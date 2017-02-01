<?php

declare(strict_types=1);

namespace Alvtek\OpenIdConnect;

use Alvtek\OpenIdConnect\Exception\InvalidArgumentException;
use Alvtek\OpenIdConnect\JWA\JWAFactory;
use Alvtek\OpenIdConnect\JWK\SigningInterface;
use Alvtek\OpenIdConnect\JWK\VerificationInterface;
use Alvtek\OpenIdConnect\JWS\Exception\InvalidJWSException;
use Alvtek\OpenIdConnect\JWS\Header;
use Alvtek\OpenIdConnect\Base64UrlSafeInterface;

/**
 * This class represents a generic Json Web Signature. 
 */
class JWS
{
    /** @var Header */
    private $header;

    /** @var array */
    private $payload;

    /** @var string */
    private $signature;
    
    /** @var string */
    private $message;
    
    /**
     * @param Header $header
     * @param string $payload
     * @param string $signature
     * @param string $message
     */
    private function __construct(Header $header, string $payload, string $signature, string $message)
    {
        if (empty($payload)) {
            throw new InvalidArgumentException("Payload must be a non empty string");
        }
        
        $this->header = $header;
        $this->payload = $payload;
        $this->signature = $signature;
        $this->message = $message;
    }
    
    public static function fromSerialised(Base64UrlSafeInterface $base64, string $string)
    {
        $segments = explode('.', $string);

        if (count($segments) != 3) {
            throw new InvalidJWSException(sprintf('Expecting 3 segments in the '
                . 'serialised Json Web Signature, found %d', count($segments)));
        }
        
        $headerData = json_decode($base64->decode($segments[0]), true);
        
        if (!is_array($headerData) || $headerData === false) {
            throw new InvalidJWSException("The JWS data is invalid");
        }
        
        $payload = $base64->decode($segments[1]);
        $signature = $base64->decode($segments[2]);

        $header = new Header($headerData);
        
        return new JWS($header, $payload, $signature, "{$segments[0]}.{$segments[1]}");
    }

    /**
     * @param Header $header
     * @param SigningInterface $key
     * @param string $payload Can be any string but is usually a Json encoded JWT
     * @return static
     */
    public static function createJWS(Base64UrlSafeInterface $base64, Header $header, SigningInterface $key, string $payload)
    {
        if (empty($payload)) {
            throw new InvalidArgumentException("Payload must be a non empty string");
        }
        
        $headerEncoded = rtrim($base64->encode($header->toJson()), '=');
        $payloadEncoded = rtrim($base64->encode($payload), '=');
        
        $jwa = JWAFactory::createFromName($header->getParameter(Header::ALGORITHM));

        $signature = $key->sign($jwa, "$headerEncoded.$payloadEncoded");
        
        return new JWS($header, $payload, $signature, "$headerEncoded.$payloadEncoded");
    }
    
    /** 
     * @return string 
     */
    public function serialise(Base64UrlSafeInterface $base64)
    {
        return $this->message() . '.' . rtrim($base64->encode($this->signature), '=');
    }
    
    /** @return string */
    public function message()
    {
        return $this->message;
    }
    
    /** @return string */
    public function type()
    {
        return $this->header->getParameter(Header::TYPE);
    }

    /** @return string */
    public function signingKeyId()
    {
        return $this->header->getParameter(Header::KEY_ID);
    }

    /**
     * @param VerificationInterface $key
     * @return boolean
     */
    public function verifySignature(VerificationInterface $key)
    {
        return $key->verify(
            JWAFactory::createFromName(
                $this->header->getParameter(Header::ALGORITHM)),
            $this->message(),
            $this->signature
        );
    }

    /**
     * Verify the nonce in the JWS. You should implement your own nonce
     * verification strategy using the Nonce Verification Interface.
     * 
     * @param VerificationInterface $verifier
     * @return boolean
     */
    public function verifyNonce(NonceVerificationInterface $verifier)
    {
        if (!isset($this->payload['nonce'])) {
            return false;
        }
        
        return $verifier->verify($this->payload['nonce']);
    }

    public function rawPayload()
    {
        return $this->payload;
    }

    /**
     * @param Provider $provider
     * @return boolean
     */
    public function providerSupportsAlgorithm(Provider $provider)
    {
        try {
            return $provider
                ->options()
                ->get(Provider::ID_TOKEN_SIGNING_ALG_VALUES_SUPPORTED)
                ->supports($this->header->getParameter(Header::ALGORITHM));
        } catch (\Exception $e) {
            return false;
        }
    }
}
