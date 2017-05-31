<?php namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Keychain;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\ValidationData;

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

        return response($token, 200);
    }

    public function validateToken(Request $request)
    {
        $data = new ValidationData();
        $signer = new Sha256();
        $keychain = new Keychain();

        try {
            $token = (new Parser())->parse($request->bearerToken()); // Parses from a string
        } catch (Exception $exception) {
            return response(['Message' => 'Error: Bad token.'], 403);
        }

        if(!$token->verify($signer, $keychain->getPublicKey(getenv('JWT_PUB_KEY'))))
            return response(['Message' => 'Error: Expired token.'], 401);

        if($token->validate($data)) {
            return response(['Message' => 'Valid token'], 200);
        } else return response(['Message' => 'Error: Unauthorised token.'], 401);
    }
}