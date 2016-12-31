<?php

declare(strict_types=1);

namespace Alvtek\OpenIdConnect;

use Alvtek\OpenIdConnect\Uri\Scheme;
use Alvtek\OpenIdConnect\Uri\PortInterface;
use Alvtek\OpenIdConnect\Uri\Port;
use Alvtek\OpenIdConnect\Uri\NullPort;
use Alvtek\OpenIdConnect\Uri\Domain;
use Alvtek\OpenIdConnect\Uri\Query;
use Alvtek\OpenIdConnect\Uri\Fragment;
use Alvtek\OpenIdConnect\Uri\Path;

use Alvtek\OpenIdConnect\Exception\InvalidArgumentException;

/**
 * Immutable Uri value object
 */
class Uri
{
    /** 
     * @var Scheme
     */
    private $scheme;

    /** 
     * @var Domain 
     */
    private $domain;

    /** 
     * @var PortInterface 
     */
    private $port;
    
    /** 
     * @var string 
     */
    private $user;
    
    /** 
     * @var string 
     */
    private $password;
    
    /** 
     * @var Path 
     */
    private $path;

    /** 
     * @var Query 
     */ 
    private $query;

    /** 
     * @var Fragment
     */
    private $fragmentIdentifier;

    /**
     * @param Domain $domain Required, the domain
     * @param Scheme $scheme Optional URI scheme, defaults to http
     * @param PortInterface $port Optional port, defaults to none
     * @param string $userinfo Userinfo element of the URI
     * @param Path $path Optional path segment of the URI, defaults to none
     * @param Query $query Optional An array of keys and values appended to the URL, defaults to none
     * @param Fragment $fragment Optional the fragment (portion beginning with the # character), defaults to none
     */
    public function __construct(Domain $domain, Scheme $scheme, 
        PortInterface $port, string $user, string $password, Path $path, 
        Query $query, Fragment $fragment)
    {
        $this->domain = $domain;
        $this->scheme = $scheme;
        $this->port = $port;
        $this->user = $user;
        $this->password = $password;
        $this->path = $path;
        $this->query = $query;
        $this->fragmentIdentifier = $fragment;
    }
    
    /**
     * @param type $url
     * 
     * @return Uri
     */
    public static function fromString(string $uri) : Uri
    {
        $scheme         = \parse_url($uri, PHP_URL_SCHEME);
        $domain         = \parse_url($uri, PHP_URL_HOST);
        $port           = \parse_url($uri, PHP_URL_PORT);
        $user           = \parse_url($uri, PHP_URL_USER);
        $pass           = \parse_url($uri, PHP_URL_PASS);
        $path           = \parse_url($uri, PHP_URL_PATH);
        $queryString    = \parse_url($uri, PHP_URL_QUERY);
        $fragment       = \parse_url($uri, PHP_URL_FRAGMENT);
        
        if (null === $domain) {
            throw new InvalidArgumentException(sprintf("%s is not a valid URI", 
                $uri));
        }
        
        return new static(
            new Domain($domain),
            null === $scheme ? new Scheme('') : new Scheme($scheme),
            null === $port ? new NullPort : new Port($port),
            null === $user ? '' : $user,
            null === $pass ? '' : $pass,
            null === $path ? new Path('') : new Path($path),
            null === $queryString ? new Query([]) : Query::fromQueryString($queryString),
            null === $fragment ? new Fragment('') : new Fragment($fragment)
        );
    }
    
    /**
     * @return Scheme
     */
    public function getScheme() : Scheme
    {
        return $this->scheme;
    }
    
    /**
     * @return string
     */
    public function getAuthority() : string
    {
        $authority = '';
        
        $userinfo = $this->getUserInfo();
        
        if (strlen($userinfo)) {
            $authority .= "{$userinfo}@";
        }
        
        $authority .= (string) $this->domain . (string) $this->port;
       
        return $authority;
    }
    
    /**
     * @return string
     */
    public function getUserInfo() : string
    {
        $userInfo = '';
        
        if (strlen($this->user)) {
            $userInfo .= "{$this->user}:";
        }
        
        if (strlen($this->password)) {
            $userInfo .= $this->password;
        }
        
        return $userInfo;
    }
    
    /**
     * @return Domain
     */
    public function getHost() : Domain
    {
        return $this->domain;
    }

    /**
     * @return PortInterface
     */
    public function getPort() : PortInterface
    {
        return $this->port;
    }

    /** 
     * @return Path 
     */
    public function getPath() : Path
    {
        return $this->path;
    }

    /** 
     * @return Query 
     */
    public function getQuery() : Query
    {
        return $this->query;
    }

    /** 
     * @return Fragment 
     */
    public function getFragment() : Fragment
    {
        return $this->fragmentIdentifier;
    }
    
    /**
     * @param Scheme $scheme
     * 
     * @return \Alvtek\OpenIdConnect\Uri
     */
    public function withScheme(Scheme $scheme) : Uri
    {
        return new static($this->domain, $scheme, $this->port, $this->user, 
            $this->password, $this->path, $this->query, $this->fragmentIdentifier);
    }
    
    /**
     * @param string $user
     * @param mixed $password
     * 
     * @return \Alvtek\OpenIdConnect\Uri
     */
    public function withUserInfo(string $user, $password = null) : Uri
    {
        return new static($this->domain, $this->scheme, $this->port, $user, 
            $password, $this->path, $this->query, $this->fragmentIdentifier);
    }
    
    /**
     * @param Domain $host
     * 
     * @return \Alvtek\OpenIdConnect\Uri
     */
    public function withHost(Domain $host) : Uri
    {
        return new static($host, $this->scheme, $this->port, $this->user, 
            $this->password, $this->path, $this->query, $this->fragmentIdentifier);
    }
    
    /**
     * @param PortInterface $port
     * 
     * @return \Alvtek\OpenIdConnect\Uri
     */
    public function withPort(PortInterface $port) : Uri
    {
        return new static($this->domain, $this->scheme, $port, $this->user, 
            $this->password, $this->path, $this->query, $this->fragmentIdentifier);
    }
    
    /**
     * @param type $path
     * 
     * @return \Alvtek\OpenIdConnect\Uri
     */
    public function withPath(Path $path) : Uri
    {
        return new static($this->domain, $this->scheme, $this->port, $this->user, 
            $this->password, $path, $this->query, $this->fragmentIdentifier);
    }

    /**
     * @param string $query
     * 
     * @return \Alvtek\OpenIdConnect\Uri
     */
    public function withQuery(Query $query) : Uri
    {
        return new static($this->domain, $this->scheme, $this->port,
            $this->user, $this->password, $this->path, $query, 
            $this->fragmentIdentifier);
    }
    
    /**
     * @param string $fragment
     * 
     * @return \Alvtek\OpenIdConnect\Uri
     */
    public function withFragment(Fragment $fragment) : Uri
    {
        return new static($this->domain, $this->scheme, $this->port, 
            $this->user, $this->password, $this->path, $this->query, $fragment);
    }

    public function __toString()
    {
        return sprintf(
            '%s//%s%s%s%s',
            (string) $this->scheme,
            $this->getAuthority(),
            (string) $this->path,
            (string) $this->query,
            (string) $this->fragmentIdentifier
        );
    }

    /**
     * @param \Alvtek\OpenIdConnect\Uri $uri
     * 
     * @return bool
     */
    public function equals(Uri $uri) : bool
    {
        return (string) $this === (string) $uri;
    }
}