<?php

namespace Alvtek\OpenIdConnect;

use Alvtek\OpenIdConnect\Uri;

use Assert\Assert;

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
    public function __construct($type, Uri $uri)
    {
        Assert::that($type)
            ->notEmpty()
            ->string();

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
