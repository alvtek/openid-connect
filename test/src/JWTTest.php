<?php

namespace Alvtek\OpenIdConnectTest;

use Alvtek\OpenIdConnect\Claim\ClaimCollection;
use Alvtek\OpenIdConnect\Claim;
use Alvtek\OpenIdConnect\Provider;

use Alvtek\OpenIdConnect\JWT;
use Alvtek\OpenIdConnect\Uri;

use PHPUnit\Framework\TestCase;

class JWTTest extends TestCase
{
    private $claimCollection;
    
    public function setup()
    {
        $this->claimCollection = $this->getMockBuilder(ClaimCollection::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
    
    public function testInstantiation()
    {
        new JWT($this->claimCollection);
    }
    
    public function testExpiration()
    {
        $expirationTimestamp = 1480248865;
        
        // Mock an expiration claim
        $expirationClaim = $this
            ->getMockBuilder(Claim::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $expirationClaim
            ->expects($this->exactly(2))
            ->method('value')
            ->willReturn($expirationTimestamp);
            
        // Mock a collection with the expiration claim
        $this->claimCollection
            ->expects($this->exactly(2))
            ->method('getUniqueClaimByType')
            ->with(JWT::EXPIRATION_TIME)
            ->willReturn($expirationClaim);
        
        $this->claimCollection
            ->expects($this->exactly(2))
            ->method('hasClaimType')
            ->with(JWT::EXPIRATION_TIME)
            ->willReturn(true);
        
        $jwt = new JWT($this->claimCollection);
        
        /* 
         * Let's test an earlier timestamp, this should return false to 
         * the expired check
         */
        $this->assertFalse($jwt->isExpiredAtTimestamp($expirationTimestamp - 3600), 
            "Failed to assert that the JWT has not yet expired.");
        
        $this->assertTrue($jwt->isExpiredAtTimestamp($expirationTimestamp + 3600),
            "Failed to assert that the JWT has expired.");
    }
    
    public function testExpirationClaimNotAvailable()
    {
        $this->claimCollection
            ->expects($this->once())
            ->method('hasClaimType')
            ->with(JWT::EXPIRATION_TIME)
            ->willReturn(false);
        
        $jwt = new JWT($this->claimCollection);
        
        $this->assertFalse($jwt->isExpiredAtTimestamp(12345),
            "Failed to assert that the JWT has not expired");
    }
    
    public function testIssuedByProvider()
    {
        $mockIssuerClaim = $this->getMockBuilder(Claim::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $mockIssuerClaim->expects($this->any())
            ->method('type')
            ->willReturn(JWT::ISSUER);
        
        $mockIssuerClaim->expects($this->any())
            ->method('value')
            ->willReturn('https://my-test-openid-connect-provider.net');
        
        $mockClaimCollection = $this->getMockBuilder(ClaimCollection::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $mockClaimCollection
            ->expects($this->any())
            ->method('hasClaimType')
            ->willReturn(true);
        
        $mockClaimCollection
            ->expects($this->any())
            ->method('getUniqueClaimByType')
            ->willReturn($mockIssuerClaim);
        
        $jwt = new JWT($mockClaimCollection);
    
        $mockProvider = $this->getMockBuilder(Provider::class)
            ->disableOriginalConstructor()
            ->getMock();
    
        $mockProvider->expects($this->at(0))
            ->method('issuerEquals')
            ->willReturn(true);
    
        $mockProvider->expects($this->at(1))
            ->method('issuerEquals')
            ->willReturn(false);
        
        $this->assertTrue($jwt->issuedByProvider($mockProvider));
        $this->assertFalse($jwt->issuedByProvider($mockProvider));
    }
}
