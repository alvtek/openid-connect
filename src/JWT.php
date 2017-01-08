<?php

declare(strict_types=1);

namespace Alvtek\OpenIdConnect;

use Alvtek\OpenIdConnect\Claim\ClaimCollection;
use Alvtek\OpenIdConnect\Claim\Exception\AmbiguousClaimException;
use Alvtek\OpenIdConnect\JWS\Exception\UnexpectedPayloadException;
use Alvtek\OpenIdConnect\JWTInterface;
use Alvtek\OpenIdConnect\Provider;
use Alvtek\OpenIdConnect\Uri;

final class JWT implements JWTInterface
{
    const ISSUER = 'iss';
    const SUBJECT = 'sub';
    const AUDIENCE = 'aud';
    const EXPIRATION_TIME = 'exp';
    const NOT_BEFORE = 'nbf';
    const ISSUED_AT = 'iat';
    const JWT_ID = 'jti';
    
    private $registeredClaims = [
        self::ISSUER,
        self::SUBJECT,
        self::AUDIENCE,
        self::EXPIRATION_TIME,
        self::NOT_BEFORE,
        self::ISSUED_AT,
        self::JWT_ID,
    ];
    
    /** 
     * @var ClaimCollection 
     */
    private $claims;
    
    private function __construct(ClaimCollection $claims)
    {
        // Check registered claims are unique
        foreach ($this->registeredClaims as $registeredClaim) {
            $registeredClaimCollection = 
                $claims->getClaimsByType($registeredClaim);

            $registeredClaimCount = \count($registeredClaimCollection);

            if ($registeredClaimCount > 1) {
                throw new AmbiguousClaimException(\sprintf("The registered "
                    . "claim '%s' is ambiguous, a collection should only "
                    . "present one claim of this type but %d occurrences "
                    . "were found.", $registeredClaim, $registeredClaimCount));
            }
        }
        
        $this->claims = $claims;
    }

    /**
     * @param JWS $jws
     * @return JWT
     * @throws UnexpectedPayloadException
     */
    public static function fromJWS(JWS $jws)
    {
        $rawPayload = $jws->rawPayload();
        $data = \json_decode($rawPayload, true);
        if (null === $data) {
            throw new UnexpectedPayloadException("The JWS does not appear to have a valid JSON payload.");
        }
        $claims = ClaimCollection::fromArray($data);
        
        return new static($claims);
    }
    
    /**
     * @param ClaimCollection $claims
     * @return JWT
     */
    public static function createJWT(ClaimCollection $claims)
    {
        return new static($claims);
    }

    /**
     * @return ClaimCollection
     */
    public function claims()
    {
        return $this->claims;
    }

    /**
     * @param int $timestamp
     * @return bool
     */
    public function isExpiredAtTimestamp(int $timestamp) : bool
    {
        if (!$this->claims->hasClaimType(self::EXPIRATION_TIME)) {
            return false;
        }
        
        $expirationClaim = $this->claims->getUniqueClaimByType(
            self::EXPIRATION_TIME);
        
        if ($timestamp > $expirationClaim->value()) {
            return true;
        }

        return false;
    }

    /**
     * @param int $timestamp
     * @return bool
     */
    public function isEarly(int $timestamp) : bool
    {
        if (!$this->claims->hasClaimType(self::NOT_BEFORE)) {
            return false;
        }
        
        $notBeforeClaim = $this->claims->getUniqueClaimByType(self::NOT_BEFORE);
        
        if ($timestamp < $notBeforeClaim->value()) {
            return true;
        }

        return false;
    }

    /**
     * @param Provider $provider
     * @return bool
     */
    public function issuedByProvider(Provider $provider) : bool
    {
        if (!$this->claims->hasClaimType(self::ISSUER)) {
            return false;
        }
        
        $issuerClaim = $this->claims->getUniqueClaimByType(self::ISSUER);
        
        return $provider->issuerEquals(Uri::fromString(
           $issuerClaim->value()
        ));
    }
}
