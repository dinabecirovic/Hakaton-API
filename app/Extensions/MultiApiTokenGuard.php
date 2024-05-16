<?php

namespace App\Extensions;

use App\ApiToken;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;

class MultiApiTokenGuard implements Guard
{

    /**
     *
     */
    use GuardHelpers;

    /**
     * @var string
     */
    private $inputKey = '';

    /**
     * @var string
     */
    private $storageKey = '';

    /**
     * @var Request
     */
    private $request;

    /**
     * AccessTokenGuard constructor.
     * @param UserProvider $provider
     * @param Request $request
     * @param $configuration
     */
    public function __construct (UserProvider $provider, Request $request, $configuration) {

        $this->provider = $provider;
        $this->request = $request;

        // key to check in request
        $this->inputKey = isset($configuration['input_key']) ? $configuration['input_key'] : 'token';
        // key to check in database
        $this->storageKey = isset($configuration['storage_key']) ? $configuration['storage_key'] : 'token';

    }

    /**
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function user () {

        if (!is_null($this->user)) {
            return $this->user;
        }
        $user = null;
        $token = $this->getTokenForRequest();

        if (!empty($token)) {
            $user = $this->getUserWithToken($token);
        }

        return $this->user = $user;

    }

    /**
     * @param $token
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function getUserWithToken ( $token ) {

        return $this->provider->retrieveByToken($this->storageKey, $token);

    }

    /**
     * Get the token for the current request.
     * @return string
     */
    public function getTokenForRequest () {

        $token = $this->request->query($this->inputKey);

        if (empty($token)) {
            $token = $this->request->input($this->inputKey);
        }
        if (empty($token)) {
            $token = $this->request->bearerToken();
        }
        if (empty($token)) {
            $token = $this->request->header('X-Gitlab-Token', '');
        }

        return $token;

    }

    /**
     * @return mixed
     */
    public function getApiTokenForRequest () {

        return ApiToken::where('token', $this->getTokenForRequest())->first();

    }

    /**
     * Validate a user's credentials.
     *
     * @param  array $credentials
     *
     * @return bool
     */
    public function validate (array $credentials = []) {

        if (empty($credentials[$this->inputKey])) {
            return false;
        }

        $credentials = [ $this->storageKey => $credentials[$this->inputKey] ];

        if ($this->provider->retrieveByCredentials($credentials)) {
            return true;
        }
        return false;

    }


}
