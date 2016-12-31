<?php

declare(strict_types=1);

namespace Alvtek\OpenIdConnect\Uri;

use Alvtek\OpenIdConnect\Exception\InvalidArgumentException;

class Fragment
{
    private $value;
    
    public function __construct(string $fragment)
    {
        $this->value = $fragment;
    }
    
    public function __toString()
    {
        return (strlen($this->value)) ? "#{$this->value}" : '';
    }
}
