<?php

namespace Alvtek\OpenIdConnectTest;

use Alvtek\OpenIdConnect\JWS;
use Alvtek\OpenIdConnect\JWS\Header;
use Alvtek\OpenIdConnect\JWK\RSA\PrivateKey\PrivateKeyBuilder as RSAPrivateKeyBuilder;
use Alvtek\OpenIdConnect\JWA;
use Alvtek\OpenIdConnect\JWT;

use Alvtek\OpenIdConnect\Provider;
use Alvtek\OpenIdConnect\Provider\Option;
use Alvtek\OpenIdConnect\Provider\Option\OptionCollection;

use Alvtek\OpenIdConnect\JWS\Exception\InvalidJWSException;

use PHPUnit\Framework\TestCase;

class JWSTest extends TestCase
{
    private $validJWS;
    
    private $rsaPrivateKey;
    
    public function setup()
    {
        $this->validJWS = file_get_contents(TEST_ASSETS . DIRECTORY_SEPARATOR 
            . 'accesstoken.txt');
        
        $this->rsaPrivateKey = openssl_get_privatekey('file://' .
            TEST_ASSETS . DIRECTORY_SEPARATOR . 'rsa');
    }
    
    public function testJWSFromSerialisation()
    {
        $jws = JWS::fromSerialised($this->validJWS);
        $this->assertEquals($this->validJWS, (string) $jws);
    }

    
    public function testInvalidSegmentCount()
    {
        $this->expectException(InvalidJWSException::class);
        JWS::fromSerialised('saelvkjsaekl.tagkljseklja');
    }

    public function testMalformed()
    {
        $this->expectException(\InvalidArgumentException::class);
        JWS::fromSerialised('saelvkjsaekl.tagkljseklja.agselkjksaelgj');
    }

