<?php

declare(strict_types=1);

namespace Alvtek\OpenIdConnect;

interface ClaimInterface
{
    public function type() : string;
    public function value();
    public function equals(ClaimInterface $claim) : bool;
}
