<?php

declare(strict_types=1);

namespace Alvtek\OpenIdConnect\Uri;

use Alvtek\OpenIdConnect\Exception\InvalidArgumentException;

class Scheme
{
    private $value;

    public function __construct(string $scheme)
    {
        if (!strlen($scheme)) {
            $this->value = '';
            return;
        }
        
        if (!\preg_match('/^[a-z]([a-z0-9\+\.-]+)?$/i', $scheme)) {
            throw new InvalidArgumentException(sprintf(
                "%s is not a valid URI scheme",
                $scheme
            ));
        }
        
        $this->value = $scheme;
    }

    public function __toString()
    {
        if (!strlen($this->value)) {
            return '';
        }
        
        return $this->value . ':';
    }
}
