<?php

declare(strict_types=1);

namespace Alvtek\OpenIdConnect\Uri;

use Alvtek\OpenIdConnect\Uri\PortInterface;
use Alvtek\OpenIdConnect\Exception\InvalidArgumentException;

class Port implements PortInterface
{
    private $portRange = [
        'min' => 0,
        'max' => 65535,
    ];

    /** 
     * @var int 
     */
    private $value;

    public function __construct(int $port)
    {
        if ($port < $this->portRange['min'] || $port > $this->portRange['max']) {
            throw new InvalidArgumentException(
                sprint(
                    "Argument must be a valid port number between %d and %d", 
                    $this->portRange['min'], 
                    $this->portRange['max']
                )
            );
        }

        $this->value = $port;
    }

    public function __toString()
    {
        return ':' . $this->value;
    }

    public function value() : int
    {
        return $this->value;
    }
}
