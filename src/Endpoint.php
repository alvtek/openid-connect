<?php

declare(strict_types=1);

namespace Alvtek\OpenIdConnect;

use Alvtek\OpenIdConnect\Exception\InvalidArgumentException;

/**
 * Description of Endpoint
 */
class Endpoint
{
    /** @var string */
    private $type;
    
    /** @var Uri */
    private $uri;

    /**
     * @param string $type
     * @param Uri $uri
     * @throws UnrecognisedEndpointException
     */
    public function __construct(string $type, Uri $uri)
    {
        if (empty($type)) {
            throw new InvalidArgumentException("type argument must be a non empty string");
        }

        $this->type = $type;
        $this->uri = $uri;
    }

    public function __toString()
    {
        return (string) $this->uri;
    }
    
    /** @return string */
    public function type()
    {
        return $this->type;
    }

    /** @return Uri */
    public function uri()
    {
        return $this->uri;
    }
    
    public function equals(Endpoint $endpoint)
    {
        return (
            $this->type === $endpoint->type() &&
            $this->uri->equals($endpoint->uri())
        );
    }
}
