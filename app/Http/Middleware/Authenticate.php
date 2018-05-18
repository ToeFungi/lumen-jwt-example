<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Contracts\Auth\Factory as Auth;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Keychain;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\ValidationData;

class Authenticate
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth, $data, $signer, $keychain;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
        $this->data = new ValidationData();
        $this->signer = new Sha256();
        $this->keychain = new Keychain();
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!$this->validateToken($request->bearerToken())) {
            return response(['error' => 'Invalid token'], 401);
        }

        return $next($request);
    }

    /**
     * Validates token of incoming request
     *
     * @param $bearerToken
     * @return bool
     */
    private function validateToken($bearerToken)
    {
        try {
            $token = (new Parser())->parse($bearerToken); // Parses from a string
        } catch (Exception $exception) {
            return false;
        }

        if(!$token->verify($this->signer, $this->keychain->getPublicKey(getenv('JWT_PUB_KEY'))))
            return false;

        if(!$token->validate($this->data))
            return false;

        return true;
    }
}