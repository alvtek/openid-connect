<?php

declare(strict_types=1);

namespace Alvtek\OpenIdConnect\Uri;

class Domain
{
    /**
     * @var string
     */
    private $value;
    
    public function __construct(string $domain)
    {
        $this->value = $domain;
    }
    
    public function __toString()
    {
        return $this->value;
    }
}
