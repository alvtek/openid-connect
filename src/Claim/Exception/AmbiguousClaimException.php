<?php

namespace Alvtek\OpenIdConnect\Claim\Exception;

use Alvtek\OpenIdConnect\Exception\RuntimeException;

/**
 * This exception should be triggered when a unique claim is expected, but 
 * multiple claims of that type are present within a collection. 
 * 
 * i.e the 'iss' or 'exp' registered claims in the context of a JWT should be 
 * present no more than once within a JWT otherwise these claims become
 * ambiguous.
 */
class AmbiguousClaimException extends RuntimeException
{
    
}