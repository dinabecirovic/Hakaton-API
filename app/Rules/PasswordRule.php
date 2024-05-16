<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use App\Http\Controllers\ApiPublic\PasswordRulesController;
use Closure;

class PasswordRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */



    private $firstName;
    private $lastName;

    private $email;
    private $username;

    public function __construct(string $firstName = null, string $lastName = null, string $email = null)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
    }


    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        $rules = PasswordRulesController::$passwordRules;


        foreach($rules as $rule){

            if(!isset($rule['regex'])) continue;

            if(!isset($this->firstName) && $rule['regex'] == 'first_name'){
                continue;
            }

            if(!isset($this->lastName) && $rule['regex'] == 'last_name'){
                continue;
            }

            if(!isset($this->email) && $rule['regex'] == 'email_part'){
                continue;
            }

            $regex = $rule['regex'];

            $regex = str_replace('first_name', $this->firstName, $regex);
            $regex = str_replace('last_name', $this->lastName, $regex);

            $regex = str_replace("email_part", explode("@", $this->email)[0], $regex);

            $bothCases = isset($rule['bothCases']) ? $rule['bothCases'] : false;

            $message = isset($rule['message']) ? $rule['message'] : '';

            $regex = $bothCases ? "/$regex/i" : "/$regex/";

            if(preg_match($regex, $value)){

                $fail($message);

            }

        }

        return;

    }
}
