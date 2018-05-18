<?php namespace App\Http\Controllers;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Keychain;
use Lcobucci\JWT\Signer\Rsa\Sha256;

class TokenController
{

    public function generateToken()
    {
        $signer = new Sha256();
        $keychain = new Keychain();

        $token = (new Builder())->setIssuer('http://mydomain.com') // Configures the issuer (iss claim)
                                ->setAudience('http://mydomain.com') // Configures the audience (aud claim)
                                ->setIssuedAt(time()) // Configures the time that the token was issue (iat claim)
                                ->setExpiration(time() + 3600) // Configures the expiration time of the token (nbf claim)
                                ->set('uid', 1) // Configures a new claim, called "uid"
                                ->sign($signer, $keychain->getPrivateKey(getenv('JWT_PRIV_KEY')))
                                ->getToken(); // Retrieves the generated token

        return response(['token' => (string) $token], 200);
    }

    public function protectedEndpoint()
    {
        return response(['message' => 'You have hit a protected endpoint!'], 200);
    }
}