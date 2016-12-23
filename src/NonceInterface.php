<?php

namespace Alvtek\OpenIdConnect;

/**
 * This interface is for use with an nonce to ensure that the response has 
 * been initiated via the relying party (client) and not some third-party.
 * There is no concrete implementation of this interface as it will vary
 * between applications and will depend on what level of security
 * is required.
 */
interface NonceVerificationInterface
{
    /**
     * This method may generate a nonce for verification later on.
     */
    public function generate();
    
    /** 
     * This method will verify a previously generated nonce is valid.
     * 
     * @return boolean 
     */
    public function verify($nonce);
}