<?php

namespace Alvtek\OpenIdConnect;

use Assert\Assert;

/**
 * Immutable Uri value object
 */
class Uri
{
    /** @var string */
    private $scheme = 'http';

    /** @var string */
    private $domain;

    /** @var int */
    private $port;

    /** @var string */
    private $path;

    /** @var array */
    private $query;

    /** @var string */
    private $fragmentId;

    /**
     * @param string $domain Required, the domain
     * @param string $scheme Optional URL scheme, defaults to http
     * @param integer $port Optional port, defaults to none
     * @param string $path Optional path segment of the URL, defaults to none
     * @param array $query Optional An array of keys and values appended to the URL, defaults to none
     * @param string $fragmentId Optional the fragment (portion beginning with the # character), defaults to none
     */
    public function __construct($domain, $scheme = null, $port = null, $path = null,
        $query = null, $fragmentId = null)
    {
        Assert::that($domain)->notEmpty()->string();
        $this->domain = $domain;

        if (!is_null($scheme)) {
            Assert::that($scheme)->notEmpty()->string();
            $this->scheme = $scheme;
        }

        if (!is_null($port)) {
            Assert::that($port)->integer()->greaterOrEqualThan(0);
            $this->port = $port;
        }

        if (!is_null($path)) {
            Assert::that($path)->string();
            $this->path = $path;
        }

        if (!is_null($query)) {
            Assert::that($query)->isArray()->all()->string();
            $this->query = $query;
        }

        if (!is_null($fragmentId)) {
            Assert::that($fragmentId)->string();
            $this->fragmentId = $fragmentId;
        }
    }

    /** @return static */
    public static function fromString($url)
    {
        $scheme      = parse_url($url, PHP_URL_SCHEME);
        $domain      = parse_url($url, PHP_URL_HOST);
        $port        = parse_url($url, PHP_URL_PORT);
        $path        = parse_url($url, PHP_URL_PATH);
        $queryString = parse_url($url, PHP_URL_QUERY);
        $fragmentId  = parse_url($url, PHP_URL_FRAGMENT);

        $query = [];
        if (!empty($queryString)) {
            parse_str($queryString, $query);
        }

        return new static(
            $domain,
            $scheme,
            $port,
            $path,
            $query,
            $fragmentId
        );
    }

    /** @return string */
    public function getScheme()
    {
        return $this->scheme;
    }

    /** @return string */
    public function getDomain()
    {
        return $this->domain;
    }

    /** @return integer */
    public function getPort()
    {
        return $this->port;
    }

    /** @return string */
    public function getPath()
    {
        return $this->path;
    }

    /** @return array */
    public function getQuery()
    {
        return $this->query;
    }

    /** @return string */
    public function getQueryString()
    {
        $query = $this->getQuery();
        if (empty($query)) {
            return '';
        }
        
        return '?' . http_build_query($this->getQuery());
    }

    /** @return string */
    public function getFragmentId()
    {
        return $this->fragmentId;
    }

    public function __toString()
    {
        return sprintf(
            '%s://%s%s%s%s%s',
            $this->scheme,
            $this->domain,
            isset($this->port) ? ":{$this->port}" : '',
            $this->path,
            $this->getQueryString(),
            isset($this->fragmentId) ? "#{$this->fragmentId}" : ''
        );
    }

    /**
     * @param \Alvtek\OpenIdConnect\Uri $uri
     * @return boolean
     */
    public function equals(Uri $uri)
    {
        return (string) $this === (string) $uri;
    }
}