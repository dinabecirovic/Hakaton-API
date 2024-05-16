<?php

namespace App\Http\Controllers\Auth;

use App\ApiToken;
use App\Events\Login;
use App\Events\Logout;
use App\Events\TokenBeforeDeleted;
use App\Events\TokenSSHKeyCreated;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserAuthenticatedResource;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use ThrottlesLogins;

    protected $maxAttempts = 15;
    protected $decayMinutes = 2;

    /**
     * LoginController constructor.
     */
    public function __construct() {

        $this->middleware('auth:api')
            ->except('login');

    }

    /**
     * @param Request $request
     * @return UserAuthenticatedResource|\Illuminate\Contracts\Routing\ResponseFactory|Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request){

        $this->validate($request, [
            'email' => 'email|required',
            'password' => 'required'
        ]);

        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $user = User::where('email', $request->email)->first();

        if(!$user || !Hash::check($request->password, $user->password) ){
            $this->incrementLoginAttempts($request);
            return response(null, Response::HTTP_UNAUTHORIZED);
        }

        if($request->paymentCheck && !$user->canUseProduct()){
            return response(null, Response::HTTP_PAYMENT_REQUIRED);
        }

        $token_type = ApiToken::typeFromSourceString($request->header("X-Quaaant-Source"));

        $token = $user->generateNewApiToken();
        $this->clearLoginAttempts($request);

        $user->refresh();

        return new UserAuthenticatedResource($token);


    }

    /**
     * @return string
     */
    public function username(){
        return 'email';
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function plugin(Request $request){

        $this->validate($request, [
            'public_ssh_key' => 'required',
        ]);

        $token = auth()->guard()->getApiTokenForRequest();

        $token->setSSHKey($request->public_ssh_key);

        return response(null, Response::HTTP_OK);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function logout(Request $request){

        $user = auth()->user();

        if(!$user)
            return response(null, Response::HTTP_NOT_FOUND);

        $token = auth()->guard()->getApiTokenForRequest();

        $token->delete();

        event(new Logout($user));

        return response(null, Response::HTTP_OK);
    }

}
