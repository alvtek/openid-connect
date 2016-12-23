<?php

namespace Alvtek\OpenIdConnectTest;

use Alvtek\OpenIdConnect\RelyingParty;
use Alvtek\OpenIdConnect\Uri;

class RelyingPartyTest extends \PHPUnit_Framework_TestCase
{
    public function testClientInstantiation()
    {
        new RelyingParty(
            Uri::fromString('https://test.com'),
            'testClient', 
            'testClient', 
            'My client secret' 
        );
    }

    public function testLoginQuery()
    {
        $client = new RelyingParty(
            Uri::fromString('https://test.com'),
            'testClient', 
            'testClient', 
            'My client secret'
        );
        
        $query = $client->getLoginQuery(
            Uri::fromString('https://relyingparty.com'), 
            ['openid', 'profile', 'email'],
            'id_token token',
            'mytestnonce'
        );
        
        $this->assertEquals(
            $query,
            "client_id=testClient&redirect_uri=https%3A%2F%2Frelyingparty.com&response_mode=form_post&response_type=id_token+token&scope=openid+profile+email&nonce=mytestnonce&acr_values=tenant%3AtestClient",
            "Expecting login query to match string"
        );
    }
}