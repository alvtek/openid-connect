<?php

declare(strict_types=1);

namespace Alvtek\OpenIdConnect;

interface SerialisableInterface
{
    /**
     * Serialise an object to an array
     * 
     * @return array
     */
    public function serialise() : array;
}
