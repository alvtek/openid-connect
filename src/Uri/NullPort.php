<?php

namespace Alvtek\OpenIdConnect\Uri;

use Alvtek\OpenIdConnect\Uri\PortInterface;

class NullPort implements PortInterface
{
    public function __toString()
    {
        return "";
    }
}
