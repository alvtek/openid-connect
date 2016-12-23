# alvtek/openid-connect-client

[![Build Status](https://travis-ci.org/alvtek/openid-connect-client.svg?branch=develop)](https://secure.travis-ci.org/alvtek/openid-connect-client)

**A PHP OpenID Connect client library.**

I created this client library implementing the JOSE (JavaScript Object 
Signing and Encryption) specification targeted towards OpenID Connect clients.
The library should be considered experimental as it only supports RS256, RS384 
and RS512 algorithms and key types.

## Compatibility
The project was originally designed for use with Identity Server 3, a C#
OpenID Connect Provider but I've gradually made it more generic so it should
work with any OpenID provider.

As of yet the library is lacking many algorithms and key types, it currently
only supports RSA public / private key encryption but doesn't support
ECDSA, HMAC and RSASSA-PSS. 

The library currently only supports JWS's and not JWE's.

## Contributing
If you would like to contribute to this project please contact me. I'd be more 
than happy to add implementations for different keys and algorithms. I would
also love to increase code coverage and test the library against various 
different OpenID Connect providers to ensure it follows the OpenID Connect 
standard in different contexts.
