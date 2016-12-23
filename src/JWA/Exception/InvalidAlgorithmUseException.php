<?php

namespace Alvtek\OpenIdConnect\JWA\Exception;

use Alvtek\OpenIdConnect\Exception\RuntimeException;

/**
 * The primary use case for this exception is when an application attempts
 * to verify or sign data using the 'None' algorithm as defined in the
 * OpenID spec.
 */
class InvalidAlgorithmUseException extends RuntimeException
{
    
}