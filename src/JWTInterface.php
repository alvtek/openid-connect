<?php

declare(strict_types=1);

namespace Alvtek\OpenIdConnect;

use Alvtek\OpenIdConnect\Claim\ClaimCollection;
use Alvtek\OpenIdConnect\JWS;
use Alvtek\OpenIdConnect\Provider;

interface JWTInterface
{
    public static function fromJWS(JWS $jws) : JWTInterface;
    public static function createJWT(ClaimCollection $claims) : JWTInterface;
    public function claims() : ClaimCollection;
    public function isExpiredAtTimestamp(int $timestamp) : bool;
    public function isEarly(int $timestamp) : bool;
    public function issuedByProvider(Provider $provider) : bool;
}
