<?php

declare(strict_types=1);

namespace Alvtek\OpenIdConnect\JWA;

interface DigestInformationProviderInterface
{
    public function getDigestInfo() : string;
}
