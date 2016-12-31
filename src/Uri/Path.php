<?php

declare(strict_types=1);

namespace Alvtek\OpenIdConnect\Uri;

use Alvtek\OpenIdConnect\Exception\InvalidArgumentException;

class Path
{
    /**
     * @var string
     */
    private $value;
    
    public function __construct(string $path)
    {
        if (!strlen($path)) {
            $this->value = '';
            return;
        }
        
        $filteredValue = \parse_url($path, PHP_URL_PATH);
        
        if (null === $filteredValue || strlen($filteredValue) != strlen($path)) {
            throw new InvalidArgumentException(\sprintf(
                "%s is not a valid path", $path));
        }
        
        $this->value = $filteredValue;
    }
    
    public function __toString()
    {
        return $this->value;
    }
    
    public function withAppendedPath(Path $path)
    {
        return new static(\rtrim($this->value, '/') . '/' . \ltrim($path->value, '/'));
    }
}
