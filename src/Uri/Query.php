<?php

declare(strict_types=1);

namespace Alvtek\OpenIdConnect\Uri;

/**
 * A Uri query string is basically a bunch of key value pairs. This object
 * therefore models a query string as an array with key value pairs. 
 * Multidimensional arrays are valid as they are in query strings. Casting a 
 * Query object to a string will output the query, properly URL encoded and with
 * the question mark character prepended.
 */

class Query
{
    /**
     * @var array
     */
    private $values;

    /**
     * @param array $values Key value array of the Uri
     */
    public function __construct(array $values)
    {
        $this->values = $values;
    }

    public static function fromQueryString(string $queryString) : Query
    {
        $values = [];
        parse_str($queryString, $values);
        
        return new static($values);
    }

    public function __toString()
    {
        return empty($this->values) ? '' : '?' . \http_build_query($this->values);
    }

    /**
     * @return array
     */
    public function toArray() : array
    {
        return $this->values;
    }

    /**
     * @param \Alvtek\OpenIdConnect\Uri\Query $query
     * 
     * @return \Alvtek\OpenIdConnect\Uri\Query
     */
    public function withQuery(Query $query) : Query
    {
        $values = array_merge($this->values, $query->values);
        return new static($values);
    }
}
