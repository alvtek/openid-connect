<?php

declare(strict_types=1);

namespace Alvtek\OpenIdConnect;

use Alvtek\OpenIdConnect\EndpointInterface;
use Alvtek\OpenIdConnect\Uri;

/**
 * Description of Endpoint
 */
final class Endpoint implements EndpointInterface
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
    public  function __construct(string $type, Uri $uri)
    {
        $this->type = $type;
        $this->uri = $uri;
    }
    
    public function __toString()
    {
        return (string) $this->uri;
    }
    
    /** @return string */
    public function type() : string
    {
        return $this->type;
    }

    /** @return Uri */
    public function uri()
    {
        return $this->uri;
    }
    
    public function equals(EndpointInterface $endpoint) : bool
    {
        return (
            $this->type === $endpoint->type() &&
            $this->uri->equals($endpoint->uri())
        );
    }
}