    public function testAlgorithmSupported()
    {
        $mockProvider = $this->getMockBuilder(Provider::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $mockOptions = $this->getMockBuilder(OptionCollection::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $mockOption = $this->getMockBuilder(Option::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $mockOption->expects($this->once())
            ->method('supports')
            ->with('RS256')
            ->willReturn(true);
        
        $mockOptions->expects($this->once())
            ->method('get')
            ->with(Provider::ID_TOKEN_SIGNING_ALG_VALUES_SUPPORTED)
            ->willReturn($mockOption);
        
        $mockProvider->expects($this->once())
            ->method('options')
            ->willReturn($mockOptions);
        
        $jws = JWS::fromSerialised($this->validJWS);
        $this->assertTrue($jws->providerSupportsAlgorithm($mockProvider));
    }

    /*
    public function testGetNonce()
    {
        $jws = JWS::fromSerialised($this->validJWS);
        var_dump($jws);
        $this->assertEquals('d533646c51', $jws->getNonce());
    }
     *
     */

    public function testExpiry()
    {
        $privateKey = RSAPrivateKeyBuilder::fromResource($this->rsaPrivateKey)->build();
        
        $payload = json_encode([
            'iss' => 'https://some-secure-openid-provider.net',
            'aud' => 'MyClientApp',
            'exp' => 10,
            'nbf' => 1,
            'nonce' => 'd533646c51',
            'iat' => 0,
            'at_hash' => 'eSDeW2id7M1S5XaGvHB5LA',
            'sid' => 'f0dd04edf0ee22280b65e3a2422dc6f3',
            'sub' => '6ecb65e4-a577-485a-8ec2-9e856263fd57',
            'auth_time' => 0,
            'idp' => 'idsrv',
            'amr' => ['password']
        ], JSON_UNESCAPED_SLASHES);
        
        $header = new Header([
            'typ' => Header::TYPE_JWT,
            'alg' => JWA::RS256,
            'x5t' => 'test',
            'kid' => 'test',
        ]);
        
        $jws = JWS::createJWS($header, $privateKey, $payload);
        $jwt = JWT::fromJWS($jws);
        
        $this->assertTrue($jwt->isExpiredAtTimestamp(11), 
            "The created token should be recognised as expired");
        
        $this->assertFalse($jwt->isExpiredAtTimestamp(9), 
            "The created token should be recognised as not expired");

        /*
        $notExpiredJwt = Jwt::createRS256FromData([
            'typ' => 'JWT',
            'alg' => 'RS256',
            'x5t' => 'test',
            'kid' => 'test',
        ], [
            'iss' => 'http://imaccessportal-dev.eu-west-1.elasticbeanstalk.com/access',
            'aud' => 'WcpTest',
            'exp' => time() + 3600,
            'nbf' => time() - 7200,
            'nonce' => 'd533646c51',
            'iat' => time() - 7200,
            'at_hash' => 'eSDeW2id7M1S5XaGvHB5LA',
            'sid' => 'f0dd04edf0ee22280b65e3a2422dc6f3',
            'sub' => '6ecb65e4-a577-485a-8ec2-9e856263fd57',
            'auth_time' => time() - 7200,
            'idp' => 'idsrv',
            'amr' => ['password']
        ], $this->testPrivateKey);

        $this->assertFalse($notExpiredJwt->isExpired(), "The created token should be recognised as not expired");
         * */
    }

    /*
    public function testJwtEarly()
    {
        $earlyJwt = Jwt::createRS256FromData([
            'typ' => 'JWT',
            'alg' => 'RS256',
            'x5t' => 'test',
            'kid' => 'test',
        ],[
            'iss' => 'http://imaccessportal-dev.eu-west-1.elasticbeanstalk.com/access',
            'aud' => 'WcpTest',
            'exp' => time() + 3600,
            'nbf' => time() + 60,
            'nonce' => 'd533646c51',
            'iat' => time(),
            'at_hash' => 'eSDeW2id7M1S5XaGvHB5LA',
            'sid' => 'f0dd04edf0ee22280b65e3a2422dc6f3',
            'sub' => '6ecb65e4-a577-485a-8ec2-9e856263fd57',
            'auth_time' => time(),
            'idp' => 'idsrv',
            'amr' => ['password']
        ], $this->testPrivateKey);

        $this->assertTrue($earlyJwt->isEarly(), "The created token should be recognised as early");

    }

    public function testJwtIssuerMatch()
    {
        $issuer = 'http://imaccessportal-dev.eu-west-1.elasticbeanstalk.com/access';
        $issuerInvalid = 'http://some-unknown-issuer.com';

        $mockProvider = $this->getMockBuilder(Provider::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockProvider->expects($this->at(0))
            ->method('issuerValid')
            ->with($this->equalTo($issuer))
            ->will($this->returnValue(true));
        
        $jwt1 = Jwt::createRS256FromData([
            'typ' => 'JWT',
            'alg' => 'RS256',
            'x5t' => 'test',
            'kid' => 'test',
        ],[
            'iss' => $issuer,
            'aud' => 'WcpTest',
            'exp' => time() + 3600,
            'nbf' => time() + 60,
            'nonce' => 'd533646c51',
            'iat' => time(),
            'at_hash' => 'eSDeW2id7M1S5XaGvHB5LA',
            'sid' => 'f0dd04edf0ee22280b65e3a2422dc6f3',
            'sub' => '6ecb65e4-a577-485a-8ec2-9e856263fd57',
            'auth_time' => time(),
            'idp' => 'idsrv',
            'amr' => ['password']
        ], $this->testPrivateKey);

        $this->assertTrue($jwt1->issuedByProvider($mockProvider),
            "The issuer value is expected to be matched");

        $mockProvider
            ->expects($this->at(0))
            ->method('issuerValid')
            ->with($this->equalTo($issuerInvalid))
            ->will($this->returnValue(false));

        $jwt2 = Jwt::createRS256FromData([
            'typ' => 'JWT',
            'alg' => 'RS256',
            'x5t' => 'test',
            'kid' => 'test',
        ],[
            'iss' => $issuerInvalid,
            'aud' => 'WcpTest',
            'exp' => time() + 3600,
            'nbf' => time() + 60,
            'nonce' => 'd533646c51',
            'iat' => time(),
            'at_hash' => 'eSDeW2id7M1S5XaGvHB5LA',
            'sid' => 'f0dd04edf0ee22280b65e3a2422dc6f3',
            'sub' => '6ecb65e4-a577-485a-8ec2-9e856263fd57',
            'auth_time' => time(),
            'idp' => 'idsrv',
            'amr' => ['password']
        ], $this->testPrivateKey);

        $this->assertFalse($jwt2->issuedByProvider($mockProvider));
    }
     * 
     */
}