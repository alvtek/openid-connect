<?php

namespace Alvtek\OpenIdConnectTest;

use Alvtek\OpenIdConnect\RelyingParty;
use Alvtek\OpenIdConnect\Uri;

class RelyingPartyTest extends \PHPUnit_Framework_TestCase
{
    public function testClientInstantiation()
    {
        RelyingParty::implicitClient(
            Uri::fromString('https://test.com'),
            'testClient'
        );
        
        RelyingParty::codeFlowClient(
            Uri::fromString('https://test.com'),
            'testClient',
            'clientSecret'
        );
    }
}
