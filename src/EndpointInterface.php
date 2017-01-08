<?php

declare(strict_types=1);

namespace Alvtek\OpenIdConnect;

use Alvtek\OpenIdConnect\Uri;

interface EndpointInterface
{
    public function __toString();
    public function type() : string;
    public function uri() : Uri;
    public function equals(EndpointInterface $endpoint) : bool;
}
