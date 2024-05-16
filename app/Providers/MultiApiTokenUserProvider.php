<?php

namespace App\Providers;

use App\ApiToken;
use App\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class MultiApiTokenUserProvider implements UserProvider
{

    /**
     * @var ApiToken
     */
    private $token;

    /**
     * @var User
     */
    private $user;

    /**
     * MultiApiTokenUserProvider constructor.
     * @param User $user
     * @param ApiToken $token
     */
    public function __construct (User $user, ApiToken $token) {
        $this->user = $user;
        $this->token = $token;
    }

    /**
     * @param mixed $identifier
     * @return Authenticatable|null
     */
    public function retrieveById ($identifier) {
        return $this->user->find($identifier);
    }

    /**
     * @param mixed $identifier
     * @param string $token
     * @return \Illuminate\Contracts\Auth\Authenticatable|mixed|null
     */
    public function retrieveByToken ($identifier, $token) {

        $token = $this->token->with('user')
            ->where($identifier, $token)->first();

        return $token && $token->user ? $token->user : null;

    }

    /**
     * @param Authenticatable $user
     * @param string $token
     */
    public function updateRememberToken (Authenticatable $user, $token) {



    }

    /**
     * @param array $credentials
     * @return Authenticatable|null
     */
    public function retrieveByCredentials (array $credentials) {

        $user = $this->user;

        foreach ($credentials as $credentialKey => $credentialValue) {
            if (!Str::contains($credentialKey, 'password')) {
                $user->where($credentialKey, $credentialValue);
            }
        }

        return $user->first();

    }

    /**
     * @param Authenticatable $user
     * @param array $credentials
     * @return bool
     */
    public function validateCredentials (Authenticatable $user, array $credentials) {

        $plain = $credentials['password'];
        return app('hash')->check($plain, $user->getAuthPassword());

    }


}






















