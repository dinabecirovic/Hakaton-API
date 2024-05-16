<?php

namespace App\Rules;

use Closure;
use Exception;
use Ibericode\Vat\Validator;
use Illuminate\Contracts\Validation\ValidationRule;

class VatNumber implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        
        $validator = new Validator();

        try{

            $valid = $validator->validateVatNumber( $value );

            if($valid) return;

        }
        catch(Exception $e){
            //
        }
    
        $fail('The :attribute must be valid VAT number.');

    }
}
