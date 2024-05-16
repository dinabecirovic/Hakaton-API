<?php

namespace App\Http\Controllers\ApiPublic;

class PasswordRulesController extends Controller
{
    public static $passwordRules = [

        //match empty string
        [
            'regex' => "^$",
            'message' => "Password is required",
            'messageType' => "error"
        ],

        [
            'regex' => "\\s",
            'message' => "Password can’t contain any spaces",
            'messageType' => "error"
        ],
        [
            'regex' => "(.)(\\1){2,}",
            'message' => "Password can’t contain 3 repeated characters",
            'messageType' => "error"
        ],
        [
            'regex' => "(123|234|345|456|567|678|789|890|qwe|asd|yxc|zxc)",
            'message' => "Password can’t contain 3 sequential characters",
            'messageType' => "error",
            'bothCases' => true
        ],
        [
            'regex' => "email_part",
            'message' => "Password can’t contain your email username “{username}”",
            'messageType' => "error",
            'bothCases' => true
        ],
        [
            'regex' => "first_name",
            'message' => "Password can’t contain your first name",
            'messageType' => "error",
            'bothCases' => true
        ],
        [
            'regex' => "last_name",
            'message' => "Password can’t contain your last name",
            'messageType' => "error",
            'bothCases' => true
        ],
        [
            'regex' => "^.{0,7}$",
            'message' => "You are very close to 8 characters",
            'messageType' => "warning"
        ],
        [
            'regex' => "^[^0-9]+$",
            'message' => "Try including a number",
            'messageType' => "warning"
        ],
        [
            'regex' => "^[^a-zA-Z]+$",
            'message' => "Try including a letter",
            'messageType' => "warning"
        ],


    ];
    public function show()
    {
        return response()->json($this::$passwordRules);
    }

}
