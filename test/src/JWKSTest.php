<?php

namespace Alvtek\OpenIdConnectTest;

use Alvtek\OpenIdConnect\JWK;
use Alvtek\OpenIdConnect\JWKS;
use Alvtek\OpenIdConnect\JWS;
use PHPUnit\Framework\TestCase;

class JWKSTest extends TestCase
{
    /* @var array */
    private $jwksData;

    /* @var stdClass */
    private $jwksDataObject;

    /* @var array */
    private $jwsData;

    /* @var string */
    private $testPrivateKey;
    
    public function setup()
    {
        $jwksJson = file_get_contents(
            'test' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'jwks.json'
        );
        
        $this->jwksData = json_decode($jwksJson, true);
        
        $this->jwksDataObject = json_decode($jwksJson);
        
        $this->jwsData = file_get_contents(
            'test' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'idtoken.txt'
        );
        $this->testPrivateKey = file_get_contents(
            'test' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'rsa'
        );
    }

    public function testValid()
    {
        $jwksFromArray = JWKS::fromJWKSData($this->jwksData);
        $this->assertInstanceOf(JWKS::class, $jwksFromArray);
    }

    public function testInvalid()
    {
        $this->expectException(\InvalidArgumentException::class);
        JWKS::fromJWKSData([]);
    }

    public function testSignatureVerification()
    {
        $jwks = JWKS::fromJWKSData($this->jwksData);
        $jws = JWS::fromSerialised($this->jwsData);
        
        $this->assertTrue($jwks->verifyJWS($jws), "The JWT and JWKS fixtures "
            . "should produce a valid and verifiable signature");
    }

    public function testArrayAccess()
    {
        $jwks = JWKS::fromJWKSData($this->jwksData);

        $this->assertCount(1, $jwks);
        $this->assertInstanceOf(JWK::class, $jwks->current());
        $this->assertEquals(0, $jwks->key());
        $jwks->next();
        $this->assertNotTrue($jwks->valid());
        reset($jwks);

        /* @var $jwk Jwk */
        foreach ($jwks as $jwk) {
            $this->assertInstanceOf(JWK::class, $jwk);
        }
    }

    public function testMissingKey()
    {
        $jwks = JWKS::fromJWKSData($this->jwksData);
        $header = [
            'typ' => 'JWT',
            'alg' => 'RS256',
            'x5t' => 'key_missing_from_jwks',
            'kid' => 'key_missing_from_jwks',
        ];

        $payload = [
            'iss' => 'https://my-test-openid-connect-provider.net',
            'aud' => 'Client1',
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
        ];

        $headerEncoded = Base64UrlSafe::encode(json_encode($header));
        $payloadEncoded = Base64UrlSafe::encode(json_encode($payload));

        $signature = '';
        $result = openssl_sign($headerEncoded . '.' .
            $payloadEncoded, $signature, $this->testPrivateKey, 'SHA256');

        $this->assertTrue($result, "Failed to assert that message could be signed using openssl_sign");
        $signatureEncoded = Base64UrlSafe::encode($signature);

        $validToken = JWS::fromSerialised(
            "$headerEncoded.$payloadEncoded.$signatureEncoded");

        $this->assertFalse($jwks->verifyJWS($validToken));
    }
    
    public function testJsonSerialisation()
    {
        $jwks = JWKS::fromJWKSData($this->jwksData);
        $jwksSerialised = $jwks->jsonSerialize();
    }
}
