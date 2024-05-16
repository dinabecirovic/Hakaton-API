<?php

namespace App\Http\Controllers\Auth;

use App\Http\Resources\UserAuthenticatedResource;
use App\User;
use App\Http\Controllers\Controller;
use App\Rules\PasswordRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    /**
     * @param Request $request
     * @return UserAuthenticatedResource
     * @throws \Illuminate\Validation\ValidationException
     */
    public function register(Request $request)
    {

        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'email|required|unique:users,email',
            'password' => ['required', new PasswordRule($request->input('first_name'), $request->input('last_name'), $request->input('email'))],
        ];

        $this->validate($request, $rules);

        $user = new User();

        $user->password = Hash::make($request->password);

        $user->fill($request->all());

        $user->save();

        $token = $user->generateNewApiToken();

        return new UserAuthenticatedResource($token);

    }

}
